<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'conservation'; // ✅ Fixed typo (was 'conserveation')
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="mainPage.css">
  <title>Conservation – WildTrack Zoo</title>

<style>
/* ── Hero Image ── */
.hero-container {
  position: relative;
  width: 100%;
  max-height: 520px;
  overflow: hidden;
}
.hero-container img {
  width: 100%;
  height: 520px;
  object-fit: cover;
  display: block;
  filter: brightness(0.65);
}
.hero-text {
  position: absolute;
  bottom: 38%;
  left: 50%;
  transform: translate(-50%, 50%);
  color: #fff;
  font-size: clamp(48px, 7vw, 100px);
  font-weight: bold;
  letter-spacing: 4px;
  text-shadow: 0 4px 24px rgba(0,0,0,0.55);
  margin: 0;
  white-space: nowrap;
}

/* ── Buy Tickets bar (like Image 2) ── */
.ticket-bar {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
  background: #1a1a1a;
  padding: 10px 40px;
}
.btn-buy-ticket {
  background: #e87722;
  color: #fff;
  font-size: 15px;
  font-weight: 700;
  padding: 10px 24px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.2s, transform 0.15s;
  letter-spacing: 0.5px;
}
.btn-buy-ticket:hover {
  background: #cf6210;
  transform: translateY(-1px);
  color: #fff;
  text-decoration: none;
}
.btn-donate {
  background: #f0b429;
  color: #1a1a1a;
  font-size: 15px;
  font-weight: 700;
  padding: 10px 24px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.2s, transform 0.15s;
}
.btn-donate:hover {
  background: #d49c18;
  transform: translateY(-1px);
  color: #1a1a1a;
  text-decoration: none;
}

/* ── Conservation cards ── */
.conservation-container {
  width: 100%;
}
.conservation-container h2 {
  font-size: 32px;
  margin: 0 0 12px;
  letter-spacing: 1px;
}
.conservation-container p {
  font-size: 16px;
  margin: 0 0 20px;
  line-height: 1.6;
  max-width: 600px;
}
.conservation-container a.card-link {
  display: inline-block;
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  font-size: 15px;
  border: 2px solid #fff;
  padding: 10px 22px;
  border-radius: 4px;
  transition: background 0.2s, color 0.2s;
}
.conservation-container a.card-link:hover {
  background: rgba(255,255,255,0.2);
}

.card-savingWildlife {
  padding: 80px 60px 80px 280px;
  background-color: #0056b3;
  color: #fff;
}
.card-greenZoo {
  padding: 80px 60px 80px 280px;
  background-color: #3a7d44;
  color: #fff;
}
.card-safeCat {
  padding: 80px 60px 80px 280px;
  background-color: #c0392b;
  color: #fff;
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .hero-text { font-size: 40px; left: 50%; }
  .card-savingWildlife,
  .card-greenZoo,
  .card-safeCat { padding: 50px 28px; }
  .ticket-bar { padding: 10px 20px; }
}
</style>
</head>
<body>

<?php include 'nav.php'; /* ✅ Use shared nav — removes old inline nav */ ?>

<!-- ── Buy Ticket bar (matches Image 2 style) ── -->
<div class="ticket-bar">
  <a href="Ticketing.php" class="btn-buy-ticket">🎟 Buy Tickets</a>
  <a href="donate.php"    class="btn-donate">♥ Donate</a>
</div>

<!-- ── Hero ── -->
<div class="hero-container">
  <h2 class="hero-text">CONSERVATION</h2>
  <img
    src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI="
    alt="Conservation – African lion paw">
</div>

<!-- ── Content cards ── -->
<div class="conservation-container">

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

<script src="mainPage.js"></script>
</body>
</html>
