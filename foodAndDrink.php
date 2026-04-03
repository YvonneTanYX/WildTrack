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
  <title>Food &amp; Drink — WildTrack Zoo</title>
</head>
<body>

<?php include 'nav.php'; ?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_site_images/Visitors/antlers_cafe_visitors.jpg" alt="Food and drink at the zoo">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title"><em>Food</em><br/>and Drink</h1>
    <p class="hero-sub">Fuel up for your adventure! WildTrack Zoo offers a variety of dining options
     for the whole family — from hearty meals to sweet treats.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <!-- Restaurant -->
  <div class="two-col">
    <div class="col-img">
      <img src="https://images.rzss.org.uk/media/Highland_Wildlife_Park/HWP_site_images/Visitors/antlers_cafe_visitors.jpg"
           alt="The Wild Restaurant" style="height:320px; object-fit:cover;">
    </div>
    <div class="col-info">
      <h2>The Wild Restaurant</h2>
      <p>Located at the heart of WildTrack Zoo, the Wild Restaurant is your go-to family
         dining spot. We serve a wide range of delicious fast food and finger food at
         affordable prices — something for everyone in the family.</p>
      <a href="https://www.zoonegara.my/images/menu-website.jpg" target="_blank" class="btn-cta">
        View Our Menu →
      </a>
    </div>
  </div>

  <!-- Ice cream -->
  <div class="two-col" style="flex-direction:row-reverse;">
    <div class="col-img">
      <img src="https://www.happypalmstays.com/wp-content/uploads/2019/03/2024-04-04.jpg"
           alt="Ice Cream Shop" style="height:320px; object-fit:cover;">
    </div>
    <div class="col-info">
      <h2>Ice Cream Shop</h2>
      <p>Situated beside the scenic lake, the Ice Cream Shop offers a sweet break with a
         view of free-roaming birds in their natural habitat. Enjoy a wide selection of
         ice creams, especially the nostalgic local favourite — <em>Aiskrim Malaysia</em>.
         A must-try!</p>
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Food &amp; Drink' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
