<?php
require_once __DIR__ . '/../config/helpers.php';
session_start();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'buy': {
        $user       = requireLogin();
        $body       = jsonBody();
        $visit_date = clean($body['visit_date'] ?? '');
        $tickets    = $body['tickets'] ?? [];
        $addons     = $body['addons']  ?? [];

        if (!$visit_date) respond(false, 'visit_date is required.');
        if (empty($tickets)) respond(false, 'No tickets selected.');

        $pdo      = getDB();
        $inserted = [];
        $total_paid = 0;

        try {
            $pdo->beginTransaction();

            $stmtTicket = $pdo->prepare("INSERT INTO tickets (user_id, ticket_type, price, visit_date, qr_code) VALUES (?, ?, ?, ?, ?)");
            $stmtAddon  = $pdo->prepare("INSERT INTO ticket_addons (ticket_id, addon_type, quantity, price_per_pax, subtotal) VALUES (?, ?, ?, ?, ?)");

            $firstTicketId = null;

            foreach ($tickets as $t) {
                $type     = clean($t['ticket_type'] ?? '');
                $price    = floatval($t['price']    ?? 0);
                $quantity = intval($t['quantity']   ?? 1);

                for ($i = 0; $i < $quantity; $i++) {
                    $qr = 'QR-' . strtoupper(uniqid()) . '-' . $user['user_id'];
                    $stmtTicket->execute([$user['user_id'], $type, $price, $visit_date, $qr]);
                    $ticketId = $pdo->lastInsertId();

                    if ($firstTicketId === null) $firstTicketId = $ticketId;

                    $inserted[] = [
                        'ticket_id'   => $ticketId,
                        'ticket_type' => $type,
                        'price'       => $price,
                        'visit_date'  => $visit_date,
                        'qr_code'     => $qr,
                    ];
                    $total_paid += $price;
                }
            }

            // Save addons linked to first ticket
            foreach ($addons as $a) {
                $addon_type   = clean($a['addon_type']    ?? '');
                $qty          = intval($a['quantity']     ?? 0);
                $price_per    = floatval($a['price_per']  ?? 0);
                $subtotal     = $qty * $price_per;

                if ($qty > 0 && $addon_type && $firstTicketId) {
                    $stmtAddon->execute([$firstTicketId, $addon_type, $qty, $price_per, $subtotal]);
                    $total_paid += $subtotal;
                }
            }

            $pdo->commit();
            respond(true, 'Tickets purchased!', [
                'tickets'    => $inserted,
                'total_paid' => $total_paid,
            ]);

        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Purchase failed: ' . $e->getMessage());
        }
        break;
    }

    case 'my': {
        $user = requireLogin();
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY purchase_date DESC");
        $stmt->execute([$user['user_id']]);
        respond(true, 'OK', ['tickets' => $stmt->fetchAll()]);
        break;
    }

    case 'all': {
        requireRole('admin');
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT t.*, u.username FROM tickets t JOIN users u ON t.user_id = u.user_id ORDER BY t.purchase_date DESC");
        respond(true, 'OK', ['tickets' => $stmt->fetchAll()]);
        break;
    }

    default:
        respond(false, 'Unknown action.');
}