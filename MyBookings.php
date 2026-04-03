<?php require_once 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status — WildTrack</title>
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
        .page-wrap { max-width: 860px; margin: 0 auto; padding: 40px 24px 80px; display: flex; flex-direction: column; gap: 24px; }

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
            background: #F9FBF7; border-radius: 24px; padding: 28px 32px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .hero-card h1 { font-size: 24px; font-weight: 700; color: #2F3640; }
        .hero-card h1 span { color: #2D5A27; }
        .hero-card p { font-size: 14px; color: #7F8C8D; margin-top: 6px; }

        /* ── BOOKING CARD ── */
        .booking-card {
            background: #F9FBF7; border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden; border: 2px solid transparent;
            transition: all 0.2s;
        }
        .booking-card:hover { border-color: rgba(45,90,39,0.15); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }

        .booking-card-header {
            padding: 18px 22px 14px;
            display: flex; justify-content: space-between; align-items: flex-start;
            border-bottom: 1px solid #f0f4ee;
        }
        .booking-ref { font-size: 18px; font-weight: 700; color: #2D5A27; letter-spacing: 1px; }
        .booking-date-small { font-size: 12px; color: #7F8C8D; margin-top: 3px; }

        /* STATUS BADGES */
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        .status-pending { background: rgba(243,156,18,0.12); color: #E67E22; }
        .status-approved { background: rgba(45,90,39,0.12); color: #2D5A27; }
        .status-rejected { background: rgba(192,57,43,0.1); color: #c0392b; }
        .pulse-dot-sm {
            width: 7px; height: 7px; border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }
        .pulse-dot-sm.orange { background: #E67E22; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.35} }

        .booking-card-body { padding: 16px 22px; display: flex; flex-direction: column; gap: 10px; }

        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px;
        }
        .info-label { color: #7F8C8D; }
        .info-value { font-weight: 600; color: #2F3640; }
        .info-value.green { color: #2D5A27; }

        .booking-card-footer {
            padding: 14px 22px; border-top: 1px solid #f0f4ee;
            display: flex; gap: 10px; align-items: center;
        }

        /* ── TICKET CHIPS ── */
        .ticket-chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(45,90,39,0.08); color: #2D5A27;
            border-radius: 10px; padding: 4px 10px;
            font-size: 12px; font-weight: 600;
        }
        .chip.addon { background: rgba(230,126,34,0.08); color: #E67E22; }

        /* ── ACTION BUTTONS ── */
        .btn-view-qr {
            flex: 1; height: 40px;
            background: #2D5A27; color: #fff; border: none;
            border-radius: 12px; font-size: 13px; font-weight: 700;
            cursor: pointer; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.2s; box-shadow: 0 4px 12px rgba(45,90,39,0.22);
        }
        .btn-view-qr:hover { background: #245020; transform: translateY(-1px); }

        .btn-outline {
            height: 40px; padding: 0 18px;
            background: transparent; color: #7F8C8D;
            border: 1.5px solid #e4e9e0; border-radius: 12px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            font-family: inherit; display: flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-outline:hover { border-color: #2D5A27; color: #2D5A27; background: #f5faf3; }

        .btn-rejected-info {
            flex: 1; height: 40px; padding: 0 18px;
            background: rgba(192,57,43,0.07); color: #c0392b;
            border: 1.5px solid rgba(192,57,43,0.2); border-radius: 12px;
            font-size: 13px; font-weight: 600; cursor: default;
            font-family: inherit; display: flex; align-items: center; gap: 6px;
        }

        /* ── EMPTY STATE ── */
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
        .btn-buy-now:hover { background: #245020; transform: translateY(-1px); }

        /* ── LOADING ── */
        .loading-state { display: flex; flex-direction: column; align-items: center; gap: 14px; padding: 48px 0; }
        .spinner {
            width: 36px; height: 36px; border: 3px solid #e4e9e0;
            border-top-color: #2D5A27; border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── MODAL (proof image viewer) ── */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.6);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; padding: 20px; opacity: 0; pointer-events: none;
            transition: opacity 0.25s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff; border-radius: 24px; padding: 24px;
            max-width: 480px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            transform: scale(0.95); transition: transform 0.25s;
        }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-title { font-size: 16px; font-weight: 700; color: #2F3640; margin-bottom: 16px; }
        .modal-img { width: 100%; border-radius: 12px; border: 2px solid #e4e9e0; max-height: 350px; object-fit: contain; background: #f8f8f8; }
        .modal-close-btn {
            margin-top: 16px; width: 100%; height: 44px; border: none;
            border-radius: 12px; background: #2D5A27; color: #fff;
            font-size: 14px; font-weight: 700; cursor: pointer; font-family: inherit;
        }

        /* ── SECTION LABEL ── */
        .section-label {
            font-size: 11px; font-weight: 700; color: #7F8C8D;
            letter-spacing: 1px; text-transform: uppercase;
            padding-left: 4px;
        }

        /* ── TAB PANELS ── */
        .tab-panel { display: none; }
        .tab-panel.active { display: flex; flex-direction: column; gap: 16px; }
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
            <span class="iconify" data-icon="lucide:calendar-check" style="font-size:24px;color:#2D5A27;"></span>
            Booking Status
        </div>
    </div>
    <div id="wt-bell-btn" onclick="wtToggleNotifDropdown()" title="Notifications" style="width:40px;height:40px;border-radius:50%;background:#fff;border:1px solid #e4e9e0;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;box-shadow:0 1px 4px rgba(0,0,0,0.06);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <span id="wt-notif-badge" style="position:absolute;top:-4px;right:-4px;width:18px;height:18px;background:#E74C3C;border-radius:50%;border:2px solid #fff;font-size:10px;font-weight:700;color:#fff;display:none;align-items:center;justify-content:center;"></span>
    </div>
</nav>

<div class="page-wrap">

    <!-- Hero -->
    <div class="hero-card">
        <h1>Booking <span>Status</span></h1>
        <p>Track your booking status and view your visit summary to WildTrack Safari Park</p>
    </div>

    <!-- Tab Bar -->
    <div class="tab-bar">
        <button class="tab-btn active" id="tab-btn-pending" onclick="switchTab('pending')">
            <span class="iconify" data-icon="lucide:clock" data-width="16"></span>
            Pending
            <span class="tab-count" id="tab-count-pending">—</span>
        </button>
        <button class="tab-btn" id="tab-btn-approved" onclick="switchTab('approved')">
            <span class="iconify" data-icon="lucide:check-circle" data-width="16"></span>
            Approved
            <span class="tab-count" id="tab-count-approved">—</span>
        </button>
        <button class="tab-btn" id="tab-btn-rejected" onclick="switchTab('rejected')">
            <span class="iconify" data-icon="lucide:x-circle" data-width="16"></span>
            Rejected
            <span class="tab-count" id="tab-count-rejected">—</span>
        </button>
    </div>

    <!-- PENDING TAB -->
    <div class="tab-panel active" id="panel-pending">
        <div class="loading-state" id="loading-pending">
            <div class="spinner"></div>
            <span style="font-size:13px;color:#7F8C8D;">Loading bookings…</span>
        </div>
        <div id="list-pending"></div>
    </div>

    <!-- APPROVED TAB -->
    <div class="tab-panel" id="panel-approved">
        <div class="loading-state" id="loading-approved" style="display:none;">
            <div class="spinner"></div>
            <span style="font-size:13px;color:#7F8C8D;">Loading bookings…</span>
        </div>
        <div id="list-approved"></div>
    </div>

    <!-- REJECTED TAB -->
    <div class="tab-panel" id="panel-rejected">
        <div class="loading-state" id="loading-rejected" style="display:none;">
            <div class="spinner"></div>
            <span style="font-size:13px;color:#7F8C8D;">Loading bookings…</span>
        </div>
        <div id="list-rejected"></div>
    </div>

    <!-- Buy Tickets CTA (shown at bottom always) -->
    <div style="text-align:center;padding-top:8px;">
        <a href="Ticketing.php" style="text-decoration:none;">
            <button class="btn-buy-now">
                <span class="iconify" data-icon="lucide:ticket" data-width="18"></span>
                Book New Visit
            </button>
        </a>
    </div>

</div>

<!-- Proof Image Modal -->
<div class="modal-overlay" id="proof-modal" onclick="closeProofModal(event)">
    <div class="modal-box">
        <div class="modal-title">💳 Payment Proof</div>
        <img class="modal-img" id="proof-modal-img" src="" alt="Payment proof" />
        <button class="modal-close-btn" onclick="document.getElementById('proof-modal').classList.remove('open')">Close</button>
    </div>
</div>

<!-- ── NOTIFICATION BELL + TOAST (self-contained, no full nav) ── -->
<div class="wt-notif-dropdown" id="wt-notif-dropdown">
  <div class="wt-notif-dropdown-header">Notifications</div>
  <div id="wt-notif-list"><div class="wt-notif-empty">No notifications yet</div></div>
</div>
<div class="wt-toast" id="wt-toast"></div>

<style>
.wt-notif-dropdown {
  position: fixed; top: 72px; right: 20px; width: 320px;
  background: #fff; border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  border: 1px solid #e4e9e0; z-index: 9999;
  display: none; overflow: hidden;
}
.wt-notif-dropdown.open { display: block; }
.wt-notif-dropdown-header { padding: 16px 20px 12px; font-size: 14px; font-weight: 700; color: #2F3640; border-bottom: 1px solid #f0f4ee; }
.wt-notif-item { padding: 14px 20px; border-bottom: 1px solid #f0f4ee; cursor: pointer; transition: background 0.15s; display: flex; gap: 10px; align-items: flex-start; }
.wt-notif-item:last-child { border-bottom: none; }
.wt-notif-item:hover { background: #f7faf5; }
.wt-notif-item-dot { width: 8px; height: 8px; background: #2D5A27; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
.wt-notif-item-title { font-size: 13px; font-weight: 600; color: #2F3640; margin-bottom: 3px; }
.wt-notif-item-body  { font-size: 12px; color: #7F8C8D; }
.wt-notif-empty { padding: 24px 20px; text-align: center; font-size: 13px; color: #aaa; }
.wt-toast {
  position: fixed; bottom: 80px; left: 50%;
  transform: translateX(-50%) translateY(20px);
  background: #2D5A27; color: #fff; padding: 14px 24px;
  border-radius: 16px; font-size: 14px; font-weight: 600;
  box-shadow: 0 8px 24px rgba(45,90,39,0.35);
  z-index: 99999; opacity: 0; transition: all 0.35s ease;
  pointer-events: none; text-align: center; max-width: 340px; width: 90%;
}
.wt-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
</style>

<script>
(function() {
  var wtToastTimer = null;
  window.wtShowToast = function(msg, duration) {
    duration = duration || 3500;
    var t = document.getElementById('wt-toast');
    t.textContent = msg; t.classList.add('show');
    if (wtToastTimer) clearTimeout(wtToastTimer);
    wtToastTimer = setTimeout(function(){ t.classList.remove('show'); }, duration);
  };
  function wtEscHtml(s){ return s?String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'):''; }
  async function wtPollNotifications() {
    try {
      var res  = await fetch('api/tickets.php?action=check_notifications', { credentials:'include' });
      var data = await res.json();
      if (!data.success) return;
      var notifications = data.notifications || [];
      var unread = notifications.filter(function(n){ return !n.is_read; });
      var badge = document.getElementById('wt-notif-badge');
      if (badge) { badge.style.display = unread.length > 0 ? 'flex' : 'none'; badge.textContent = unread.length || ''; }
      var listEl = document.getElementById('wt-notif-list');
      if (!notifications.length) { listEl.innerHTML = '<div class="wt-notif-empty">No notifications yet</div>'; return; }
      listEl.innerHTML = '';
      notifications.forEach(function(n) {
        var item = document.createElement('div'); item.className = 'wt-notif-item';
        item.innerHTML = (n.is_read?'':'<div class="wt-notif-item-dot"></div>') +
          '<div><div class="wt-notif-item-title">'+wtEscHtml(n.title)+'</div><div class="wt-notif-item-body">'+wtEscHtml(n.body)+'</div></div>';
        item.onclick = function(){ document.getElementById('wt-notif-dropdown').classList.remove('open'); window.location.reload(); };
        listEl.appendChild(item);
      });
    } catch(e){}
  }
  window.wtToggleNotifDropdown = function(){ document.getElementById('wt-notif-dropdown').classList.toggle('open'); };
  document.addEventListener('click', function(e){
    var dd=document.getElementById('wt-notif-dropdown'), bell=e.target.closest('#wt-bell-btn');
    if(dd&&!bell&&!dd.contains(e.target)) dd.classList.remove('open');
  });
  window.addEventListener('load', function(){ wtPollNotifications(); setInterval(wtPollNotifications,30000); });
})();
</script>

<script>
// ════════════════════════════════════════════════
//  DATA
// ════════════════════════════════════════════════
var allBookings  = [];
var currentTab   = 'pending';

// ════════════════════════════════════════════════
//  FETCH
// ════════════════════════════════════════════════
async function loadBookings() {
    try {
        const res  = await fetch('api/my_bookings.php?action=list', { credentials: 'include' });
        const data = await res.json();
        // helpers.php respond() may nest data inside data.data or spread it at top level
        const bookings = data.bookings ?? data.data?.bookings ?? [];
        if (!data.success) { showError(data.message || 'Failed to load bookings.'); return; }
        allBookings = bookings;
        renderAll();
    } catch (e) {
        showError('Could not connect to server. Please try again.');
    }
}

function renderAll() {
    const pending  = allBookings.filter(b => b.status === 'pending');
    const approved = allBookings.filter(b => b.status === 'approved');
    const rejected = allBookings.filter(b => b.status === 'rejected');

    document.getElementById('tab-count-pending').textContent  = pending.length;
    document.getElementById('tab-count-approved').textContent = approved.length;
    document.getElementById('tab-count-rejected').textContent = rejected.length;

    document.getElementById('loading-pending').style.display  = 'none';
    document.getElementById('loading-approved').style.display = 'none';
    document.getElementById('loading-rejected').style.display = 'none';

    renderList('list-pending',  pending,  'pending');
    renderList('list-approved', approved, 'approved');
    renderList('list-rejected', rejected, 'rejected');
}

function renderList(containerId, bookings, status) {
    const el = document.getElementById(containerId);
    if (bookings.length === 0) {
        el.innerHTML = buildEmptyState(status);
        return;
    }
    el.innerHTML = bookings.map(b => buildBookingCard(b)).join('');
}

// ════════════════════════════════════════════════
//  BUILD CARD HTML
// ════════════════════════════════════════════════
function buildBookingCard(b) {
    const statusBadge = buildStatusBadge(b.status);
    const ticketChips = buildTicketChips(b.tickets, b.addons);
    const visitDate   = formatDate(b.visit_date);
    const submittedAt = formatDateTime(b.created_at);
    const amountStr   = 'RM' + parseFloat(b.total_amount).toFixed(2);

    let footer = '';
    if (b.status === 'pending') {
        footer = `
            <button class="btn-outline" onclick="viewProof('${escAttr(b.proof_image_url)}')">
                <span class="iconify" data-icon="lucide:image" data-width="14"></span>
                View Proof
            </button>
            <div style="flex:1;display:flex;align-items:center;gap:6px;font-size:12px;color:#E67E22;font-weight:600;padding-left:4px;">
                <span class="iconify" data-icon="lucide:clock" data-width="14"></span>
                Awaiting admin review
            </div>`;
    } else if (b.status === 'approved') {
        const ticketIds = JSON.stringify(b.ticket_ids || []);
        footer = `
            <button class="btn-view-qr" onclick="viewTickets(${escAttr(ticketIds)})">
                <span class="iconify" data-icon="lucide:qr-code" data-width="15"></span>
                View QR Tickets
            </button>
            <button class="btn-outline" onclick="viewProof('${escAttr(b.proof_image_url)}')">
                <span class="iconify" data-icon="lucide:image" data-width="14"></span>
            </button>`;
    } else if (b.status === 'rejected') {
        const reason = b.rejection_reason || 'Payment could not be verified. Please contact to admin for further assistance.';
        footer = `
            <div class="btn-rejected-info">
                <span class="iconify" data-icon="lucide:alert-circle" data-width="14"></span>
                ${escHtml(reason)}
            </div>`;
    }

    return `
    <div class="booking-card">
        <div class="booking-card-header">
            <div>
                <div class="booking-ref">${escHtml(b.booking_ref)}</div>
                <div class="booking-date-small">Submitted ${submittedAt}</div>
            </div>
            ${statusBadge}
        </div>
        <div class="booking-card-body">
            <div class="info-row">
                <span class="info-label">📅 Visit Date</span>
                <span class="info-value green">${escHtml(visitDate)}</span>
            </div>
            <div class="info-row">
                <span class="info-label">💰 Amount</span>
                <span class="info-value">${escHtml(amountStr)}</span>
            </div>
            <div class="info-row">
                <span class="info-label">💳 Payment</span>
                <span class="info-value">Touch 'n Go eWallet</span>
            </div>
            <div style="margin-top:4px;">
                <div class="ticket-chips">${ticketChips}</div>
            </div>
        </div>
        <div class="booking-card-footer">${footer}</div>
    </div>`;
}

function buildStatusBadge(status) {
    if (status === 'pending')  return `<span class="status-badge status-pending"><span class="pulse-dot-sm orange"></span>Awaiting Approval</span>`;
    if (status === 'approved') return `<span class="status-badge status-approved">✓ Approved</span>`;
    if (status === 'rejected') return `<span class="status-badge status-rejected">✕ Rejected</span>`;
    return '';
}

function buildTicketChips(tickets, addons) {
    let html = '';
    (tickets || []).forEach(function(t) {
        html += `<span class="chip"><span class="iconify" data-icon="lucide:ticket" data-width="11"></span>${escHtml(t.ticket_type)} ×${t.quantity}</span>`;
    });
    (addons || []).forEach(function(a) {
        html += `<span class="chip addon"><span class="iconify" data-icon="lucide:plus-circle" data-width="11"></span>${escHtml(a.addon_type)} ×${a.quantity}</span>`;
    });
    return html || '<span style="font-size:12px;color:#aaa;">No ticket details</span>';
}

function buildEmptyState(status) {
    const cfg = {
        pending:  { icon: '⏳', title: 'No pending bookings',  desc: 'Bookings waiting for admin approval will appear here.' },
        approved: { icon: '🎟️', title: 'No approved bookings', desc: 'Once admin approves your payment, your tickets will appear here.' },
        rejected: { icon: '❌', title: 'No rejected bookings',  desc: 'Any declined bookings will appear here with a reason.' },
    };
    const c = cfg[status] || cfg.pending;
    return `
    <div class="empty-state">
        <span class="empty-state-icon">${c.icon}</span>
        <h3>${c.title}</h3>
        <p>${c.desc}</p>
    </div>`;
}

// ════════════════════════════════════════════════
//  ACTIONS
// ════════════════════════════════════════════════
function viewProof(url) {
    if (!url) { alert('Proof image not available.'); return; }
    document.getElementById('proof-modal-img').src = url;
    document.getElementById('proof-modal').classList.add('open');
}

function closeProofModal(e) {
    if (e.target === document.getElementById('proof-modal')) {
        document.getElementById('proof-modal').classList.remove('open');
    }
}

function viewTickets(ticketIdsJson) {
    window.location.href = 'MyTickets.php';
}

// ════════════════════════════════════════════════
//  TABS
// ════════════════════════════════════════════════
function switchTab(tab) {
    currentTab = tab;
    ['pending','approved','rejected'].forEach(function(t) {
        document.getElementById('tab-btn-' + t).classList.toggle('active', t === tab);
        document.getElementById('panel-'    + t).classList.toggle('active', t === tab);
    });
}

// ════════════════════════════════════════════════
//  UTILITIES
// ════════════════════════════════════════════════
function formatDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('en-MY', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
}

function formatDateTime(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('en-MY', { day:'numeric', month:'short', year:'numeric' }) + ' ' +
           d.toLocaleTimeString('en-MY', { hour:'2-digit', minute:'2-digit' });
}

function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function escAttr(val) {
    if (typeof val === 'object') val = JSON.stringify(val);
    return String(val || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function showError(msg) {
    ['pending','approved','rejected'].forEach(function(t) {
        document.getElementById('loading-' + t).style.display = 'none';
        document.getElementById('list-'    + t).innerHTML =
            '<div style="text-align:center;padding:40px;color:#c0392b;font-size:14px;">⚠️ ' + escHtml(msg) + '</div>';
    });
}

// ════════════════════════════════════════════════
//  INIT
// ════════════════════════════════════════════════
window.addEventListener('load', loadBookings);
</script>

</body>
</html>
