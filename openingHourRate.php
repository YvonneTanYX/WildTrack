<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <link rel="stylesheet" href="hero.css"/>
  <title>Opening Hours &amp; Rates — WildTrack Zoo</title>
  <style>
    /* ── Ticket grid ── */
    .ticket-grid {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 0;
      max-width: 420px;
      border: 2px solid #76d7c4;
      border-radius: 12px;
      overflow: hidden;
      margin: 20px 0 32px;
    }
    .ticket-grid .t-label,
    .ticket-grid .t-price {
      padding: 14px 20px;
      font-size: 16px;
      border-bottom: 1px solid #dde8dd;
    }
    .ticket-grid .t-label { font-weight: bold; color: #3a3a3a; }
    .ticket-grid .t-price { color: #2a5a2e; font-weight: bold; text-align: right; }
    .ticket-grid .t-note  {
      grid-column: 1 / -1;
      font-size: 14px;
      color: #777;
      padding: 10px 20px;
      font-style: italic;
    }
    .ticket-grid .t-label:last-of-type,
    .ticket-grid .t-price:last-of-type { border-bottom: none; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_animals/Amur_tiger/amur_tiger_2.jpg" alt="Highland Wildlife Park"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Opening<br/><em>Hours and Rate</em></h1>
    <p class="hero-sub">Plan Your Visit: Times & Ticket Pricing</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<?php include 'announcement-banner.php'; ?>

<div class="content-section">

  <!-- ── Opening Hours ── -->
  <h2>Opening Hours</h2>
  <p>WildTrack Zoo is open every day of the year,
     subject to weather conditions.</p>

  <div class="hours-rules" id="hoursRules">
    <!-- populated by JS -->
    <div class="rule-chip">⏱ Last entry: <span id="ruleLastEntry">loading…</span></div>
    <div class="rule-chip">🛒 Last online purchase: <span id="ruleOnlinePurchase">loading…</span></div>
  </div>

  <div class="hours-grid">
    <div class="hours-row">
      <span class="period">Every day</span>
      <span class="time" id="hoursDisplay">loading…</span>
    </div>
  </div>

  <!-- ── Ticket Prices ── -->
  <div class="two-col">
    <div class="col-img">
      <img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_animals/Japanese_macaque/japanese_macaque_3.jpg"
           alt="Japanese macaque" style="height:340px; object-fit:cover;">
    </div>
    <div class="col-info">
      <h2>Ticket Prices</h2>
      <p>Save by booking online! Group rates also available.</p>

      <div class="ticket-grid">
        <span class="t-label">Adult</span>           <span class="t-price" id="ohr-adult">RM 20</span>
        <span class="t-label">Senior</span>          <span class="t-price" id="ohr-senior">RM 15</span>
        <span class="t-label">Child</span>           <span class="t-price" id="ohr-child">RM 10</span>
        <span class="t-label">Child (Under 4)</span> <span class="t-price">Free</span>
        <span class="t-label">Family Bundle</span>   <span class="t-price" id="ohr-family">RM 55</span>
        <span class="t-note">Family Bundle: 2 Adults + 1 Child + 1 Senior — save 15%</span>
      </div>

      <a href="Ticketing.php" class="btn-cta">Book Tickets Now →</a>
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Opening Hours &amp; Rates' }
  ];
</script>
<script src="FinalProject.js"></script>
<script>
/* ── Utility: convert "HH:MM" → "H:MM AM/PM" ───────────────────── */
function fmtTime(t) {
  if (!t) return '';
  const [h, m] = t.split(':').map(Number);
  const ampm = h >= 12 ? 'PM' : 'AM';
  const h12  = h % 12 || 12;
  return h12 + ':' + String(m).padStart(2, '0') + ' ' + ampm;
}

/* ── Utility: mins → human label ───────────────────────────────── */
function fmtMins(mins) {
  mins = parseInt(mins, 10);
  if (mins >= 60) {
    const h = mins / 60;
    return h === Math.floor(h) ? h + ' hour' + (h > 1 ? 's' : '') : (mins / 60).toFixed(1) + ' hours';
  }
  return mins + ' minute' + (mins !== 1 ? 's' : '');
}

/* ── 1. Load opening hours from zoo_settings ───────────────────── */
(async function loadOpeningHours() {
  try {
    const res  = await fetch('api/announcements.php?action=get_settings');
    const data = await res.json();
    if (!data.success) return;

    const s = data.settings;
    const open  = fmtTime(s.open_time  || '09:00');
    const close = fmtTime(s.close_time || '18:00');

    // Update hours display
    const hoursEl = document.getElementById('hoursDisplay');
    if (hoursEl) hoursEl.textContent = open + ' – ' + close;

    // Update rule chips
    const lastEntryEl = document.getElementById('ruleLastEntry');
    const onlineEl    = document.getElementById('ruleOnlinePurchase');
    if (lastEntryEl)
      lastEntryEl.textContent = fmtMins(s.last_entry_mins || 60) + ' before closing';
    if (onlineEl)
      onlineEl.textContent = fmtMins(s.last_online_purchase_mins || 180) + ' before closing';

  } catch(e) {
    // silently keep defaults
    const hoursEl = document.getElementById('hoursDisplay');
    if (hoursEl) hoursEl.textContent = '9:00 AM – 6:00 PM';
    const lastEntryEl = document.getElementById('ruleLastEntry');
    if (lastEntryEl) lastEntryEl.textContent = '1 hour before closing';
    const onlineEl = document.getElementById('ruleOnlinePurchase');
    if (onlineEl) onlineEl.textContent = '3 hours before closing';
  }
})();

/* ── 3. Dynamically load ticket prices from admin-controlled DB ── */
(async function loadTicketPrices() {
  try {
    const res  = await fetch('api/tickets.php?action=get_prices');
    const data = await res.json();
    if (!data.success) return;
    const typeMap = { Adult: 'ohr-adult', Child: 'ohr-child', Senior: 'ohr-senior', Group: 'ohr-family' };
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
