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
$visitDate       = clean($_POST['visit_date']          ?? '');
$bookingRef      = clean($_POST['booking_ref']         ?? '');
$finalTotal      = floatval($_POST['final_total']      ?? 0);
$voucherId       = intval($_POST['voucher_id']         ?? 0);
$voucherCode     = clean($_POST['voucher_code']        ?? '');
$voucherDiscount = floatval($_POST['voucher_discount'] ?? 0);
$paymentMethod   = clean($_POST['payment_method']      ?? "Touch 'n Go eWallet");
$tickets         = json_decode($_POST['tickets']       ?? '[]', true) ?: [];
$addons          = json_decode($_POST['addons']        ?? '[]', true) ?: [];

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

// ── FIX: Re-validate voucher per-user before committing ────────────────
// Prevents a race condition where the user applies the voucher in the
// validate step, then submits payment before another tab's booking commits.
$pdo = getDB();
if ($voucherId > 0) {
    $stmtCheck = $pdo->prepare("
        SELECT id FROM voucher_usage
        WHERE voucher_id = ? AND user_id = ?
        LIMIT 1
    ");
    $stmtCheck->execute([$voucherId, $user['user_id']]);
    if ($stmtCheck->fetch()) {
        @unlink($fullPath);
        respond(false, 'You have already used this voucher. Please remove it and resubmit.');
    }
}

// ── Detect optional ticket columns ─────────────────────────────────────
$hasExtraColumns = true;
try {
    $pdo->query("SELECT payment_method FROM tickets LIMIT 1");
} catch (PDOException $e) {
    $hasExtraColumns = false;
}

// ── Insert tickets & addons ─────────────────────────────────────────────
$insertedIds   = [];
$firstTicketId = null;

try {
    $pdo->beginTransaction();

    if ($hasExtraColumns) {
        $stmtTicket = $pdo->prepare(
            "INSERT INTO tickets
                (user_id, ticket_type, price, visit_date, booking_ref, status,
                 payment_proof, qr_code, payment_method, voucher_code, discount_amount)
             VALUES (?, ?, ?, ?, ?, 'pending', ?, '', ?, ?, ?)"
        );
    } else {
        $stmtTicket = $pdo->prepare(
            "INSERT INTO tickets
                (user_id, ticket_type, price, visit_date, booking_ref, status, payment_proof, qr_code)
             VALUES (?, ?, ?, ?, ?, 'pending', ?, '')"
        );
    }

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
            if ($hasExtraColumns) {
                $stmtTicket->execute([
                    $user['user_id'],
                    $type,
                    $price,
                    $visitDate,
                    $bookingRef,
                    $dbPath,
                    $paymentMethod,
                    $voucherCode ?: null,
                    // Only store discount on first ticket to avoid double-counting
                    $firstTicketId === null ? $voucherDiscount : 0,
                ]);
            } else {
                $stmtTicket->execute([
                    $user['user_id'],
                    $type,
                    $price,
                    $visitDate,
                    $bookingRef,
                    $dbPath,
                ]);
            }
            $ticketId = (int) $pdo->lastInsertId();
            $insertedIds[] = $ticketId;
            if ($firstTicketId === null) $firstTicketId = $ticketId;
        }
    }

    // Insert add-ons linked to the FIRST ticket of this booking
    foreach ($addons as $a) {
        $addonType = clean($a['addon_type']  ?? '');
        $qty       = intval($a['quantity']   ?? 0);
        $pricePer  = floatval($a['price_per'] ?? 0);
        $subtotal  = $qty * $pricePer;
        if ($qty > 0 && $addonType && $firstTicketId) {
            $stmtAddon->execute([$firstTicketId, $addonType, $qty, $pricePer, $subtotal]);
        }
    }

    // ── Store voucher booking link ──────────────────────────────────────
    if ($voucherId > 0 && !empty($insertedIds)) {
        $pdo->prepare(
            "INSERT IGNORE INTO ticket_vouchers (booking_ref, voucher_id) VALUES (?, ?)"
        )->execute([$bookingRef, $voucherId]);
    }

    // ── FIX: Record per-user voucher usage immediately on submission ────
    // This locks the voucher to this account so no other booking from
    // the same user can apply it again, even before admin approval.
    if ($voucherId > 0) {
        $pdo->prepare("
            INSERT IGNORE INTO voucher_usage (voucher_id, user_id, booking_ref)
            VALUES (?, ?, ?)
        ")->execute([$voucherId, $user['user_id'], $bookingRef]);

        // Increment global used_count so admin panel reflects usage live
        $pdo->prepare("
            UPDATE vouchers SET used_count = used_count + 1 WHERE id = ?
        ")->execute([$voucherId]);
    }

    // ── Notify admin of new pending payment ─────────────────────────────
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
    @unlink($fullPath);
    respond(false, 'Could not save booking: ' . $e->getMessage());
}
