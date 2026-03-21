/* =====================================================
   FinalProject.js — WildTrack Zoo Visitor Pages
   Handles: dropdown nav, breadcrumb, image slider
   ===================================================== */

(function () {

  /* ── Dropdown nav ── */
  const dropdowns = document.querySelectorAll('.dropdown');

  dropdowns.forEach(function (dd) {
    const btn   = dd.querySelector('.dropbutton');
    const menu  = dd.querySelector('.dropdown-menu');
    if (!btn || !menu) return;

    // Open on click (toggle)
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      const isOpen = menu.classList.contains('active');

      // Close all others first
      closeAll();

      if (!isOpen) {
        menu.classList.add('active');
        dd.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Close all dropdowns when clicking outside
  document.addEventListener('click', function () {
    closeAll();
  });

  // Prevent clicks inside menu from closing it
  document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
    menu.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAll();
  });

  function closeAll() {
    dropdowns.forEach(function (dd) {
      const menu = dd.querySelector('.dropdown-menu');
      if (menu) menu.classList.remove('active');
      dd.setAttribute('aria-expanded', 'false');
    });
  }

  /* ── Breadcrumb ── */
  // Each page sets window.breadcrumb before this script runs, e.g.:
  // window.breadcrumb = [{ label: 'Visit', href: 'visitMain.php' }, { label: 'Zoo Map' }];
  const bcEl = document.getElementById('breadcrumb-trail');
  if (bcEl && Array.isArray(window.breadcrumb)) {
    const parts = [{ label: 'Home', href: 'mainPage.php' }].concat(window.breadcrumb);
    bcEl.innerHTML = parts.map(function (item, i) {
      const isLast = i === parts.length - 1;
      if (isLast) {
        return '<span>' + item.label + '</span>';
      }
      return '<a href="' + item.href + '">' + item.label + '</a><span class="sep">›</span>';
    }).join('');
  }

  /* ── Image Slider ── */
  const slides = document.querySelectorAll('.slide');
  const dots   = document.querySelectorAll('.slider-dot');
  if (slides.length === 0) return;

  let current = 0;
  let timer   = null;

  function goTo(index) {
    slides[current].classList.remove('active');
    dots[current] && dots[current].classList.remove('active');
    current = (index + slides.length) % slides.length;
    slides[current].classList.add('active');
    dots[current] && dots[current].classList.add('active');
  }

  function startAuto() {
    timer = setInterval(function () { goTo(current + 1); }, 4500);
  }

  function stopAuto() {
    clearInterval(timer);
  }

  dots.forEach(function (dot, i) {
    dot.addEventListener('click', function () {
      stopAuto();
      goTo(i);
      startAuto();
    });
  });

  startAuto();

})();
