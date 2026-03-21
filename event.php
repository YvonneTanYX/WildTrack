<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'things'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Events &amp; Talk Times — WildTrack Zoo</title>
  <style>
    .talk-section {
      margin: 32px 0;
    }
    .talk-label {
      display: inline-block;
      background: #2a5a2e;
      color: white;
      font-weight: bold;
      font-size: 14px;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 16px;
      letter-spacing: 0.5px;
    }
    .talk-grid {
      display: flex;
      flex-direction: column;
      gap: 10px;
      max-width: 560px;
    }
    .talk-row {
      display: flex;
      align-items: center;
      gap: 16px;
      background: #fff;
      border-radius: 10px;
      padding: 14px 20px;
      box-shadow: 0 1px 6px rgba(0,0,0,0.06);
      transition: transform 0.15s;
    }
    .talk-row:hover { transform: translateX(4px); }
    .talk-time {
      min-width: 80px;
      font-weight: bold;
      color: #2a5a2e;
      font-size: 15px;
    }
    .talk-name {
      font-size: 16px;
      color: #3a3a3a;
    }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI="
     class="page-img" alt="Zoo events">

<div class="content-section">

  <h1>Events &amp; Talk Times</h1>
  <p>Join our keepers for daily animal talks — included free with your Zoo entry!
     Check the schedule below and plan your visit around your favourite animals.</p>

  <!-- Morning talks -->
  <div class="talk-section">
    <div class="talk-label">🌅 Morning Sessions</div>
    <div class="talk-grid">
      <div class="talk-row"><span class="talk-time">10:00am</span><span class="talk-name">Penguin Talk</span></div>
      <div class="talk-row"><span class="talk-time">10:30am</span><span class="talk-name">Tiger Talk</span></div>
      <div class="talk-row"><span class="talk-time">11:00am</span><span class="talk-name">Snow Leopard Talk</span></div>
      <div class="talk-row"><span class="talk-time">11:15am</span><span class="talk-name">Vets in The Nest</span></div>
      <div class="talk-row"><span class="talk-time">11:45am</span><span class="talk-name">Cool Creatures at Hero HQ</span></div>
    </div>
  </div>

  <!-- Afternoon talks -->
  <div class="talk-section">
    <div class="talk-label">☀️ Afternoon Sessions</div>
    <div class="talk-grid">
      <div class="talk-row"><span class="talk-time">12:45pm</span><span class="talk-name">Chimpanzee Talk</span></div>
      <div class="talk-row"><span class="talk-time">1:15pm</span> <span class="talk-name">Giraffe Talk</span></div>
      <div class="talk-row"><span class="talk-time">2:00pm</span> <span class="talk-name">Sun Bear Talk</span></div>
      <div class="talk-row"><span class="talk-time">2:30pm</span> <span class="talk-name">Ring-tailed Lemur Talk</span></div>
      <div class="talk-row"><span class="talk-time">3:30pm</span> <span class="talk-name">Red Panda Talk</span></div>
    </div>
  </div>

  <p style="font-size:14px; color:#888; margin-top:8px;">
    ⚠️ Talk times are subject to change. Check with staff at the Zoo on the day of your visit.
  </p>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Things to Do', href: 'thingMain.php' },
    { label: 'Events &amp; Talk Times' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
