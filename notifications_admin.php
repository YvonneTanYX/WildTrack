<?php
/**
 * api/notifications.php  –  WildTrack Zoo  (ADMIN)
 * Dynamic admin notifications from live DB data.
 * Actions: get | mark_all_read
 *
 * Sources:
 *   1. Pending ticket payments         → tickets table
 *   2. New payment proofs submitted     → notifications table (new_payment_proof)
 *   3. Unread feedback awaiting reply   → feedback table
 *   4. New 5-star reviews               → feedback table
 *   5. Expired specific-date events     → zoo_events table
 */

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/helpers.php';  // provides getDB(), respond()

function isAdmin(): bool {
    $u = $_SESSION['user'] ?? null;
    return $u && ($u['role'] ?? '') === 'admin';
}
function okN(array $extra = []): void {
    echo json_encode(array_merge(['success' => true], $extra)); exit;
}
function errN(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]); exit;
}

if (!isAdmin()) errN('Unauthorised', 401);

$action = $_GET['action'] ?? 'get';
$pdo    = getDB();

/* ════════════════════════════════════════════════════════════
   MARK ALL READ — stores timestamp in session
════════════════════════════════════════════════════════════ */
if ($action === 'mark_all_read') {
    $_SESSION['admin_notif_last_read'] = date('Y-m-d H:i:s');
    okN();
}

/* ════════════════════════════════════════════════════════════
   GET — build notification list from live DB
════════════════════════════════════════════════════════════ */
if ($action === 'get') {
    $notifications = [];
    // Use session timestamp to determine "read" state; default to 30 days ago
    // so notifications before that are always treated as read (no flood on first load)
    $lastRead = $_SESSION['admin_notif_last_read'] ?? date('Y-m-d H:i:s', strtotime('-30 days'));
    $today    = date('Y-m-d');

    /* ── 1. Pending ticket payments ── */
    try {
        $row = $pdo->query(
            "SELECT COUNT(DISTINCT booking_ref) AS cnt
               FROM tickets
              WHERE status = 'pending'"
        )->fetch();
        $pendingCount = (int)($row['cnt'] ?? 0);
        if ($pendingCount > 0) {
            $notifications[] = [
                'id'       => 'pending_tickets',
                'type'     => 'orange',
                'icon'     => 'ticket',
                'title'    => $pendingCount . ' booking' . ($pendingCount > 1 ? 's' : '') . ' pending approval',
                'sub'      => 'Require your review — click to go to Ticketing',
                'is_read'  => false,   // always unread while pending exist
                'action'   => 'ticketing',
                'priority' => 1,
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 2. New payment proofs submitted (unread, sent to admin user_id=5) ──
       The notifications table has type='new_payment_proof' rows written by
       payment_proof.php whenever a visitor uploads their proof.              */
    try {
        $stmt = $pdo->prepare(
            "SELECT n.id, n.title, n.body, n.booking_ref, n.created_at
               FROM notifications n
              WHERE n.type = 'new_payment_proof'
                AND n.is_read = 0
              ORDER BY n.created_at DESC
              LIMIT 10"
        );
        $stmt->execute();
        $newProofs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($newProofs as $np) {
            $notifications[] = [
                'id'       => 'proof_' . $np['id'],
                'type'     => 'orange',
                'icon'     => 'ticket',
                'title'    => 'New payment proof: ' . ($np['booking_ref'] ?? ''),
                'sub'      => $np['body'] ?? 'Visitor uploaded payment screenshot',
                'is_read'  => false,
                'action'   => 'ticketing',
                'priority' => 1,
                '_notif_id' => (int)$np['id'],  // for marking read in notifications table
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 3. Unread feedback awaiting reply ── */
    try {
        $row = $pdo->query(
            "SELECT COUNT(*) AS cnt FROM feedback WHERE status = 'pending'"
        )->fetch();
        $unreadFb = (int)($row['cnt'] ?? 0);
        if ($unreadFb > 0) {
            // Check if this count changed since last read to determine read state
            $isReadFb = isset($_SESSION['admin_notif_last_read'])
                && isset($_SESSION['admin_fb_count_at_read'])
                && $_SESSION['admin_fb_count_at_read'] >= $unreadFb;
            $notifications[] = [
                'id'       => 'unread_feedback',
                'type'     => 'blue',
                'icon'     => 'message',
                'title'    => $unreadFb . ' feedback' . ($unreadFb > 1 ? 's' : '') . ' awaiting reply',
                'sub'      => 'Visit Feedback & Reviews to respond',
                'is_read'  => $isReadFb,
                'action'   => 'feedback',
                'priority' => 3,
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 4. New 5-star reviews since last panel read ── */
    try {
        $stmt = $pdo->prepare(
            "SELECT f.id, f.name, f.rating, f.created_at
               FROM feedback f
              WHERE f.rating = 5
                AND f.created_at > :lastRead
              ORDER BY f.created_at DESC
              LIMIT 5"
        );
        $stmt->execute([':lastRead' => $lastRead]);
        $newFive = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($newFive as $fb) {
            $name = $fb['name'] ?: 'A visitor';
            $notifications[] = [
                'id'       => 'review_5star_' . $fb['id'],
                'type'     => 'green',
                'icon'     => 'star',
                'title'    => 'New 5-star review',
                'sub'      => $name . ' left a 5-star rating',
                'is_read'  => false,
                'action'   => 'feedback',
                'priority' => 4,
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 5. Expired specific-date events still marked active ── */
    try {
        $stmt = $pdo->prepare(
            "SELECT id, event_name, event_date FROM zoo_events
              WHERE event_date IS NOT NULL
                AND event_date < :today
                AND is_active = 1
              ORDER BY event_date DESC
              LIMIT 10"
        );
        $stmt->execute([':today' => $today]);
        $expired = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($expired) {
            // Auto-deactivate them in the DB right now
            $ids          = array_column($expired, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $pdo->prepare("UPDATE zoo_events SET is_active = 0 WHERE id IN ($placeholders)")
                ->execute($ids);

            foreach ($expired as $ev) {
                $notifications[] = [
                    'id'       => 'expired_event_' . $ev['id'],
                    'type'     => 'orange',
                    'icon'     => 'calendar',
                    'title'    => 'Event expired: ' . $ev['event_name'],
                    'sub'      => 'Date ' . $ev['event_date'] . ' has passed — auto-deactivated',
                    'is_read'  => false,
                    'action'   => 'events',
                    'priority' => 2,
                ];
            }
        }
    } catch (\Throwable $e) {}

    // Sort: unread first, then by priority
    usort($notifications, function ($a, $b) {
        if ($a['is_read'] !== $b['is_read']) return $a['is_read'] ? 1 : -1;
        return $a['priority'] - $b['priority'];
    });

    $unreadCount = count(array_filter($notifications, fn($n) => !$n['is_read']));

    // Store feedback count at read time so we can detect changes
    if ($action === 'get' && isset($_SESSION['admin_notif_last_read'])) {
        $fbCountNow = 0;
        foreach ($notifications as $n) {
            if ($n['id'] === 'unread_feedback') {
                preg_match('/^(\d+)/', $n['title'], $m);
                $fbCountNow = (int)($m[1] ?? 0);
            }
        }
        $_SESSION['admin_fb_count_at_read'] = $fbCountNow;
    }

    okN([
        'notifications' => $notifications,
        'unread_count'  => $unreadCount,
    ]);
}

errN('Unknown action.', 400);
