<?php
/**
 * api/MapData.php  — Zoo Map data API
 * Location: /WildTrack/api/MapData.php
 *
 * FIXES applied:
 *  1. map_settings PK is map_id (not 'id')
 *  2. map_image needs LONGTEXT (base64 images are large)
 *  3. animals uses 'zone' column (not 'location_name')
 *  4. pins table auto-created if missing
 *  5. Raises MySQL max_allowed_packet for this session so large base64 saves work
 *  6. Added GET endpoints: ?animals_by_zone and ?animal_species for map editor
 *  7. Strip HTML tags from pin description on save to prevent clickable links
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/../config/db.php';

$pdo = getDB();

// ── Raise packet size so large base64 map images can be saved ────────────────
try { $pdo->exec("SET SESSION max_allowed_packet = 67108864"); } catch (Exception $e) {}

// ── Auto-create pins table if missing ────────────────────────────────────────
$pdo->exec("
    CREATE TABLE IF NOT EXISTS `pins` (
        `id`      VARCHAR(64)  NOT NULL,
        `name`    VARCHAR(100) NOT NULL DEFAULT '',
        `emoji`   VARCHAR(10)  NOT NULL DEFAULT '📍',
        `color`   VARCHAR(20)  NOT NULL DEFAULT '#2D5A27',
        `light`   VARCHAR(20)  NOT NULL DEFAULT '#EAF1E8',
        `zone`    VARCHAR(50)  NOT NULL DEFAULT '',
        `descr`   TEXT,
        `animals` TEXT,
        `pos_x`   FLOAT        NOT NULL DEFAULT 50,
        `pos_y`   FLOAT        NOT NULL DEFAULT 50,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ── Ensure map_image column is LONGTEXT (upgrade TEXT if needed) ──────────────
try {
    $pdo->exec("ALTER TABLE `map_settings` MODIFY COLUMN `map_image` LONGTEXT DEFAULT NULL");
} catch (Exception $e) {} // already correct type — ignore

// ── Ensure map_settings row 1 exists ─────────────────────────────────────────
$pdo->exec("INSERT IGNORE INTO `map_settings` (`map_id`, `map_image`) VALUES (1, '')");

$method = $_SERVER['REQUEST_METHOD'];

// ═══════════════════════════════════════════════════════════════════════════════
// GET ?animals_by_zone=Zone+A  — list of animal names in that zone
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'GET' && isset($_GET['animals_by_zone'])) {
    try {
        $stmt = $pdo->prepare(
            "SELECT name FROM animals WHERE zone = ? ORDER BY name ASC"
        );
        $stmt->execute([$_GET['animals_by_zone']]);
        $animals = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode(['animals' => $animals]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════════
// GET ?animal_species=AnimalName  — returns species
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'GET' && isset($_GET['animal_species'])) {
    try {
        $stmt = $pdo->prepare(
            "SELECT species FROM animals WHERE name = ?"
        );
        $stmt->execute([$_GET['animal_species']]);
        $species = $stmt->fetchColumn();
        echo json_encode(['species' => $species ?: '']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════════
// GET ?zones  — distinct zone values from animals.zone
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'GET' && isset($_GET['zones'])) {
    try {
        $rows = $pdo->query(
            "SELECT DISTINCT zone AS location_name
             FROM animals
             WHERE zone IS NOT NULL AND zone != ''
             ORDER BY zone ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['zones' => $rows]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════════
// GET ?zone=Zone+A  — animals in that zone (used by other parts)
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'GET' && isset($_GET['zone'])) {
    try {
        $stmt = $pdo->prepare(
            "SELECT name FROM animals WHERE zone = ? ORDER BY name ASC"
        );
        $stmt->execute([$_GET['zone']]);
        echo json_encode(['animals' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════════
// GET  — map image + all pins
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'GET') {
    try {
        $pins = [];
        foreach ($pdo->query("SELECT * FROM pins")->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $pins[] = [
                'id'      => $row['id'],
                'name'    => $row['name'],
                'emoji'   => $row['emoji'],
                'color'   => $row['color'],
                'light'   => $row['light'],
                'zone'    => $row['zone'] ?? '',
                'desc'    => $row['descr'] ?? '',
                'animals' => $row['animals'] ? explode(',', $row['animals']) : [],
                'pos'     => ['x' => (float)$row['pos_x'], 'y' => (float)$row['pos_y']],
            ];
        }
        $map = $pdo->query("SELECT map_image FROM map_settings WHERE map_id = 1")
                   ->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['Map' => $map['map_image'] ?? '', 'Pins' => $pins]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// ═══════════════════════════════════════════════════════════════════════════════
// POST  — save map image + replace all pins
// ═══════════════════════════════════════════════════════════════════════════════
if ($method === 'POST') {
    $raw  = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
        exit;
    }

    try {
        // Save map image (map_id is the PK, not 'id')
        $pdo->prepare(
            "INSERT INTO map_settings (map_id, map_image)
             VALUES (1, ?)
             ON DUPLICATE KEY UPDATE map_image = VALUES(map_image)"
        )->execute([$data['Map'] ?? '']);

        // Replace all pins
        $pdo->exec("DELETE FROM pins");

        $stmt = $pdo->prepare(
            "INSERT INTO pins (id, name, emoji, color, light, zone, descr, animals, pos_x, pos_y)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        foreach (($data['Pins'] ?? []) as $p) {
            $light = $p['light'] ?? '';
            if (!$light && !empty($p['color'])) {
                $light = $p['color'] . '22';
            }
            // Strip HTML tags from description to prevent clickable links
            $cleanDesc = strip_tags($p['desc'] ?? '');
            $stmt->execute([
                $p['id']    ?? uniqid('pin-'),
                $p['name']  ?? '',
                $p['emoji'] ?? '📍',
                $p['color'] ?? '#2D5A27',
                $light,
                $p['zone']  ?? '',
                $cleanDesc,
                is_array($p['animals'] ?? null) ? implode(',', $p['animals']) : ($p['animals'] ?? ''),
                (float)($p['pos']['x'] ?? $p['pos_x'] ?? 50),
                (float)($p['pos']['y'] ?? $p['pos_y'] ?? 50),
            ]);
        }

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);