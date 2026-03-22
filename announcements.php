<?php
/**
 * api/announcements.php
 * Handles announcements (CRUD) and zoo_settings (opening hours, etc.)
 */

// Start session manually — do NOT use check_session.php here because
// that file only allows role=visitor and would redirect admins away.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
header('Cache-Control: no-cache');

/* ── DB connection ──────────────────────────────────────────────────── */
$host    = 'localhost';
$dbname  = 'wildtrack';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $e->getMessage()]);
    exit;
}

/* ── Auth guard ─────────────────────────────────────────────────────── */
$_sess_user = $_SESSION['user'] ?? null;
$_sess_role = $_sess_user['role'] ?? ($_SESSION['role'] ?? '');
$is_admin   = in_array($_sess_role, ['admin', 'worker'], true);

$action = $_GET['action'] ?? '';

/* ── Raw JSON body ──────────────────────────────────────────────────── */
$body = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true) ?: [];
}

/* ── DEBUG: visit ?action=debug_session while logged in as admin ─────── */
if ($action === 'debug_session') {
    echo json_encode([
        'session_id'   => session_id(),
        'session_user' => $_sess_user,
        'session_role' => $_sess_role,
        'is_admin'     => $is_admin,
        'full_session' => $_SESSION,
    ], JSON_PRETTY_PRINT);
    exit;
}

/* ── get_announcements (visitor, no auth) ────────────────────────────── */
if ($action === 'get_announcements') {
    $stmt = $pdo->query(
        "SELECT id, icon, icon_color, title, body, audience, created_at
           FROM announcements WHERE is_active = 1 ORDER BY created_at DESC"
    );
    echo json_encode(['success' => true, 'announcements' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

/* ── get_all_announcements (admin list, no auth needed for read) ─────── */
if ($action === 'get_all_announcements') {
    $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
    echo json_encode(['success' => true, 'announcements' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

/* ── get_settings (no auth needed for read) ─────────────────────────── */
if ($action === 'get_settings') {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM zoo_settings");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out  = [];
    foreach ($rows as $r) { $out[$r['setting_key']] = $r['setting_value']; }
    echo json_encode(['success' => true, 'settings' => $out]);
    exit;
}

/* ── save_settings ───────────────────────────────────────────────────── */
if ($action === 'save_settings') {
    if (!$is_admin) { echo json_encode(['success'=>false,'message'=>'Unauthorised — role: '.$_sess_role]); exit; }
    $allowed  = ['open_time','close_time','last_entry_mins','last_online_purchase_mins'];
    $settings = $body['settings'] ?? [];
    $stmt = $pdo->prepare(
        "INSERT INTO zoo_settings (setting_key, setting_value) VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($allowed as $key) {
        if (isset($settings[$key])) $stmt->execute([':k'=>$key, ':v'=>$settings[$key]]);
    }
    echo json_encode(['success' => true, 'message' => 'Settings saved']);
    exit;
}

/* ── create_announcement ─────────────────────────────────────────────── */
if ($action === 'create_announcement') {
    if (!$is_admin) { echo json_encode(['success'=>false,'message'=>'Unauthorised — role: '.$_sess_role]); exit; }
    $title = trim($body['title'] ?? '');
    $text  = trim($body['body']  ?? '');
    if (!$title || !$text) { echo json_encode(['success'=>false,'message'=>'Title and body required']); exit; }
    $stmt = $pdo->prepare(
        "INSERT INTO announcements (icon, icon_color, title, body, audience, is_active)
         VALUES (:icon, :color, :title, :body, :aud, :active)"
    );
    $stmt->execute([
        ':icon'   => $body['icon']       ?? '📢',
        ':color'  => $body['icon_color'] ?? 'orange',
        ':title'  => $title,
        ':body'   => $text,
        ':aud'    => $body['audience']   ?? 'All Visitors',
        ':active' => isset($body['is_active']) ? (int)$body['is_active'] : 1,
    ]);
    echo json_encode(['success'=>true, 'id'=>(int)$pdo->lastInsertId(), 'message'=>'Created']);
    exit;
}

/* ── update_announcement ─────────────────────────────────────────────── */
if ($action === 'update_announcement') {
    if (!$is_admin) { echo json_encode(['success'=>false,'message'=>'Unauthorised — role: '.$_sess_role]); exit; }
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'Missing id']); exit; }
    $stmt = $pdo->prepare(
        "UPDATE announcements SET icon=:icon, icon_color=:color, title=:title,
         body=:body, audience=:aud, is_active=:active WHERE id=:id"
    );
    $stmt->execute([
        ':icon'   => $body['icon']       ?? '📢',
        ':color'  => $body['icon_color'] ?? 'orange',
        ':title'  => trim($body['title'] ?? ''),
        ':body'   => trim($body['body']  ?? ''),
        ':aud'    => $body['audience']   ?? 'All Visitors',
        ':active' => isset($body['is_active']) ? (int)$body['is_active'] : 1,
        ':id'     => $id,
    ]);
    echo json_encode(['success'=>true,'message'=>'Updated']);
    exit;
}

/* ── toggle_announcement ─────────────────────────────────────────────── */
if ($action === 'toggle_announcement') {
    if (!$is_admin) { echo json_encode(['success'=>false,'message'=>'Unauthorised — role: '.$_sess_role]); exit; }
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'Missing id']); exit; }
    $pdo->prepare("UPDATE announcements SET is_active = 1 - is_active WHERE id=:id")->execute([':id'=>$id]);
    echo json_encode(['success'=>true,'message'=>'Toggled']);
    exit;
}

/* ── delete_announcement ─────────────────────────────────────────────── */
if ($action === 'delete_announcement') {
    if (!$is_admin) { echo json_encode(['success'=>false,'message'=>'Unauthorised — role: '.$_sess_role]); exit; }
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'Missing id']); exit; }
    $pdo->prepare("DELETE FROM announcements WHERE id=:id")->execute([':id'=>$id]);
    echo json_encode(['success'=>true,'message'=>'Deleted']);
    exit;
}

http_response_code(400);
echo json_encode(['success'=>false,'message'=>'Unknown action: '.htmlspecialchars($action)]);
