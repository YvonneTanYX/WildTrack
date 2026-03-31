<?php
/**
 * api/feedback.php
 *
 * Visitor (no login needed):
 *   POST ?action=submit         { name, email, rating, message }
 *   GET  ?action=my_feedback    (requires visitor login)
 *
 * Admin:
 *   GET  ?action=list           [&rating=&status=&search=&page=]
 *   GET  ?action=stats
 *   POST ?action=reply          { id, reply }
 *   POST ?action=delete_reply   { id }
 *   POST ?action=flag           { id }
 *   POST ?action=unflag         { id }
 *   POST ?action=delete         { id }
 *   POST ?action=mark_read      { ids: [...] }
 */

require_once __DIR__ . '/../config/helpers.php';
session_start();
header('Content-Type: application/json');

$action = $_GET['action'] ?? 'list';
$pdo    = getDB();

/* ── SUBMIT (visitor, no login required) ── */
if ($action === 'submit') {
    $body    = jsonBody();
    $name    = trim($body['name']    ?? '');
    $email   = trim($body['email']   ?? '');
    $rating  = (int)($body['rating'] ?? 0);
    $message = trim($body['message'] ?? '');

    if (!$name || !$email || $rating < 1 || $rating > 5 || !$message) {
        respond(false, 'All fields are required and rating must be 1-5.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Invalid email address.');
    }

    $userId = null;
    $u = $_SESSION['user'] ?? null;
    if ($u && $u['role'] === 'visitor') {
        $userId = (int)$u['user_id'];
    }

    $pdo->prepare(
        "INSERT INTO feedback (user_id, name, email, rating, message, status, is_read)
         VALUES (?, ?, ?, ?, ?, 'pending', 0)"
    )->execute([$userId, $name, $email, $rating, $message]);

    // Notify all active admins
    $admins = $pdo->query(
        "SELECT user_id FROM users WHERE role = 'admin' AND is_active = 1"
    )->fetchAll(PDO::FETCH_COLUMN);

    $nStmt   = $pdo->prepare(
        "INSERT INTO notifications (user_id, type, title, body)
         VALUES (?, 'new_feedback', 'New Feedback Received', ?)"
    );
    $preview = mb_substr($message, 0, 80) . (mb_strlen($message) > 80 ? '...' : '');
    foreach ($admins as $aid) {
        $nStmt->execute([$aid, "{$name} left a {$rating}-star review: {$preview}"]);
    }

    respond(true, 'Feedback submitted. Thank you!');
}

/* ── MY FEEDBACK (visitor: view own submissions + replies) ── */
if ($action === 'my_feedback') {
    $u = $_SESSION['user'] ?? null;
    if (!$u || $u['role'] !== 'visitor') respond(false, 'Login required.');

    $stmt = $pdo->prepare(
        "SELECT id, name, email, rating, message,
                status, admin_reply, replied_at, created_at
         FROM feedback
         WHERE user_id = ?
         ORDER BY created_at DESC
         LIMIT 50"
    );
    $stmt->execute([(int)$u['user_id']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$r) {
        $r['id']     = (int)$r['id'];
        $r['rating'] = (int)$r['rating'];
    }
    unset($r);

    respond(true, 'OK', ['feedback' => $rows]);
}

/* ── All other actions require admin ── */
$admin = $_SESSION['user'] ?? null;
if (!$admin || $admin['role'] !== 'admin') {
    respond(false, 'Unauthorized.');
}

/* ── STATS ── */
if ($action === 'stats') {
    $total   = (int)$pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
    $unread  = (int)$pdo->query("SELECT COUNT(*) FROM feedback WHERE is_read = 0")->fetchColumn();
    $pending = (int)$pdo->query("SELECT COUNT(*) FROM feedback WHERE status = 'pending'")->fetchColumn();
    $avg     = $total ? (float)$pdo->query("SELECT AVG(rating) FROM feedback")->fetchColumn() : 0;

    $dist = [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
    $rows = $pdo->query("SELECT rating, COUNT(*) as cnt FROM feedback GROUP BY rating")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $dist[(int)$r['rating']] = (int)$r['cnt'];
    }

    respond(true, 'OK', [
        'total'     => $total,
        'unread'    => $unread,
        'pending'   => $pending,
        'avg'       => round($avg, 1),
        'breakdown' => $dist,
    ]);
}

/* ── LIST ── */
if ($action === 'list') {
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;
    $offset  = ($page - 1) * $perPage;

    $where  = [];
    $params = [];

    if (!empty($_GET['rating'])) {
        $where[]  = 'f.rating = ?';
        $params[] = (int)$_GET['rating'];
    }
    if (!empty($_GET['status'])) {
        $where[]  = 'f.status = ?';
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['search'])) {
        $s        = '%' . $_GET['search'] . '%';
        $where[]  = '(f.name LIKE ? OR f.email LIKE ? OR f.message LIKE ?)';
        $params[] = $s;
        $params[] = $s;
        $params[] = $s;
    }

    $whereSQL = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $cStmt = $pdo->prepare("SELECT COUNT(*) FROM feedback f $whereSQL");
    $cStmt->execute($params);
    $total = (int)$cStmt->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT f.id, f.user_id, f.name, f.email, f.rating, f.message,
                f.status, f.admin_reply, f.replied_at, f.is_read, f.created_at
         FROM feedback f
         $whereSQL
         ORDER BY f.created_at DESC
         LIMIT $perPage OFFSET $offset"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$r) {
        $r['id']      = (int)$r['id'];
        $r['rating']  = (int)$r['rating'];
        $r['is_read'] = (int)$r['is_read'];
    }
    unset($r);

    respond(true, 'OK', [
        'feedback'    => $rows,
        'total'       => $total,
        'page'        => $page,
        'per_page'    => $perPage,
        'total_pages' => max(1, (int)ceil($total / $perPage)),
    ]);
}

