<?php
require_once __DIR__ . '/check_session.php';
$currentPage = ''; ?>
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

<!-- Hero Slider -->
<div class="slider">
  <div class="slide active">
    <img src="https://images.unsplash.com/photo-1695272016860-c3d5eaa6c660?q=80&w=1740&auto=format&fit=crop" alt="Zoo wildlife">
  </div>
  <div class="slide">
    <img src="https://images.unsplash.com/photo-1684262406822-e8c8629d3831?q=80&w=1548&auto=format&fit=crop" alt="Zoo animals">
  </div>
  <div class="slide">
    <img src="https://images.unsplash.com/photo-1678067574187-3d81952c7199?q=80&w=2070&auto=format&fit=crop" alt="Zoo experience">
  </div>
  <div class="slider-overlay"></div>
  <div class="slider-caption">
    <h1>Welcome to<br>WildTrack Zoo</h1>
    <p>Where wildlife meets wonder</p>
  </div>
</div>

<div class="slider-nav">
  <button class="slider-dot active" aria-label="Slide 1"></button>
  <button class="slider-dot" aria-label="Slide 2"></button>
  <button class="slider-dot" aria-label="Slide 3"></button>
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
