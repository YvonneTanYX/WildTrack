<?php
require_once __DIR__ . '/../config/helpers.php';
session_start();

$action = $_GET['action'] ?? '';

switch ($action) {

    // ─────────────────────────────────────────────────────
    //  EXISTING: buy (kept for reference / direct purchase)
    // ─────────────────────────────────────────────────────
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

            $stmtTicket = $pdo->prepare(
                "INSERT INTO tickets (user_id, ticket_type, price, visit_date, qr_code, booking_ref, status)
                 VALUES (?, ?, ?, ?, ?, ?, 'approved')"
            );
            $stmtAddon = $pdo->prepare(
                "INSERT INTO ticket_addons (ticket_id, addon_type, quantity, price_per_pax, subtotal)
                 VALUES (?, ?, ?, ?, ?)"
            );

            $firstTicketId = null;
            $bookingRef    = $body['booking_ref'] ?? ('WT-' . strtoupper(uniqid()));

            foreach ($tickets as $t) {
                $type     = clean($t['ticket_type'] ?? '');
                $price    = floatval($t['price']    ?? 0);
                $quantity = intval($t['quantity']   ?? 1);

                for ($i = 0; $i < $quantity; $i++) {
                    $qr = 'http://localhost/WildTrack/verify.php?code=QR-' . strtoupper(uniqid()) . '-' . $user['user_id'];
                    $stmtTicket->execute([$user['user_id'], $type, $price, $visit_date, $qr, $bookingRef]);
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

            foreach ($addons as $a) {
                $addon_type = clean($a['addon_type']  ?? '');
                $qty        = intval($a['quantity']   ?? 0);
                $price_per  = floatval($a['price_per'] ?? 0);
                $subtotal   = $qty * $price_per;
                if ($qty > 0 && $addon_type && $firstTicketId) {
                    $stmtAddon->execute([$firstTicketId, $addon_type, $qty, $price_per, $subtotal]);
                    $total_paid += $subtotal;
                }
            }

            $voucher_id = intval($body['voucher_id'] ?? 0);
            if ($voucher_id > 0) {
                $pdo->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = ? AND used_count < max_uses")
                    ->execute([$voucher_id]);
            }

            $pdo->commit();
            respond(true, 'Tickets purchased!', [
                'tickets'    => $inserted,
                'total_paid' => $body['final_total'] ?? $total_paid,
            ]);

        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Purchase failed: ' . $e->getMessage());
        }
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: tng_settings — fetch admin TNG QR + receiver
    // ─────────────────────────────────────────────────────
    case 'tng_settings': {
        $pdo  = getDB();
        $stmt = $pdo->query("SELECT tng_qr_image, tng_receiver_name FROM admins LIMIT 1");
        $row  = $stmt->fetch();

        if (!$row) {
            // No admin has set TNG details yet — return placeholder
            respond(true, 'No TNG settings found.', [
                'tng_qr_url'    => null,
                'receiver_name' => 'WildTrack Safari Park',
            ]);
        }

        $qrUrl = $row['tng_qr_image']
            ? 'http://localhost/WildTrack/' . ltrim($row['tng_qr_image'], '/')
            : null;

        respond(true, 'OK', [
            'tng_qr_url'    => $qrUrl,
            'receiver_name' => $row['tng_receiver_name'] ?: 'WildTrack Safari Park',
        ]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: check_notifications — poll for visitor notifs
    // ─────────────────────────────────────────────────────
    case 'check_notifications': {
        $user = requireLogin();
        $pdo  = getDB();
        $stmt = $pdo->prepare(
            "SELECT id, type, title, body, is_read, ticket_ids, booking_ref, created_at
             FROM notifications
             WHERE user_id = ?
             ORDER BY created_at DESC
             LIMIT 20"
        );
        $stmt->execute([$user['user_id']]);
        respond(true, 'OK', ['notifications' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: mark_notification_read
    // ─────────────────────────────────────────────────────
    case 'mark_notification_read': {
        $user = requireLogin();
        $body = jsonBody();
        $id   = intval($body['notification_id'] ?? 0);
        if (!$id) respond(false, 'notification_id required.');
        $pdo  = getDB();
        $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?")
            ->execute([$id, $user['user_id']]);
        respond(true, 'Marked as read.');
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: get_tickets_by_ids — used after approval to
    //       fetch the approved tickets with QR codes
    // ─────────────────────────────────────────────────────
    case 'get_tickets_by_ids': {
        $user = requireLogin();
        $body = jsonBody();
        $ids  = $body['ticket_ids'] ?? [];

        if (empty($ids)) respond(false, 'No ticket IDs provided.');

        // Whitelist: only integers, only belonging to this user
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, fn($id) => $id > 0);

        if (empty($ids)) respond(false, 'Invalid ticket IDs.');

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $pdo  = getDB();
        $stmt = $pdo->prepare(
            "SELECT ticket_id, ticket_type, price, visit_date, qr_code
             FROM tickets
             WHERE ticket_id IN ($placeholders) AND user_id = ? AND status = 'approved'"
        );
        $stmt->execute([...$ids, $user['user_id']]);
        $tickets = $stmt->fetchAll();

        respond(true, 'OK', ['tickets' => $tickets]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  EXISTING: my tickets
    // ─────────────────────────────────────────────────────
    case 'my': {
        $user = requireLogin();
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY purchase_date DESC");
        $stmt->execute([$user['user_id']]);
        respond(true, 'OK', ['tickets' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  EXISTING: all (admin)
    // ─────────────────────────────────────────────────────
    case 'all': {
        requireRole('admin');
        $pdo  = getDB();
        $stmt = $pdo->query(
            "SELECT t.*, u.username
             FROM tickets t
             JOIN users u ON t.user_id = u.user_id
             ORDER BY t.purchase_date DESC"
        );
        respond(true, 'OK', ['tickets' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  stats — overview dashboard numbers (admin)
    // ─────────────────────────────────────────────────────
    case 'stats': {
        requireRole('admin');
        $pdo = getDB();

        // Total tickets sold (all time, approved only)
        $totalTickets = $pdo->query(
            "SELECT COUNT(*) FROM tickets WHERE status = 'approved'"
        )->fetchColumn();

        // Pending approvals count
        $pendingCount = $pdo->query(
            "SELECT COUNT(DISTINCT booking_ref) FROM tickets WHERE status = 'pending'"
        )->fetchColumn();

        // Total revenue (approved tickets)
        $totalRevenue = $pdo->query(
            "SELECT COALESCE(SUM(price),0) FROM tickets WHERE status = 'approved'"
        )->fetchColumn();

        // Today's tickets (approved, visit_date = today)
        $todayTickets = $pdo->query(
            "SELECT COUNT(*) FROM tickets WHERE status = 'approved' AND DATE(purchase_date) = CURDATE()"
        )->fetchColumn();

        respond(true, 'OK', [
            'total_tickets'  => (int) $totalTickets,
            'pending_count'  => (int) $pendingCount,
            'total_revenue'  => (float) $totalRevenue,
            'today_tickets'  => (int) $todayTickets,
        ]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  get_pending — admin lists pending bookings (grouped)
    // ─────────────────────────────────────────────────────
    case 'get_pending': {
        requireRole('admin');
        $pdo  = getDB();
        // One row per booking_ref — shows ticket summary, total price, proof from first ticket
        $stmt = $pdo->query(
            "SELECT
                t.booking_ref,
                t.user_id,
                t.visit_date,
                t.status,
                MIN(t.payment_proof) AS payment_proof,
                MIN(t.purchase_date) AS purchase_date,
                COUNT(t.ticket_id)   AS ticket_count,
                SUM(t.price)         AS total_price,
                GROUP_CONCAT(DISTINCT t.ticket_type ORDER BY t.ticket_type SEPARATOR ', ') AS ticket_types,
                u.username,
                u.email
             FROM tickets t
             JOIN users u ON t.user_id = u.user_id
             WHERE t.status = 'pending'
             GROUP BY t.booking_ref, t.user_id, t.visit_date, t.status, u.username, u.email
             ORDER BY MIN(t.purchase_date) DESC"
        );
        respond(true, 'OK', ['payments' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: pending_payments — kept for compatibility
    // ─────────────────────────────────────────────────────
    case 'pending_payments': {
        requireRole('admin');
        $pdo  = getDB();
        $stmt = $pdo->query(
            "SELECT
                t.booking_ref,
                t.user_id,
                t.visit_date,
                t.status,
                MIN(t.payment_proof) AS payment_proof,
                MIN(t.purchase_date) AS purchase_date,
                COUNT(t.ticket_id)   AS ticket_count,
                SUM(t.price)         AS total_price,
                GROUP_CONCAT(DISTINCT t.ticket_type ORDER BY t.ticket_type SEPARATOR ', ') AS ticket_types,
                u.username,
                u.email
             FROM tickets t
             JOIN users u ON t.user_id = u.user_id
             WHERE t.status = 'pending'
             GROUP BY t.booking_ref, t.user_id, t.visit_date, t.status, u.username, u.email
             ORDER BY MIN(t.purchase_date) DESC"
        );
        respond(true, 'OK', ['payments' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: approve_payment — admin approves a booking ref
    //       Saves any admin edits, generates QR, notifies visitor
    // ─────────────────────────────────────────────────────
    case 'approve_payment': {
        $admin = requireRole('admin');
        $body  = jsonBody();
        $ref   = clean($body['booking_ref'] ?? '');

        if (!$ref) respond(false, 'booking_ref required.');

        // Optional admin overrides
        $newType      = isset($body['ticket_type'])  && $body['ticket_type']  !== '' ? clean($body['ticket_type'])       : null;
        $newPrice     = isset($body['price'])         && $body['price']         !== '' ? floatval($body['price'])          : null;
        $newVisitDate = isset($body['visit_date'])    && $body['visit_date']    !== '' ? clean($body['visit_date'])        : null;

        $pdo = getDB();

        try {
            $pdo->beginTransaction();

            // Fetch all pending tickets for this booking ref
            $stmt = $pdo->prepare(
                "SELECT ticket_id, user_id, ticket_type, price, visit_date
                 FROM tickets WHERE booking_ref = ? AND status = 'pending'"
            );
            $stmt->execute([$ref]);
            $tickets = $stmt->fetchAll();

            if (empty($tickets)) {
                $pdo->rollBack();
                respond(false, 'No pending tickets found for this booking ref.');
            }

            $userId    = $tickets[0]['user_id'];
            $ticketIds = [];

            // Build the UPDATE — apply admin edits where provided, generate QR
            $updateStmt = $pdo->prepare(
                "UPDATE tickets
                 SET status     = 'approved',
                     qr_code    = ?,
                     ticket_type = COALESCE(?, ticket_type),
                     price       = COALESCE(?, price),
                     visit_date  = COALESCE(?, visit_date)
                 WHERE ticket_id = ?"
            );

            foreach ($tickets as $t) {
                $qr = 'http://localhost/WildTrack/verify.php?code=QR-' . strtoupper(uniqid()) . '-' . $t['user_id'];
                $updateStmt->execute([$qr, $newType, $newPrice, $newVisitDate, $t['ticket_id']]);
                $ticketIds[] = $t['ticket_id'];
            }

            // Increment voucher if applicable
            $vStmt = $pdo->prepare(
                "SELECT voucher_id FROM ticket_vouchers WHERE booking_ref = ? LIMIT 1"
            );
            $vStmt->execute([$ref]);
            $vRow = $vStmt->fetch();
            if ($vRow && $vRow['voucher_id']) {
                $pdo->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = ? AND used_count < max_uses")
                    ->execute([$vRow['voucher_id']]);
            }

            // Create notification for the visitor
            $visitDate = $tickets[0]['visit_date'];
            $notifStmt = $pdo->prepare(
                "INSERT INTO notifications (user_id, type, title, body, ticket_ids, booking_ref)
                 VALUES (?, 'booking_approved', ?, ?, ?, ?)"
            );
            $notifStmt->execute([
                $userId,
                'Booking Confirmed! 🎉',
                'Your booking ' . $ref . ' for ' . $visitDate . ' has been approved. Your QR tickets are ready!',
                json_encode($ticketIds),
                $ref,
            ]);

            $pdo->commit();
            respond(true, 'Booking approved and visitor notified.', [
                'booking_ref' => $ref,
                'ticket_ids'  => $ticketIds,
            ]);

        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Approval failed: ' . $e->getMessage());
        }
        break;
    }

    // ─────────────────────────────────────────────────────
    //  NEW: reject_payment — admin rejects a booking ref
    // ─────────────────────────────────────────────────────
    case 'reject_payment': {
        $admin  = requireRole('admin');
        $body   = jsonBody();
        $ref    = clean($body['booking_ref'] ?? '');
        $reason = clean($body['reason'] ?? 'Payment could not be verified.');

        if (!$ref) respond(false, 'booking_ref required.');

        $pdo = getDB();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "SELECT ticket_id, user_id, visit_date FROM tickets
                 WHERE booking_ref = ? AND status = 'pending'"
            );
            $stmt->execute([$ref]);
            $tickets = $stmt->fetchAll();

            if (empty($tickets)) {
                $pdo->rollBack();
                respond(false, 'No pending tickets for this ref.');
            }

            $userId = $tickets[0]['user_id'];

            $pdo->prepare("UPDATE tickets SET status = 'rejected' WHERE booking_ref = ? AND status = 'pending'")
                ->execute([$ref]);

            $notifStmt = $pdo->prepare(
                "INSERT INTO notifications (user_id, type, title, body, booking_ref)
                 VALUES (?, 'booking_rejected', ?, ?, ?)"
            );
            $notifStmt->execute([
                $userId,
                'Booking Not Approved',
                'Your booking ' . $ref . ' was not approved. Reason: ' . $reason . '. Please contact support.',
                $ref,
            ]);

            $pdo->commit();
            respond(true, 'Booking rejected and visitor notified.');

        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Rejection failed: ' . $e->getMessage());
        }
        break;
    }

    // ─────────────────────────────────────────────────────
    //  get_prices — public, visitor side loads this on init
    // ─────────────────────────────────────────────────────
    case 'get_prices': {
        $pdo  = getDB();
        $stmt = $pdo->query(
            "SELECT ticket_type, label, description, price
             FROM ticket_prices
             ORDER BY FIELD(ticket_type,'Adult','Child','Senior','Group')"
        );
        respond(true, 'OK', ['prices' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  save_price — admin updates a single ticket price
    // ─────────────────────────────────────────────────────
    case 'save_price': {
        requireRole('admin');
        $body  = jsonBody();
        $type  = clean($body['ticket_type'] ?? '');
        $price = floatval($body['price']       ?? 0);

        if (!$type || $price <= 0) respond(false, 'ticket_type and price are required.');

        $pdo = getDB();
        $pdo->prepare(
            "UPDATE ticket_prices SET price = ? WHERE ticket_type = ?"
        )->execute([$price, $type]);

        respond(true, 'Price updated.', ['ticket_type' => $type, 'price' => $price]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  get_vouchers — admin loads all vouchers
    // ─────────────────────────────────────────────────────
    case 'get_vouchers': {
        requireRole('admin');
        $pdo  = getDB();
        $stmt = $pdo->query(
            "SELECT id, code, discount_type, discount_value, min_spend,
                    max_uses, used_count, expires_at, is_active, created_at
             FROM vouchers
             ORDER BY created_at DESC"
        );
        respond(true, 'OK', ['vouchers' => $stmt->fetchAll()]);
        break;
    }

    // ─────────────────────────────────────────────────────
    //  save_voucher — admin adds a new voucher
    // ─────────────────────────────────────────────────────
    case 'save_voucher': {
        requireRole('admin');
        $body          = jsonBody();
        $code          = strtoupper(clean($body['code']           ?? ''));
        $discountType  = clean($body['discount_type']             ?? 'fixed');
        $discountValue = floatval($body['discount_value']         ?? 0);
        $minSpend      = floatval($body['min_spend']              ?? 0);
        $maxUses       = intval($body['max_uses']                 ?? 1);
        $expiresAt     = clean($body['expires_at']                ?? '') ?: null;

        if (!$code)           respond(false, 'Voucher code is required.');
        if ($discountValue <= 0) respond(false, 'Discount value must be greater than 0.');
        if (!in_array($discountType, ['fixed', 'percent'])) respond(false, 'Invalid discount type.');

        $pdo = getDB();
        try {
            $pdo->prepare(
                "INSERT INTO vouchers (code, discount_type, discount_value, min_spend, max_uses, expires_at, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, 1)"
            )->execute([$code, $discountType, $discountValue, $minSpend, $maxUses, $expiresAt]);
            $newId = $pdo->lastInsertId();
            respond(true, 'Voucher created.', ['id' => $newId, 'code' => $code]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) respond(false, 'Voucher code already exists.');
            respond(false, 'Could not create voucher: ' . $e->getMessage());
        }
        break;
    }

    // ─────────────────────────────────────────────────────
    //  delete_voucher — admin deletes a voucher by id
    // ─────────────────────────────────────────────────────
    case 'delete_voucher': {
        requireRole('admin');
        $body = jsonBody();
        $id   = intval($body['id'] ?? 0);
        if (!$id) respond(false, 'Voucher id required.');
        $pdo = getDB();
        $pdo->prepare("DELETE FROM vouchers WHERE id = ?")->execute([$id]);
        respond(true, 'Voucher deleted.');
        break;
    }

    // ─────────────────────────────────────────────────────
    //  toggle_voucher — admin flips is_active on a voucher
    // ─────────────────────────────────────────────────────
    case 'toggle_voucher': {
        requireRole('admin');
        $body = jsonBody();
        $id   = intval($body['id'] ?? 0);
        if (!$id) respond(false, 'Voucher id required.');
        $pdo = getDB();
        $pdo->prepare(
            "UPDATE vouchers SET is_active = 1 - is_active WHERE id = ?"
        )->execute([$id]);
        $row = $pdo->prepare("SELECT is_active FROM vouchers WHERE id = ?");
        $row->execute([$id]);
        $result = $row->fetch();
        respond(true, 'Toggled.', ['is_active' => (int)$result['is_active']]);
        break;
    }

    default:
        respond(false, 'Unknown action.');
}
