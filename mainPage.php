<?php
require_once __DIR__ . '/check_session.php';
require_once __DIR__ . '/config/db.php';
$currentPage = '';

// ── Fetch live slider images from DB ────────────────────────────────
$sliderImages = getDB()
  ->query("SELECT image_url, alt_text FROM slider_images WHERE show_in_slider = 1 ORDER BY sort_order ASC, uploaded_at DESC")
  ->fetchAll(PDO::FETCH_ASSOC);

// Fallback to default images if DB is empty
if (empty($sliderImages)) {
  $sliderImages = [
    ['image_url' => 'https://images.unsplash.com/photo-1695272016860-c3d5eaa6c660?q=80&w=1740&auto=format&fit=crop', 'alt_text' => 'Zoo wildlife'],
    ['image_url' => 'https://images.unsplash.com/photo-1684262406822-e8c8629d3831?q=80&w=1548&auto=format&fit=crop', 'alt_text' => 'Zoo animals'],
    ['image_url' => 'https://images.unsplash.com/photo-1678067574187-3d81952c7199?q=80&w=2070&auto=format&fit=crop', 'alt_text' => 'Zoo experience'],
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>WildTrack Zoo — Home</title>
</head>
<body>

<?php include 'nav.php'; ?>

<!-- Hero Slider — dynamically populated from DB -->
<div class="slider">
  <?php foreach ($sliderImages as $index => $img): ?>
  <div class="slide<?= $index === 0 ? ' active' : '' ?>">
    <img src="<?= htmlspecialchars($img['image_url']) ?>"
         alt="<?= htmlspecialchars($img['alt_text']) ?>">
  </div>
  <?php endforeach; ?>
  <div class="slider-overlay"></div>
  <div class="slider-caption">
    <h1>Welcome to<br>WildTrack Zoo</h1>
    <p>Where wildlife meets wonder</p>
  </div>
</div>

<!-- Slider nav dots — generated to match image count -->
<div class="slider-nav">
  <?php foreach ($sliderImages as $index => $img): ?>
  <button class="slider-dot<?= $index === 0 ? ' active' : '' ?>"
          aria-label="Slide <?= $index + 1 ?>"></button>
  <?php endforeach; ?>
</div>

<!-- Quick links section -->
<div class="content-section">
  <h2 style="text-align:center; margin-bottom:32px; color:#2a5a2e;">Plan Your Visit</h2>
  <div class="two-col" style="justify-content:center; gap:20px; flex-wrap:wrap;">

    <a href="openingHourRate.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">🕙</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Opening Hours<br>&amp; Rates</div>
      </div>
    </a>

    <a href="zooMap.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">🗺️</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Zoo Map</div>
      </div>
    </a>

    <a href="buyTicket.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">🎟️</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Buy a Ticket</div>
      </div>
    </a>

    <a href="event.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">📅</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Events &amp;<br>Talk Times</div>
      </div>
    </a>

     <a href="recognition.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">📸</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Animal Recognition</div>
      </div>
    </a>

    <a href="animalFeeding.php" style="text-decoration:none;">
      <div style="background:#fff; border-radius:14px; padding:28px 24px; text-align:center; width:200px; box-shadow:0 2px 12px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
        <div style="font-size:36px; margin-bottom:10px;">🦒</div>
        <div style="font-weight:bold; color:#2a5a2e; font-size:15px;">Animal Feeding</div>
      </div>
    </a>

  </div>
</div>

<?php include 'footer.php'; ?>

<script>window.breadcrumb = [];</script>
<script src="FinalProject.js"></script>
</body>
</html>
