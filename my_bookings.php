<?php
/**
 * api/my_bookings.php
 * GET ?action=list    — all bookings grouped by booking_ref
 * GET ?action=tickets — all approved QR tickets
 * GET ?action=debug   — shows real column names (remove after fixing)
 */

// Buffer ALL output so any stray PHP warnings don't corrupt the JSON response
ob_start();

require_once __DIR__ . '/../config/helpers.php';

// Only start session if not already started (helpers.php may have started it)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Discard any output that happened before this point (warnings, notices, etc.)
ob_clean();
header('Content-Type: application/json');

$user   = requireLogin();
$userId = (int)($user['user_id'] ?? $user['id'] ?? 0);

$pdo    = getDB();
$action = $_GET['action'] ?? 'list';

// ══════════════════════════════════════════════════════════════
//  DEBUG — visit ?action=debug to see your real column names
// ══════════════════════════════════════════════════════════════
if ($action === 'debug') {
    $cols = $pdo->query("SHOW COLUMNS FROM tickets")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['columns' => array_column($cols, 'Field')]);
    exit;
}

// Timestamp column in your tickets table is: purchase_date
$tsSelect  = "MIN(purchase_date)";
$tsOrderBy = "MIN(purchase_date)";

// ══════════════════════════════════════════════════════════════
//  ACTION: list
// ══════════════════════════════════════════════════════════════
if ($action === 'list') {

    $stmt = $pdo->prepare(
        "SELECT booking_ref,
                visit_date,
                status,
                payment_proof                                AS proof_image_url,
                payment_method,
                MAX(CASE WHEN discount_amount > 0 THEN discount_amount ELSE 0 END) AS voucher_discount,
                SUM(price)                                   AS total_amount,
                $tsSelect                                    AS created_at,
                GROUP_CONCAT(ticket_id)                      AS ticket_ids_raw,
                GROUP_CONCAT(ticket_type ORDER BY ticket_id) AS ticket_types_raw,
                COUNT(*)                                     AS ticket_count
         FROM   tickets
         WHERE  user_id = :uid
         GROUP  BY booking_ref, visit_date, status, payment_proof, payment_method
         ORDER  BY $tsOrderBy DESC"
    );
    $stmt->execute([':uid' => $userId]);
    $rows = $stmt->fetchAll();

    if (empty($rows)) {
        respond(true, 'OK', ['bookings' => []]);
        exit;
    }

    // Add-ons
    $refs    = array_column($rows, 'booking_ref');
    $inList  = implode(',', array_fill(0, count($refs), '?'));
    $addonsByRef = [];
    try {
        $stmt2 = $pdo->prepare(
            "SELECT t.booking_ref, ta.addon_type, ta.quantity
             FROM   ticket_addons ta
             JOIN   tickets t ON t.ticket_id = ta.ticket_id
             WHERE  t.booking_ref IN ($inList)
             GROUP  BY t.booking_ref, ta.addon_type"
        );
        $stmt2->execute($refs);
        foreach ($stmt2->fetchAll() as $a) {
            $addonsByRef[$a['booking_ref']][] = [
                'addon_type' => $a['addon_type'],
                'quantity'   => $a['quantity'],
            ];
        }
    } catch (PDOException $e) {}

    // Dynamic base URL — no hardcoded localhost
    $scheme      = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host        = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir   = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
    $docRoot     = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $subPath     = str_replace($docRoot, '', $scriptDir);
    $projectPath = preg_replace('#/api$#i', '', $subPath);
    $baseUrl     = $scheme . '://' . $host . $projectPath;

    $bookings = [];
    foreach ($rows as $r) {
        $ref       = $r['booking_ref'];
        $ticketIds = array_map('intval', explode(',', $r['ticket_ids_raw']));
        $typesList = array_count_values(explode(',', $r['ticket_types_raw']));
        $tickets   = [];
        foreach ($typesList as $type => $qty) {
            $tickets[] = ['ticket_type' => $type, 'quantity' => $qty];
        }

        $proofUrl = null;
        if ($r['proof_image_url']) {
            $proof    = ltrim(str_replace('\\', '/', $r['proof_image_url']), '/');
            $proofUrl = (strpos($proof, 'http') === 0) ? $proof : $baseUrl . '/' . $proof;
        }

        $bookings[] = [
            'booking_ref'      => $ref,
            'visit_date'       => $r['visit_date'],
            'status'           => $r['status'],
            'total_amount'     => $r['total_amount'],
            'payment_method'   => $r['payment_method'],
            'proof_image_url'  => $proofUrl,
            'rejection_reason' => null,
            'created_at'       => $r['created_at'],  // null → frontend shows "—"
            'tickets'          => $tickets,
            'addons'           => $addonsByRef[$ref] ?? [],
            'ticket_ids'       => $ticketIds,
        ];
    }

    respond(true, 'OK', ['bookings' => $bookings]);
    exit;
}

// ══════════════════════════════════════════════════════════════
//  ACTION: tickets — approved QR tickets
// ══════════════════════════════════════════════════════════════
if ($action === 'tickets') {
    $stmt = $pdo->prepare(
        "SELECT ticket_id, ticket_type, qr_code,
                visit_date, booking_ref, price,
                0 AS is_used
         FROM   tickets
         WHERE  user_id = :uid
           AND  status  = 'approved'
         ORDER  BY visit_date ASC, ticket_type ASC"
    );
    $stmt->execute([':uid' => $userId]);
    $tickets = $stmt->fetchAll();

    respond(true, 'OK', ['tickets' => $tickets]);
    exit;
}

http_response_code(400);
respond(false, 'Unknown action');
