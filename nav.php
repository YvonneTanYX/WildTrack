<?php
/**
 * nav.php — Shared navigation for all WildTrack visitor pages.
 * Requires check_session.php to have already run (session must be active).
 * Usage: include 'nav.php';  (after require_once 'check_session.php')
 */
if (!isset($currentPage)) $currentPage = '';
$loggedInUser = $_SESSION['user'] ?? null;
$displayName  = $loggedInUser ? htmlspecialchars($loggedInUser['username']) : 'Guest';
?>
<nav>
  <div class="container-header">
    <ul class="nav-list">

      <li class="logo">
        <a href="mainPage.php">
          <img src="Logo_Green.png" width="90" height="90" alt="WildTrack Zoo">
        </a>
      </li>

      <!-- VISIT -->
      <li class="dropdown <?php echo $currentPage==='visit'?'active-section':''; ?>" aria-expanded="false">
        <a href="visitMain.php" class="dropbutton">Visit ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-visit">
              <h2 class="image-text-visit">Visit</h2>
              <a href="visitMain.php"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTA5f_frFRYSM6PSi3YDwW9T7IAu16vWNPl5qmYno5Xg28xHhpn2xLpEsNVh1_MXvfuNQzz_OVufFSTEjj5L5_VikHXcT1vJL80Ip2RGAM&s=10" alt="Visit"></a>
            </div>
          </div>
          <div class="right-column">
            <a href="openingHourRate.php">Opening Hours &amp; Rates</a>
            <a href="zooMap.php">Zoo Map</a>
            <a href="accessibility.php">Accessibility at the Zoo</a>
            <a href="buyTicket.php">Buy a Ticket</a>
            <a href="foodAndDrink.php">Food and Drink</a>
            <a href="animalFeeding.php">Animal Feeding</a>
          </div>
        </div>
      </li>

      <!-- THINGS TO DO -->
      <li class="dropdown <?php echo $currentPage==='things'?'active-section':''; ?>" aria-expanded="false">
        <a href="thingMain.php" class="dropbutton">Things to Do ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-thingToDo">
              <h2 class="image-text-thingToDo">Things to Do</h2>
              <a href="thingMain.php"><img src="https://plus.unsplash.com/premium_photo-1673296129756-e45408e25250?q=80&w=1113&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Things to Do"></a>
            </div>
          </div>
          <div class="right-column">
            <a href="event.php">Events &amp; Talk Times</a>
          </div>
        </div>
      </li>

      <!-- ANIMAL -->
      <li class="dropdown <?php echo $currentPage==='animal'?'active-section':''; ?>" aria-expanded="false">
        <a href="animalMain.php" class="dropbutton">Animal ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-animal">
              <h2 class="image-text-animal">Animal</h2>
              <a href="animalMain.php"><img src="https://images.unsplash.com/photo-1605092676920-8ac5ae40c7c8?q=80&w=1065&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Animals"></a>
            </div>
          </div>
          <div class="right-column"></div>
        </div>
      </li>

      <!-- CONSERVATION -->
      <li class="dropdown <?php echo $currentPage==='conservation'?'active-section':''; ?>" aria-expanded="false">
        <a href="conservationMain.php" class="dropbutton">Conservation ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-conservation">
              <h2 class="image-text-conservation">Conservation</h2>
              <a href="conservationMain.php"><img src="https://images.unsplash.com/photo-1757947100964-5bd95456867e?q=80&w=2107&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Conservation"></a>
            </div>
          </div>
          <div class="right-column">
            <a href="savingWildlife.php">Saving Wildlife</a>
            <a href="greenZooGreenYou.php">Green Zoo, Green You</a>
            <a href="safeCatSafeWildlife.php">Safe Cat, Safe Wildlife</a>
          </div>
        </div>
      </li>

      <!-- VENUE -->
      <li class="dropdown <?php echo $currentPage==='venue'?'active-section':''; ?>" aria-expanded="false">
        <a href="venueMain.php" class="dropbutton">Venue ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-venue">
              <h2 class="image-text-venue">Venue</h2>
              <a href="venueMain.php"><img src="https://images.unsplash.com/photo-1550853024-fae8cd4be47f?q=80&w=1488&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Venue"></a>
            </div>
          </div>
          <div class="right-column"></div>
        </div>
      </li>

      <!-- GET IN TOUCH -->
      <li class="dropdown <?php echo $currentPage==='contact'?'active-section':''; ?>" aria-expanded="false">
        <a href="getInTouch.php" class="dropbutton">Get in Touch ▾</a>
        <div class="dropdown-menu">
          <div class="left-column">
            <div class="image-container-getInTouch">
              <h2 class="image-text-getInTouch">Get in Touch</h2>
              <a href="getInTouch.php"><img src="https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?q=80&w=987&auto=format&fit=crop&ixlib=rb-4.1.0" alt="Get in Touch"></a>
            </div>
          </div>
          <div class="right-column"></div>
        </div>
      </li>

      <!-- USER MENU (right side) -->
      <li class="dropdown" aria-expanded="false" style="margin-left:auto;">
        <a href="#" class="dropbutton" style="display:flex;align-items:center;gap:6px;">
          <span style="font-size:18px;">👤</span> <?php echo $displayName; ?> ▾
        </a>
        <div class="dropdown-menu" style="min-width:200px;left:auto;right:0;transform:none;">
          <div class="right-column" style="margin-left:0;">
            <a href="Ticketing.php">🎟 My Tickets</a>
            <a href="#" onclick="doLogout();return false;">🚪 Sign Out</a>
          </div>
        </div>
      </li>

    </ul>
  </div>
</nav>

<!-- Breadcrumb bar -->
<nav class="breadcrumb" aria-label="Breadcrumb">
  <span id="breadcrumb-trail"></span>
</nav>

<script>
function doLogout() {
  fetch('api/auth.php?action=logout', { credentials: 'include' })
    .finally(function() { window.location.href = 'login.html'; });
}
</script>
