<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'conservation';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Conservation – WildTrack Zoo</title>

<style>

.card-link {
  display: inline-block;
  color: #fff !important;
  text-decoration: none !important;
  font-weight: 700;
  font-size: 15px;
  border: 2px solid rgba(255,255,255,0.8);
  padding: 10px 24px;
  border-radius: 4px;
  transition: background 0.2s;
}
.card-link:hover {
  background: rgba(255,255,255,0.18);
}

.card-savingWildlife {
  padding: 80px 60px 80px 15%;
  background-color: #3f8bdb;
  color: #fff;
}
.card-greenZoo {
  padding: 80px 60px 80px 15%;
  background-color: #59bf4b;
  color: #fff;
}
.card-safeCat {
  padding: 80px 60px 80px 15%;
  background-color: #ff604f;
  color: #fff;
}

@media (max-width: 768px) {
  .hero-text { font-size: 40px; }
  .card-savingWildlife,
  .card-greenZoo,
  .card-safeCat { padding: 50px 28px; }
}
</style>
</head>
<body>

<?php include 'nav.php'; ?>

  <img src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI="
         class="page-img" alt="Conservation – WildTrack Zoo">

<!-- ── Content cards ── -->
<div class="content-section">

  <h1>Conservation</h1>
  <div class="card-savingWildlife">
    <h2>SAVING ANIMALS IN THE WILD</h2>
    <p>See what WildTrack Zoo is doing to help animals in the wild. Every effort counts in preserving our planet's biodiversity.</p>
    <a href="savingWildlife.php" class="card-link">Read more &rsaquo;</a>
  </div>

  <div class="card-greenZoo">
    <h2>GREEN ZOO, GREEN YOU</h2>
    <p>Find out about why our environment is important to us and how we are caring for the animals and our planet.</p>
    <a href="greenZooGreenYou.php" class="card-link">View it here &rsaquo;</a>
  </div>

  <div class="card-safeCat">
    <h2>SAFE CAT, SAFE WILDLIFE</h2>
    <p>Support our animals by adopting a cat symbolically. Your contribution helps provide food, medical care, and a safe habitat.</p>
    <a href="safeCatSafeWildlife.php" class="card-link">Let's start &rsaquo;</a>
  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Conservation' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
