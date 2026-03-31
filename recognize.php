<?php
require_once __DIR__ . '/../config.php';
session_start();
header('Content-Type: application/json');

$data       = json_decode(file_get_contents('php://input'), true);
$animalName = $data['animal_name'] ?? '';
$confidence = floatval($data['confidence'] ?? 0) * 100;
$userId     = $_SESSION['user_id'] ?? null;

$db = getDB();

// Look up animal_id by name
$stmt = $db->prepare("SELECT animal_id FROM animals WHERE name = ? LIMIT 1");
$stmt->bind_param('s', $animalName);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$animalId = $row['animal_id'] ?? null;

// Log to photo_recognition_logs
$ins = $db->prepare("
    INSERT INTO photo_recognition_logs (user_id, animal_id, photo_path, confidence_score)
    VALUES (?, ?, ?, ?)
");
$ins->bind_param('iisd', $userId, $animalId, $data['photo'], $confidence);
$ins->execute();

echo json_encode(['success' => true, 'animal' => $animalName, 'confidence' => $confidence]);