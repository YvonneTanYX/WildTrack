<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'visit'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Visit — WildTrack Zoo</title>
  <style>
    .visit-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 32px 0;
    }
    .visit-link-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      width: 280px;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
      display: block;
    }
    .visit-link-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    }
    .visit-link-card img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      display: block;
    }
    .visit-link-card .vlc-body {
      padding: 16px 18px;
    }
    .visit-link-card h3 {
      font-size: 17px;
      color: #2a5a2e;
      margin-bottom: 4px;
    }
    .visit-link-card p {
      font-size: 13px;
      color: #777;
      margin: 0;
      line-height: 1.5;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTA5f_frFRYSM6PSi3YDwW9T7IAu16vWNPl5qmYno5Xg28xHhpn2xLpEsNVh1_MXvfuNQzz_OVufFSTEjj5L5_VikHXcT1vJL80Ip2RGAM&s=10"
     class="page-img" alt="Visit WildTrack Zoo" style="object-position:center top;">

<div class="content-section">

  <h1>Plan Your Visit</h1>
  <p>Everything you need to know before you arrive at WildTrack Zoo —
     from tickets and opening hours to maps, food, and accessibility.</p>

  <div class="visit-grid">

    <a href="openingHourRate.php" class="visit-link-card">
      <img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_animals/Amur_tiger/amur_tiger_2.jpg" alt="Opening hours">
      <div class="vlc-body">
        <h3>🕙 Opening Hours &amp; Rates</h3>
        <p>Plan your day — check our opening times and ticket prices before you arrive.</p>
      </div>
    </a>

    <a href="zooMap.php" class="visit-link-card">
      <img src="https://www.wellingtonzoo.com/assets/Wellington-Zoo-Map-__ResizedImageWzYwMCw4NDld.jpg" alt="Zoo map">
      <div class="vlc-body">
        <h3>🗺️ Zoo Map</h3>
        <p>Download and print our Zoo map so you never miss an exhibit.</p>
      </div>
    </a>

    <a href="buyTicket.php" class="visit-link-card">
      <img src="https://images.unsplash.com/photo-1695272016860-c3d5eaa6c660?q=80&w=800&auto=format&fit=crop" alt="Buy ticket">
      <div class="vlc-body">
        <h3>🎟️ Buy a Ticket</h3>
        <p>Book online to save time at the gate and avoid peak-day queues.</p>
      </div>
    </a>

    <a href="accessibility.php" class="visit-link-card">
      <img src="https://images.unsplash.com/photo-1618142990632-1afb1bd67e7c?q=80&w=800&auto=format&fit=crop" alt="Accessibility">
      <div class="vlc-body">
        <h3>♿ Accessibility</h3>
        <p>Wheelchair hire, mobility parking, accessible routes and more.</p>
      </div>
    </a>

    <a href="foodAndDrink.php" class="visit-link-card">
      <img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_site_images/Visitors/antlers_cafe_visitors.jpg" alt="Food and drink">
      <div class="vlc-body">
        <h3>🍽️ Food &amp; Drink</h3>
        <p>Dine at The Wild Restaurant or grab a sweet treat at the Ice Cream Shop.</p>
      </div>
    </a>

    <a href="animalFeeding.php" class="visit-link-card">
      <img src="https://images.unsplash.com/photo-1762367513797-65e32e050ef5?q=80&w=800&auto=format&fit=crop" alt="Animal feeding">
      <div class="vlc-body">
        <h3>🐐 Animal Feeding</h3>
        <p>Hand-feed goats, sheep, and rabbits on weekends and public holidays.</p>
      </div>
    </a>

  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
