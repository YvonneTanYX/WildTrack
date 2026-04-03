<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lion — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <style>
    :root{--nav:#4e7a51;--green:#2d5a30;--green-md:#3e7a3e;--green-lt:#5a9e52;--teal:#76d7c4;--teal-dk:#4eb8a8;--bg:#f1f8e9;--white:#fff;--text:#1a2e1a;--muted:#5a7a5a;--border:#c8e0c8;--amber:#e8a020;--card-sh:0 4px 24px rgba(46,90,48,.10)}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
    .hero{position:relative;height:520px;overflow:hidden}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(135deg,#3a2200 0%,#7a4a00 40%,#4e7a51 100%)}
    .hero-pattern{position:absolute;inset:0;opacity:.06;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:44px 44px}
    .hero-deco{position:absolute;right:-80px;top:-80px;width:560px;height:560px;border-radius:50%;background:radial-gradient(circle,rgba(232,160,32,.18) 0%,transparent 70%)}
    .hero-emoji{position:absolute;right:6%;top:50%;transform:translateY(-50%);font-size:210px;opacity:.16;animation:float 6s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(-50%) rotate(-3deg)}50%{transform:translateY(calc(-50% - 16px)) rotate(3deg)}}
    .hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 8%;max-width:700px}
    .back-btn{display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.65);text-decoration:none;font-size:.82rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2rem;transition:color .2s}
    .back-btn:hover{color:#fff}
    .hero-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#f0c040;margin-bottom:.8rem;animation:fadeUp .5s ease both}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(3rem,6vw,5rem);font-weight:800;color:#fff;line-height:1.05;margin-bottom:1rem;animation:fadeUp .6s .1s ease both}
    .hero-latin{font-family:'Playfair Display',serif;font-style:italic;font-size:1.1rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;animation:fadeUp .6s .15s ease both}
    .hero-badges{display:flex;flex-wrap:wrap;gap:.6rem;animation:fadeUp .6s .2s ease both}
    .badge{padding:.35rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700}
    .badge-vu{background:rgba(232,160,32,.22);color:#f0c040;border:1px solid rgba(232,160,32,.45)}
    .badge-zone{background:rgba(118,215,196,.15);color:var(--teal);border:1px solid rgba(118,215,196,.3)}
    .badge-diet{background:rgba(217,79,61,.18);color:#f08070;border:1px solid rgba(217,79,61,.35)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
    .stats-bar{background:var(--green);padding:0 8%}
    .stats-inner{display:flex;overflow-x:auto;border-top:1px solid rgba(255,255,255,.1)}
    .stat-item{flex:1;min-width:130px;padding:22px 20px;text-align:center;border-right:1px solid rgba(255,255,255,.1)}
    .stat-item:last-child{border-right:none}
    .stat-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#fff}
    .stat-lbl{font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-top:4px}
    .page{max-width:1080px;margin:0 auto;padding:56px 24px 80px}
    .sec-title{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--green);margin-bottom:.3rem}
    .sec-sub{font-size:.85rem;color:var(--muted);margin-bottom:24px}
    .divider{border:none;border-top:2px solid var(--border);margin:48px 0}
    .overview-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:start}
    @media(max-width:700px){.overview-grid{grid-template-columns:1fr}}
    .overview-text p{font-size:.95rem;color:var(--muted);line-height:1.8;margin-bottom:16px}
    .overview-text p strong{color:var(--text)}
    .info-chips{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .chip{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:16px 18px;transition:transform .2s,box-shadow .2s}
    .chip:hover{transform:translateY(-3px);box-shadow:var(--card-sh)}
    .chip-lbl{font-size:.66rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
    .chip-val{font-size:.94rem;font-weight:600;color:var(--green)}
    .cons-banner{background:linear-gradient(135deg,#fff8e8,#fff3d0);border:2px solid #f0c040;border-radius:18px;padding:28px 32px;display:flex;align-items:flex-start;gap:20px;margin-bottom:36px}
    .cons-icon-big{font-size:3rem;flex-shrink:0}
    .cons-text h3{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:#7a4a00;margin-bottom:6px}
    .cons-text p{font-size:.88rem;color:#8a5a10;line-height:1.7}
    .iucn-pill{display:inline-block;background:#e8a020;color:#3a2000;padding:.3rem .9rem;border-radius:20px;font-size:.75rem;font-weight:700;margin-top:10px}
    .diet-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px}
    .diet-item{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center;transition:transform .2s}
    .diet-item:hover{transform:translateY(-3px);box-shadow:var(--card-sh)}
    .diet-emoji{font-size:2.2rem;margin-bottom:8px}
    .diet-name{font-size:.82rem;font-weight:600;color:var(--green)}
    .diet-amt{font-size:.72rem;color:var(--muted);margin-top:2px}
    .behaviour-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px}
    .bcard{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:24px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s}
    .bcard:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(46,90,48,.14)}
    .bcard-num{position:absolute;top:12px;right:16px;font-family:'Playfair Display',serif;font-size:3.5rem;font-weight:800;color:rgba(78,122,81,.07);line-height:1}
    .bcard-icon{font-size:1.8rem;margin-bottom:10px}
    .bcard-title{font-size:.96rem;font-weight:700;color:var(--green);margin-bottom:6px}
    .bcard-desc{font-size:.82rem;color:var(--muted);line-height:1.6}
    .facts-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
    .fact-card{background:var(--white);border-left:4px solid var(--amber);border-radius:0 14px 14px 0;padding:20px 22px;box-shadow:var(--card-sh)}
    .fact-card .fact-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:800;color:var(--amber);opacity:.4;line-height:1;margin-bottom:4px}
    .fact-card p{font-size:.88rem;color:var(--muted);line-height:1.65}
    .fact-card p strong{color:var(--text)}
    .threats-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .threat{background:var(--white);border:1px solid #fca5a5;border-radius:14px;padding:20px 22px}
    .threat-icon{font-size:1.6rem;margin-bottom:8px}
    .threat-name{font-size:.9rem;font-weight:700;color:#b91c1c;margin-bottom:4px}
    .threat-desc{font-size:.8rem;color:#7f1d1d;line-height:1.55}
    .efforts-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .effort{background:var(--white);border:1px solid #6ee7b7;border-radius:14px;padding:20px 22px}
    .effort-icon{font-size:1.6rem;margin-bottom:8px}
    .effort-name{font-size:.9rem;font-weight:700;color:#065f46;margin-bottom:4px}
    .effort-desc{font-size:.8rem;color:#047857;line-height:1.55}
    .pride-card{background:linear-gradient(135deg,#fff8e8,#fff3d0);border:1px solid #f0d080;border-radius:18px;padding:28px 32px}
    .pride-card h4{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:#7a4a00;margin-bottom:16px}
    .pride-list{list-style:none}
    .pride-list li{display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;font-size:.88rem;color:#8a5a10;line-height:1.6}
    .pride-list li::before{content:'🦁';flex-shrink:0;margin-top:1px}
    .zoo-card{background:linear-gradient(135deg,var(--nav) 0%,var(--green-md) 100%);border-radius:20px;padding:36px;color:#fff;display:flex;gap:32px;flex-wrap:wrap;align-items:center}
    .zoo-emoji{font-size:5rem;flex-shrink:0}
    .zoo-text h3{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;margin-bottom:8px}
    .zoo-text p{font-size:.9rem;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:16px}
    .zoo-pills{display:flex;flex-wrap:wrap;gap:8px}
    .zoo-pill{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:.3rem .9rem;font-size:.78rem;font-weight:600;color:#fff}
    .back-cta{text-align:center;margin-top:56px}
    .back-cta a{display:inline-flex;align-items:center;gap:.5rem;background:var(--nav);color:#fff;padding:.85rem 2rem;border-radius:40px;text-decoration:none;font-weight:600;font-size:.95rem;transition:all .2s;box-shadow:0 6px 20px rgba(46,90,48,.3)}
    .back-cta a:hover{background:var(--green);transform:translateY(-2px)}
  </style>
</head>
<body>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-deco"></div>
  <div class="hero-emoji">🦁</div>
  <div class="hero-content">
    <a href="meetTheAnimals.php" class="back-btn">← BACK</a>
    <div class="hero-eyebrow">WildTrack Malaysia · Africa Zone</div>
    <h1 class="hero-title">Lion</h1>
    <div class="hero-latin">Panthera leo</div>
    <div class="hero-badges">
      <span class="badge badge-vu">🟡 Vulnerable</span>
      <span class="badge badge-zone">🌍 Africa Zone</span>
      <span class="badge badge-diet">🥩 Carnivore</span>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item"><div class="stat-num">190 kg</div><div class="stat-lbl">Max Weight</div></div>
    <div class="stat-item"><div class="stat-num">14 yrs</div><div class="stat-lbl">Wild Lifespan</div></div>
    <div class="stat-item"><div class="stat-num">8 km</div><div class="stat-lbl">Roar Distance</div></div>
    <div class="stat-item"><div class="stat-num">80 km/h</div><div class="stat-lbl">Sprint Speed</div></div>
    <div class="stat-item"><div class="stat-num">40%</div><div class="stat-lbl">Population Decline (3 gen.)</div></div>
  </div>
</div>

<div class="page">

  <section>
    <h2 class="sec-title">Overview</h2>
    <p class="sec-sub">The only truly social wild cat</p>
    <div class="overview-grid">
      <div class="overview-text">
        <p>The lion (Panthera leo) is the <strong>second-largest wild cat in the world</strong>, after the tiger, and the only cat species that is <strong>truly social</strong>. Unlike all other big cats, lions live and hunt in groups called <strong>prides</strong> — a social structure that has made them extraordinarily effective apex predators of the African savannah.</p>
        <p>Male lions are instantly recognisable by their <strong>magnificent manes</strong> — a secondary sexual characteristic that signals dominance and health to both rivals and potential mates. A darker, fuller mane indicates a healthier, more dominant individual.</p>
        <p>Lions are Africa's top predators and <strong>ecosystem engineers</strong> — by controlling prey populations, they maintain the health and balance of entire savannah ecosystems. Their loss from an ecosystem triggers cascading effects on vegetation and other species.</p>
      </div>
      <div class="info-chips">
        <div class="chip"><div class="chip-lbl">Class</div><div class="chip-val">Mammalia</div></div>
        <div class="chip"><div class="chip-lbl">Family</div><div class="chip-val">Felidae</div></div>
        <div class="chip"><div class="chip-lbl">Habitat</div><div class="chip-val">Savannah & Grassland</div></div>
        <div class="chip"><div class="chip-lbl">Weight</div><div class="chip-val">120–190 kg</div></div>
        <div class="chip"><div class="chip-lbl">Length</div><div class="chip-val">1.7–2.5 m (body)</div></div>
        <div class="chip"><div class="chip-lbl">Lifespan</div><div class="chip-val">10–14 years (wild)</div></div>
        <div class="chip"><div class="chip-lbl">Gestation</div><div class="chip-val">110 days</div></div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Status</h2>
    <p class="sec-sub">IUCN Red List Assessment</p>
    <div class="cons-banner">
      <div class="cons-icon-big">🟡</div>
      <div class="cons-text">
        <h3>Vulnerable — Panthera leo</h3>
        <p>Lions are listed as <strong>Vulnerable</strong> on the IUCN Red List, with wild populations estimated at <strong>20,000–25,000 individuals</strong> in sub-Saharan Africa. This represents a decline of <strong>over 40%</strong> in just three generations. In West and Central Africa, lions are in an even more critical situation and may warrant Endangered status regionally.</p>
        <p>The primary drivers of decline are habitat loss as grassland is converted to farmland, human–wildlife conflict as lions prey on livestock and are killed in retaliation, and prey depletion through unsustainable bushmeat hunting.</p>
        <span class="iucn-pill">IUCN: Vulnerable (VU)</span>
      </div>
    </div>
    <div class="threats-list">
      <div class="threat"><div class="threat-icon">🌾</div><div class="threat-name">Habitat Loss</div><div class="threat-desc">Savannah conversion to agriculture drastically reduces territory available to lions and fragments the large ranges prides require.</div></div>
      <div class="threat"><div class="threat-icon">🐄</div><div class="threat-name">Human–Wildlife Conflict</div><div class="threat-desc">Lions that prey on livestock are killed by farmers. In some areas, retaliatory poisoning eliminates entire prides at once.</div></div>
      <div class="threat"><div class="threat-icon">🏹</div><div class="threat-name">Trophy Hunting</div><div class="threat-desc">While regulated in some countries, trophy hunting removes dominant males, destabilising prides and often leading to cub deaths.</div></div>
      <div class="threat"><div class="threat-icon">🦌</div><div class="threat-name">Prey Depletion</div><div class="threat-desc">Bushmeat poaching strips lion territories of prey species, forcing lions into conflict with humans as they target livestock.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Diet & Hunting</h2>
    <p class="sec-sub">Apex predators of the African savannah</p>
    <div class="diet-grid">
      <div class="diet-item"><div class="diet-emoji">🦓</div><div class="diet-name">Zebra</div><div class="diet-amt">Primary prey</div></div>
      <div class="diet-item"><div class="diet-emoji">🦬</div><div class="diet-name">Wildebeest</div><div class="diet-amt">Most hunted prey</div></div>
      <div class="diet-item"><div class="diet-emoji">🦌</div><div class="diet-name">Antelope & Gazelle</div><div class="diet-amt">Common targets</div></div>
      <div class="diet-item"><div class="diet-emoji">🐗</div><div class="diet-name">Warthog</div><div class="diet-amt">Opportunistic prey</div></div>
      <div class="diet-item"><div class="diet-emoji">🐘</div><div class="diet-name">Young Elephant</div><div class="diet-amt">Large pride hunts only</div></div>
      <div class="diet-item"><div class="diet-emoji">🦊</div><div class="diet-name">Stolen Kills</div><div class="diet-amt">Kleptoparsitism</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Social Structure: The Pride</h2>
    <p class="sec-sub">The only social wild cat in the world</p>
    <div class="pride-card" style="margin-bottom:20px">
      <h4>🦁 How a Pride Works</h4>
      <ul class="pride-list">
        <li>A typical lion pride consists of 2–4 adult males, several related females, and their cubs, although larger prides can include up to 30 members.</li>
        <li>Female lions are the primary hunters. They work together in coordinated group hunts, using strategies such as flanking and ambush to capture prey more effectively than a single hunter could.</li>
        <li>Male lions are responsible for defending the pride’s territory, which can cover areas of up to 260 km². They protect their domain from rival males through roaring, scent marking, and physical confrontation.</li>
        <li>When new males take over a pride, they often kill the existing cubs. This causes the females to return to oestrus, allowing the new males to produce their own offspring.</li>
        <li>Female lions within a pride form strong social bonds. They cooperate in raising cubs, nursing each other’s young, and maintaining group stability.</li>
      </ul>
    </div>
    <div class="behaviour-grid">
      <div class="bcard"><div class="bcard-num">01</div><div class="bcard-icon">🗣️</div><div class="bcard-title">The Roar</div><div class="bcard-desc">A lion's roar — the loudest of any big cat — is produced by a square-shaped vocal fold unique to lions and tigers. It can be heard 8 km away and serves to mark territory and locate pride members.</div></div>
      <div class="bcard"><div class="bcard-num">02</div><div class="bcard-icon">🌙</div><div class="bcard-title">Nocturnal Hunters</div><div class="bcard-desc">Lions hunt predominantly at night, exploiting their excellent low-light vision and the cover of darkness to stalk prey to within a short distance before launching explosive sprints.</div></div>
      <div class="bcard"><div class="bcard-num">03</div><div class="bcard-icon">😴</div><div class="bcard-title">Masters of Rest</div><div class="bcard-desc">Lions sleep and rest for 16–20 hours daily — a metabolic adaptation that conserves energy between hunts in the heat of the African savannah. Activity peaks at dawn and dusk.</div></div>
      <div class="bcard"><div class="bcard-num">04</div><div class="bcard-icon">👑</div><div class="bcard-title">Mane as Signal</div><div class="bcard-desc">A male's mane darkens and grows with age and testosterone. Research shows lionesses prefer darker-maned males, and rivals are more deterred by darker manes when assessing a fight.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Remarkable Facts</h2>
    <p class="sec-sub">The king of the savannah up close</p>
    <div class="facts-grid">
      <div class="fact-card"><div class="fact-num">01</div><p>A lion's roar can be heard <strong>up to 8 kilometres away</strong> — produced by a unique square-shaped vocal fold shared only with tigers among the big cats.</p></div>
      <div class="fact-card"><div class="fact-num">02</div><p>Despite being the "King of the Jungle," lions actually live in <strong>savannahs, not jungles</strong>. The only jungle-dwelling lions are the critically endangered Asiatic lion subspecies in India.</p></div>
      <div class="fact-card"><div class="fact-num">03</div><p>A lion's <strong>tongue is covered with sharp, backward-facing spines</strong> called papillae — ideal for scraping meat off bones and for grooming, which is also a key social bonding behaviour.</p></div>
      <div class="fact-card"><div class="fact-num">04</div><p>Female lions do <strong>up to 90% of the pride's hunting</strong> — but adult males eat first at a kill despite not participating in the hunt, a social dynamic determined by dominance.</p></div>
      <div class="fact-card"><div class="fact-num">05</div><p>Each lion has a <strong>unique whisker spot pattern</strong> — the pattern of dark spots at the base of each whisker line is as individual as a fingerprint, used by researchers to identify individuals.</p></div>
      <div class="fact-card"><div class="fact-num">06</div><p>Lion cubs are born with <strong>spotted coats</strong> that provide camouflage in the grass. These spots gradually fade as cubs grow, usually disappearing by around 3 months of age.</p></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Efforts</h2>
    <p class="sec-sub">Protecting Africa's apex predators</p>
    <div class="efforts-list">
      <div class="effort"><div class="effort-icon">🐄</div><div class="effort-name">Livestock Protection</div><div class="effort-desc">Predator-proof enclosures called "bomas" allow herders to safely lock cattle at night, dramatically reducing lion-livestock conflict.</div></div>
      <div class="effort"><div class="effort-icon">📡</div><div class="effort-name">GPS Tracking</div><div class="effort-desc">Collared lions are tracked in real time to understand movement patterns, predict conflict hotspots, and alert herders before encounters occur.</div></div>
      <div class="effort"><div class="effort-icon">🌿</div><div class="effort-name">Savannah Restoration</div><div class="effort-desc">Restoring grassland habitat and creating large wildlife corridors ensures lions have sufficient territory to sustain viable populations.</div></div>
      <div class="effort"><div class="effort-icon">🏘️</div><div class="effort-name">Community Coexistence</div><div class="effort-desc">Lion Guardians and similar programmes employ local Maasai warriors as conservation ambassadors, turning former lion killers into protectors.</div></div>
    </div>
  </section>

  <hr class="divider">

  <div class="back-cta"><a href="meetTheAnimals.php">Meet The Other Animals</a></div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
