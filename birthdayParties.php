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
  <title>Birthday Parties — WildTrack Zoo</title>
  <style>
    /* ── Hero ── */
    .page-img { width:100%; max-height:420px; object-fit:cover; display:block; }

    /* ── Info strip ── */
    .info-strip {
      display: flex;
      justify-content: center;
      gap: 18px;
      flex-wrap: wrap;
      margin-bottom: 52px;
    }
    .info-chip {
      background: #f0f7f0;
      border: 2px solid #c8e6c9;
      border-radius: 40px;
      padding: 11px 24px;
      font-size: 14px;
      font-weight: 700;
      color: #2a5a2e;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* ── Package card ── */
    .package-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 14px rgba(0,0,0,0.08);
      overflow: hidden;
      display: flex;
      gap: 0;
      margin-bottom: 52px;
      flex-wrap: wrap;
    }
    .package-card-img {
      flex: 0 0 380px;
      max-width: 380px;
    }
    .package-card-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .package-card-body {
      flex: 1;
      padding: 36px 38px;
      min-width: 260px;
    }
    .package-card-body h2 {
      color: #2a5a2e;
      font-size: 26px;
      margin-bottom: 6px;
    }
    .price-tag {
      display: inline-block;
      background: #e8f5e9;
      color: #2a5a2e;
      font-size: 18px;
      font-weight: 800;
      border-radius: 30px;
      padding: 6px 20px;
      margin-bottom: 22px;
    }
    .include-list {
      list-style: none;
      padding: 0;
      margin: 0 0 24px;
    }
    .include-list li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      padding: 7px 0;
      font-size: 15px;
      color: #444;
      border-bottom: 1px solid #f3f3f3;
      line-height: 1.5;
    }
    .include-list li:last-child { border-bottom: none; }
    .include-list li::before {
      content: '✓';
      color: #4caf50;
      font-weight: 800;
      font-size: 14px;
      flex-shrink: 0;
      margin-top: 2px;
    }

    /* ── Book CTA ── */
    .bday-cta {
      background: linear-gradient(135deg, #3aa042 0%, #92c193 100%);
      border-radius: 18px;
      padding: 40px 36px;
      text-align: center;
      color: #fff;
    }
    .bday-cta h2 { font-size: 26px; margin-bottom: 10px; }
    .bday-cta p  { font-size: 16px; opacity: 0.9; margin-bottom: 22px; }
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

    @media (max-width: 700px) {
      .package-card-img { flex: 0 0 100%; max-width: 100%; }
      .package-card-img img { max-height: 220px; }
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://img.freepik.com/premium-vector/animal-cartoon-jungle_49499-240.jpg" alt="Birthday Parties at WildTrack Zoo">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Birthday<br/><em>Parties</em></h1>
    <p class="hero-sub">Calling all party animals! Celebrate your child's big day somewhere truly unique at WildTrack Zoo —
       the perfect, one-of-a-kind setting for an unforgettable Birthday Party surrounded by the magic of wildlife. 🐾</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <!-- Info strip -->
  <div class="info-strip">
    <div class="info-chip">📅 Saturdays &amp; Sundays</div>
    <div class="info-chip">🕙 10 am or 2 pm timeslots</div>
    <div class="info-chip">⏱️ 2 hours exclusive venue hire</div>
    <div class="info-chip">🦁 Supports conservation</div>
  </div>

  <!-- Package card -->
  <div class="package-card">
    <div class="package-card-img">
      <img src="https://images.unsplash.com/photo-1530103862676-de8c9debad1d?q=80&w=800&auto=format&fit=crop"
           alt="Wild Party Package">
    </div>
    <div class="package-card-body">
      <h2>Wild Party Package</h2>
      <div class="price-tag">From RM 30 per child</div>
      <ul class="include-list">
        <li>All-day entry into WildTrack Zoo</li>
        <li>Living Room venue hire for 2 hours</li>
        <li>Free Zoo entry for up to 6 adults</li>
        <li>Birthday present for the birthday child</li>
        <li>Catering from our partners at Rex Tremendous</li>
        <li>60-inch display screen</li>
        <li>Stereo — play music at your leisure</li>
        <li>Tables, chairs &amp; decorations</li>
        <li>Toy box in the room (6 toys)</li>
        <li>Free Wi-Fi</li>
      </ul>
    </div>
  </div>

  <!-- CTA -->
  <div class="bday-cta">
    <h2>Ready to Book the Party?</h2>
    <p>Get in touch with our team to check availability and reserve your date!</p>
    <a href="getInTouch.php" class="btn-white">Enquire Now</a>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Things to Do', href: 'thingMain.php' },
    { label: 'Birthday Parties' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
