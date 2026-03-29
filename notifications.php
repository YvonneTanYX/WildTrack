<?php
/**
 * api/notifications.php
 * Visitor-side endpoint — returns unread notifications for the logged-in visitor.
 * Called by Ticketing.php on a 15-second poll to detect booking_approved /
 * booking_rejected / feedback_reply.
 *
 * GET  (no params needed — uses session user)
 * POST ?action=mark_read   body: { ids: [1,2,3] }
 */

require_once __DIR__ . '/../config/helpers.php';
session_start();

header('Content-Type: application/json');

$action = $_GET['action'] ?? 'get';

if ($action === 'mark_read') {
    $user = requireLogin();
    $body = jsonBody();
    $ids  = array_filter(array_map('intval', $body['ids'] ?? []));

    if (empty($ids)) respond(false, 'No ids provided.');

    $pdo          = getDB();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // Only mark read if the notification belongs to this user
    $pdo->prepare(
        "UPDATE notifications SET is_read = 1
         WHERE id IN ($placeholders) AND user_id = ?"
    )->execute([...$ids, $user['user_id']]);

    respond(true, 'Marked as read.');
}

// Default: get notifications
$user = requireLogin();
$pdo  = getDB();

$stmt = $pdo->prepare(
    "SELECT n.id, n.type, n.title, n.body, n.booking_ref, n.ticket_ids,
            n.is_read, n.created_at
     FROM notifications n
     WHERE n.user_id = ?
       AND n.type IN ('booking_approved','booking_rejected','feedback_reply')
     ORDER BY n.created_at DESC
     LIMIT 20"
);
$stmt->execute([$user['user_id']]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For approved bookings, attach QR codes so visitor can display them
foreach ($notifications as &$notif) {
    if ($notif['type'] === 'booking_approved' && $notif['ticket_ids']) {
        $ids = json_decode($notif['ticket_ids'], true);
        if ($ids) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $tStmt = $pdo->prepare(
                "SELECT ticket_id, ticket_type, price, visit_date, qr_code
                 FROM tickets WHERE ticket_id IN ($placeholders)"
            );
            $tStmt->execute($ids);
            $notif['tickets'] = $tStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    $notif['ticket_ids'] = json_decode($notif['ticket_ids'] ?? 'null', true);
}
unset($notif);

$unread = array_filter($notifications, fn($n) => !$n['is_read']);

respond(true, 'OK', [
    'notifications' => array_values($notifications),
    'unread_count'  => count($unread),
]);
