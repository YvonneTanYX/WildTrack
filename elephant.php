<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Elephant — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <style>
    :root {
      --nav:#4e7a51;--green:#2d5a30;--green-md:#3e7a3e;--green-lt:#5a9e52;
      --teal:#76d7c4;--teal-dk:#4eb8a8;--bg:#f1f8e9;--white:#fff;
      --text:#1a2e1a;--muted:#5a7a5a;--border:#c8e0c8;
      --accent:#e8a020;--red:#d94f3d;
      --card-sh:0 4px 24px rgba(46,90,48,.10);
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}

    /* ── HERO ── */
    .hero{position:relative;height:520px;overflow:hidden;background:var(--green)}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(135deg,#1a3a1a 0%,#2d5a30 40%,#1a4a3a 100%)}
    .hero-pattern{position:absolute;inset:0;opacity:.07;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:40px 40px}
    .hero-deco{position:absolute;right:-60px;top:-60px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(118,215,196,.15) 0%,transparent 70%)}
    .hero-deco2{position:absolute;left:-80px;bottom:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(90,158,82,.12) 0%,transparent 70%)}
    .hero-emoji{position:absolute;right:8%;top:50%;transform:translateY(-50%);font-size:220px;opacity:.15;filter:drop-shadow(0 0 60px rgba(255,255,255,.1));animation:float 6s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(-50%) rotate(-3deg)}50%{transform:translateY(calc(-50% - 18px)) rotate(3deg)}}
    .hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 8%;max-width:700px}
    .back-btn{display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.7);text-decoration:none;font-size:.82rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2rem;transition:color .2s}
    .back-btn:hover{color:#fff}
    .hero-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--teal);margin-bottom:.8rem;animation:fadeUp .5s ease both}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(3rem,6vw,5rem);font-weight:800;color:#fff;line-height:1.05;margin-bottom:1rem;animation:fadeUp .6s .1s ease both}
    .hero-latin{font-family:'Playfair Display',serif;font-style:italic;font-size:1.1rem;color:rgba(255,255,255,.6);margin-bottom:1.5rem;animation:fadeUp .6s .15s ease both}
    .hero-badges{display:flex;flex-wrap:wrap;gap:.6rem;animation:fadeUp .6s .2s ease both}
    .badge{padding:.35rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700;letter-spacing:.04em}
    .badge-en{background:rgba(232,160,32,.2);color:#f0c040;border:1px solid rgba(232,160,32,.4)}
    .badge-zone{background:rgba(118,215,196,.15);color:var(--teal);border:1px solid rgba(118,215,196,.3)}
    .badge-diet{background:rgba(90,158,82,.2);color:#8fd48f;border:1px solid rgba(90,158,82,.35)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}

    /* ── QUICK STATS BAR ── */
    .stats-bar{background:var(--green);padding:0 8%}
    .stats-inner{display:flex;overflow-x:auto;gap:0;border-top:1px solid rgba(255,255,255,.1)}
    .stat-item{flex:1;min-width:140px;padding:22px 20px;text-align:center;border-right:1px solid rgba(255,255,255,.1)}
    .stat-item:last-child{border-right:none}
    .stat-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#fff;line-height:1}
    .stat-lbl{font-size:.7rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-top:4px}

    /* ── PAGE BODY ── */
    .page{max-width:1080px;margin:0 auto;padding:56px 24px 80px}

    /* SECTION TITLE */
    .sec-title{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--green);margin-bottom:.3rem}
    .sec-sub{font-size:.85rem;color:var(--muted);margin-bottom:24px}
    .divider{border:none;border-top:2px solid var(--border);margin:48px 0}

    /* ── OVERVIEW ── */
    .overview-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:start}
    @media(max-width:700px){.overview-grid{grid-template-columns:1fr}}
    .overview-text p{font-size:.95rem;color:var(--muted);line-height:1.8;margin-bottom:16px}
    .overview-text p strong{color:var(--text)}

    .info-chips{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .chip{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:16px 18px;transition:transform .2s,box-shadow .2s}
    .chip:hover{transform:translateY(-3px);box-shadow:var(--card-sh)}
    .chip-lbl{font-size:.66rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:5px}
    .chip-val{font-size:.94rem;font-weight:600;color:var(--green)}

    /* ── CONSERVATION BANNER ── */
    .cons-banner{background:linear-gradient(135deg,#fff8e8 0%,#fff3d0 100%);border:2px solid #f0c040;border-radius:18px;padding:28px 32px;display:flex;align-items:flex-start;gap:20px;margin-bottom:36px}
    .cons-icon-big{font-size:3rem;flex-shrink:0}
    .cons-text h3{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:#7a4a00;margin-bottom:6px}
    .cons-text p{font-size:.88rem;color:#8a5a10;line-height:1.7}
    .iucn-pill{display:inline-block;background:#f0c040;color:#4a2a00;padding:.3rem .9rem;border-radius:20px;font-size:.75rem;font-weight:700;margin-top:10px}

    /* ── HABITAT MAP CARD ── */
    .habitat-card{background:var(--white);border:1px solid var(--border);border-radius:18px;overflow:hidden;box-shadow:var(--card-sh)}
    .habitat-visual{height:200px;background:linear-gradient(135deg,#2d5a30 0%,#4e7a51 40%,#76d7c4 100%);position:relative;display:flex;align-items:center;justify-content:center}
    .habitat-visual .hab-emoji{font-size:5rem;filter:drop-shadow(0 4px 12px rgba(0,0,0,.3))}
    .habitat-body{padding:22px 24px}
    .habitat-body h4{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--green);margin-bottom:8px}
    .habitat-body p{font-size:.86rem;color:var(--muted);line-height:1.7}
    .habitat-tags{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
    .htag{background:var(--bg);border:1px solid var(--border);border-radius:20px;padding:.3rem .8rem;font-size:.75rem;font-weight:600;color:var(--green)}

    /* ── DIET SECTION ── */
    .diet-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px}
    .diet-item{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center;transition:transform .2s}
    .diet-item:hover{transform:translateY(-3px);box-shadow:var(--card-sh)}
    .diet-emoji{font-size:2.2rem;margin-bottom:8px}
    .diet-name{font-size:.82rem;font-weight:600;color:var(--green)}
    .diet-amt{font-size:.72rem;color:var(--muted);margin-top:2px}

    /* ── BEHAVIOUR CARDS ── */
    .behaviour-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px}
    .bcard{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:24px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s}
    .bcard:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(46,90,48,.14)}
    .bcard-num{position:absolute;top:12px;right:16px;font-family:'Playfair Display',serif;font-size:3.5rem;font-weight:800;color:rgba(78,122,81,.07);line-height:1}
    .bcard-icon{font-size:1.8rem;margin-bottom:10px}
    .bcard-title{font-size:.96rem;font-weight:700;color:var(--green);margin-bottom:6px}
    .bcard-desc{font-size:.82rem;color:var(--muted);line-height:1.6}

    /* ── SOCIAL STRUCTURE ── */
    .social-card{background:linear-gradient(135deg,#eaf5ea,#f4fbf4);border:1px solid var(--border);border-radius:18px;padding:30px 32px}
    .social-card h4{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--green);margin-bottom:16px}
    .social-list{list-style:none}
    .social-list li{display:flex;align-items:flex-start;gap:12px;margin-bottom:14px;font-size:.88rem;color:var(--muted);line-height:1.6}
    .social-list li::before{content:'🐘';flex-shrink:0;margin-top:1px}

    /* ── FUN FACTS ── */
    .facts-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
    .fact-card{background:var(--white);border-left:4px solid var(--teal-dk);border-radius:0 14px 14px 0;padding:20px 22px;box-shadow:var(--card-sh)}
    .fact-card .fact-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:800;color:var(--teal-dk);opacity:.3;line-height:1;margin-bottom:4px}
    .fact-card p{font-size:.88rem;color:var(--muted);line-height:1.65}
    .fact-card p strong{color:var(--text)}

    /* ── THREATS ── */
    .threats-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .threat{background:var(--white);border:1px solid #fca5a5;border-radius:14px;padding:20px 22px}
    .threat-icon{font-size:1.6rem;margin-bottom:8px}
    .threat-name{font-size:.9rem;font-weight:700;color:#b91c1c;margin-bottom:4px}
    .threat-desc{font-size:.8rem;color:#7f1d1d;line-height:1.55}

    /* ── CONSERVATION EFFORTS ── */
    .efforts-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .effort{background:var(--white);border:1px solid #6ee7b7;border-radius:14px;padding:20px 22px}
    .effort-icon{font-size:1.6rem;margin-bottom:8px}
    .effort-name{font-size:.9rem;font-weight:700;color:#065f46;margin-bottom:4px}
    .effort-desc{font-size:.8rem;color:#047857;line-height:1.55}

    /* ── ZOO INFO ── */
    .zoo-card{background:linear-gradient(135deg,var(--nav) 0%,var(--green-md) 100%);border-radius:20px;padding:36px;color:#fff;display:flex;gap:32px;flex-wrap:wrap;align-items:center}
    .zoo-card .zoo-emoji{font-size:5rem;flex-shrink:0}
    .zoo-text h3{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;margin-bottom:8px}
    .zoo-text p{font-size:.9rem;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:16px}
    .zoo-pills{display:flex;flex-wrap:wrap;gap:8px}
    .zoo-pill{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:.3rem .9rem;font-size:.78rem;font-weight:600;color:#fff}

    /* ── BACK CTA ── */
    .back-cta{text-align:center;margin-top:56px}
    .back-cta a{display:inline-flex;align-items:center;gap:.5rem;background:var(--nav);color:#fff;padding:.85rem 2rem;border-radius:40px;text-decoration:none;font-weight:600;font-size:.95rem;transition:all .2s;box-shadow:0 6px 20px rgba(46,90,48,.3)}
    .back-cta a:hover{background:var(--green);transform:translateY(-2px);box-shadow:0 10px 28px rgba(46,90,48,.4)}
  </style>
</head>
<body>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-deco"></div>
  <div class="hero-deco2"></div>
  <div class="hero-emoji">🐘</div>
  <div class="hero-content">
    <a href="meetTheAnimals.php" class="back-btn">← BACK</a>
    <div class="hero-eyebrow">WildTrack Malaysia · Asia Zone</div>
    <h1 class="hero-title">Elephant</h1>
    <div class="hero-latin">Elephas maximus</div>
    <div class="hero-badges">
      <span class="badge badge-en">🟠 Endangered</span>
      <span class="badge badge-zone">🌏 Asia Zone</span>
      <span class="badge badge-diet">🌿 Herbivore</span>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item"><div class="stat-num">5,000 kg</div><div class="stat-lbl">Max Weight</div></div>
    <div class="stat-item"><div class="stat-num">70 yrs</div><div class="stat-lbl">Lifespan</div></div>
    <div class="stat-item"><div class="stat-num">150 kg</div><div class="stat-lbl">Food / Day</div></div>
    <div class="stat-item"><div class="stat-num">40,000</div><div class="stat-lbl">Trunk Muscles</div></div>
    <div class="stat-item"><div class="stat-num">11 km</div><div class="stat-lbl">Communication Range</div></div>
  </div>
</div>

<!-- PAGE BODY -->
<div class="page">

  <!-- OVERVIEW -->
  <section>
    <h2 class="sec-title">Overview</h2>
    <p class="sec-sub">Everything you need to know about the Asian Elephant</p>
    <div class="overview-grid">
      <div class="overview-text">
        <p>The <strong>Asian elephant</strong> (Elephas maximus) is the largest land animal in Asia and one of the most intelligent creatures on Earth. Distinguished from its African cousin by its <strong>smaller ears, rounded back, and smoother skin</strong>, the Asian elephant has been deeply intertwined with human culture across the continent for thousands of years.</p>
        <p>These magnificent animals live in <strong>matriarchal herds</strong> led by the oldest and largest female. They are known for their remarkable cognitive abilities — they can recognise themselves in mirrors, use tools, mourn their dead, and communicate across vast distances using <strong>infrasonic rumbles</strong> inaudible to human ears.</p>
        <p>In Malaysia, the <strong>Borneo pygmy elephant</strong> — a subspecies — represents one of Asia's most important elephant populations, found in the rainforests of Sabah.</p>
      </div>
      <div class="info-chips">
        <div class="chip"><div class="chip-lbl">Class</div><div class="chip-val">Mammalia</div></div>
        <div class="chip"><div class="chip-lbl">Order</div><div class="chip-val">Proboscidea</div></div>
        <div class="chip"><div class="chip-lbl">Habitat</div><div class="chip-val">Grassland & Forest</div></div>
        <div class="chip"><div class="chip-lbl">Weight</div><div class="chip-val">2,700–5,000 kg</div></div>
        <div class="chip"><div class="chip-lbl">Height</div><div class="chip-val">2.5–3 m (shoulder)</div></div>
        <div class="chip"><div class="chip-lbl">Lifespan</div><div class="chip-val">Up to 70 years</div></div>
        <div class="chip"><div class="chip-lbl">Gestation</div><div class="chip-val">22 months</div></div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <!-- CONSERVATION -->
  <section>
    <h2 class="sec-title">Conservation Status</h2>
    <p class="sec-sub">IUCN Red List Assessment</p>
    <div class="cons-banner">
      <div class="cons-icon-big">🟠</div>
      <div class="cons-text">
        <h3>Endangered — Elephas maximus</h3>
        <p>The Asian elephant is listed as <strong>Endangered</strong> on the IUCN Red List, with wild populations estimated at fewer than <strong>50,000 individuals</strong>. The population has declined by more than 50% over the last three generations. Habitat loss, fragmentation, human–wildlife conflict, and illegal poaching for ivory and skin are the primary drivers of decline.</p>
        <p>In Malaysia, elephant populations face particular pressure from rapid deforestation and palm oil plantation expansion, which destroys migration corridors and creates deadly conflict when elephants enter agricultural areas.</p>
        <span class="iucn-pill">IUCN: Endangered (EN)</span>
      </div>
    </div>

    <div class="threats-list">
      <div class="threat"><div class="threat-icon">🌴</div><div class="threat-name">Habitat Loss</div><div class="threat-desc">Deforestation for palm oil, logging, and agriculture eliminates critical elephant habitat and breaks up migration corridors.</div></div>
      <div class="threat"><div class="threat-icon">⚔️</div><div class="threat-name">Human–Wildlife Conflict</div><div class="threat-desc">Elephants entering farmland are frequently killed by farmers protecting crops. Retaliatory killings are a major mortality cause.</div></div>
      <div class="threat"><div class="threat-icon">🏹</div><div class="threat-name">Poaching</div><div class="threat-desc">Ivory poaching affects tusked males, skewing sex ratios and reducing genetic diversity across wild populations.</div></div>
      <div class="threat"><div class="threat-icon">🛣️</div><div class="threat-name">Fragmentation</div><div class="threat-desc">Roads and infrastructure divide herds and reduce the genetic connectivity essential for healthy elephant populations.</div></div>
    </div>
  </section>

  <hr class="divider">

  <!-- HABITAT -->
  <section>
    <h2 class="sec-title">Habitat & Range</h2>
    <p class="sec-sub">Where Asian elephants are found in the wild</p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
      <div class="habitat-card">
        <div class="habitat-visual"><span class="hab-emoji">🌿</span></div>
        <div class="habitat-body">
          <h4>Tropical & Subtropical Forests</h4>
          <p>Asian elephants thrive in diverse habitat types — from dense tropical rainforests and grasslands to scrub forests and even the edges of agricultural land. They require large territories to meet their nutritional needs.</p>
          <div class="habitat-tags">
            <span class="htag">Rainforest</span><span class="htag">Grassland</span><span class="htag">Scrubland</span><span class="htag">River Valleys</span>
          </div>
        </div>
      </div>
      <div class="habitat-card">
        <div class="habitat-visual" style="background:linear-gradient(135deg,#1a4a5a,#2d6a7a,#4eb8a8)"><span class="hab-emoji">🗺️</span></div>
        <div class="habitat-body">
          <h4>Geographic Range</h4>
          <p>Found across South and Southeast Asia — from India and Sri Lanka east to Borneo and Sumatra. In Malaysia, they are primarily found in Peninsular Malaysia and the Bornean state of Sabah.</p>
          <div class="habitat-tags">
            <span class="htag">India</span><span class="htag">Sri Lanka</span><span class="htag">Thailand</span><span class="htag">Malaysia</span><span class="htag">Borneo</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <!-- DIET -->
  <section>
    <h2 class="sec-title">Diet & Feeding</h2>
    <p class="sec-sub">Elephants are mega-herbivores consuming up to 150 kg of food daily</p>
    <div class="diet-grid">
      <div class="diet-item"><div class="diet-emoji">🌾</div><div class="diet-name">Grasses</div><div class="diet-amt">Primary food source</div></div>
      <div class="diet-item"><div class="diet-emoji">🍃</div><div class="diet-name">Leaves & Bark</div><div class="diet-amt">Browse from trees</div></div>
      <div class="diet-item"><div class="diet-emoji">🌱</div><div class="diet-name">Roots</div><div class="diet-amt">Dug with tusks</div></div>
      <div class="diet-item"><div class="diet-emoji">🍌</div><div class="diet-name">Wild Fruits</div><div class="diet-amt">When available</div></div>
      <div class="diet-item"><div class="diet-emoji">🌿</div><div class="diet-name">Bamboo</div><div class="diet-amt">Common in Asia</div></div>
      <div class="diet-item"><div class="diet-emoji">💧</div><div class="diet-name">Water</div><div class="diet-amt">~200 litres/day</div></div>
    </div>
  </section>

  <hr class="divider">

  <!-- BEHAVIOUR -->
  <section>
    <h2 class="sec-title">Behaviour & Intelligence</h2>
    <p class="sec-sub">Among the most cognitively complex animals on Earth</p>
    <div class="behaviour-grid">
      <div class="bcard"><div class="bcard-num">01</div><div class="bcard-icon">🧠</div><div class="bcard-title">Mirror Self-Recognition</div><div class="bcard-desc">Elephants can recognise themselves in mirrors — a cognitive ability shared with only great apes, dolphins, and magpies. This indicates a high degree of self-awareness.</div></div>
      <div class="bcard"><div class="bcard-num">02</div><div class="bcard-icon">😢</div><div class="bcard-title">Mourning the Dead</div><div class="bcard-desc">Elephants return to the bones of deceased family members and caress them gently with their trunks. They may stand silently for hours at the site of a dead elephant.</div></div>
      <div class="bcard"><div class="bcard-num">03</div><div class="bcard-icon">🔊</div><div class="bcard-title">Infrasonic Communication</div><div class="bcard-desc">They communicate using low-frequency rumbles below human hearing that can travel up to 11 km through the ground, sensed by other elephants through their feet.</div></div>
      <div class="bcard"><div class="bcard-num">04</div><div class="bcard-icon">🛠️</div><div class="bcard-title">Tool Use</div><div class="bcard-desc">Wild elephants have been observed using sticks to scratch themselves, branches as fly swatters, and even modifying objects to use more effectively — true tool manufacture.</div></div>
    </div>

    <div class="social-card" style="margin-top:20px">
      <h4>🐘 Social Structure</h4>
      <ul class="social-list">
        <li>Elephant herds are led by the oldest and most experienced female, known as the matriarch. Her knowledge and memory play a crucial role in guiding the group to water and food sources, especially during times of drought.</li>
        <li>Female offspring remain with the herd throughout their lives, forming strong, lifelong bonds, while males leave the group during adolescence (around 10–15 years old) to live alone or in small bachelor groups.</li>
        <li>The relationships among females are exceptionally close. They work together to protect calves, assist during births, and care for injured or sick members.</li>
        <li>Even between different herds that share the same territory, elephants maintain complex social structures and long-term relationships that can span generations.</li>
      </ul>
    </div>
  </section>

  <hr class="divider">

  <!-- FUN FACTS -->
  <section>
    <h2 class="sec-title">Remarkable Facts</h2>
    <p class="sec-sub">Things that make elephants truly extraordinary</p>
    <div class="facts-grid">
      <div class="fact-card"><div class="fact-num">01</div><p>An elephant's trunk contains <strong>over 40,000 muscles</strong> and no bones — making it capable of lifting 350 kg and delicately picking up a single grain of rice.</p></div>
      <div class="fact-card"><div class="fact-num">02</div><p>Elephants have the <strong>largest brain of any land animal</strong> — weighing up to 5 kg — with a highly folded cortex similar in structure to the human brain.</p></div>
      <div class="fact-card"><div class="fact-num">03</div><p>They are one of the few non-primate animals that can <strong>pass the mirror self-recognition test</strong>, demonstrating a theory of mind and self-awareness.</p></div>
      <div class="fact-card"><div class="fact-num">04</div><p>Elephants use their massive <strong>ears as radiators</strong> — flapping them to cool blood flowing through a network of large veins just beneath the skin's surface.</p></div>
      <div class="fact-card"><div class="fact-num">05</div><p>A newborn elephant calf weighs around <strong>100 kg at birth</strong> and can stand within minutes. It is cared for by the entire herd — not just its mother.</p></div>
      <div class="fact-card"><div class="fact-num">06</div><p>Elephants are <strong>keystone species</strong> — their feeding and movement shapes entire ecosystems, creating water holes and seed-dispersing hundreds of plant species.</p></div>
    </div>
  </section>

  <hr class="divider">

  <!-- CONSERVATION EFFORTS -->
  <section>
    <h2 class="sec-title">Conservation Efforts</h2>
    <p class="sec-sub">How people around the world are working to protect elephants</p>
    <div class="efforts-list">
      <div class="effort"><div class="effort-icon">🛡️</div><div class="effort-name">Wildlife Corridors</div><div class="effort-desc">Governments and NGOs are establishing green corridors connecting fragmented forests to allow safe elephant movement.</div></div>
      <div class="effort"><div class="effort-icon">📡</div><div class="effort-name">GPS Monitoring</div><div class="effort-desc">Collared elephants are tracked in real time to study movements, predict conflicts, and alert farmers before elephants enter fields.</div></div>
      <div class="effort"><div class="effort-icon">🏘️</div><div class="effort-name">Community Programs</div><div class="effort-desc">Compensation schemes and beehive fences help local communities coexist with elephants without resorting to lethal conflict.</div></div>
      <div class="effort"><div class="effort-icon">🧬</div><div class="effort-name">Genetic Research</div><div class="effort-desc">DNA studies help conservationists understand population connectivity and prioritise which corridors to protect first.</div></div>
    </div>
  </section>

  <hr class="divider">

  <!-- BACK CTA -->
  <div class="back-cta">
  <div class="back-cta"><a href="meetTheAnimals.php">Meet The Other Animals</a></div>
  </div>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
