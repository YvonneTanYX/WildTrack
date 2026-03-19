<?php
/**
 * payment_proof.php
 * Handles multipart/form-data file upload for TNG payment proof.
 * Creates tickets with status='pending' and saves proof image path.
 *
 * POST fields:
 *   proof_image  — the screenshot file (image/jpeg|png|webp, max 5MB)
 *   visit_date   — YYYY-MM-DD
 *   booking_ref  — e.g. WT-AB123
 *   final_total  — float
 *   voucher_id   — int (optional)
 *   tickets      — JSON string: [{ticket_type, price, quantity}, ...]
 *   addons       — JSON string: [{addon_type, quantity, price_per}, ...]
 */

require_once __DIR__ . '/../config/helpers.php';
session_start();

header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.');
}

$user = requireLogin();

// ── Validate uploaded file ──────────────────────────────────────────────
if (!isset($_FILES['proof_image']) || $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK) {
    $errCode = $_FILES['proof_image']['error'] ?? -1;
    respond(false, 'File upload failed. Error code: ' . $errCode);
}

$file     = $_FILES['proof_image'];
$maxBytes = 5 * 1024 * 1024; // 5 MB

if ($file['size'] > $maxBytes) {
    respond(false, 'File too large. Maximum allowed size is 5MB.');
}

$allowed  = ['image/jpeg', 'image/png', 'image/webp'];
$mimeType = mime_content_type($file['tmp_name']);
if (!in_array($mimeType, $allowed)) {
    respond(false, 'Invalid file type. Only JPG, PNG, and WEBP are accepted.');
}

// ── Determine storage path ──────────────────────────────────────────────
// Stored at: /WildTrack/uploads/payment_proofs/YYYY-MM/filename.ext
$uploadBase = __DIR__ . '/../uploads/payment_proofs';
$yearMonth  = date('Y-m');
$uploadDir  = $uploadBase . '/' . $yearMonth;

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext      = match($mimeType) {
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    default      => 'jpg',
};
$filename    = 'proof_' . $user['user_id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$fullPath    = $uploadDir . '/' . $filename;
$dbPath      = 'uploads/payment_proofs/' . $yearMonth . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
    respond(false, 'Failed to save uploaded file. Check server write permissions.');
}

// ── Parse form fields ───────────────────────────────────────────────────
$visitDate  = clean($_POST['visit_date']  ?? '');
$bookingRef = clean($_POST['booking_ref'] ?? '');
$finalTotal = floatval($_POST['final_total'] ?? 0);
$voucherId  = intval($_POST['voucher_id']  ?? 0);
$tickets    = json_decode($_POST['tickets'] ?? '[]', true) ?: [];
$addons     = json_decode($_POST['addons']  ?? '[]', true) ?: [];

if (!$visitDate) {
    @unlink($fullPath);
    respond(false, 'visit_date is required.');
}
if (!$bookingRef) {
    @unlink($fullPath);
    respond(false, 'booking_ref is required.');
}
if (empty($tickets)) {
    @unlink($fullPath);
    respond(false, 'No tickets provided.');
}

// ── Insert tickets with status = 'pending', no QR yet ──────────────────
$pdo = getDB();
$insertedIds = [];
$firstTicketId = null;

try {
    $pdo->beginTransaction();

    $stmtTicket = $pdo->prepare(
        "INSERT INTO tickets
            (user_id, ticket_type, price, visit_date, booking_ref, status, payment_proof, qr_code)
         VALUES (?, ?, ?, ?, ?, 'pending', ?, '')"
    );

    $stmtAddon = $pdo->prepare(
        "INSERT INTO ticket_addons (ticket_id, addon_type, quantity, price_per_pax, subtotal)
         VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($tickets as $t) {
        $type     = clean($t['ticket_type'] ?? '');
        $price    = floatval($t['price']    ?? 0);
        $quantity = intval($t['quantity']   ?? 1);

        if (!$type || $quantity <= 0) continue;

        for ($i = 0; $i < $quantity; $i++) {
            $stmtTicket->execute([
                $user['user_id'],
                $type,
                $price,
                $visitDate,
                $bookingRef,
                $dbPath,
            ]);
            $ticketId = (int) $pdo->lastInsertId();
            $insertedIds[] = $ticketId;
            if ($firstTicketId === null) $firstTicketId = $ticketId;
        }
    }

    foreach ($addons as $a) {
        $addonType = clean($a['addon_type']  ?? '');
        $qty       = intval($a['quantity']   ?? 0);
        $pricePer  = floatval($a['price_per'] ?? 0);
        $subtotal  = $qty * $pricePer;
        if ($qty > 0 && $addonType && $firstTicketId) {
            $stmtAddon->execute([$firstTicketId, $addonType, $qty, $pricePer, $subtotal]);
        }
    }

    // Store voucher link if applied (for approval step to increment used_count)
    if ($voucherId > 0 && !empty($insertedIds)) {
        // We store it on the booking_ref so approve_payment can find it
        $pdo->prepare(
            "INSERT IGNORE INTO ticket_vouchers (booking_ref, voucher_id) VALUES (?, ?)"
        )->execute([$bookingRef, $voucherId]);
    }

    // Create a notification for ADMIN (so admin panel knows there's a new pending payment)
    $pdo->prepare(
        "INSERT INTO notifications
            (user_id, type, title, body, ticket_ids, booking_ref)
         SELECT a.user_id, 'new_payment_proof',
                'New payment proof received',
                ?,
                ?,
                ?
         FROM admins a
         LIMIT 1"
    )->execute([
        'Booking ' . $bookingRef . ' by ' . $user['username'] . ' for ' . $visitDate . ' is awaiting approval.',
        json_encode($insertedIds),
        $bookingRef,
    ]);

    $pdo->commit();

    respond(true, 'Booking submitted for approval.', [
        'booking_ref' => $bookingRef,
        'ticket_ids'  => $insertedIds,
        'status'      => 'pending',
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    @unlink($fullPath); // Remove saved file if DB failed
    respond(false, 'Could not save booking: ' . $e->getMessage());
}
