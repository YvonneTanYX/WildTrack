<?php /* announcement-banner.php — include on any visitor page, right after nav.php */ ?>

<div id="zoo-announcement-banner" style="display:none;"><!-- populated by JS below --></div>

<style>
/* ── Announcement Banner ─────────────────────────────────────────── */
#zoo-announcement-banner {
  width: 100%;
  background: #fffbeb;
  border-bottom: 2px solid #f59e0b;
}

#zoo-announcement-banner.color-green  { background: #f0fdf4; border-color: #22c55e; }
#zoo-announcement-banner.color-blue   { background: #eff6ff; border-color: #3b82f6; }
#zoo-announcement-banner.color-purple { background: #faf5ff; border-color: #a855f7; }
#zoo-announcement-banner.color-orange { background: #fffbeb; border-color: #f59e0b; }

.ann-banner-inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

/* ── Single announcement: inline strip ── */
.ann-single {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 0;
}
.ann-single-icon  { font-size: 18px; flex-shrink: 0; }
.ann-single-text  { flex: 1; font-size: 14px; font-weight: 600; color: #1a2b18; }
.ann-single-text span { font-weight: 400; color: #555; margin-left: 6px; }
.ann-single-close {
  background: none; border: none; cursor: pointer;
  font-size: 18px; color: #999; line-height: 1;
  padding: 0 4px; flex-shrink: 0;
}
.ann-single-close:hover { color: #333; }

/* ── Multiple announcements: expandable ── */
.ann-multi-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 0;
  cursor: pointer;
  user-select: none;
}
.ann-multi-bar-icon  { font-size: 18px; flex-shrink: 0; }
.ann-multi-bar-text  { flex: 1; font-size: 14px; font-weight: 600; color: #1a2b18; }
.ann-multi-bar-count {
  background: #f59e0b;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 2px 9px;
  border-radius: 20px;
  flex-shrink: 0;
}
#zoo-announcement-banner.color-green  .ann-multi-bar-count { background: #22c55e; }
#zoo-announcement-banner.color-blue   .ann-multi-bar-count { background: #3b82f6; }
#zoo-announcement-banner.color-purple .ann-multi-bar-count { background: #a855f7; }

.ann-chevron {
  font-size: 13px; color: #888; flex-shrink: 0;
  transition: transform .2s;
}
.ann-chevron.open { transform: rotate(180deg); }

.ann-multi-close {
  background: none; border: none; cursor: pointer;
  font-size: 18px; color: #999; line-height: 1;
  padding: 0 4px; flex-shrink: 0;
}
.ann-multi-close:hover { color: #333; }

.ann-drawer {
  max-height: 0;
  overflow: hidden;
  transition: max-height .3s ease;
  border-top: 1px solid rgba(0,0,0,.07);
}
.ann-drawer.open { max-height: 400px; }

.ann-drawer-list {
  display: flex;
  flex-direction: column;
  gap: 0;
  padding: 4px 0 10px;
}
.ann-drawer-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(0,0,0,.06);
}
.ann-drawer-item:last-child { border-bottom: none; }
.ann-drawer-icon  { font-size: 18px; padding-top: 2px; flex-shrink: 0; }
.ann-drawer-body  { flex: 1; }
.ann-drawer-body strong { display: block; font-size: 14px; color: #1a2b18; margin-bottom: 3px; }
.ann-drawer-body p  { font-size: 13px; color: #444; margin: 0; }
.ann-drawer-body small { font-size: 12px; color: #888; }
</style>

<script>
(async function initAnnouncementBanner() {
  try {
    const res  = await fetch('api/announcements.php?action=get_announcements');
    const data = await res.json();
    if (!data.success || !data.announcements.length) return;

    const banner = document.getElementById('zoo-announcement-banner');
    if (!banner) return;

    const list   = data.announcements;
    const first  = list[0];

    // Pick banner colour from first announcement
    const colorClass = 'color-' + (first.icon_color || 'orange');
    banner.classList.add(colorClass);

    function escH(s) {
      return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function fmtDate(d) {
      return new Date(d).toLocaleDateString('en-MY', { day:'numeric', month:'short', year:'numeric' });
    }
    function closeBanner() {
      banner.style.transition = 'max-height .3s ease, opacity .3s ease';
      banner.style.maxHeight  = banner.offsetHeight + 'px';
      banner.style.overflow   = 'hidden';
      requestAnimationFrame(() => {
        banner.style.maxHeight = '0';
        banner.style.opacity   = '0';
        setTimeout(() => banner.style.display = 'none', 320);
      });
    }

    if (list.length === 1) {
      /* ── Single announcement: one-line strip ── */
      banner.innerHTML = `
        <div class="ann-banner-inner">
          <div class="ann-single">
            <span class="ann-single-icon">${escH(first.icon)}</span>
            <span class="ann-single-text">
              ${escH(first.title)}
              <span>${escH(first.body)}</span>
            </span>
            <button class="ann-single-close" onclick="this.closest('#zoo-announcement-banner') && (function(b){
              b.style.transition='max-height .3s,opacity .3s';
              b.style.maxHeight=b.offsetHeight+'px';
              b.style.overflow='hidden';
              requestAnimationFrame(()=>{b.style.maxHeight='0';b.style.opacity='0';setTimeout(()=>b.style.display='none',320);});
            })(document.getElementById('zoo-announcement-banner'))" title="Dismiss">×</button>
          </div>
        </div>`;
    } else {
      /* ── Multiple: collapsible drawer ── */
      const items = list.map(a => `
        <div class="ann-drawer-item">
          <span class="ann-drawer-icon">${escH(a.icon)}</span>
          <div class="ann-drawer-body">
            <strong>${escH(a.title)}</strong>
            <p>${escH(a.body)}</p>
            <small>${fmtDate(a.created_at)} · ${escH(a.audience)}</small>
          </div>
        </div>`).join('');

      banner.innerHTML = `
        <div class="ann-banner-inner">
          <div class="ann-multi-bar" onclick="document.getElementById('annDrawer').classList.toggle('open');this.querySelector('.ann-chevron').classList.toggle('open');">
            <span class="ann-multi-bar-icon">${escH(first.icon)}</span>
            <span class="ann-multi-bar-text">Zoo Notices — ${escH(first.title)}</span>
            <span class="ann-multi-bar-count">${list.length} notices</span>
            <span class="ann-chevron">▼</span>
            <button class="ann-multi-close" onclick="event.stopPropagation();(function(b){b.style.transition='max-height .3s,opacity .3s';b.style.maxHeight=b.offsetHeight+'px';b.style.overflow='hidden';requestAnimationFrame(()=>{b.style.maxHeight='0';b.style.opacity='0';setTimeout(()=>b.style.display='none',320);});})(document.getElementById('zoo-announcement-banner'))" title="Dismiss">×</button>
          </div>
          <div class="ann-drawer" id="annDrawer">
            <div class="ann-drawer-list">${items}</div>
          </div>
        </div>`;
    }

    banner.style.display = 'block';

  } catch(e) { /* silently skip if API unavailable */ }
})();
</script>
