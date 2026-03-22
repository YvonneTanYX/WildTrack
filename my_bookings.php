<?php
/**
 * api/my_bookings.php
 * GET ?action=list    — all bookings (grouped by booking_ref) for this visitor
 * GET ?action=tickets — all approved QR tickets for this visitor
 *
 * Schema (from payment_proof.php):
 *   tickets: ticket_id, user_id, ticket_type, price, visit_date,
 *            booking_ref, status, payment_proof, qr_code,
 *            payment_method, voucher_code, discount_amount
 *   ticket_addons: ticket_id, addon_type, quantity, price_per_pax, subtotal
 */

require_once __DIR__ . '/../config/helpers.php';
session_start();

header('Content-Type: application/json');

$user = requireLogin();
$userId = (int)($user['user_id'] ?? $user['id'] ?? 0);

$pdo    = getDB();
$action = $_GET['action'] ?? 'list';

// ══════════════════════════════════════════════════════════════
//  ACTION: list  —  bookings grouped by booking_ref
// ══════════════════════════════════════════════════════════════
if ($action === 'list') {

    // Get one row per booking_ref (representative ticket carries the booking info)
    $stmt = $pdo->prepare(
        "SELECT booking_ref,
                visit_date,
                status,
                payment_proof                          AS proof_image_url,
                payment_method,
                MAX(CASE WHEN discount_amount > 0 THEN discount_amount ELSE 0 END) AS voucher_discount,
                SUM(price)                             AS total_amount,
                MIN(created_at)                        AS created_at,
                GROUP_CONCAT(ticket_id)                AS ticket_ids_raw,
                GROUP_CONCAT(ticket_type ORDER BY ticket_id) AS ticket_types_raw,
                COUNT(*)                               AS ticket_count
         FROM   tickets
         WHERE  user_id = :uid
         GROUP  BY booking_ref, visit_date, status, payment_proof, payment_method
         ORDER  BY MIN(created_at) DESC"
    );
    $stmt->execute([':uid' => $userId]);
    $rows = $stmt->fetchAll();

    if (empty($rows)) {
        respond(true, 'OK', ['bookings' => []]);
    }

    // Fetch add-ons for all these booking_refs
    $refs   = array_column($rows, 'booking_ref');
    $inList = implode(',', array_fill(0, count($refs), '?'));

    $addonRows = [];
    try {
        $stmt = $pdo->prepare(
            "SELECT t.booking_ref, ta.addon_type, ta.quantity
             FROM   ticket_addons ta
             JOIN   tickets t ON t.ticket_id = ta.ticket_id
             WHERE  t.booking_ref IN ($inList)
             GROUP  BY t.booking_ref, ta.addon_type"
        );
        $stmt->execute($refs);
        $addonRows = $stmt->fetchAll();
    } catch (PDOException $e) { /* ticket_addons may be empty */ }

    $addonsByRef = [];
    foreach ($addonRows as $a) {
        $addonsByRef[$a['booking_ref']][] = [
            'addon_type' => $a['addon_type'],
            'quantity'   => $a['quantity'],
        ];
    }

    // Build clean booking objects
    $bookings = [];
    foreach ($rows as $r) {
        $ref        = $r['booking_ref'];
        $ticketIds  = array_map('intval', explode(',', $r['ticket_ids_raw']));
        $typesList  = array_count_values(explode(',', $r['ticket_types_raw']));

        $tickets = [];
        foreach ($typesList as $type => $qty) {
            $tickets[] = ['ticket_type' => $type, 'quantity' => $qty];
        }

        $bookings[] = [
            'booking_ref'      => $ref,
            'visit_date'       => $r['visit_date'],
            'status'           => $r['status'],
            'total_amount'     => $r['total_amount'],
            'payment_method'   => $r['payment_method'],
            'proof_image_url'  => $r['proof_image_url']
                                  ? 'http://localhost/WildTrack/' . ltrim($r['proof_image_url'], '/')
                                  : null,
            'rejection_reason' => null,   // add column to tickets table if needed
            'created_at'       => $r['created_at'],
            'tickets'          => $tickets,
            'addons'           => $addonsByRef[$ref] ?? [],
            'ticket_ids'       => $ticketIds,
        ];
    }

    respond(true, 'OK', ['bookings' => $bookings]);
}

// ══════════════════════════════════════════════════════════════
//  ACTION: tickets  —  approved tickets with QR codes
// ══════════════════════════════════════════════════════════════
if ($action === 'tickets') {

    $stmt = $pdo->prepare(
        "SELECT ticket_id, ticket_type, qr_code,
                visit_date, booking_ref, price,
                COALESCE(is_used, 0) AS is_used
         FROM   tickets
         WHERE  user_id = :uid
           AND  status  = 'approved'
         ORDER  BY visit_date ASC, ticket_type ASC"
    );
    $stmt->execute([':uid' => $userId]);
    $tickets = $stmt->fetchAll();

    respond(true, 'OK', ['tickets' => $tickets]);
}

http_response_code(400);
respond(false, 'Unknown action');
