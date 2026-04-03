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
  <title>Accessibility — WildTrack Zoo</title>
  <style>
    .access-block {
      background: #fff;
      border-left: 5px solid #76d7c4;
      border-radius: 0 10px 10px 0;
      padding: 20px 24px;
      margin: 20px 0;
      max-width: 780px;
    }
    .access-block h2 {
      font-size: 22px;
      color: #2a5a2e;
      margin-bottom: 8px;
    }
    .access-block p {
      font-size: 16px;
      line-height: 1.7;
      color: #4a4a4a;
      margin: 0;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1618142990632-1afb1bd67e7c?q=80&w=3050&auto=format&fit=crop" alt="Zoo accessibility">/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title"><em>Accessibility</em><br/>at the Zoo</h1>
    <p class="hero-sub">Inclusive Exploration: Facilities for All Visitors</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<div class="content-section">

  <h1>Accessibilities in WildTrack</h1>
  <p>We work hard to make sure WildTrack Zoo is a place where everyone feels welcome.
     We have staff and systems in place to ensure a trip to the Zoo is accessible for as many people as possible.</p>
  <p>Our Zoo was built on a steep hill that can be challenging for some to navigate.
     Please reach out to our staff if you need any guidance on accessible routes.</p>

  <div class="access-block">
    <h2>Caregivers</h2>
    <p>Caregivers of disabled persons are given complimentary entry to the Zoo upon
       presentation of suitable supporting documentation or identification.</p>
  </div>

  <div class="access-block">
    <h2>Mobility Parking</h2>
    <p>We have four designated mobility car parks in the main car park right next to the Zoo,
       and one right outside the café at the entrance.</p>
  </div>

  <div class="access-block">
    <h2>Wheelchairs</h2>
    <p>Wheelchairs are available free of charge — just ask our friendly Visitor Advisors when
       you arrive. Accessible toilets are available throughout the Zoo. Our Visitor Advisors
       can show you which pathways are more accessible.</p>
  </div>

  <div class="access-block">
    <h2>Assistance Dogs</h2>
    <p>Assistance dogs are welcome at the Zoo but must have proof of vaccination. Please be
       aware that there are some areas they won't be able to visit. Contact us for more
       information before your visit.</p>
  </div>

  <div class="access-block">
    <h2>Accessibility Map</h2>
    <p>Our accessibility map highlights the steep gradients around the Zoo so you can see
       which pathways are more accessible. You can print this out and bring it along, or
       we'll have a copy for you when you arrive.</p>
  </div>

  <div class="access-block">
    <h2>Access to Zoo Experiences</h2>
    <p>Not all of our special Zoo experiences are fully accessible. For instance, some Close
       Encounters aren't accessible by wheelchair. Please contact us in advance to check
       whether a specific experience is accessible for you.</p>
  </div>

  <a href="getInTouch.php" class="btn-cta" style="margin-top:16px;">Contact Us for Help →</a>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Visit', href: 'visitMain.php' },
    { label: 'Accessibility' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
