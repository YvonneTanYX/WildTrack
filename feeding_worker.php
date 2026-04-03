<?php
/**
 * api/feeding_worker.php
 *
 * Worker feeding log CRUD — uses existing `feeding_schedule` table.
 * Table columns: feeding_id, animal_id, food_type, quantity,
 *                feeding_time, feeding_date, fed_by (worker_id), status
 *
 * Also adds a `consumed` and `notes` column if missing (worker extras).
 *
 * GET    — list today's feeding records for the logged-in worker
 * POST   — insert a new feeding record
 * PUT    — update a feeding record
 * DELETE — delete a feeding record
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../check_session.php';

header('Content-Type: application/json');
requireVisitorLogin();

$pdo    = getDB();
$userId = $_SESSION['user']['id'] ?? 0;
$method = $_SERVER['REQUEST_METHOD'];

// Add extra worker columns if missing
foreach ([
    "ALTER TABLE feeding_schedule ADD COLUMN IF NOT EXISTS consumed VARCHAR(30) DEFAULT 'All Eaten'",
    "ALTER TABLE feeding_schedule ADD COLUMN IF NOT EXISTS notes    TEXT DEFAULT NULL",
] as $sql) {
    try { $pdo->exec($sql); } catch (PDOException $e) {}
}

// Resolve worker_id from user_id
function getWorkerId(PDO $pdo, int $userId): ?int {
    $stmt = $pdo->prepare("SELECT worker_id FROM workers WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return $row ? (int)$row['worker_id'] : null;
}

try {
    switch ($method) {

        // ── GET: all feeding records (today) for this worker ───────────────
        case 'GET':
            $workerId = getWorkerId($pdo, $userId);
            if (!$workerId) {
                // Admin or no worker record — return all today's feedings
                $stmt = $pdo->prepare(
                    "SELECT fs.*, a.name AS animal_name
                     FROM feeding_schedule fs
                     LEFT JOIN animals a ON a.animal_id = fs.animal_id
                     WHERE fs.feeding_date = CURDATE()
                     ORDER BY fs.feeding_time DESC"
                );
                $stmt->execute();
            } else {
                $stmt = $pdo->prepare(
                    "SELECT fs.*, a.name AS animal_name
                     FROM feeding_schedule fs
                     LEFT JOIN animals a ON a.animal_id = fs.animal_id
                     WHERE fs.fed_by = ? AND fs.feeding_date = CURDATE()
                     ORDER BY fs.feeding_time DESC"
                );
                $stmt->execute([$workerId]);
            }
            $rows = $stmt->fetchAll();
            $records = array_map(fn($r) => [
                'id'       => (int)$r['feeding_id'],
                'time'     => $r['feeding_time'] ? substr($r['feeding_time'],0,5) : '—',
                'animal'   => $r['animal_name'] ?? 'Unknown',
                'type'     => $r['food_type']   ?? '—',
                'qty'      => $r['quantity']     ?? '0',
                'consumed' => $r['consumed']     ?? 'All Eaten',
                'notes'    => $r['notes']        ?? '',
                'worker'   => '', // filled client-side
                'status'   => $r['status']       ?? 'Completed',
            ], $rows);
            echo json_encode(['success' => true, 'records' => $records]);
            break;

        // ── POST: insert a new feeding record ─────────────────────────────
        case 'POST':
            $d        = json_decode(file_get_contents('php://input'), true) ?? [];
            $workerId = getWorkerId($pdo, $userId);
            $stmt     = $pdo->prepare(
                "INSERT INTO feeding_schedule
                 (animal_id, food_type, quantity, feeding_time, feeding_date,
                  fed_by, status, consumed, notes)
                 VALUES (?,?,?,?,CURDATE(),?,'Completed',?,?)"
            );
            $stmt->execute([
                $d['animal_id'] ?? null,
                $d['food_type'] ?? '',
                $d['quantity']  ?? 0,
                $d['feeding_time'] ?? date('H:i:s'),
                $workerId,
                $d['consumed'] ?? 'All Eaten',
                $d['notes']    ?? '',
            ]);
            echo json_encode(['success' => true, 'id' => (int)$pdo->lastInsertId()]);
            break;

        // ── PUT: update a feeding record ───────────────────────────────────
        case 'PUT':
            $d    = json_decode(file_get_contents('php://input'), true) ?? [];
            $stmt = $pdo->prepare(
                "UPDATE feeding_schedule
                 SET food_type=?, quantity=?, consumed=?, notes=?
                 WHERE feeding_id=?"
            );
            $stmt->execute([
                $d['food_type'] ?? '',
                $d['quantity']  ?? 0,
                $d['consumed']  ?? 'All Eaten',
                $d['notes']     ?? '',
                (int)($d['id']  ?? 0),
            ]);
            echo json_encode(['success' => true]);
            break;

        // ── DELETE: remove a feeding record ───────────────────────────────
        case 'DELETE':
            $d    = json_decode(file_get_contents('php://input'), true) ?? [];
            $stmt = $pdo->prepare("DELETE FROM feeding_schedule WHERE feeding_id = ?");
            $stmt->execute([(int)($d['id'] ?? 0)]);
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
