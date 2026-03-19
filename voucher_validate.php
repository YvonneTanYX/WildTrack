<?php

require_once '../check_session.php'; 
header('Content-Type: application/json');

require_once '../config/db.php';  
$pdo = getDB();   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$code  = strtoupper(trim($body['code']  ?? ''));
$total = floatval($body['total'] ?? 0);

if ($code === '') {
    echo json_encode(['success' => false, 'message' => 'Please enter a voucher code.']);
    exit;
}

// ── Look up voucher ──
$stmt = $pdo->prepare("
    SELECT * FROM vouchers
    WHERE code = ?
      AND is_active = 1
      AND (expires_at IS NULL OR expires_at >= CURDATE())
      AND used_count < max_uses
    LIMIT 1
");
$stmt->execute([$code]);
$voucher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$voucher) {
    echo json_encode(['success' => false, 'message' => 'Invalid or expired voucher code.']);
    exit;
}

// ── Check minimum spend ──
if ($total < floatval($voucher['min_spend'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Minimum spend of RM' . number_format($voucher['min_spend'], 2) . ' required for this voucher.'
    ]);
    exit;
}

// ── Calculate discount ──
if ($voucher['discount_type'] === 'percent') {
    $discount = round($total * ($voucher['discount_value'] / 100), 2);
} else {
    $discount = floatval($voucher['discount_value']);
}

// Discount cannot exceed total (must always pay something)
$discount   = min($discount, $total - 0.01);
$finalTotal = round($total - $discount, 2);

echo json_encode([
    'success'        => true,
    'voucher_id'     => $voucher['id'],
    'code'           => $voucher['code'],
    'discount_type'  => $voucher['discount_type'],
    'discount_value' => $voucher['discount_value'],
    'discount_amount'=> $discount,
    'final_total'    => $finalTotal,
    'message'        => 'Voucher applied! You save RM' . number_format($discount, 2) . '.'
]);