/* ── REPLY ── */
if ($action === 'reply') {
    $body  = jsonBody();
    $id    = (int)($body['id']   ?? 0);
    $reply = trim($body['reply'] ?? '');

    if (!$id || !$reply) respond(false, 'Feedback ID and reply text are required.');

    $fbStmt = $pdo->prepare("SELECT * FROM feedback WHERE id = ?");
    $fbStmt->execute([$id]);
    $fb = $fbStmt->fetch(PDO::FETCH_ASSOC);
    if (!$fb) respond(false, 'Feedback not found.');

    $upd = $pdo->prepare(
        "UPDATE feedback
         SET admin_reply = ?,
             replied_at  = NOW(),
             status      = 'replied',
             is_read     = 1
         WHERE id = ?"
    );
    $upd->execute([$reply, $id]);

    // Visitor in-app notification
    if (!empty($fb['user_id'])) {
        $preview = mb_substr($reply, 0, 100) . (mb_strlen($reply) > 100 ? '...' : '');
        $pdo->prepare(
            "INSERT INTO notifications (user_id, type, title, body)
             VALUES (?, 'feedback_reply', 'Admin replied to your feedback', ?)"
        )->execute([$fb['user_id'], "The zoo team has replied: {$preview}"]);
    }

    respond(true, 'Reply sent.');
}

/* ── DELETE REPLY ── */
if ($action === 'delete_reply') {
    $body = jsonBody();
    $id   = (int)($body['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');

    $pdo->prepare(
        "UPDATE feedback
         SET admin_reply = NULL,
             replied_at  = NULL,
             status      = 'pending'
         WHERE id = ?"
    )->execute([$id]);

    respond(true, 'Reply deleted.');
}

/* ── FLAG ── */
if ($action === 'flag') {
    $body = jsonBody();
    $id   = (int)($body['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare("UPDATE feedback SET status = 'flagged' WHERE id = ?")->execute([$id]);
    respond(true, 'Flagged.');
}

/* ── UNFLAG ── */
if ($action === 'unflag') {
    $body = jsonBody();
    $id   = (int)($body['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare("UPDATE feedback SET status = 'pending' WHERE id = ?")->execute([$id]);
    respond(true, 'Unflagged.');
}

/* ── DELETE ── */
if ($action === 'delete') {
    $body = jsonBody();
    $id   = (int)($body['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare("DELETE FROM feedback WHERE id = ?")->execute([$id]);
    respond(true, 'Deleted.');
}

/* ── MARK READ ── */
if ($action === 'mark_read') {
    $body = jsonBody();
    $ids  = array_filter(array_map('intval', $body['ids'] ?? []));
    if (empty($ids)) respond(false, 'No ids.');
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $pdo->prepare("UPDATE feedback SET is_read = 1 WHERE id IN ($ph)")->execute(array_values($ids));
    respond(true, 'Marked read.');
}

respond(false, 'Unknown action.');
