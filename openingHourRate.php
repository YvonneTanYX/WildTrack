<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Opening Hours &amp; Rates — WildTrack Zoo</title>
  <style>
    .hours-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin: 20px 0 36px;
      max-width: 600px;
    }
    .hours-row {
      display: contents;
    }
    .hours-row .period {
      font-weight: bold;
      font-size: 16px;
      color: #2a5a2e;
      padding: 10px 0;
      border-bottom: 1px solid #dde8dd;
    }
    .hours-row .time {
      font-size: 16px;
      color: #3a3a3a;
      padding: 10px 0;
      border-bottom: 1px solid #dde8dd;
    }
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

<img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_animals/Amur_tiger/amur_tiger_2.jpg"
     class="page-img" alt="Highland Wildlife Park">

<div class="content-section">

  <h1>Opening Hours &amp; Rates</h1>

  <!-- Opening hours -->
  <h2>Opening Hours</h2>
  <p>WildTrack Zoo is open every day of the year apart from Christmas Day (25 December),
     subject to weather conditions. Last entry is <strong>one hour before closing time</strong>.</p>

  <div class="hours-grid">
    <div class="hours-row"><span class="period">January – February</span><span class="time">10:00am – 4:00pm</span></div>
    <div class="hours-row"><span class="period">March – October</span>   <span class="time">10:00am – 5:00pm</span></div>
    <div class="hours-row"><span class="period">November – December</span><span class="time">10:00am – 4:00pm</span></div>
    <div class="hours-row"><span class="period">Christmas Day</span>      <span class="time">Closed</span></div>
  </div>

  <!-- Ticket prices -->
  <div class="two-col">
    <div class="col-img">
      <img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_animals/Japanese_macaque/japanese_macaque_3.jpg"
           alt="Japanese macaque" style="height:340px; object-fit:cover;">
    </div>
    <div class="col-info">
      <h2>Ticket Prices</h2>
      <p>Save by booking online! Group rates also available.</p>

      <div class="ticket-grid">
        <span class="t-label">Adult</span>              <span class="t-price" id="ohr-adult">RM 20</span>
        <span class="t-label">Senior</span>              <span class="t-price" id="ohr-senior">RM 15</span>
        <span class="t-label">Child</span>              <span class="t-price" id="ohr-child">RM 10</span>
        <span class="t-label">Child (Under 4)</span>    <span class="t-price">Free</span>
        <span class="t-label">Family Bundle</span>       <span class="t-price" id="ohr-family">RM 55</span>
        <span class="t-note">Family Bundle: 2 Adults + 1 Children + 1 Senior — save 15%</span>
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
  // ── Dynamically load ticket prices from admin-controlled DB ──────────────
  (async function loadTicketPrices() {
    try {
      const res  = await fetch('http://localhost/WildTrack/api/tickets.php?action=get_prices');
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
