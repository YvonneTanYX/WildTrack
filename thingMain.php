<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'things'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Things to Do — WildTrack Zoo</title>
  <style>
    .activity-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 32px 0;
    }
    .activity-card {
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      width: 320px;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s;
      display: block;
    }
    .activity-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.13);
    }
    .activity-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      display: block;
    }
    .activity-card .ac-body { padding: 18px 20px; }
    .activity-card h3 { font-size: 20px; color: #2a5a2e; margin-bottom: 6px; }
    .activity-card p  { font-size: 14px; color: #666; line-height: 1.6; margin: 0; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://plus.unsplash.com/premium_photo-1673296129756-e45408e25250?q=80&w=1113&auto=format&fit=crop"
     class="page-img" alt="Things to do at WildTrack Zoo">

<div class="content-section">

  <h1>Things to Do</h1>
  <p>There's always something happening at WildTrack Zoo! From daily keeper talks and
     animal encounters to special events — plan your day for maximum adventure.</p>

  <div class="activity-grid">

    <a href="event.php" class="activity-card">
      <img src="https://media.istockphoto.com/id/477317714/photo/african-lions-paw.jpg?s=612x612&w=0&k=20&c=q_qbmrjRBABc6lAyBO3rEe84ICgRBIE3Nq9dyRBg_zI=" alt="Talk times">
      <div class="ac-body">
        <h3>📅 Events &amp; Talk Times</h3>
        <p>Join our keepers for daily animal talks included free with Zoo entry.
           Morning and afternoon sessions available every day.</p>
      </div>
    </a>

    <a href="animalFeeding.php" class="activity-card">
      <img src="https://images.unsplash.com/photo-1762367513797-65e32e050ef5?q=80&w=800&auto=format&fit=crop" alt="Animal feeding">
      <div class="ac-body">
        <h3>🐐 Animal Feeding Sessions</h3>
        <p>Hand-feed goats, sheep, and rabbits on weekends and public holidays.
           A favourite experience for kids of all ages!</p>
      </div>
    </a>

    <a href="zooMap.php" class="activity-card">
      <img src="https://images.unsplash.com/photo-1605092676920-8ac5ae40c7c8?q=80&w=800&auto=format&fit=crop" alt="Explore the zoo">
      <div class="ac-body">
        <h3>🗺️ Explore the Zoo</h3>
        <p>Use our Zoo map to discover all the exhibits and find the shortest routes between
           your favourite animal zones.</p>
      </div>
    </a>

    <a href="learning.php" class="activity-card">
      <img src="https://sunwaylagoon.com/wp-content/uploads/2025/04/Asset-29.png" alt="Learning">
      <div class="ac-body">
        <h3>🎓 Learning</h3>
        <p>Join our Explorer Ranger program — outdoor educational classes curated by zoologists
           for Junior Rangers (5–12) and Master Rangers (13–17).</p>
      </div>
    </a>

    <a href="birthdayParties.php" class="activity-card">
      <img src="https://images.unsplash.com/photo-1530103862676-de8c9debad1d?q=80&w=800&auto=format&fit=crop" alt="Birthday Parties">
      <div class="ac-body">
        <h3>🎉 Birthday Parties</h3>
        <p>Celebrate your child's big day at the Zoo! Wild Party Packages available on weekends
           from RM 30 per child, including venue hire, catering, and Zoo entry.</p>
      </div>
    </a>

  </div>

</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [
    { label: 'Things to Do' }
  ];
</script>
<script src="FinalProject.js"></script>
</body>
</html>
