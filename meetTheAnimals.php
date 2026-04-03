<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'animal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Meet the Animals — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css"/>
  <link rel="stylesheet" href="mainPage.css"/>
  <link rel="stylesheet" href="hero.css"/>
  <style>

    /* ── FILTER BAR ── */
    .filter-bar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 0 5%;
      display: flex; align-items: center; gap: 8px;
      overflow-x: auto;
    }
    .filter-label {
      font-size: .75rem; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--muted);
      white-space: nowrap; margin-right: 8px;
    }
    .filter-btn {
      border: none; background: none; cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      font-size: .85rem; font-weight: 600; color: var(--muted);
      padding: 14px 16px; border-bottom: 3px solid transparent;
      white-space: nowrap; transition: all .2s;
    }
    .filter-btn:hover { color: var(--nav); }
    .filter-btn.active { color: var(--nav); border-bottom-color: var(--nav); }

    /* ── PAGE BODY ── */
    .page-body { max-width: 1200px; margin: 0 auto; padding: 60px 24px 80px; }

    .section-intro { margin-bottom: 48px; }
    .section-intro h2 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 700;
      color: var(--green); margin-bottom: .5rem;
    }
    .section-intro p { color: var(--muted); font-size: .95rem; line-height: 1.7; max-width: 640px; }

    /* ── ANIMAL GRID ── */
    .animals-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 28px;
    }

    /* Featured first card (full-width) */
    .animal-card.featured {
      grid-column: 1 / -1;
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .animal-card {
      background: var(--white);
      border-radius: 20px;
      overflow: hidden;
      border: 1px solid var(--border);
      box-shadow: var(--card-sh);
      transition: transform .28s ease, box-shadow .28s ease;
      display: flex; flex-direction: column;
    }
    .animal-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 48px rgba(46,90,48,.16);
    }

    .card-img-wrap {
      position: relative; overflow: hidden;
      height: 220px; flex-shrink: 0;
    }
    .animal-card.featured .card-img-wrap { height: 100%; min-height: 380px; }

    .card-img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform .5s ease;
    }
    .animal-card:hover .card-img { transform: scale(1.05); }

    .card-zone-badge {
      position: absolute; top: 14px; left: 14px;
      background: rgba(46,90,48,.85);
      color: #fff; font-size: .7rem; font-weight: 700;
      letter-spacing: .1em; text-transform: uppercase;
      padding: .3rem .8rem; border-radius: 20px;
      backdrop-filter: blur(4px);
    }

    .card-status-badge {
      position: absolute; bottom: 14px; left: 14px;
      font-size: .72rem; font-weight: 700;
      padding: .3rem .75rem; border-radius: 20px;
      letter-spacing: .04em;
    }
    .badge-cr { background: #fde8e8; color: #b91c1c; }
    .badge-en { background: #fef3c7; color: #92400e; }
    .badge-vu { background: #fff3e0; color: #b45309; }
    .badge-lc { background: #d1fae5; color: #065f46; }

    .card-body { padding: 26px 28px 28px; display: flex; flex-direction: column; flex: 1; }
    .card-emoji { font-size: 2rem; margin-bottom: 10px; }
    .card-name {
      font-family: 'Playfair Display', serif;
      font-size: 1.55rem; font-weight: 700;
      color: var(--green); margin-bottom: 3px;
    }
    .card-species { font-size: .82rem; color: var(--muted); font-style: italic; margin-bottom: 14px; }
    .card-desc { font-size: .88rem; color: var(--text); line-height: 1.7; margin-bottom: 20px; flex: 1; }

    .card-chips {
      display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 22px;
    }
    .chip {
      background: #f0f7f0; border: 1px solid var(--border);
      border-radius: 20px; padding: .25rem .75rem;
      font-size: .75rem; font-weight: 600; color: var(--green);
      display: flex; align-items: center; gap: .3rem;
    }

    .card-link {
      display: inline-flex; align-items: center; gap: .5rem;
      background: var(--nav); color: #fff;
      padding: .7rem 1.5rem; border-radius: 30px;
      text-decoration: none; font-size: .88rem; font-weight: 600;
      transition: all .2s; box-shadow: 0 3px 12px rgba(46,90,48,.22);
      align-self: flex-start;
    }
    .card-link:hover { background: var(--green); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(46,90,48,.3); }
    .card-link .arrow { transition: transform .2s; }
    .card-link:hover .arrow { transform: translateX(4px); }

    /* ── QUICK-FIND STRIP ── */
    .quick-strip {
      margin-top: 64px;
      background: linear-gradient(135deg, var(--green) 0%, var(--green-md) 100%);
      border-radius: 20px; padding: 40px 48px;
      display: flex; align-items: center; justify-content: space-between;
      gap: 24px; flex-wrap: wrap;
    }
    .quick-strip h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem; font-weight: 700; color: #fff;
      margin-bottom: 6px;
    }
    .quick-strip p { font-size: .9rem; color: rgba(255,255,255,.78); line-height: 1.6; }
    .quick-strip-btn {
      display: inline-flex; align-items: center; gap: .5rem;
      background: var(--teal); color: var(--green);
      padding: .8rem 2rem; border-radius: 30px;
      text-decoration: none; font-weight: 700; font-size: .92rem;
      white-space: nowrap; transition: all .2s;
      box-shadow: 0 4px 16px rgba(0,0,0,.15);
    }
    .quick-strip-btn:hover { background: #fff; transform: translateY(-1px); }

    /* hidden by filter */
    .animal-card.hidden { display: none; }

    @media (max-width: 768px) {
      .animal-card.featured { grid-template-columns: 1fr; }
      .animal-card.featured .card-img-wrap { min-height: 240px; }
      .quick-strip { padding: 28px 24px; }
    }
  </style>
</head>
<body>
<?php include 'nav.php'; $currentPage = 'animal'; ?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1534567153574-2b12153a87f0?q=80&w=1600&auto=format&fit=crop" alt="Animals at WildTrack"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Meet the<br/><em>Animals</em></h1>
    <p class="hero-sub">Discover the incredible residents of WildTrack Malaysia — learn their stories, habitats, and the conservation efforts protecting them.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>

<!-- FILTER BAR -->
<div class="filter-bar">
  <span class="filter-label">Filter:</span>
  <button class="filter-btn active" data-filter="all">All Animals</button>
  <button class="filter-btn" data-filter="Africa Zone">Africa Zone</button>
  <button class="filter-btn" data-filter="Asia Zone">Asia Zone</button>
  <button class="filter-btn" data-filter="Polar Zone">Polar Zone</button>
  <button class="filter-btn" data-filter="Endangered">Endangered</button>
</div>

<!-- MAIN CONTENT -->
<div class="page-body">
  <div class="section-intro">
    <h2>Our Residents</h2>
    <p>From the misty bamboo groves of Asia to the sun-drenched savannahs of Africa, our animals come from all corners of the world. Click on any animal to discover their full profile.</p>
  </div>

  <div class="animals-grid" id="animalsGrid">

    <!-- GIANT PANDA — Featured -->
    <div class="animal-card featured" data-zone="Asia Zone" data-status="vu">
      <div class="card-img-wrap">
        <img class="card-img" src="https://images.unsplash.com/photo-1564349683136-77e08dba1ef7?q=80&w=1200&auto=format&fit=crop" alt="Giant Panda"/>
        <span class="card-zone-badge">Asia Zone</span>
        <span class="card-status-badge badge-vu">Vulnerable</span>
      </div>
      <div class="card-body">
        <div class="card-emoji">🐼</div>
        <h2 class="card-name">Giant Panda</h2>
        <p class="card-species">Ailuropoda melanoleuca</p>
        <p class="card-desc">One of the world's most beloved and iconic animals, the giant panda spends up to 16 hours a day eating bamboo. Once critically endangered, dedicated conservation programs have helped bring this species back from the brink. Visit them in our lush Bamboo Grove.</p>
        <div class="card-chips">
          <span class="chip">🌿 Bamboo Grove</span>
          <span class="chip">🌏 Asia Zone</span>
          <span class="chip">🎋 Herbivore</span>
          <span class="chip">⚖️ 70–125 kg</span>
        </div>
        <a href="panda.php" class="card-link">View Full Profile <span class="arrow">→</span></a>
      </div>
    </div>

    <!-- AFRICAN ELEPHANT -->
    <div class="animal-card" data-zone="Africa Zone" data-status="en">
      <div class="card-img-wrap">
        <img class="card-img" src="https://images.unsplash.com/photo-1557050543-4d5f4e07ef46?q=80&w=800&auto=format&fit=crop" alt="Elephant"/>
        <span class="card-zone-badge">Africa Zone</span>
        <span class="card-status-badge badge-en">Endangered</span>
      </div>
      <div class="card-body">
        <div class="card-emoji">🐘</div>
        <h2 class="card-name">Elephant</h2>
        <p class="card-species">Elephas maximus</p>
        <p class="card-desc">The largest land animal on Earth, elephants are highly intelligent and deeply social. They mourn their dead, use tools, and can recognise themselves in a mirror — one of only a few species able to do so.</p>
        <div class="card-chips">
          <span class="chip">🌍 Africa Zone</span>
          <span class="chip">🌿 Herbivore</span>
          <span class="chip">⚖️ Up to 5,000 kg</span>
        </div>
        <a href="elephant.php" class="card-link">View Full Profile <span class="arrow">→</span></a>
      </div>
    </div>

    <!-- LION -->
    <div class="animal-card" data-zone="Africa Zone" data-status="vu">
      <div class="card-img-wrap">
        <img class="card-img" src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?q=80&w=800&auto=format&fit=crop" alt="Lion"/>
        <span class="card-zone-badge">Africa Zone</span>
        <span class="card-status-badge badge-vu">Vulnerable</span>
      </div>
      <div class="card-body">
        <div class="card-emoji">🦁</div>
        <h2 class="card-name">Lion</h2>
        <p class="card-species">Panthera leo</p>
        <p class="card-desc">The only truly social wild cat, lions live in prides of up to 30 individuals. A male's roar can be heard up to 8 km away. Lion populations have declined by over 40% in just three generations.</p>
        <div class="card-chips">
          <span class="chip">🌍 Africa Zone</span>
          <span class="chip">🥩 Carnivore</span>
          <span class="chip">⚖️ 120–190 kg</span>
        </div>
        <a href="lion.php" class="card-link">View Full Profile <span class="arrow">→</span></a>
      </div>
    </div>

    <!-- GORILLA -->
    <div class="animal-card" data-zone="Africa Zone" data-status="cr">
      <div class="card-img-wrap">
        <img class="card-img" src="images/gorilla.avif" alt="Gorilla"/>
        <span class="card-zone-badge">Africa Zone</span>
        <span class="card-status-badge badge-cr">Critically Endangered</span>
      </div>
      <div class="card-body">
        <div class="card-emoji">🦍</div>
        <h2 class="card-name">Gorilla</h2>
        <p class="card-species">Gorilla gorilla</p>
        <p class="card-desc">Sharing 98.3% of their DNA with humans, gorillas are our closest relatives after chimpanzees. They are capable of learning sign language and demonstrate remarkable problem-solving abilities.</p>
        <div class="card-chips">
          <span class="chip">🌍 Africa Zone</span>
          <span class="chip">🌿 Herbivore</span>
          <span class="chip">⚖️ 100–200 kg</span>
        </div>
        <a href="gorilla.php" class="card-link">View Full Profile <span class="arrow">→</span></a>
      </div>
    </div>

    <!-- PENGUIN -->
    <div class="animal-card" data-zone="Polar Zone" data-status="vu">
      <div class="card-img-wrap">
        <img class="card-img" src="images/penguin.avif" alt="Penguin"/>
        <span class="card-zone-badge">Polar Zone</span>
        <span class="card-status-badge badge-vu">Vulnerable</span>
      </div>
      <div class="card-body">
        <div class="card-emoji">🐧</div>
        <h2 class="card-name">Penguin</h2>
        <p class="card-species">Spheniscidae (family)</p>
        <p class="card-desc">Flightless birds that are exceptional swimmers, penguins can reach speeds of 25 km/h underwater. They use their wings as flippers and are threatened by climate change and overfishing.</p>
        <div class="card-chips">
          <span class="chip">❄️ Polar Zone</span>
          <span class="chip">🐟 Carnivore</span>
          <span class="chip">⚖️ 1–40 kg</span>
        </div>
        <a href="penguin.php" class="card-link">View Full Profile <span class="arrow">→</span></a>
      </div>
    </div>

  </div><!-- /grid -->

  <!-- QUICK-FIND STRIP -->
  <div class="quick-strip">
    <div>
      <h3>Not sure which animal you spotted?</h3>
      <p>Use our AI-powered Animal Recognition tool — upload a photo and identify any animal in seconds.</p>
    </div>
    <a href="recognition.php" class="quick-strip-btn">🔍 Try Animal Recognition</a>
  </div>

</div><!-- /page-body -->
<?php include 'footer.php'; ?>

<script>
// ── Filter ──
const filterBtns = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.animal-card');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const f = btn.dataset.filter;

    cards.forEach(card => {
      if (f === 'all') {
        card.classList.remove('hidden');
      } else if (f === 'Endangered') {
        // show cr + en
        const s = card.dataset.status;
        card.classList.toggle('hidden', s !== 'cr' && s !== 'en');
      } else {
        card.classList.toggle('hidden', card.dataset.zone !== f);
      }
    });
  });
});

// ── Nav dropdowns ──
document.querySelectorAll('.dropdown').forEach(dd => {
  const btn  = dd.querySelector('.dropbutton') || dd.querySelector('a');
  const menu = dd.querySelector('.dropdown-menu');
  if (!btn || !menu) return;
  btn.addEventListener('click', e => {
    e.preventDefault();
    const open = menu.classList.contains('active');
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
    if (!open) menu.classList.add('active');
  });
});
document.addEventListener('click', e => {
  if (!e.target.closest('.dropdown'))
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
});

// ── Breadcrumb ──
document.addEventListener('DOMContentLoaded', () => {
  const trail = document.getElementById('breadcrumb-trail');
  if (trail) trail.innerHTML =
    '<a href="mainPage.php">Home</a>' +
    '<span class="sep">›</span>' +
    '<a href="animalMain.php">Animal</a>' +
    '<span class="sep">›</span>' +
    '<span>Meet the Animals</span>';
});
</script>
</body>
</html>
