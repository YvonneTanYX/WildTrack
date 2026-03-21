<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Animal Feeding — WildTrack Zoo</title>
  <style>
    .feeding-badge {
      display: inline-block;
      background: #76d7c4;
      color: #1a3a1a;
      font-weight: bold;
      font-size: 13px;
      padding: 5px 14px;
      border-radius: 20px;
      margin-bottom: 20px;
    }
    .feeding-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 24px;
      margin: 28px 0;
    }
    .feeding-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      width: 300px;
      flex-shrink: 0;
    }
    .feeding-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      display: block;
    }
    .feeding-card .fc-body {
      padding: 18px 20px;
    }
    .feeding-card h2 {
      font-size: 22px;
      color: #2a5a2e;
      margin-bottom: 6px;
    }
    .feeding-card .fc-time {
      font-size: 15px;
      color: #555;
      margin-bottom: 8px;
    }
    .feeding-card .fc-price {
      font-size: 16px;
      font-weight: bold;
      color: #3a3a3a;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://images.unsplash.com/photo-1762367513797-65e32e050ef5?q=80&w=2070&auto=format&fit=crop"
     class="page-img" alt="Animal feeding">

<div class="content-section">

  <h1>Animal Feeding</h1>
  <span class="feeding-badge">📅 Weekends &amp; Public Holidays Only</span>
  <p>Get up close to our animals during our interactive feeding sessions!
     These sessions are available on weekends and public holidays.
     All feed cups are purchased at the animal enclosure on the day.</p>

  <div class="feeding-grid">

    <div class="feeding-card">
      <img src="https://images.unsplash.com/photo-1762367513797-65e32e050ef5?q=80&w=2070&auto=format&fit=crop"
           alt="Goat feeding">
      <div class="fc-body">
        <h2>🐐 Goat</h2>
        <div class="fc-time">🕛 11:30am – 12:00pm</div>
        <div class="fc-price">RM 3 / cup</div>
      </div>
    </div>

    <div class="feeding-card">
      <img src="https://images.unsplash.com/photo-1761284827108-778e42dfde0f?w=900&auto=format&fit=crop&q=60"
           alt="Sheep feeding">
      <div class="fc-body">
        <h2>🐑 Sheep</h2>
        <div class="fc-time">🕛 12:00pm – 12:30pm</div>
        <div class="fc-price">RM 3 / cup</div>
      </div>
    </div>

    <div class="feeding-card">
      <img src="https://images.unsplash.com/photo-1604952962037-67b8c6fa477d?q=80&w=987&auto=format&fit=crop"
           alt="Rabbit feeding">
      <div class="fc-body">
        <h2>🐇 Rabbit</h2>
        <div class="fc-time">🕧 12:30pm – 1:00pm</div>
        <div class="fc-price">RM 2 / cup</div>
      </div>
    </div>

  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Animal Feeding' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
