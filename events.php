<?php
/**
 * api/events.php  –  WildTrack Zoo
 * Uses PDO via getDB() from config/db.php — matches your project's DB pattern.
 * Actions: get_events | create_event | update_event | delete_event | toggle_event
 */

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php'; // provides getDB() → PDO

/* ── Helpers ─────────────────────────────────────────────── */
function isAdmin(): bool {
    $u = $_SESSION['user'] ?? null;
    return $u && ($u['role'] ?? '') === 'admin';
}
function ok(array $extra = []): void {
    echo json_encode(array_merge(['success' => true], $extra)); exit;
}
function err(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $msg]); exit;
}

$action = $_GET['action'] ?? '';
$pdo    = getDB();

/* ════════════════════════════════════════════════════════════
   GET EVENTS  (public – visitor page uses this)
════════════════════════════════════════════════════════════ */
if ($action === 'get_events') {
    $stmt = $pdo->query(
        "SELECT id, session, event_name, event_time, venue, event_date, is_active, sort_order
         FROM zoo_events
         ORDER BY sort_order ASC, event_time ASC"
    );
    $rows = [];
    foreach ($stmt->fetchAll() as $row) {
        $row['event_time_fmt'] = date('g:ia', strtotime($row['event_time']));
        // NULL event_date = recurring daily; send today so JS shows "Today" pill
        $row['display_date']   = $row['event_date'] ?? date('Y-m-d');
        $rows[] = $row;
    }
    ok(['events' => $rows]);
}

/* ── All write actions require admin session ── */
if (!isAdmin()) err('Unauthorised', 401);

/* Read JSON body */
$body = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
}

/* ════════════════════════════════════════════════════════════
   CREATE EVENT
════════════════════════════════════════════════════════════ */
if ($action === 'create_event') {
    $name    = trim($body['event_name']  ?? '');
    $time    = trim($body['event_time']  ?? '');
    $session = trim($body['session']     ?? '');
    $venue   = trim($body['venue']       ?? '');
    $date    = ($body['event_date'] ?? '') ?: null;  // empty string → NULL = recurring
    $active  = isset($body['is_active'])  ? (int)$body['is_active']  : 1;
    $order   = isset($body['sort_order']) ? (int)$body['sort_order'] : 0;

    if (!$name || !$time || !in_array($session, ['morning', 'afternoon'])) {
        err('Event name, time and session are required.');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO zoo_events (session, event_name, event_time, venue, event_date, is_active, sort_order)
         VALUES (:session, :name, :time, :venue, :date, :active, :order)"
    );
    $stmt->execute([
        ':session' => $session, ':name'   => $name,   ':time'   => $time,
        ':venue'   => $venue,   ':date'   => $date,   ':active' => $active,
        ':order'   => $order,
    ]);
    ok(['id' => $pdo->lastInsertId()]);
}

/* ════════════════════════════════════════════════════════════
   UPDATE EVENT
════════════════════════════════════════════════════════════ */
if ($action === 'update_event') {
    $id      = (int)($body['id']          ?? 0);
    $name    = trim($body['event_name']   ?? '');
    $time    = trim($body['event_time']   ?? '');
    $session = trim($body['session']      ?? '');
    $venue   = trim($body['venue']        ?? '');
    $date    = ($body['event_date'] ?? '') ?: null;
    $active  = isset($body['is_active'])  ? (int)$body['is_active']  : 1;
    $order   = isset($body['sort_order']) ? (int)$body['sort_order'] : 0;

    if (!$id || !$name || !$time || !in_array($session, ['morning', 'afternoon'])) {
        err('Invalid data.');
    }

    $stmt = $pdo->prepare(
        "UPDATE zoo_events
            SET session=:session, event_name=:name, event_time=:time,
                venue=:venue, event_date=:date, is_active=:active, sort_order=:order
          WHERE id=:id"
    );
    $stmt->execute([
        ':session' => $session, ':name'   => $name,   ':time'   => $time,
        ':venue'   => $venue,   ':date'   => $date,   ':active' => $active,
        ':order'   => $order,   ':id'     => $id,
    ]);
    ok();
}

/* ════════════════════════════════════════════════════════════
   TOGGLE ACTIVE
════════════════════════════════════════════════════════════ */
if ($action === 'toggle_event') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) err('Invalid id.');
    $pdo->prepare("UPDATE zoo_events SET is_active = 1 - is_active WHERE id = :id")
        ->execute([':id' => $id]);
    ok();
}

/* ════════════════════════════════════════════════════════════
   DELETE EVENT
════════════════════════════════════════════════════════════ */
if ($action === 'delete_event') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) err('Invalid id.');
    $pdo->prepare("DELETE FROM zoo_events WHERE id = :id")
        ->execute([':id' => $id]);
    ok();
}

err('Unknown action.', 400);
