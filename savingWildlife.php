<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'conservation'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Saving Wildlife — WildTrack Zoo</title>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI="
     class="page-img" alt="Wildlife conservation">

<div class="content-section">

  <h1>Saving Wildlife</h1>
  <p>Conservation is at the heart of everything we do at WildTrack Zoo.
     We are committed to protecting wildlife and empowering people to value and love nature.</p>

  <div class="two-col">
    <div class="col-info">
      <h2>Our Mission</h2>
      <p>WildTrack is a wildlife conservation charity with a bold vision: a world where nature
         is protected, valued and loved. Our mission is to save wildlife and empower people in
         Malaysia and around the world to protect and cherish the natural world.</p>

      <h2 style="margin-top:28px;">What We Do</h2>
      <p>From breeding programmes for endangered species to habitat restoration and community
         education, every visit you make to WildTrack Zoo directly funds vital conservation work
         both locally and globally.</p>

      <a href="conservationMain.php" class="btn-cta" style="margin-top:8px;">
        Learn More About Conservation →
      </a>
    </div>
    <div class="col-img">
      <img src="https://images.rzss.org.uk/media/RZSS/Conservation/Wild_Animal_Conservation_Institute_(ICAS)/icas%20anteater%202.jpg"
           alt="Conservation work" style="height:380px; object-fit:cover;">
    </div>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Conservation', href: 'conservationMain.php' },
    { label: 'Saving Wildlife' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
