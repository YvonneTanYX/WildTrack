<?php require_once 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets — WildTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="iconify.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #eef2eb; min-height: 100vh; }

        /* ── NAV ── */
        nav {
            background: #F9FBF7; border-bottom: 1px solid #e4e9e0;
            padding: 0 40px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .nav-left { display: flex; align-items: center; gap: 12px; }
        .nav-brand { display: flex; align-items: center; gap: 8px; font-size: 20px; font-weight: 700; color: #2D5A27; }
        .nav-back {
            width: 38px; height: 38px; border-radius: 50%;
            background: #fff; border: 1.5px solid #e4e9e0;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            transition: all 0.2s; color: #2D5A27; text-decoration: none;
        }
        .nav-back:hover { background: #f0f4ee; border-color: #2D5A27; }

        /* ── LAYOUT ── */
        .page-wrap { max-width: 860px; margin: 0 auto; padding: 40px 24px 80px; display: flex; flex-direction: column; gap: 28px; }

        /* ── TAB BAR ── */
        .tab-bar {
            display: flex; gap: 0; background: #F9FBF7;
            border-radius: 20px; padding: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .tab-btn {
            flex: 1; padding: 12px 0; border: none; cursor: pointer;
            border-radius: 14px; font-size: 14px; font-weight: 600;
            font-family: inherit; transition: all 0.25s;
            background: transparent; color: #7F8C8D;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .tab-btn.active { background: #2D5A27; color: #fff; box-shadow: 0 4px 12px rgba(45,90,39,0.25); }
        .tab-btn:hover:not(.active) { background: #f0f4ee; color: #2D5A27; }
        .tab-count {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 20px; height: 20px; border-radius: 10px;
            font-size: 11px; font-weight: 700; padding: 0 5px;
        }
        .tab-btn.active .tab-count { background: rgba(255,255,255,0.25); color: #fff; }
        .tab-btn:not(.active) .tab-count { background: rgba(45,90,39,0.12); color: #2D5A27; }

        /* ── HERO ── */
        .hero-card {
            background: linear-gradient(135deg, #2D5A27, #3d7a35);
            border-radius: 24px; padding: 30px 32px;
            box-shadow: 0 8px 24px rgba(45,90,39,0.25);
        }
        .hero-card h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .hero-card p { font-size: 14px; color: rgba(255,255,255,0.75); margin-top: 6px; }
        .hero-stats { display: flex; gap: 24px; margin-top: 18px; }
        .hero-stat { text-align: center; }
        .hero-stat-num { font-size: 28px; font-weight: 700; color: #fff; }
        .hero-stat-lbl { font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

        /* ── VISIT GROUP ── */
        .visit-group { display: flex; flex-direction: column; gap: 14px; }
        .visit-group-header {
            display: flex; align-items: center; gap: 12px; padding: 0 4px;
        }
        .visit-date-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: #F9FBF7; border-radius: 14px;
            padding: 8px 16px; font-size: 14px; font-weight: 700; color: #2F3640;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .visit-date-pill.upcoming { border-left: 4px solid #2D5A27; }
        .visit-date-pill.past { border-left: 4px solid #aaa; color: #7F8C8D; }
        .visit-status-tag {
            font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 8px;
        }
        .tag-upcoming { background: rgba(45,90,39,0.1); color: #2D5A27; }
        .tag-today    { background: rgba(243,156,18,0.12); color: #E67E22; }
        .tag-past     { background: #f0f0f0; color: #aaa; }

        /* ── QR TICKET CARD ── */
        .qr-ticket-card {
            background: #F9FBF7; border-radius: 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            overflow: hidden; position: relative;
        }
        /* Ticket tear effect */
        .qr-ticket-card::before {
            content: ''; position: absolute; left: -10px; top: 50%;
            width: 20px; height: 20px; border-radius: 50%; background: #eef2eb;
        }
        .qr-ticket-card::after {
            content: ''; position: absolute; right: -10px; top: 50%;
            width: 20px; height: 20px; border-radius: 50%; background: #eef2eb;
        }

        .qr-ticket-top {
            display: flex; align-items: flex-start; gap: 18px;
            padding: 20px 22px 18px;
        }

        /* QR Code box */
        .qr-thumb-wrap {
            flex-shrink: 0; width: 90px; height: 90px;
            border: 2.5px solid #e4e9e0; border-radius: 14px;
            overflow: hidden; background: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: border-color 0.2s;
        }
        .qr-thumb-wrap:hover { border-color: #2D5A27; }
        .qr-thumb-wrap img { width: 80px; height: 80px; display: block; }

        .qr-ticket-info { flex: 1; min-width: 0; }
        .qr-ticket-type { font-size: 17px; font-weight: 700; color: #2D5A27; margin-bottom: 4px; }
        .qr-ticket-code { font-size: 11px; color: #aaa; letter-spacing: 1px; word-break: break-all; margin-bottom: 10px; }

        .info-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; margin-bottom: 4px; }
        .info-label { color: #7F8C8D; }
        .info-value { font-weight: 600; color: #2F3640; }
        .info-value.green { color: #2D5A27; }

        .qr-ticket-divider {
            margin: 0 22px;
            border: none; border-top: 2px dashed #e4e9e0;
        }
        .qr-ticket-bottom {
            padding: 14px 22px;
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
        }

        /* used / unused badge */
        .used-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 10px; font-size: 12px; font-weight: 700; }
        .used-badge.unused  { background: rgba(45,90,39,0.1); color: #2D5A27; }
        .used-badge.used    { background: #f0f0f0; color: #999; }
        .used-badge.expired { background: #fff0ee; color: #c0392b; }

        .btn-expand-qr {
            height: 36px; padding: 0 16px;
            background: #2D5A27; color: #fff; border: none;
            border-radius: 10px; font-size: 12px; font-weight: 700;
            cursor: pointer; font-family: inherit;
            display: flex; align-items: center; gap: 5px;
            transition: all 0.2s;
        }
        .btn-expand-qr:hover { background: #245020; }

        /* ── FULL QR MODAL ── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.65);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; padding: 20px; opacity: 0; pointer-events: none;
            transition: opacity 0.25s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff; border-radius: 28px; padding: 32px 28px;
            max-width: 380px; width: 100%; box-shadow: 0 24px 60px rgba(0,0,0,0.28);
            text-align: center;
            transform: scale(0.92); transition: transform 0.25s;
        }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-ticket-type { font-size: 20px; font-weight: 700; color: #2D5A27; margin-bottom: 4px; }
        .modal-ticket-date { font-size: 13px; color: #7F8C8D; margin-bottom: 22px; }
        .modal-qr-wrap {
            display: inline-block; border: 3px solid #e4e9e0;
            border-radius: 16px; padding: 14px; background: #fff; margin-bottom: 14px;
        }
        .modal-qr-img { width: 200px; height: 200px; display: block; border-radius: 8px; }
        .modal-qr-code { font-size: 11px; color: #aaa; letter-spacing: 0.5px; word-break: break-all; margin-bottom: 20px; }
        .modal-hint { font-size: 12px; color: #7F8C8D; margin-bottom: 20px; line-height: 1.5; }
        .modal-close-btn {
            width: 100%; height: 46px; border: none;
            border-radius: 14px; background: #2D5A27; color: #fff;
            font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit;
        }

        /* ── EMPTY / LOADING ── */
        .empty-state {
            background: #F9FBF7; border-radius: 24px; padding: 56px 32px;
            text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        .empty-state-icon { font-size: 56px; margin-bottom: 14px; display: block; }
        .empty-state h3 { font-size: 18px; font-weight: 700; color: #2F3640; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; color: #7F8C8D; line-height: 1.6; }
        .btn-buy-now {
            margin-top: 22px; display: inline-flex; align-items: center; gap: 8px;
            height: 48px; padding: 0 28px;
            background: #2D5A27; color: #fff; border: none; border-radius: 14px;
            font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit;
            box-shadow: 0 6px 18px rgba(45,90,39,0.28); transition: all 0.2s;
        }
        .btn-buy-now:hover { background: #245020; }

        .loading-state { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 56px 0; }
        .spinner {
            width: 36px; height: 36px; border: 3px solid #e4e9e0;
            border-top-color: #2D5A27; border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── TAB PANELS ── */
        .tab-panel { display: none; }
        .tab-panel.active { display: flex; flex-direction: column; gap: 20px; }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <div class="nav-left">
        <a class="nav-back" href="mainPage.php">
            <span class="iconify" data-icon="lucide:arrow-left" data-width="18"></span>
        </a>
        <div class="nav-brand">
            <span class="iconify" data-icon="lucide:ticket-check" style="font-size:24px;color:#2D5A27;"></span>
            My Tickets
        </div>
    </div>
    <div id="wt-bell-btn" onclick="wtToggleNotifDropdown()" title="Notifications" style="width:40px;height:40px;border-radius:50%;background:#fff;border:1px solid #e4e9e0;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span id="wt-notif-badge" style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;background:#E74C3C;border-radius:50%;border:2px solid #fff;font-size:10px;font-weight:700;color:#fff;display:none;align-items:center;justify-content:center;"></span>
    </div>
</nav>

<div class="page-wrap">

    <!-- Hero (stat summary) -->
    <div class="hero-card" id="hero-card" style="display:none;">
        <h1>🎟️ Your Tickets</h1>
        <p>Show the QR code at the entrance on your visit day</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num" id="stat-total">—</div>
                <div class="hero-stat-lbl">Total Tickets</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num" id="stat-upcoming">—</div>
                <div class="hero-stat-lbl">Upcoming</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num" id="stat-past">—</div>
                <div class="hero-stat-lbl">Past Visits</div>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div class="loading-state" id="loading-main">
        <div class="spinner"></div>
        <span style="font-size:13px;color:#7F8C8D;">Loading your tickets…</span>
    </div>

    <!-- Tabs -->
    <div id="tabs-section" style="display:none;">
        <div class="tab-bar">
            <button class="tab-btn active" id="tab-btn-upcoming" onclick="switchTab('upcoming')">
                <span class="iconify" data-icon="lucide:calendar-clock" data-width="16"></span>
                Upcoming
                <span class="tab-count" id="tab-count-upcoming">—</span>
            </button>
            <button class="tab-btn" id="tab-btn-past" onclick="switchTab('past')">
                <span class="iconify" data-icon="lucide:history" data-width="16"></span>
                Past
                <span class="tab-count" id="tab-count-past">—</span>
            </button>
        </div>
    </div>

    <!-- UPCOMING TAB -->
    <div class="tab-panel active" id="panel-upcoming">
        <div id="list-upcoming"></div>
    </div>

    <!-- PAST TAB -->
    <div class="tab-panel" id="panel-past">
        <div id="list-past"></div>
    </div>

</div>

<!-- Full QR Modal -->
<div class="modal-overlay" id="qr-modal" onclick="closeQRModal(event)">
    <div class="modal-box">
        <div class="modal-ticket-type" id="modal-ticket-type">—</div>
        <div class="modal-ticket-date" id="modal-ticket-date">—</div>
        <div class="modal-qr-wrap">
            <img class="modal-qr-img" id="modal-qr-img" src="" alt="QR Code" />
        </div>
        <div class="modal-qr-code" id="modal-qr-code">—</div>
        <div class="modal-hint">📱 Present this QR code at the WildTrack entrance gate for scanning</div>
        <button class="modal-close-btn" onclick="document.getElementById('qr-modal').classList.remove('open')">Done</button>
    </div>
</div>

<!-- ── NOTIFICATION BELL + TOAST (self-contained) ── -->
<div class="wt-notif-dropdown" id="wt-notif-dropdown">
  <div class="wt-notif-dropdown-header">Notifications</div>
  <div id="wt-notif-list"><div class="wt-notif-empty">No notifications yet</div></div>
</div>
<div class="wt-toast" id="wt-toast"></div>
<style>
.wt-notif-dropdown { position:fixed;top:72px;right:20px;width:320px;background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.12);border:1px solid #e4e9e0;z-index:9999;display:none;overflow:hidden; }
.wt-notif-dropdown.open { display:block; }
.wt-notif-dropdown-header { padding:16px 20px 12px;font-size:14px;font-weight:700;color:#2F3640;border-bottom:1px solid #f0f4ee; }
.wt-notif-item { padding:14px 20px;border-bottom:1px solid #f0f4ee;cursor:pointer;transition:background 0.15s;display:flex;gap:10px;align-items:flex-start; }
.wt-notif-item:last-child { border-bottom:none; }
.wt-notif-item:hover { background:#f7faf5; }
.wt-notif-item-dot { width:8px;height:8px;background:#2D5A27;border-radius:50%;flex-shrink:0;margin-top:4px; }
.wt-notif-item-title { font-size:13px;font-weight:600;color:#2F3640;margin-bottom:3px; }
.wt-notif-item-body  { font-size:12px;color:#7F8C8D; }
.wt-notif-empty { padding:24px 20px;text-align:center;font-size:13px;color:#aaa; }
.wt-toast { position:fixed;bottom:80px;left:50%;transform:translateX(-50%) translateY(20px);background:#2D5A27;color:#fff;padding:14px 24px;border-radius:16px;font-size:14px;font-weight:600;box-shadow:0 8px 24px rgba(45,90,39,0.35);z-index:99999;opacity:0;transition:all 0.35s ease;pointer-events:none;text-align:center;max-width:340px;width:90%; }
.wt-toast.show { opacity:1;transform:translateX(-50%) translateY(0); }
</style>
<script>
(function(){
  var wtToastTimer=null;
  window.wtShowToast=function(msg,dur){ dur=dur||3500;var t=document.getElementById('wt-toast');t.textContent=msg;t.classList.add('show');if(wtToastTimer)clearTimeout(wtToastTimer);wtToastTimer=setTimeout(function(){t.classList.remove('show');},dur); };
  function esc(s){return s?String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'):'';}
  async function poll(){
    try{
      var res=await fetch('api/tickets.php?action=check_notifications',{credentials:'include'});
      var data=await res.json(); if(!data.success)return;
      var notifs=data.notifications||[], unread=notifs.filter(function(n){return !n.is_read;});
      var badge=document.getElementById('wt-notif-badge');
      if(badge){badge.style.display=unread.length>0?'flex':'none';badge.textContent=unread.length||'';}
      var listEl=document.getElementById('wt-notif-list');
      if(!notifs.length){listEl.innerHTML='<div class="wt-notif-empty">No notifications yet</div>';return;}
      listEl.innerHTML='';
      notifs.forEach(function(n){
        var item=document.createElement('div');item.className='wt-notif-item';
        item.innerHTML=(n.is_read?'':'<div class="wt-notif-item-dot"></div>')+'<div><div class="wt-notif-item-title">'+esc(n.title)+'</div><div class="wt-notif-item-body">'+esc(n.body)+'</div></div>';
        item.onclick=function(){document.getElementById('wt-notif-dropdown').classList.remove('open');window.location.reload();};
        listEl.appendChild(item);
      });
    }catch(e){}
  }
  window.wtToggleNotifDropdown=function(){document.getElementById('wt-notif-dropdown').classList.toggle('open');};
  document.addEventListener('click',function(e){var dd=document.getElementById('wt-notif-dropdown'),bell=e.target.closest('#wt-bell-btn');if(dd&&!bell&&!dd.contains(e.target))dd.classList.remove('open');});
  window.addEventListener('load',function(){poll();setInterval(poll,30000);});
})();
</script>

<script>
// ════════════════════════════════════════════════
//  FETCH APPROVED TICKETS
// ════════════════════════════════════════════════
var allTickets = [];

async function loadTickets() {
    try {
        const res  = await fetch('api/my_bookings.php?action=tickets', { credentials: 'include' });
        const data = await res.json();
        document.getElementById('loading-main').style.display = 'none';
        if (!data.success) { showError((data.message || 'Failed to load tickets.') + ' (server)'); return; }
        // helpers.php respond() may nest data inside data.data or spread at top level
        allTickets = data.tickets ?? data.data?.tickets ?? [];
        renderTickets();
    } catch (e) {
        document.getElementById('loading-main').style.display = 'none';
        showError('Could not connect to server: ' + e.message);
    }
}

function renderTickets() {
    const today = new Date(); today.setHours(0,0,0,0);

    const upcoming = allTickets.filter(t => new Date(t.visit_date) >= today);
    const past     = allTickets.filter(t => new Date(t.visit_date) <  today);

    // Show hero
    if (allTickets.length > 0) {
        document.getElementById('hero-card').style.display = 'block';
        document.getElementById('stat-total').textContent    = allTickets.length;
        document.getElementById('stat-upcoming').textContent = upcoming.length;
        document.getElementById('stat-past').textContent     = past.length;
        document.getElementById('tabs-section').style.display = 'block';
    }

    document.getElementById('tab-count-upcoming').textContent = upcoming.length;
    document.getElementById('tab-count-past').textContent     = past.length;

    renderGroupedList('list-upcoming', upcoming, 'upcoming');
    renderGroupedList('list-past',     past,     'past');
}

// Group tickets by visit_date
function renderGroupedList(containerId, tickets, kind) {
    const el = document.getElementById(containerId);
    if (tickets.length === 0) {
        el.innerHTML = buildEmptyState(kind);
        return;
    }

    // Group by visit_date
    const groups = {};
    tickets.forEach(function(t) {
        const d = t.visit_date;
        if (!groups[d]) groups[d] = [];
        groups[d].push(t);
    });

    // Sort dates
    const sortedDates = Object.keys(groups).sort(kind === 'upcoming' ? (a,b)=>new Date(a)-new Date(b) : (a,b)=>new Date(b)-new Date(a));
    const today = new Date(); today.setHours(0,0,0,0);

    el.innerHTML = sortedDates.map(function(date) {
        const d = new Date(date);
        const isToday    = d.getTime() === today.getTime();
        const isUpcoming = d >= today;
        const dateLabel  = d.toLocaleDateString('en-MY', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        const pillClass  = isToday ? '' : (isUpcoming ? 'upcoming' : 'past');
        const tagClass   = isToday ? 'tag-today' : (isUpcoming ? 'tag-upcoming' : 'tag-past');
        const tagText    = isToday ? '🟡 Today!' : (isUpcoming ? '✓ Upcoming' : '🕒 Past');

        const cardsHtml = groups[date].map(function(t) { return buildQRCard(t, isUpcoming, isToday); }).join('');

        return `
        <div class="visit-group">
            <div class="visit-group-header">
                <div class="visit-date-pill ${pillClass}">
                    <span class="iconify" data-icon="lucide:calendar" data-width="16" style="color:#2D5A27;"></span>
                    ${escHtml(dateLabel)}
                </div>
                <span class="visit-status-tag ${tagClass}">${tagText}</span>
            </div>
            ${cardsHtml}
        </div>`;
    }).join('');
}

function buildQRCard(t, isUpcoming, isToday) {
    const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=' + encodeURIComponent(t.qr_code);
    const qrUrlLarge = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(t.qr_code);

    let usedBadge;
    if (!isUpcoming && !isToday) {
        usedBadge = '<span class="used-badge expired"><span class="iconify" data-icon="lucide:clock" data-width="13"></span>Visit Passed</span>';
    } else if (t.is_used) {
        usedBadge = '<span class="used-badge used"><span class="iconify" data-icon="lucide:check-circle" data-width="13"></span>Used</span>';
    } else {
        usedBadge = '<span class="used-badge unused"><span class="iconify" data-icon="lucide:circle" data-width="13"></span>Valid</span>';
    }

    const showQRBtn = (isUpcoming || isToday)
        ? `<button class="btn-expand-qr" onclick='openQRModal(${JSON.stringify(t)})'>
               <span class="iconify" data-icon="lucide:maximize-2" data-width="13"></span>
               Show QR
           </button>`
        : `<button class="btn-expand-qr" style="background:#aaa;cursor:default;">
               <span class="iconify" data-icon="lucide:lock" data-width="13"></span>
               Expired
           </button>`;

    return `
    <div class="qr-ticket-card">
        <div class="qr-ticket-top">
            <div class="qr-thumb-wrap" onclick='openQRModal(${JSON.stringify(t)})' title="Click to enlarge QR">
                <img src="${escHtml(qrUrl)}" alt="QR" loading="lazy" />
            </div>
            <div class="qr-ticket-info">
                <div class="qr-ticket-type">${escHtml(t.ticket_type)} Pass</div>
                <div class="qr-ticket-code">${escHtml(t.qr_code)}</div>
                <div class="info-row">
                    <span class="info-label">📅 Visit</span>
                    <span class="info-value green">${formatDate(t.visit_date)}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">🎟 Booking</span>
                    <span class="info-value">${escHtml(t.booking_ref)}</span>
                </div>
            </div>
        </div>
        <hr class="qr-ticket-divider">
        <div class="qr-ticket-bottom">
            ${usedBadge}
            ${showQRBtn}
        </div>
    </div>`;
}

// ════════════════════════════════════════════════
//  QR MODAL
// ════════════════════════════════════════════════
function openQRModal(ticket) {
    if (typeof ticket === 'string') { try { ticket = JSON.parse(ticket); } catch(e) {} }
    const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(ticket.qr_code);
    document.getElementById('modal-ticket-type').textContent = ticket.ticket_type + ' Pass';
    document.getElementById('modal-ticket-date').textContent = '📅 Visit: ' + formatDate(ticket.visit_date);
    document.getElementById('modal-qr-img').src              = qrUrl;
    document.getElementById('modal-qr-code').textContent     = ticket.qr_code;
    document.getElementById('qr-modal').classList.add('open');
}

function closeQRModal(e) {
    if (e.target === document.getElementById('qr-modal')) {
        document.getElementById('qr-modal').classList.remove('open');
    }
}

// ════════════════════════════════════════════════
//  TABS
// ════════════════════════════════════════════════
function switchTab(tab) {
    ['upcoming','past'].forEach(function(t) {
        document.getElementById('tab-btn-' + t).classList.toggle('active', t === tab);
        document.getElementById('panel-'    + t).classList.toggle('active', t === tab);
    });
}

// ════════════════════════════════════════════════
//  EMPTY STATE
// ════════════════════════════════════════════════
function buildEmptyState(kind) {
    const cfg = {
        upcoming: { icon: '🎟️', title: 'No upcoming tickets', desc: 'Book a visit and once approved, your QR tickets will appear here.' },
        past:     { icon: '📁', title: 'No past visits',      desc: 'Your visit history will appear here after your visit dates pass.' },
    };
    const c = cfg[kind] || cfg.upcoming;
    return `
    <div class="empty-state">
        <span class="empty-state-icon">${c.icon}</span>
        <h3>${c.title}</h3>
        <p>${c.desc}</p>
        ${kind === 'upcoming' ? `<a href="Ticketing.php"><button class="btn-buy-now"><span class="iconify" data-icon="lucide:ticket" data-width="18"></span>Book a Visit</button></a>` : ''}
    </div>`;
}

// ════════════════════════════════════════════════
//  UTILS
// ════════════════════════════════════════════════
function formatDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('en-MY', { day:'numeric', month:'short', year:'numeric' });
}

function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function showError(msg) {
    document.getElementById('panel-upcoming').innerHTML =
    document.getElementById('panel-past').innerHTML =
        '<div style="text-align:center;padding:40px;color:#c0392b;font-size:14px;">⚠️ ' + escHtml(msg) + '</div>';
    document.getElementById('tabs-section').style.display = 'block';
    document.getElementById('panel-upcoming').classList.add('active');
}

// ── Check if redirected here with ?show=qr from notification bell ──
window.addEventListener('load', function() {
    var params = new URLSearchParams(window.location.search);
    loadTickets();
});
</script>

</body>
</html>
