<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="hero.css"/>
  <link rel="stylesheet" href="shared.css">
  <title>Zoo Map — WildTrack Zoo</title>
  <style>
    .map-img {
      width: 100%;
      max-width: 700px;
      border-radius: 12px;
      display: block;
      margin: 20px 0;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .download-card {
      display: flex;
      align-items: center;
      gap: 20px;
      background: #fff;
      border-radius: 12px;
      padding: 20px 24px;
      max-width: 420px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      margin: 20px 0;
    }
    .download-card img {
      width: 90px;
      border-radius: 6px;
    }
    .download-card p {
      font-size: 14px;
      color: #666;
      margin: 4px 0 10px;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1480044965905-02098d419e96?q=80&w=2070&auto=format&fit=crop" alt="Zoo"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Zoo<em> Map</em></h1>
    <p class="hero-sub">Explore the Wild: Your Interactive Zoo Guide</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <h2>Find your way around WildTrack Zoo</h2>
  <p>Use the map below to plan your visit. You can print it out and bring it along,
     or we'll have a copy available for you at the entrance.</p>

  <img src="https://www.wellingtonzoo.com/assets/Wellington-Zoo-Map-__ResizedImageWzYwMCw4NDld.jpg"
       class="map-img" alt="WildTrack Zoo Map">

  <h2 style="color:#2a5a2e; margin-top:32px;">Download the Map</h2>
  <p>Take the map with you — print it before your visit or save it to your phone.</p>

  <div class="download-card">
    <img src="https://www.wellingtonzoo.com/assets/Slices/Events/Wellington-Zoo-Map-__ScaleWidthWzExNV0.jpg"
         alt="Map thumbnail">
    <div>
      <strong>WildTrack Zoo Map</strong>
      <p>PDF format, easy to print</p>
      <a href="https://www.wellingtonzoo.com/assets/Resources/Wellington-Zoo-Map-.jpg"
         download class="download-link">⬇ Download Map (6.1 MB)</a>
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Zoo Map' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
