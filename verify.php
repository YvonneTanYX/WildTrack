<?php
// ══════════════════════════════════════════
//  WildTrack — verify.php
//  Place this in: WildTrack/verify.php
//  Opens when a ticket QR code is scanned
// ══════════════════════════════════════════
require_once __DIR__ . '/config/db.php';

$code   = trim($_GET['code'] ?? '');
$ticket = null;
$addons = [];
$error  = '';

if ($code === '') {
    $error = 'No ticket code provided.';
} else {
    try {
        $pdo  = getDB();

        // Look up ticket by qr_code (stored as full URL, match by LIKE or exact)
        $stmt = $pdo->prepare("
            SELECT t.*, u.username, u.email
            FROM tickets t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.qr_code LIKE ?
            LIMIT 1
        ");
        $stmt->execute(['%' . $code . '%']);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            $error = 'Ticket not found. This QR code is invalid.';
        } else {
            // Get addons for this ticket
            $aStmt = $pdo->prepare("SELECT * FROM ticket_addons WHERE ticket_id = ?");
            $aStmt->execute([$ticket['ticket_id']]);
            $addons = $aStmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        $error = 'Database error. Please try again.';
    }
}

// Check if visit date is today or future
$isValid   = false;
$statusMsg = '';
$statusColor = '#c0392b';

if ($ticket) {
    $visitDate = new DateTime($ticket['visit_date']);
    $today     = new DateTime(date('Y-m-d'));
    $diff      = (int)$today->diff($visitDate)->format('%r%a'); // negative = past

    if ($diff < 0) {
        $statusMsg   = 'Ticket Expired';
        $statusColor = '#c0392b';
        $isValid     = false;
    } elseif ($diff === 0) {
        $statusMsg   = 'Valid — Entry Today';
        $statusColor = '#2D5A27';
        $isValid     = true;
    } else {
        $statusMsg   = 'Valid — Visit on ' . $visitDate->format('d M Y');
        $statusColor = '#2D5A27';
        $isValid     = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WildTrack — Ticket Verification</title>
    <script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2eb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 20px 60px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            color: #2D5A27;
            margin-bottom: 28px;
        }
        .card {
            background: #F9FBF7;
            border-radius: 24px;
            padding: 28px 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 440px;
        }

        /* ── STATUS BANNER ── */
        .status-banner {
            border-radius: 16px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .status-banner.valid   { background: rgba(45,90,39,0.1);  border: 2px solid #2D5A27; }
        .status-banner.invalid { background: rgba(192,57,43,0.08); border: 2px solid #c0392b; }
        .status-icon { font-size: 36px; }
        .status-text-label { font-size: 12px; font-weight: 600; color: #7F8C8D; text-transform: uppercase; letter-spacing: 0.8px; }
        .status-text-value { font-size: 18px; font-weight: 700; margin-top: 2px; }

        /* ── INFO ROWS ── */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #7F8C8D;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f0f4ee;
            font-size: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #7F8C8D; }
        .info-value { font-weight: 600; color: #2F3640; }
        .info-value.green { color: #2D5A27; }

        .divider { border: none; border-top: 1px solid #e4e9e0; margin: 20px 0; }

        /* ── ADDON PILLS ── */
        .addon-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(230,126,34,0.1);
            color: #E67E22;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            margin: 4px 4px 0 0;
        }

        /* ── ERROR STATE ── */
        .error-box {
            text-align: center;
            padding: 32px 20px;
        }
        .error-box .error-icon { font-size: 56px; margin-bottom: 16px; }
        .error-box h2 { font-size: 20px; font-weight: 700; color: #c0392b; margin-bottom: 8px; }
        .error-box p  { font-size: 14px; color: #7F8C8D; }

        .ticket-type-badge {
            display: inline-block;
            background: rgba(45,90,39,0.1);
            color: #2D5A27;
            border-radius: 10px;
            padding: 4px 12px;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="brand">
        <span class="iconify" data-icon="lucide:tent-tree" style="font-size:26px;"></span>
        WildTrack
    </div>

    <div class="card">

        <?php if ($error): ?>
        <!-- ERROR STATE -->
        <div class="error-box">
            <div class="error-icon">
                <span class="iconify" data-icon="lucide:scan-x" style="font-size:56px;color:#c0392b;"></span>
            </div>
            <h2>Invalid Ticket</h2>
            <p><?= htmlspecialchars($error) ?></p>
        </div>

        <?php else: ?>
        <!-- STATUS BANNER -->
        <div class="status-banner <?= $isValid ? 'valid' : 'invalid' ?>">
            <span class="iconify status-icon" data-icon="<?= $isValid ? 'lucide:circle-check-big' : 'lucide:circle-x' ?>"
                  style="color:<?= $statusColor ?>;font-size:36px;"></span>
            <div>
                <div class="status-text-label">Ticket Status</div>
                <div class="status-text-value" style="color:<?= $statusColor ?>">
                    <?= htmlspecialchars($statusMsg) ?>
                </div>
            </div>
        </div>

        <!-- VISITOR INFO -->
        <div class="section-title">Visitor</div>
        <div class="info-row">
            <span class="info-label">👤 Name</span>
            <span class="info-value"><?= htmlspecialchars($ticket['username']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">✉️ Email</span>
            <span class="info-value"><?= htmlspecialchars($ticket['email']) ?></span>
        </div>

        <hr class="divider">

        <!-- TICKET INFO -->
        <div class="section-title">Ticket Details</div>
        <div class="info-row">
            <span class="info-label">🎟 Type</span>
            <span class="info-value">
                <span class="ticket-type-badge"><?= htmlspecialchars($ticket['ticket_type']) ?> Pass</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">📅 Visit Date</span>
            <span class="info-value green">
                <?= (new DateTime($ticket['visit_date']))->format('d M Y, D') ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">🕘 Opening Hours</span>
            <span class="info-value">9:00 AM – 6:00 PM</span>
        </div>
        <div class="info-row">
            <span class="info-label">💳 Price Paid</span>
            <span class="info-value green">RM<?= number_format($ticket['price'], 2) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">🗓 Booked On</span>
            <span class="info-value">
                <?= (new DateTime($ticket['purchase_date']))->format('d M Y, h:i A') ?>
            </span>
        </div>

        <?php if (!empty($addons)): ?>
        <hr class="divider">
        <div class="section-title">Add-ons</div>
        <div style="padding: 8px 0;">
            <?php foreach ($addons as $a): ?>
                <span class="addon-pill">
                    <span class="iconify" data-icon="<?= $a['addon_type'] === 'Safari Shuttle' ? 'lucide:bus' : 'lucide:cookie' ?>" data-width="13"></span>
                    <?= htmlspecialchars($a['addon_type']) ?> x<?= $a['quantity'] ?>
                </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>

    </div>

</body>
</html>