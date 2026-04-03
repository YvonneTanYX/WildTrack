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
  <title>Learning — WildTrack Zoo</title>
  <style>
    /* ── Hero ── */
    .page-img { width:100%; max-height:420px; object-fit:cover; display:block; }

    /* ── Intro ── */
    .learning-intro {
      max-width: 860px;
      margin: 0 auto 48px;
      text-align: center;
    }
    .learning-intro h1 { color: #2a5a2e; font-size: 38px; margin-bottom: 14px; }
    .learning-intro p  { font-size: 17px; color: #555; line-height: 1.8; }

    /* ── Program badges ── */
    .badge-row {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 52px;
      flex-wrap: wrap;
    }
    .badge {
      background: #f0f7f0;
      border: 2px solid #c8e6c9;
      border-radius: 40px;
      padding: 10px 26px;
      font-size: 15px;
      font-weight: 700;
      color: #2a5a2e;
    }

    /* ── Ranger cards ── */
    .ranger-grid {
      display: flex;
      gap: 32px;
      flex-wrap: wrap;
      margin-bottom: 56px;
    }
    .ranger-card {
      flex: 1;
      min-width: 280px;
      background: #fff;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 2px 14px rgba(0,0,0,0.08);
      display: flex;
      flex-direction: column;
    }
    .ranger-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
    }
    .ranger-card-body { padding: 24px 26px 28px; flex: 1; }
    .ranger-card-body h3 {
      font-size: 22px;
      color: #2a5a2e;
      margin-bottom: 14px;
    }
    .ranger-card-body .tag {
      display: inline-block;
      background: #e8f5e9;
      color: #2a5a2e;
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 16px;
    }
    .info-row {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 9px;
      font-size: 14px;
      color: #555;
      line-height: 1.5;
    }
    .info-row .icon { font-size: 16px; flex-shrink: 0; }

    /* ── CTA ── */
    .learning-cta {
      background: linear-gradient(135deg, #3aa042 0%, #92c193 100%);
      border-radius: 18px;
      padding: 40px 36px;
      text-align: center;
      color: #fff;
      margin-bottom: 16px;
    }
    .learning-cta h2 { font-size: 26px; margin-bottom: 10px; }
    .learning-cta p  { font-size: 16px; opacity: 0.9; margin-bottom: 22px; }
    .btn-white {
      display: inline-block;
      background: #fff;
      color: #2a5a2e;
      font-weight: 700;
      padding: 13px 32px;
      border-radius: 30px;
      text-decoration: none;
      font-size: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="images/redPanda.avif" alt="Learning at WildTrack Zoo"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Explorer<br/><em>Ranger Program</em></h1>
    <p class="hero-sub">Discovery Zone: Educational Resources and School Programs</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <!-- Intro -->
  <div class="learning-intro">
    <p>Explorer Ranger is an outdoor educational membership program with <strong>Classes Alive</strong> &amp;
       <strong>Discovery Classes</strong> — curated by zoologists, educators, and industry experts.
       Our Explorer Rangers gain in-depth knowledge about wildlife, sustainability, and the natural world!</p>
  </div>

  <!-- Badges -->
  <div class="badge-row">
    <span class="badge">🌿 Classes Alive</span>
    <span class="badge">🔍 Discovery Class</span>
    <span class="badge">🦁 Zoo-based Learning</span>
    <span class="badge">🌏 Sustainability Focus</span>
  </div>

  <!-- Ranger cards -->
  <div class="ranger-grid">

    <!-- Junior Rangers -->
    <div class="ranger-card">
      <img src="https://sunwaylagoon.com/wp-content/uploads/2025/04/Asset-29.png" alt="Junior Rangers">
      <div class="ranger-card-body">
        <span class="tag">Ages 5 – 12</span>
        <h3>🐾 Junior Rangers</h3>
        <div class="info-row"><span class="icon">📍</span><span>WildTrack Zoo</span></div>
        <div class="info-row"><span class="icon">🕙</span><span>10 am – 1 pm</span></div>
        <div class="info-row"><span class="icon">🗣️</span><span>English, supplemented by Bahasa Malaysia</span></div>
        <div class="info-row"><span class="icon">📅</span><span><strong>Classes Alive:</strong> Every first 3 weekends of each month</span></div>
        <div class="info-row"><span class="icon">🔍</span><span><strong>Discovery Class:</strong> Every final weekend of each month</span></div>
      </div>
    </div>

    <!-- Master Rangers -->
    <div class="ranger-card">
      <img src="https://sunwaylagoon.com/wp-content/uploads/2025/04/Asset-32.png" alt="Master Rangers">
      <div class="ranger-card-body">
        <span class="tag">Ages 13 – 17</span>
        <h3>🦅 Master Rangers</h3>
        <div class="info-row"><span class="icon">📍</span><span>WildTrack Zoo</span></div>
        <div class="info-row"><span class="icon">🕙</span><span>10 am – 1 pm</span></div>
        <div class="info-row"><span class="icon">🗣️</span><span>English, supplemented by Bahasa Malaysia</span></div>
        <div class="info-row"><span class="icon">📅</span><span><strong>Classes Alive:</strong> Every first 3 Saturdays of each month</span></div>
        <div class="info-row"><span class="icon">🔍</span><span><strong>Discovery Class:</strong> Every last Saturday of each month</span></div>
      </div>
    </div>

  </div>

  <!-- CTA -->
  <div class="learning-cta">
    <h2>Ready to Raise a Ranger?</h2>
    <p>Enrol your child in the Explorer Ranger program and spark a lifelong love of wildlife.</p>
    <a href="getInTouch.php" class="btn-white">Get in Touch to Enrol</a>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Things to Do', href: 'thingMain.php' },
    { label: 'Learning' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
