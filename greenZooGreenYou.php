<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'conservation'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="hero.css"/>
  <link rel="stylesheet" href="shared.css">
  <title>Green Zoo, Green You — WildTrack Zoo</title>
  <style>
    .green-stat-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 32px 0;
    }
    .green-stat {
      background: #fff;
      border-radius: 12px;
      padding: 24px 28px;
      text-align: center;
      min-width: 180px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      flex: 1;
    }
    .green-stat .gs-num  { font-size: 36px; font-weight: bold; color: #2a5a2e; }
    .green-stat .gs-label{ font-size: 14px; color: #666; margin-top: 6px; }
    .tip-list { list-style: none; padding: 0; margin: 16px 0; }
    .tip-list li {
      padding: 12px 0;
      border-bottom: 1px solid #dde8dd;
      font-size: 16px;
      color: #3a3a3a;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }
    .tip-list li::before { content: '🌿'; font-size: 18px; flex-shrink: 0; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="images/giraffe.avif" alt="Green Zoo"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Green Zoo<br/><em>Green You</em></h1>
    <p class="hero-sub">WildTrack Zoo is committed to being one of the most environmentally sustainable
     zoos in the region. Every action we take — and every action you take — makes a difference.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <div class="green-stat-row">
    <div class="green-stat">
      <div class="gs-num">85%</div>
      <div class="gs-label">Waste diverted from landfill</div>
    </div>
    <div class="green-stat">
      <div class="gs-num">40%</div>
      <div class="gs-label">Energy from renewable sources</div>
    </div>
    <div class="green-stat">
      <div class="gs-num">0</div>
      <div class="gs-label">Single-use plastic straws on site</div>
    </div>
  </div>

  <h2>What You Can Do</h2>
  <p>Small steps add up. Here's how you can help the planet during your visit and at home:</p>

  <ul class="tip-list">
    <li>Use our refill water stations instead of buying single-use bottles</li>
    <li>Dispose of rubbish in the correct bins — recycling stations are marked throughout the Zoo</li>
    <li>Walk, cycle, or use public transport to reach the Zoo</li>
    <li>Choose sustainably sourced items from our gift shop</li>
    <li>Donate to our conservation fund at checkout when buying tickets online</li>
  </ul>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Conservation', href: 'conservationMain.php' },
    { label: 'Green Zoo, Green You' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
