<?php
/**
 * api/notifications_worker.php
 * Worker notification CRUD — uses the existing `notifications` table.
 *
 * GET    — fetch notifications for the logged-in worker
 * POST   — insert a new worker notification
 * PATCH  — mark one (action=read) or all (action=read_all) as read
 * DELETE — clear all notifications for this worker
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../check_session.php';

header('Content-Type: application/json');
requireVisitorLogin();

$pdo    = getDB();
$userId = $_SESSION['user']['id'] ?? 0;
$method = $_SERVER['REQUEST_METHOD'];

// Icon mapping for response (type → [icon, iconClass])
$iconMap = [
    'shift_start'      => ['🌅', 'ni-green'],
    'feeding_reminder' => ['🍖', 'ni-orange'],
    'health_alert'     => ['🩺', 'ni-red'],
    'vaccination_due'  => ['💉', 'ni-orange'],
    'incident_flagged' => ['🚨', 'ni-red'],
    'task_assigned'    => ['📋', 'ni-green'],
    'worker_general'   => ['🔔', 'ni-green'],
];

try {
    switch ($method) {

        // ── GET: list latest 50 notifications for this worker ──────────────
        case 'GET':
            $stmt = $pdo->prepare(
                "SELECT id, type, title, body, is_read, created_at
                 FROM notifications
                 WHERE user_id = ?
                 ORDER BY created_at DESC
                 LIMIT 50"
            );
            $stmt->execute([$userId]);
            $rows  = $stmt->fetchAll();
            $items = array_map(function ($r) use ($iconMap) {
                [$icon, $cls] = $iconMap[$r['type']] ?? ['🔔', 'ni-green'];
                return [
                    'id'        => (int)$r['id'],
                    'icon'      => $icon,
                    'iconClass' => $cls,
                    'title'     => $r['title'],
                    'sub'       => $r['body'] ?? '',
                    'unread'    => !(bool)$r['is_read'],
                    'ts'        => strtotime($r['created_at']) * 1000,
                ];
            }, $rows);
            echo json_encode(['success' => true, 'notifications' => $items]);
            break;

        // ── POST: insert a new worker notification ─────────────────────────
        case 'POST':
            $body  = json_decode(file_get_contents('php://input'), true) ?? [];
            $type  = trim($body['type']  ?? 'worker_general');
            $title = trim($body['title'] ?? '');
            $text  = trim($body['body']  ?? '');

            if (!$title) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'title is required']);
                exit;
            }

            $stmt = $pdo->prepare(
                "INSERT INTO notifications (user_id, type, title, body)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$userId, $type, $title, $text]);
            echo json_encode(['success' => true, 'id' => (int)$pdo->lastInsertId()]);
            break;

        // ── PATCH: mark one or all as read ─────────────────────────────────
        case 'PATCH':
            $body   = json_decode(file_get_contents('php://input'), true) ?? [];
            $action = $body['action'] ?? 'read';

            if ($action === 'read_all') {
                $stmt = $pdo->prepare(
                    "UPDATE notifications SET is_read = 1 WHERE user_id = ?"
                );
                $stmt->execute([$userId]);
            } else {
                $nid = (int)($body['id'] ?? 0);
                if ($nid > 0) {
                    $stmt = $pdo->prepare(
                        "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?"
                    );
                    $stmt->execute([$nid, $userId]);
                }
            }
            echo json_encode(['success' => true]);
            break;

        // ── DELETE: clear all notifications for this worker ────────────────
        case 'DELETE':
            $stmt = $pdo->prepare("DELETE FROM notifications WHERE user_id = ?");
            $stmt->execute([$userId]);
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
}
