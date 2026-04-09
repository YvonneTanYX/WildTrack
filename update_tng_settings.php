<?php
require_once __DIR__ . '/../config/helpers.php';
session_start();

$admin = $_SESSION['user'] ?? null;
if (!$admin || $admin['role'] !== 'admin') {
    respond(false, 'Administrators only.');
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    respond(false, 'Only POST allowed.');
}

// --- Validate password -------------------------------------------------
$password = $_POST['password'] ?? '';
if (!$password) {
    respond(false, 'Password is required to change QR settings.');
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->execute([$admin['user_id']]);
$userRow = $stmt->fetch();
if (!$userRow || !password_verify($password, $userRow['password'])) {
    respond(false, 'Incorrect password. Changes not saved.');
}

// --- Get current settings --------------------------------------------
$stmt = $pdo->query("SELECT tng_qr_image, tng_receiver_name FROM admins LIMIT 1");
$current = $stmt->fetch(PDO::FETCH_ASSOC);
$oldQrPath   = $current['tng_qr_image'] ?? null;
$oldReceiver = $current['tng_receiver_name'] ?? 'WildTrack Safari Park';

// --- Prepare new values -----------------------------------------------
$newReceiver = trim($_POST['receiver_name'] ?? '');
if (strlen($newReceiver) < 2) {
    respond(false, 'Receiver name must be at least 2 characters.');
}

$newQrPath = $oldQrPath; // default to old if no upload

// --- Handle file upload (QR image) ------------------------------------
if (isset($_FILES['qr_image']) && $_FILES['qr_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['qr_image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        respond(false, 'Only JPG, PNG or WebP images allowed.');
    }
    if ($file['size'] > 2 * 1024 * 1024) { // 2 MB
        respond(false, 'Image size must be ≤ 2MB.');
    }

    $uploadDir = __DIR__ . '/../uploads/tng_qr/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = 'qr_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $destPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        respond(false, 'Failed to save uploaded file.');
    }
    $newQrPath = 'uploads/tng_qr/' . $newFileName;
}

// --- Update admins table ----------------------------------------------
$updateStmt = $pdo->prepare("
    UPDATE admins SET tng_qr_image = ?, tng_receiver_name = ? WHERE admin_id = 1
");
$updateStmt->execute([$newQrPath, $newReceiver]);

// --- Log to history ---------------------------------------------------
$logStmt = $pdo->prepare("
    INSERT INTO tng_qr_history
        (admin_id, admin_name, old_qr_path, new_qr_path, old_receiver_name, new_receiver_name)
    VALUES (?, ?, ?, ?, ?, ?)
");
$logStmt->execute([
    $admin['user_id'],
    $admin['username'],
    $oldQrPath,
    $newQrPath,
    $oldReceiver,
    $newReceiver
]);

respond(true, 'TNG payment settings updated.', [
    'qr_url'     => $newQrPath ? ('http://localhost/WildTrack/' . $newQrPath) : null,
    'receiver'   => $newReceiver,
]);