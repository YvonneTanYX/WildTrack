<?php
/**
 * api/notifications_admin.php – WildTrack Zoo (ADMIN)
 * Dynamic admin notifications from live DB data.
 * Actions: get | mark_all_read
 */

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/helpers.php';

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
$adminId = $_SESSION['user']['user_id'];

// Helper: get/set unified last read timestamp for this admin
function getAdminLastRead(PDO $pdo, int $adminId): string {
    $stmt = $pdo->prepare("SELECT setting_value FROM zoo_settings WHERE setting_key = ?");
    $stmt->execute(["admin_last_read_all_{$adminId}"]);
    $row = $stmt->fetch();
    return $row ? $row['setting_value'] : '1970-01-01 00:00:00';
}

function setAdminLastRead(PDO $pdo, int $adminId, string $timestamp): void {
    $pdo->prepare(
        "INSERT INTO zoo_settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    )->execute(["admin_last_read_all_{$adminId}", $timestamp]);
}

/* ════════════════════════════════════════════════════════════
   MARK ALL READ — store current timestamp and mark DB notifications
════════════════════════════════════════════════════════════ */
if ($action === 'mark_all_read') {
    $now = date('Y-m-d H:i:s');
    
    // 1. Store unified last read timestamp
    setAdminLastRead($pdo, $adminId, $now);
    
    // 2. Mark ALL system notifications (new_payment_proof, new_staff, etc.) as read for this admin
    $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$adminId]);
    
    okN();
}

