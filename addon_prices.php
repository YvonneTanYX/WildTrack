<?php
/**
 * api/addon_prices.php
 * Handles GET and POST for add-on prices (Safari Shuttle, Feeding Pass).
 * Stores prices in a simple JSON file next to this script so no extra DB
 * table is required.  Admin can call action=save, visitors call action=get.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

$STORE = __DIR__ . '/addon_prices_data.json';

// ── Default prices ──────────────────────────────────────────────────────────
$DEFAULTS = [
    'safari'  => ['label' => 'Safari Shuttle', 'price' => 5.00],
    'feeding' => ['label' => 'Feeding Pass',   'price' => 12.00],
];

// ── Load stored prices (fall back to defaults if file missing) ───────────────
function loadPrices(string $store, array $defaults): array {
    if (file_exists($store)) {
        $data = json_decode(file_get_contents($store), true);
        if (is_array($data)) {
            // Merge so any new defaults are included
            return array_merge($defaults, $data);
        }
    }
    return $defaults;
}

$action = $_GET['action'] ?? 'get';

// ── GET ──────────────────────────────────────────────────────────────────────
if ($action === 'get') {
    echo json_encode(['success' => true, 'prices' => loadPrices($STORE, $DEFAULTS)]);
    exit;
}

// ── SAVE ─────────────────────────────────────────────────────────────────────
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Optionally add session/admin check here to match your check_session.php
    $body = json_decode(file_get_contents('php://input'), true);
    if (!isset($body['key'], $body['price'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing key or price.']);
        exit;
    }
    $key   = trim($body['key']);
    $price = floatval($body['price']);
    if ($price <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Price must be > 0.']);
        exit;
    }
    $current = loadPrices($STORE, $DEFAULTS);
    if (!array_key_exists($key, $current)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Unknown addon key.']);
        exit;
    }
    $current[$key]['price'] = $price;
    file_put_contents($STORE, json_encode($current, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'message' => "Price updated for {$key}.", 'prices' => $current]);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid action.']);
