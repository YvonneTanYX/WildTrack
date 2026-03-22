<?php
require_once __DIR__ . '/config/helpers.php';
session_start();

$user = requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(false, 'POST only.');

$pdo = getDB();

// ── Support both FormData (file upload) and JSON (URL only) ───────
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isJson      = str_contains($contentType, 'application/json');

if ($isJson) {
  $data    = jsonBody();
  $title   = clean($data['title']          ?? '');
  $alt     = clean($data['alt_text']       ?? $title);
  $show    = isset($data['show_in_slider']) ? (int)$data['show_in_slider'] : 1;
  $order   = (int)($data['sort_order']     ?? 0);
  $url     = clean($data['image_url']      ?? '');
  $editId  = (int)($data['edit_id']        ?? 0);
} else {
  $title   = clean($_POST['title']          ?? '');
  $alt     = clean($_POST['alt_text']       ?? $title);
  $show    = isset($_POST['show_in_slider']) ? (int)$_POST['show_in_slider'] : 1;
  $order   = (int)($_POST['sort_order']     ?? 0);
  $url     = clean($_POST['image_url']      ?? '');
  $editId  = (int)($_POST['edit_id']        ?? 0);
}

if (!$title) respond(false, 'Title is required.');

// ── If editing an existing record with no new file ─────────────────
if ($editId && empty($_FILES['image']['name'])) {
  if ($url) {
    $stmt = $pdo->prepare("UPDATE slider_images SET title=?, image_url=?, alt_text=?, show_in_slider=?, sort_order=? WHERE id=?");
    $stmt->execute([$title, $url, $alt, $show, $order, $editId]);
  } else {
    $stmt = $pdo->prepare("UPDATE slider_images SET title=?, alt_text=?, show_in_slider=?, sort_order=? WHERE id=?");
    $stmt->execute([$title, $alt, $show, $order, $editId]);
  }
  respond(true, 'Image updated.');
}

// ── URL-only new entry ─────────────────────────────────────────────
if ($url && empty($_FILES['image']['name'])) {
  $stmt = $pdo->prepare("INSERT INTO slider_images (title, image_url, alt_text, show_in_slider, sort_order) VALUES (?,?,?,?,?)");
  $stmt->execute([$title, $url, $alt, $show, $order]);
  respond(true, 'Image added.', ['id' => $pdo->lastInsertId(), 'image_url' => $url]);
}

// ── File upload ────────────────────────────────────────────────────
$uploadDir = __DIR__ . '/uploads/slider/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
  respond(false, 'No file uploaded or upload error.');
}

$file    = $_FILES['image'];
$maxSize = 10 * 1024 * 1024; // 10 MB
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

if ($file['size'] > $maxSize) respond(false, 'File too large (max 10 MB).');

$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (!in_array($mimeType, $allowed)) respond(false, 'Invalid file type. Only JPG, PNG, WebP, GIF allowed.');

$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = uniqid('slider_', true) . '.' . $ext;
$destPath = $uploadDir . $filename;
$webPath  = 'uploads/slider/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) respond(false, 'Failed to save file.');

if ($editId) {
  $stmt = $pdo->prepare("UPDATE slider_images SET title=?, image_url=?, alt_text=?, show_in_slider=?, sort_order=? WHERE id=?");
  $stmt->execute([$title, $webPath, $alt, $show, $order, $editId]);
  respond(true, 'Image updated.', ['image_url' => $webPath]);
} else {
  $stmt = $pdo->prepare("INSERT INTO slider_images (title, image_url, alt_text, show_in_slider, sort_order) VALUES (?,?,?,?,?)");
  $stmt->execute([$title, $webPath, $alt, $show, $order]);
  respond(true, 'Image uploaded.', ['id' => $pdo->lastInsertId(), 'image_url' => $webPath]);
}
