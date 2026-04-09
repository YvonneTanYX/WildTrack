<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'things'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="hero.css"/>
  <link rel="stylesheet" href="shared.css">
  <title>Events &amp; Talk Times — WildTrack Zoo</title>
  <style>
    /* ── Live clock bar ── */
    .live-date-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      background: #2a5a2e;
      color: #fff;
      border-radius: 12px;
      padding: 14px 22px;
      margin-bottom: 28px;
    }
    .live-date-bar .ldb-date {
      font-size: 16px;
      font-weight: 700;
      letter-spacing: 0.3px;
    }
    .live-date-bar .ldb-time {
      font-size: 22px;
      font-weight: 800;
      font-variant-numeric: tabular-nums;
      letter-spacing: 1px;
    }
    .live-dot {
      display: inline-block;
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: #7ee87a;
      margin-right: 7px;
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0%,100% { opacity:1; transform:scale(1); }
      50%      { opacity:.5; transform:scale(1.3); }
    }

    /* ── Session sections ── */
    .talk-section { margin: 32px 0; }
    .talk-label {
      display: inline-block;
      background: #2a5a2e;
      color: white;
      font-weight: bold;
      font-size: 14px;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 16px;
      letter-spacing: 0.5px;
    }
    .talk-grid {
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 620px;
    }

    /* ── Event row ── */
    .talk-row {
      display: grid;
      grid-template-columns: 90px 1fr auto;
      align-items: center;
      gap: 14px;
      background: #fff;
      border-radius: 10px;
      padding: 14px 20px;
      box-shadow: 0 1px 6px rgba(0,0,0,0.07);
      transition: transform 0.15s;
    }
    .talk-row:hover { transform: translateX(4px); }
    .talk-row.inactive {
      opacity: 0.45;
      filter: grayscale(0.5);
    }
    .talk-time {
      font-weight: bold;
      color: #2a5a2e;
      font-size: 15px;
      white-space: nowrap;
    }
    .talk-info .talk-name  { font-size: 16px; color: #3a3a3a; font-weight: 600; }
    .talk-info .talk-venue {
      font-size: 12px;
      color: #888;
      margin-top: 2px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .talk-date-pill {
      font-size: 11px;
      font-weight: 700;
      background: #eaf1e8;
      color: #2a5a2e;
      padding: 3px 10px;
      border-radius: 20px;
      white-space: nowrap;
    }
    .talk-date-pill.specific {
      background: #fff3e0;
      color: #c0620a;
    }

    /* ── Empty/loading states ── */
    .schedule-loading, .schedule-empty {
      text-align: center;
      padding: 40px 20px;
      color: #888;
      font-size: 15px;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI=" alt="Zoo events">/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title"><em>Events</em><br/>and Talk Times</h1>
    <p class="hero-sub">Join our keepers for daily animal talks — included free with your Zoo entry!
     Check the schedule below and plan your visit around your favourite animals.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <!-- Live date & clock bar -->
  <div class="live-date-bar">
    <span class="ldb-date" id="liveDate">—</span>
    <span class="ldb-time"><span class="live-dot"></span><span id="liveClock">--:--:--</span></span>
  </div>

  <!-- Schedule renders here -->
  <div id="scheduleWrap">
    <div class="schedule-loading">⏳ Loading today's schedule…</div>
  </div>

  <p style="font-size:14px; color:#888; margin-top:24px;">
    ⚠️ Talk times are subject to change. Check with staff at the Zoo on the day of your visit.
  </p>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Things to Do', href: 'thingMain.php' },
    { label: 'Events & Talk Times' }
  ];
</script>
<script src="FinalProject.js"></script>

<script>
/* ════════════════════════════════════════
   LIVE CLOCK
════════════════════════════════════════ */
function tickClock() {
  const now  = new Date();
  const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  const mons = ['January','February','March','April','May','June',
                'July','August','September','October','November','December'];

  document.getElementById('liveDate').textContent =
    `${days[now.getDay()]}, ${now.getDate()} ${mons[now.getMonth()]} ${now.getFullYear()}`;

  const h  = String(now.getHours()).padStart(2,'0');
  const m  = String(now.getMinutes()).padStart(2,'0');
  const s  = String(now.getSeconds()).padStart(2,'0');
  document.getElementById('liveClock').textContent = `${h}:${m}:${s}`;
}
tickClock();
setInterval(tickClock, 1000);

/* ════════════════════════════════════════
   FETCH & RENDER SCHEDULE
════════════════════════════════════════ */
async function loadSchedule() {
  try {
    const res  = await fetch('api/events.php?action=get_events');
    const data = await res.json();
    if (!data.success) throw new Error('API error');
    renderSchedule(data.events);
  } catch(e) {
    document.getElementById('scheduleWrap').innerHTML =
      '<div class="schedule-empty">⚠️ Unable to load schedule. Please check with Zoo staff.</div>';
  }
}

function formatDisplayDate(dateStr) {
  const d    = new Date(dateStr + 'T00:00:00');
  const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
  const mons = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  return `${days[d.getDay()]} ${d.getDate()} ${mons[d.getMonth()]}`;
}

function renderSchedule(events) {
  const active = events.filter(e => parseInt(e.is_active));

  const morning   = active.filter(e => e.session === 'morning');
  const afternoon = active.filter(e => e.session === 'afternoon');

  if (!active.length) {
    document.getElementById('scheduleWrap').innerHTML =
      '<div class="schedule-empty">No events scheduled today. Please check back later.</div>';
    return;
  }

  let html = '';

  if (morning.length) {
    html += `<div class="talk-section">
      <div class="talk-label">🌅 Morning Sessions</div>
      <div class="talk-grid">${morning.map(eventRow).join('')}</div>
    </div>`;
  }

  if (afternoon.length) {
    html += `<div class="talk-section">
      <div class="talk-label">☀️ Afternoon Sessions</div>
      <div class="talk-grid">${afternoon.map(eventRow).join('')}</div>
    </div>`;
  }

  document.getElementById('scheduleWrap').innerHTML = html;
}

function eventRow(e) {
  /* If event_date is set in DB, it's a specific day (show orange pill).
     If NULL, the API sends today's date as display_date (show green "Today" pill). */
  const hasSpecificDate = e.event_date && e.event_date !== '';
  const pillClass = hasSpecificDate ? 'talk-date-pill specific' : 'talk-date-pill';
  const pillText  = hasSpecificDate ? formatDisplayDate(e.event_date) : 'Today';

  return `
    <div class="talk-row">
      <span class="talk-time">${esc(e.event_time_fmt)}</span>
      <div class="talk-info">
        <div class="talk-name">${esc(e.event_name)}</div>
        <div class="talk-venue">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
          </svg>
          ${esc(e.venue)}
        </div>
      </div>
      <span class="${pillClass}">${pillText}</span>
    </div>`;
}

function esc(s) {
  return String(s ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

loadSchedule();
</script>
</body>
</html>
