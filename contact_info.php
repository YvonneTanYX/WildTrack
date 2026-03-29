<?php
/**
 * api/contact_info.php
 *
 * Public:
 *   GET ?action=get_public   -> active cards
 *
 * Admin:
 *   GET  ?action=list
 *   POST ?action=create   { icon, department, phone, email, sort_order }
 *   POST ?action=update   { id, icon, department, phone, email, sort_order }
 *   POST ?action=toggle   { id }
 *   POST ?action=delete   { id }
 */

require_once __DIR__ . '/../config/helpers.php';
session_start();
header('Content-Type: application/json');

$action = $_GET['action'] ?? 'get_public';
$pdo    = getDB();

/* ── PUBLIC ── */
if ($action === 'get_public') {
    $rows = $pdo->query(
        "SELECT id, icon, department, phone, email
         FROM contact_info
         WHERE is_active = 1
         ORDER BY sort_order ASC, id ASC"
    )->fetchAll(PDO::FETCH_ASSOC);
    respond(true, 'OK', ['contacts' => $rows]);
}

/* ── Admin only ── */
$admin = $_SESSION['user'] ?? null;
if (!$admin || $admin['role'] !== 'admin') {
    respond(false, 'Unauthorized.');
}

if ($action === 'list') {
    $rows = $pdo->query(
        "SELECT * FROM contact_info ORDER BY sort_order ASC, id ASC"
    )->fetchAll(PDO::FETCH_ASSOC);
    respond(true, 'OK', ['contacts' => $rows]);
}

if ($action === 'create') {
    $b    = jsonBody();
    $dept = trim($b['department'] ?? '');
    if (!$dept) respond(false, 'Department name is required.');
    $pdo->prepare(
        "INSERT INTO contact_info (icon, department, phone, email, sort_order)
         VALUES (?, ?, ?, ?, ?)"
    )->execute([
        trim($b['icon']       ?? '') ?: '📞',
        $dept,
        trim($b['phone']      ?? '') ?: null,
        trim($b['email']      ?? '') ?: null,
        (int)($b['sort_order'] ?? 0),
    ]);
    respond(true, 'Contact card created.');
}

if ($action === 'update') {
    $b  = jsonBody();
    $id = (int)($b['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare(
        "UPDATE contact_info
         SET icon = ?, department = ?, phone = ?, email = ?, sort_order = ?
         WHERE id = ?"
    )->execute([
        trim($b['icon']       ?? '') ?: '📞',
        trim($b['department'] ?? ''),
        trim($b['phone']      ?? '') ?: null,
        trim($b['email']      ?? '') ?: null,
        (int)($b['sort_order'] ?? 0),
        $id,
    ]);
    respond(true, 'Updated.');
}

if ($action === 'toggle') {
    $b  = jsonBody();
    $id = (int)($b['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare("UPDATE contact_info SET is_active = 1 - is_active WHERE id = ?")->execute([$id]);
    respond(true, 'Toggled.');
}

if ($action === 'delete') {
    $b  = jsonBody();
    $id = (int)($b['id'] ?? 0);
    if (!$id) respond(false, 'ID required.');
    $pdo->prepare("DELETE FROM contact_info WHERE id = ?")->execute([$id]);
    respond(true, 'Deleted.');
}

respond(false, 'Unknown action.');
