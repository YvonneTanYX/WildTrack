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
  <title>Safe Cat, Safe Wildlife — WildTrack Zoo</title>
  <style>
    .tip-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin: 28px 0;
    }
    .tip-card {
      background: #fff;
      border-radius: 12px;
      padding: 20px 22px;
      width: 220px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
      flex: 1;
      min-width: 180px;
    }
    .tip-card .tc-icon  { font-size: 28px; margin-bottom: 10px; }
    .tip-card h3        { font-size: 16px; color: #2a5a2e; margin-bottom: 6px; }
    .tip-card p         { font-size: 14px; color: #555; line-height: 1.6; margin: 0; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1511044568932-338cba0ad803?q=80&w=2070&auto=format&fit=crop" alt="Safe Cat Safe Wildlife">/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Safe Cat<br/><em>Safe Wildlife</em></h1>
    <p class="hero-sub">Responsible Pet Ownership: Protecting Local Biodiversity</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <div class="two-col">
    <div class="col-info">
      <h2>Join Our Safe Cat, Safe Wildlife Community!</h2>
      <p>Here at WildTrack, we care about cats big and small. Did you know that a cat kept
         safe at home can live up to <strong>4× longer</strong> than a cat left to roam alone?
         With the proper care and attention, your cat can thrive while also protecting local
         native wildlife.</p>
      <p>Free-roaming cats are one of the leading threats to native birds and small animals.
         By keeping your cat safely at home, you protect both your pet and the wildlife
         around you.</p>
    </div>
    <div class="col-img">
      <img src="https://www.wellingtonzoo.com/assets/pexels-pixabay-160755__ResizedImageWzYwMCw0MDFd.jpg"
           alt="Safe cat" style="height:340px; object-fit:cover;">
    </div>
  </div>

  <h2>Simple Tips for a Happy Indoor Cat</h2>
  <div class="tip-cards">
    <div class="tip-card">
      <div class="tc-icon">🎯</div>
      <h3>Enrichment</h3>
      <p>Provide toys, scratching posts, and climbing structures to keep your cat active and entertained indoors.</p>
    </div>
    <div class="tip-card">
      <div class="tc-icon">🪟</div>
      <h3>Window Perches</h3>
      <p>Give your cat a window seat to watch the outside world — mental stimulation without the risk.</p>
    </div>
    <div class="tip-card">
      <div class="tc-icon">🐾</div>
      <h3>Leash Training</h3>
      <p>Train your cat to walk on a leash for safe outdoor exploration under your supervision.</p>
    </div>
    <div class="tip-card">
      <div class="tc-icon">💉</div>
      <h3>Regular Vet Visits</h3>
      <p>Keep vaccinations and health checks up to date for a long and healthy indoor life.</p>
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Conservation', href: 'conservationMain.php' },
    { label: 'Safe Cat, Safe Wildlife' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
