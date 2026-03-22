<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Buy a Ticket — WildTrack Zoo</title>
  <style>
    .ticket-options {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 28px 0;
    }
    .ticket-card {
      background: #fff;
      border-radius: 14px;
      padding: 24px 28px;
      min-width: 200px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      border: 2px solid transparent;
      transition: border-color 0.2s, transform 0.2s;
      text-align: center;
    }
    .ticket-card:hover {
      border-color: #76d7c4;
      transform: translateY(-3px);
    }
    .ticket-card .tc-type  { font-size: 18px; font-weight: bold; color: #2a5a2e; margin-bottom: 6px; }
    .ticket-card .tc-price { font-size: 32px; font-weight: bold; color: #3a3a3a; }
    .ticket-card .tc-note  { font-size: 13px; color: #888; margin-top: 6px; }
    .notice-box {
      background: #e8f5e8;
      border-radius: 10px;
      padding: 18px 22px;
      max-width: 600px;
      font-size: 15px;
      color: #2a5a2e;
      margin: 20px 0 32px;
      line-height: 1.6;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://images.unsplash.com/photo-1695272016860-c3d5eaa6c660?q=80&w=1740&auto=format&fit=crop"
     class="page-img" alt="Zoo entrance">

<div class="content-section">

  <h1>Buy a Ticket</h1>
  <p>We can't wait to welcome you to WildTrack Zoo! Book online to save time at the gate.</p>

  <div class="notice-box">
    💡 <strong>Save by booking online</strong> — tickets purchased at the gate may be subject
    to higher walk-in pricing on peak days.
  </div>

  <h2>Ticket Prices</h2>
  <div class="ticket-options">
    <div class="ticket-card">
      <div class="tc-type">Adult</div>
      <div class="tc-price" id="btp-adult">RM 20</div>
      <div class="tc-note">Age 13 - 64</div>
    </div>
    <div class="ticket-card">
      <div class="tc-type">Senior</div>
      <div class="tc-price" id="btp-child">RM 10</div>
      <div class="tc-note">Age 65 and above</div>
    </div>
    <div class="ticket-card">
      <div class="tc-type">Child</div>
      <div class="tc-price" id="btp-child">RM 10</div>
      <div class="tc-note">Age 4 – 12</div>
    </div>
    <div class="ticket-card">
      <div class="tc-type">Under 4</div>
      <div class="tc-price">Free</div>
      <div class="tc-note">No ticket needed</div>
    </div>
    <div class="ticket-card" style="border-color:#76d7c4; background:#f0faf8;">
      <div class="tc-type">Family Bundle</div>
      <div class="tc-price" id="btp-family">RM 55</div>
      <div class="tc-note">2 Adults + 1 Children + 1 Senior<br>Save 15%</div>
    </div>
  </div>

  <!-- Link to actual ticketing system -->
  <a href="Ticketing.php" class="btn-cta" style="font-size:18px; padding:16px 36px;">
    Proceed to Booking →
  </a>

  <p style="margin-top:16px; font-size:14px; color:#888;">
    You will be taken to our secure ticketing system to complete your purchase.
  </p>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Buy a Ticket' }
  ];
</script>
<script src="FinalProject.js"></script>
<script>
  // ── Dynamically load ticket prices from admin-controlled DB ──────────────
  (async function loadTicketPrices() {
    try {
      const res  = await fetch('http://localhost/WildTrack/api/tickets.php?action=get_prices');
      const data = await res.json();
      if (!data.success) return;
      const typeMap = { Adult: 'btp-adult', Child: 'btp-child', Group: 'btp-family' };
      data.prices.forEach(function(row) {
        const elId = typeMap[row.ticket_type];
        if (!elId) return;
        const el = document.getElementById(elId);
        if (el) el.textContent = 'RM ' + parseFloat(row.price).toFixed(0);
      });
    } catch(e) { /* silently keep defaults */ }
  })();
</script>
</body>
</html>