/* ════════════════════════════════════════════════════════════
   GET — build notification list
════════════════════════════════════════════════════════════ */
if ($action === 'get') {
    $notifications = [];
    $lastReadAll = getAdminLastRead($pdo, $adminId);
    $today = date('Y-m-d');

    // Load notification preferences
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
    } catch (\Throwable $e) {}

    /* ── 1. Pending ticket payments (aggregated) ── */
    if ($prefs['tickets']) {
        try {
            $row = $pdo->query(
                "SELECT COUNT(DISTINCT booking_ref) AS cnt, MAX(purchase_date) as latest
                 FROM tickets WHERE status = 'pending'"
            )->fetch();
            $pendingCount = (int)($row['cnt'] ?? 0);
            $latestTime = $row['latest'] ?? '1970-01-01';
            if ($pendingCount > 0) {
                $isRead = ($latestTime <= $lastReadAll);
                $notifications[] = [
                    'id'         => 'pending_tickets',
                    'type'       => 'orange',
                    'icon'       => 'ticket',
                    'title'      => $pendingCount . ' booking' . ($pendingCount > 1 ? 's' : '') . ' pending approval',
                    'sub'        => 'Require your review — click to go to Ticketing',
                    'is_read'    => $isRead,
                    'action'     => 'ticketing',
                    'priority'   => 1,
                    'created_at' => $latestTime,
                ];
            }
        } catch (\Throwable $e) {}
    }

    /* ── 2. Individual payment proofs from notifications table ── */
    if ($prefs['tickets']) {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, title, body, booking_ref, created_at
                 FROM notifications
                 WHERE type = 'new_payment_proof' AND user_id = ? AND is_read = 0
                 ORDER BY created_at DESC
                 LIMIT 10"
            );
            $stmt->execute([$adminId]);
            $newProofs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($newProofs as $np) {
                $isRead = ($np['created_at'] <= $lastReadAll);
                $notifications[] = [
                    'id'         => 'proof_' . $np['id'],
                    'type'       => 'orange',
                    'icon'       => 'ticket',
                    'title'      => 'New payment proof: ' . ($np['booking_ref'] ?? ''),
                    'sub'        => $np['body'] ?? 'Visitor uploaded payment screenshot',
                    'is_read'    => $isRead,
                    'action'     => 'ticketing',
                    'priority'   => 1,
                    'created_at' => $np['created_at'],
                    '_notif_id'  => (int)$np['id'],
                ];
            }
        } catch (\Throwable $e) {}
    }

    /* ── 3. Unread feedback awaiting reply (aggregated) ── */
    if ($prefs['reviews']) {
        try {
            $row = $pdo->query(
                "SELECT COUNT(*) AS cnt, MAX(created_at) as latest
                 FROM feedback WHERE status = 'pending'"
            )->fetch();
            $unreadFb = (int)($row['cnt'] ?? 0);
            $latestFb = $row['latest'] ?? '1970-01-01';
            if ($unreadFb > 0) {
                $isRead = ($latestFb <= $lastReadAll);
                $notifications[] = [
                    'id'         => 'unread_feedback',
                    'type'       => 'blue',
                    'icon'       => 'message',
                    'title'      => $unreadFb . ' feedback' . ($unreadFb > 1 ? 's' : '') . ' awaiting reply',
                    'sub'        => 'Visit Feedback & Reviews to respond',
                    'is_read'    => $isRead,
                    'action'     => 'feedback',
                    'priority'   => 3,
                    'created_at' => $latestFb,
                ];
            }
        } catch (\Throwable $e) {}
    }

    /* ── 4. New 5-star reviews (individual, only if created after last read) ── */
    if ($prefs['stars']) {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, name, rating, created_at
                 FROM feedback
                 WHERE rating = 5 AND created_at > :lastRead
                 ORDER BY created_at DESC
                 LIMIT 5"
            );
            $stmt->execute([':lastRead' => $lastReadAll]);
            $newFive = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($newFive as $fb) {
                $name = $fb['name'] ?: 'A visitor';
                $notifications[] = [
                    'id'         => 'review_5star_' . $fb['id'],
                    'type'       => 'green',
                    'icon'       => 'star',
                    'title'      => 'New 5-star review',
                    'sub'        => $name . ' left a 5-star rating',
                    'is_read'    => false, // always unread because created > lastRead
                    'action'     => 'feedback',
                    'priority'   => 4,
                    'created_at' => $fb['created_at'],
                ];
            }
        } catch (\Throwable $e) {}
    }

    /* ── 5. Expired events ── */
    if ($prefs['events']) {
        try {
            $stmt = $pdo->prepare(
                "SELECT id, event_name, event_date FROM zoo_events
                 WHERE event_date IS NOT NULL AND event_date < :today AND is_active = 1
                 ORDER BY event_date DESC LIMIT 10"
            );
            $stmt->execute([':today' => $today]);
            $expired = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if ($expired) {
                $ids = array_column($expired, 'id');
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $pdo->prepare("UPDATE zoo_events SET is_active = 0 WHERE id IN ($placeholders)")
                    ->execute($ids);
                foreach ($expired as $ev) {
                    $eventTime = $ev['event_date'] . ' 23:59:59';
                    $isRead = ($eventTime <= $lastReadAll);
                    $notifications[] = [
                        'id'         => 'expired_event_' . $ev['id'],
                        'type'       => 'orange',
                        'icon'       => 'calendar',
                        'title'      => 'Event expired: ' . $ev['event_name'],
                        'sub'        => 'Date ' . $ev['event_date'] . ' has passed — auto-deactivated',
                        'is_read'    => $isRead,
                        'action'     => 'events',
                        'priority'   => 2,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                }
            }
        } catch (\Throwable $e) {}
    }

    /* ── 6. New staff accounts (from notifications table) ── */
    try {
        $stmt = $pdo->prepare(
            "SELECT id, title, body, created_at
             FROM notifications
             WHERE type = 'new_staff' AND user_id = ? AND is_read = 0
             ORDER BY created_at DESC LIMIT 10"
        );
        $stmt->execute([$adminId]);
        $newStaff = $stmt->fetchAll();
        foreach ($newStaff as $ns) {
            $isRead = ($ns['created_at'] <= $lastReadAll);
            $notifications[] = [
                'id'         => 'staff_' . $ns['id'],
                'type'       => 'blue',
                'icon'       => 'user-plus',
                'title'      => $ns['title'],
                'sub'        => $ns['body'],
                'is_read'    => $isRead,
                'action'     => 'staff',
                'priority'   => 2,
                'created_at' => $ns['created_at'],
                '_notif_id'  => (int)$ns['id'],
            ];
        }
    } catch (\Throwable $e) {}

    // Final sort: unread first, then newest first
    usort($notifications, function ($a, $b) {
        if ($a['is_read'] !== $b['is_read']) return $a['is_read'] ? 1 : -1;
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    $unreadCount = count(array_filter($notifications, fn($n) => !$n['is_read']));

    okN([
        'notifications' => $notifications,
        'unread_count'  => $unreadCount,
    ]);
}

errN('Unknown action.', 400);