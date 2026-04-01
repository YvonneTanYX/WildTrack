<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'animal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Animals — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css"/>
  <link rel="stylesheet" href="mainPage.css"/>
  <style>
    :root {
      --nav:      #4e7a51;
      --green:    #2d5a30;
      --green-md: #3e7a3e;
      --green-lt: #5a9e52;
      --teal:     #76d7c4;
      --teal-dk:  #4eb8a8;
      --bg:       #f1f8e9;
      --white:    #ffffff;
      --text:     #1a2e1a;
      --muted:    #5a7a5a;
      --border:   #c8e0c8;
      --card-sh:  0 4px 24px rgba(46,90,48,.10);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

    /* ── HERO ── */
    .hero {
      position: relative; width: 100%; height: 500px;
      overflow: hidden; background: var(--green);
    }
    .hero-img {
      width: 100%; height: 100%; object-fit: cover;
      opacity: .45; transform: scale(1.06);
      transition: transform 10s ease;
    }
    .hero:hover .hero-img { transform: scale(1); }
    .hero-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(160deg, rgba(30,60,30,.80) 0%, rgba(46,90,48,.55) 55%, transparent 100%);
    }
    .hero-content {
      position: absolute; inset: 0;
      display: flex; flex-direction: column;
      justify-content: center; padding: 0 10%;
    }
    .hero-eyebrow {
      font-size: .75rem; font-weight: 600; letter-spacing: .22em;
      text-transform: uppercase; color: var(--teal);
      margin-bottom: 1rem; animation: fadeUp .55s ease both;
    }
    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(3rem, 7vw, 5.5rem);
      font-weight: 800; color: #fff;
      line-height: 1; margin-bottom: 1.2rem;
      animation: fadeUp .65s .1s ease both;
      text-transform: uppercase; letter-spacing: .02em;
    }
    .hero-sub {
      font-size: 1.08rem; color: rgba(255,255,255,.82);
      max-width: 520px; line-height: 1.75;
      animation: fadeUp .65s .2s ease both;
    }
    .paw { position: absolute; opacity: .07; pointer-events: none; }
    .paw1 { right: 8%; top: 10%; font-size: 6rem; transform: rotate(20deg); }
    .paw2 { right: 18%; bottom: 10%; font-size: 4rem; transform: rotate(-12deg); }
    .paw3 { right: 4%; bottom: 32%; font-size: 2.8rem; transform: rotate(38deg); }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── MAIN TWO-SECTION LAYOUT ── */
    .animal-sections { max-width: 1200px; margin: 0 auto; padding: 60px 24px 80px; display: flex; flex-direction: column; gap: 48px; }

    /* ── SECTION BLOCK ── */
    .section-block {
      background: var(--white);
      border-radius: 24px;
      overflow: hidden;
      border: 1px solid var(--border);
      box-shadow: var(--card-sh);
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 380px;
      transition: box-shadow .3s ease;
    }
    .section-block:hover { box-shadow: 0 16px 56px rgba(46,90,48,.14); }
    .section-block.reverse { direction: rtl; }
    .section-block.reverse > * { direction: ltr; }

    /* Image side */
    .sb-img-wrap {
      position: relative; overflow: hidden;
    }
    .sb-img {
      width: 100%; height: 100%; object-fit: cover;
      transition: transform .6s ease;
    }
    .section-block:hover .sb-img { transform: scale(1.04); }
    .sb-img-label {
      position: absolute; bottom: 0; left: 0; right: 0;
      background: linear-gradient(0deg, rgba(30,60,30,.85) 0%, transparent 100%);
      padding: 32px 28px 20px;
    }
    .sb-img-label h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 800;
      color: #fff; text-transform: uppercase; letter-spacing: .04em;
      line-height: 1;
    }

    /* Content side */
    .sb-content {
      padding: 44px 48px;
      display: flex; flex-direction: column;
      justify-content: center;
    }
    .sb-eyebrow {
      font-size: .72rem; font-weight: 700; letter-spacing: .18em;
      text-transform: uppercase; color: var(--teal-dk);
      margin-bottom: 12px;
    }
    .sb-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.5rem, 2.5vw, 2rem); font-weight: 700;
      color: var(--green); margin-bottom: 14px; line-height: 1.2;
    }
    .sb-desc {
      font-size: .93rem; color: var(--muted); line-height: 1.75;
      margin-bottom: 28px;
    }

    /* Mini animal bubbles for Meet the Animals block */
    .animal-bubbles {
      display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 28px;
    }
    .animal-bubble {
      display: flex; align-items: center; gap: 7px;
      background: #f0f7f0; border: 1px solid var(--border);
      border-radius: 30px; padding: .4rem 1rem;
      text-decoration: none;
      transition: all .2s;
    }
    .animal-bubble:hover {
      background: var(--nav); border-color: var(--nav);
    }
    .animal-bubble:hover .ab-name { color: #fff; }
    .ab-emoji { font-size: 1.1rem; }
    .ab-name { font-size: .8rem; font-weight: 600; color: var(--green); transition: color .2s; }

    /* AI features list for recognition block */
    .ai-features { margin-bottom: 28px; display: flex; flex-direction: column; gap: 10px; }
    .ai-feat {
      display: flex; align-items: flex-start; gap: 12px;
    }
    .ai-feat-icon {
      width: 36px; height: 36px; border-radius: 10px;
      background: linear-gradient(135deg, var(--nav), var(--green-lt));
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; flex-shrink: 0;
      box-shadow: 0 3px 10px rgba(46,90,48,.18);
    }
    .ai-feat-text h5 { font-size: .88rem; font-weight: 700; color: var(--green); margin-bottom: 2px; }
    .ai-feat-text p  { font-size: .8rem; color: var(--muted); line-height: 1.5; }

    .sb-btn {
      display: inline-flex; align-items: center; gap: .5rem;
      background: var(--nav); color: #fff;
      padding: .8rem 2rem; border-radius: 30px;
      text-decoration: none; font-size: .92rem; font-weight: 600;
      transition: all .2s;
      box-shadow: 0 4px 14px rgba(46,90,48,.25);
      align-self: flex-start;
    }
    .sb-btn:hover { background: var(--green); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(46,90,48,.3); }
    .sb-btn .arrow { transition: transform .2s; }
    .sb-btn:hover .arrow { transform: translateX(4px); }

    /* ── STATS STRIP ── */
    .stats-strip {
      background: linear-gradient(135deg, var(--green) 0%, var(--green-md) 100%);
      border-radius: 20px; padding: 36px 48px;
      display: grid; grid-template-columns: repeat(4, 1fr);
      gap: 24px; text-align: center;
    }
    .stat { display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 2.4rem; font-weight: 800; color: var(--teal);
      line-height: 1;
    }
    .stat-label { font-size: .8rem; font-weight: 600; color: rgba(255,255,255,.75); letter-spacing: .06em; text-transform: uppercase; }

    /* ── FOOTER ── */
    footer { background-color: #67976a; padding-bottom: 30px; margin-top: 60px; }
    .footer-container { padding: 0; }
    .footers { display: flex; flex-direction: row; flex-wrap: wrap; }
    .footers > div { margin: 30px 5px 0 40px; }
    .footers > div:first-child { margin-left: 250px; }
    .footers h3 { margin-bottom: 10px; }
    .footers a { color: #3a3a3a; text-decoration: none; display: block; margin-bottom: 4px; font-size: .9rem; }
    .footers a:hover { color: #fff; }
    .footer-bottom {
      text-align: center; padding: 16px;
      font-size: .8rem; color: rgba(255,255,255,.7);
      border-top: 1px solid rgba(255,255,255,.15); margin-top: 20px;
    }

    @media (max-width: 768px) {
      .section-block { grid-template-columns: 1fr; }
      .section-block.reverse { direction: ltr; }
      .sb-img-wrap { min-height: 240px; }
      .sb-content { padding: 28px 24px; }
      .stats-strip { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
      .stats-strip { grid-template-columns: 1fr 1fr; padding: 24px 20px; }
      .stat-num { font-size: 1.8rem; }
    }
  </style>
</head>
<body>
<?php include 'nav.php'; $currentPage = 'animal'; ?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1474511320723-9a56873867b5?q=80&w=1600&auto=format&fit=crop" alt="Animals at WildTrack Malaysia"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Our<br/>Animals</h1>
    <p class="hero-sub">Meet the magnificent creatures that call WildTrack Malaysia home — from critically endangered gorillas to the beloved giant panda.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<!-- MAIN CONTENT -->
<div class="animal-sections">

  <!-- SECTION 1: MEET THE ANIMALS -->
  <div class="section-block">
    <div class="sb-img-wrap">
      <img class="sb-img" src="https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?q=80&w=1000&auto=format&fit=crop" alt="Meet the Animals"/>
      <div class="sb-img-label">
        <h2>Meet the<br/>Animals</h2>
      </div>
    </div>
    <div class="sb-content">
      <p class="sb-eyebrow">Our Residents</p>
      <h3 class="sb-title">Discover Every Species at WildTrack</h3>
      <p class="sb-desc">From the misty bamboo groves of Asia to the sunlit savannahs of Africa — explore detailed profiles of all our resident animals, including their habitats, diets, and conservation status.</p>

      <div class="animal-bubbles">
        <a href="panda.html" class="animal-bubble"><span class="ab-emoji">🐼</span><span class="ab-name">Giant Panda</span></a>
        <a href="elephant.html" class="animal-bubble"><span class="ab-emoji">🐘</span><span class="ab-name">Elephant</span></a>
        <a href="lion.html" class="animal-bubble"><span class="ab-emoji">🦁</span><span class="ab-name">Lion</span></a>
        <a href="gorilla.html" class="animal-bubble"><span class="ab-emoji">🦍</span><span class="ab-name">Gorilla</span></a>
        <a href="penguin.html" class="animal-bubble"><span class="ab-emoji">🐧</span><span class="ab-name">Penguin</span></a>
      </div>

      <a href="meetTheAnimals.php" class="sb-btn">View All Animals <span class="arrow">→</span></a>
    </div>
  </div>

  <!-- SECTION 2: ANIMAL RECOGNITION (reversed layout) -->
  <div class="section-block reverse">
    <div class="sb-img-wrap">
      <img class="sb-img" src="https://images.unsplash.com/photo-1605092676920-8ac5ae40c7c8?q=80&w=1000&auto=format&fit=crop" alt="Animal Recognition"/>
      <div class="sb-img-label">
        <h2>Animal<br/>Recognition</h2>
      </div>
    </div>
    <div class="sb-content">
      <p class="sb-eyebrow">AI-Powered Feature</p>
      <h3 class="sb-title">Identify Any Animal Instantly</h3>
      <p class="sb-desc">Spotted something in the park? Our AI model can identify any of our resident animals from a photo in seconds — and instantly shows you everything about them.</p>

      <div class="ai-features">
        <div class="ai-feat">
          <div class="ai-feat-icon">📷</div>
          <div class="ai-feat-text">
            <h5>Upload or Drag & Drop</h5>
            <p>Supports JPG, PNG and most image formats directly from your device.</p>
          </div>
        </div>
        <div class="ai-feat">
          <div class="ai-feat-icon">🧠</div>
          <div class="ai-feat-text">
            <h5>Instant AI Identification</h5>
            <p>Our Teachable Machine model analyses your photo and returns results with a confidence score.</p>
          </div>
        </div>
        <div class="ai-feat">
          <div class="ai-feat-icon">📋</div>
          <div class="ai-feat-text">
            <h5>Full Animal Profile</h5>
            <p>Recognised animals link directly to their complete species profile page.</p>
          </div>
        </div>
      </div>

      <a href="recognition.php" class="sb-btn">Try Recognition Now <span class="arrow">→</span></a>
    </div>
  </div>

</div><!-- /animal-sections -->

<!-- FOOTER -->
<footer>
  <div class="footer-container">
    <div class="footers">
      <div class="visit-footer">
        <h3><a href="visitMain.php">Visit</a></h3>
        <a href="openingHourRate.php">Opening Hours &amp; Rates</a>
        <a href="zooMap.php">Zoo Map</a>
        <a href="buyTicket.php">Buy a Ticket</a>
      </div>
      <div class="things-footer">
        <h3><a href="thingMain.php">Things to Do</a></h3>
        <a href="event.php">Events &amp; Talk Times</a>
        <a href="learning.php">Learning</a>
      </div>
      <div class="animal-footer">
        <h3><a href="animalMain.php">Animal</a></h3>
        <a href="meetTheAnimals.php">Meet the Animals</a>
        <a href="recognition.php">Animal Recognition</a>
      </div>
      <div class="conservation-footer">
        <h3><a href="conservationMain.php">Conservation</a></h3>
        <a href="savingWildlife.php">Saving Wildlife</a>
        <a href="greenZooGreenYou.php">Green Zoo Green You</a>
        <a href="safeCatSafeWildlife.php">Safe Cat, Safe Wildlife</a>
      </div>
      <div class="getInTouch-footer">
        <h3><a href="getInTouch.php">Get in Touch</a></h3>
      </div>
    </div>
  </div>
  <div class="footer-bottom">© 2026 WildTrack Malaysia · All rights reserved</div>
</footer>

<script>
// ── Nav dropdowns ──
document.querySelectorAll('.dropdown').forEach(dd => {
  const btn  = dd.querySelector('.dropbutton') || dd.querySelector('a');
  const menu = dd.querySelector('.dropdown-menu');
  if (!btn || !menu) return;
  btn.addEventListener('click', e => {
    e.preventDefault();
    const open = menu.classList.contains('active');
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
    if (!open) menu.classList.add('active');
  });
});
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown'))
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
});

// ── Breadcrumb ──
document.addEventListener('DOMContentLoaded', () => {
  const trail = document.getElementById('breadcrumb-trail');
  if (trail) trail.innerHTML =
    '<a href="mainPage.php">Home</a>' +
    '<span class="sep">›</span>' +
    '<span>Animal</span>';
});
</script>
</body>
</html>
