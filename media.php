<?php
require_once __DIR__ . '/../config/helpers.php';
session_start();

$user = requireRole('admin');

$pdo    = getDB();
$action = $_GET['action'] ?? '';

switch ($action) {

  // ── List all images (admin) ────────────────────────────────────────
  case 'list':
    $rows = $pdo->query("SELECT * FROM slider_images ORDER BY sort_order ASC, uploaded_at DESC")->fetchAll();
    respond(true, 'ok', ['images' => $rows]);
    break;

  // ── List only LIVE images (visitor mainPage uses this) ─────────────
  case 'list_live':
    $rows = $pdo->query("SELECT id, image_url, alt_text FROM slider_images WHERE show_in_slider = 1 ORDER BY sort_order ASC, uploaded_at DESC")->fetchAll();
    respond(true, 'ok', ['images' => $rows]);
    break;

  // ── Add new image via URL (no file upload) ─────────────────────────
  case 'upload':
    $data  = jsonBody();
    $title = clean($data['title'] ?? '');
    $url   = clean($data['image_url'] ?? '');
    $alt   = clean($data['alt_text'] ?? $title);
    $show  = isset($data['show_in_slider']) ? (int)$data['show_in_slider'] : 1;
    $order = (int)($data['sort_order'] ?? 0);

    if (!$title || !$url) respond(false, 'Title and image URL are required.');

    $stmt = $pdo->prepare("INSERT INTO slider_images (title, image_url, alt_text, show_in_slider, sort_order) VALUES (?,?,?,?,?)");
    $stmt->execute([$title, $url, $alt, $show, $order]);
    respond(true, 'Image added.', ['id' => $pdo->lastInsertId()]);
    break;

  // ── Update existing image details ──────────────────────────────────
  case 'update':
    $data  = jsonBody();
    $id    = (int)($data['id'] ?? 0);
    $title = clean($data['title'] ?? '');
    $url   = clean($data['image_url'] ?? '');
    $alt   = clean($data['alt_text'] ?? '');
    $show  = isset($data['show_in_slider']) ? (int)$data['show_in_slider'] : 1;
    $order = (int)($data['sort_order'] ?? 0);

    if (!$id || !$title) respond(false, 'ID and title are required.');

    if ($url) {
      $stmt = $pdo->prepare("UPDATE slider_images SET title=?, image_url=?, alt_text=?, show_in_slider=?, sort_order=? WHERE id=?");
      $stmt->execute([$title, $url, $alt, $show, $order, $id]);
    } else {
      $stmt = $pdo->prepare("UPDATE slider_images SET title=?, alt_text=?, show_in_slider=?, sort_order=? WHERE id=?");
      $stmt->execute([$title, $alt, $show, $order, $id]);
    }
    respond(true, 'Image updated.');
    break;

  // ── Toggle show_in_slider ──────────────────────────────────────────
  case 'toggle':
    $data = jsonBody();
    $id   = (int)($data['id'] ?? 0);
    if (!$id) respond(false, 'Invalid ID.');

    $pdo->prepare("UPDATE slider_images SET show_in_slider = 1 - show_in_slider WHERE id = ?")->execute([$id]);
    $val = $pdo->prepare("SELECT show_in_slider FROM slider_images WHERE id = ?");
    $val->execute([$id]);
    respond(true, 'Toggled.', ['show_in_slider' => (int)$val->fetchColumn()]);
    break;

  // ── Delete image ───────────────────────────────────────────────────
  case 'delete':
    $data = jsonBody();
    $id   = (int)($data['id'] ?? 0);
    if (!$id) respond(false, 'Invalid ID.');

    // Delete local file if stored on server
    $row = $pdo->prepare("SELECT image_url FROM slider_images WHERE id = ?");
    $row->execute([$id]);
    $imgUrl = $row->fetchColumn();
    if ($imgUrl && strpos($imgUrl, 'http') !== 0) {
      $localPath = __DIR__ . '/../' . $imgUrl;
      if (file_exists($localPath)) unlink($localPath);
    }

    $pdo->prepare("DELETE FROM slider_images WHERE id = ?")->execute([$id]);
    respond(true, 'Image deleted.');
    break;

  default:
    respond(false, 'Unknown action.');
}
