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

    // Store pending count at time of mark-read so new ones will appear unread
    try {
        $row = $pdo->query("SELECT COUNT(DISTINCT booking_ref) AS cnt FROM tickets WHERE status = 'pending'")->fetch();
        $_SESSION['admin_pending_count_at_read'] = (int)($row['cnt'] ?? 0);
    } catch (\Throwable $e) {
        $_SESSION['admin_pending_count_at_read'] = 0;
    }

    // Store feedback count at time of mark-read
    try {
        $row = $pdo->query("SELECT COUNT(*) AS cnt FROM feedback WHERE status = 'pending'")->fetch();
        $_SESSION['admin_fb_count_at_read'] = (int)($row['cnt'] ?? 0);
    } catch (\Throwable $e) {
        $_SESSION['admin_fb_count_at_read'] = 0;
    }

    okN();
}

/* ════════════════════════════════════════════════════════════
   GET — build notification list from live DB
════════════════════════════════════════════════════════════ */
if ($action === 'get') {
    $notifications = [];
    $lastRead = $_SESSION['admin_notif_last_read'] ?? date('Y-m-d H:i:s', strtotime('-30 days'));
    $today    = date('Y-m-d');

    // Load notification preferences from zoo_settings (default all ON)
    $prefs = ['tickets' => true, 'reviews' => true, 'events' => true, 'stars' => true];
    try {
        $prefStmt = $pdo->query(
            "SELECT setting_key, setting_value FROM zoo_settings
              WHERE setting_key IN ('notif_pref_tickets','notif_pref_reviews','notif_pref_events','notif_pref_stars')"
        );
        foreach ($prefStmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $k = str_replace('notif_pref_', '', $row['setting_key']);
            $prefs[$k] = ($row['setting_value'] !== '0');
        }
    } catch (\Throwable $e) {}   // table/cols missing → use defaults

    /* ── 1. Pending ticket payments ── */
    if ($prefs['tickets']) try {
        $row = $pdo->query(
            "SELECT COUNT(DISTINCT booking_ref) AS cnt
               FROM tickets
              WHERE status = 'pending'"
        )->fetch();
        $pendingCount = (int)($row['cnt'] ?? 0);
        if ($pendingCount > 0) {
            // Treat as read if admin has marked-all-read AND pending count hasn't grown since
            $isReadTickets = isset($_SESSION['admin_notif_last_read'])
                && isset($_SESSION['admin_pending_count_at_read'])
                && (int)$_SESSION['admin_pending_count_at_read'] >= $pendingCount;
            $notifications[] = [
                'id'       => 'pending_tickets',
                'type'     => 'orange',
                'icon'     => 'ticket',
                'title'    => $pendingCount . ' booking' . ($pendingCount > 1 ? 's' : '') . ' pending approval',
                'sub'      => 'Require your review — click to go to Ticketing',
                'is_read'  => $isReadTickets,
                'action'   => 'ticketing',
                'priority' => 1,
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 2. New unread payment proofs from notifications table ── */
    if ($prefs['tickets']) try {
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
            // Respect mark-all-read: if the proof was created before lastRead, treat as read
            $isReadProof = isset($_SESSION['admin_notif_last_read'])
                && $np['created_at'] <= $_SESSION['admin_notif_last_read'];
            $notifications[] = [
                'id'        => 'proof_' . $np['id'],
                'type'      => 'orange',
                'icon'      => 'ticket',
                'title'     => 'New payment proof: ' . ($np['booking_ref'] ?? ''),
                'sub'       => $np['body'] ?? 'Visitor uploaded payment screenshot',
                'is_read'   => $isReadProof,
                'action'    => 'ticketing',
                'priority'  => 1,
                '_notif_id' => (int)$np['id'],
            ];
        }
    } catch (\Throwable $e) {}

    /* ── 3. Unread feedback awaiting reply ── */
    if ($prefs['reviews']) try {
        $row = $pdo->query(
            "SELECT COUNT(*) AS cnt FROM feedback WHERE status = 'pending'"
        )->fetch();
        $unreadFb = (int)($row['cnt'] ?? 0);
        if ($unreadFb > 0) {
            // Read if admin has marked-all-read AND no new feedback has arrived since then
            $isReadFb = isset($_SESSION['admin_notif_last_read'])
                && isset($_SESSION['admin_fb_count_at_read'])
                && (int)$_SESSION['admin_fb_count_at_read'] >= $unreadFb;
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

    /* ── 4. New 5-star reviews ── */
    if ($prefs['stars']) try {
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

    /* ── 5. Expired specific-date events ── */
    if ($prefs['events']) try {
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
            $ids          = array_column($expired, 'id');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $pdo->prepare("UPDATE zoo_events SET is_active = 0 WHERE id IN ($placeholders)")
                ->execute($ids);
            foreach ($expired as $ev) {
                // Respect mark-all-read: expired events that were already seen before lastRead are read
                $isReadEvent = isset($_SESSION['admin_notif_last_read'])
                    && $ev['event_date'] . ' 23:59:59' <= $_SESSION['admin_notif_last_read'];
                $notifications[] = [
                    'id'       => 'expired_event_' . $ev['id'],
                    'type'     => 'orange',
                    'icon'     => 'calendar',
                    'title'    => 'Event expired: ' . $ev['event_name'],
                    'sub'      => 'Date ' . $ev['event_date'] . ' has passed — auto-deactivated',
                    'is_read'  => $isReadEvent,
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

    okN([
        'notifications' => $notifications,
        'unread_count'  => $unreadCount,
    ]);
}

errN('Unknown action.', 400);
