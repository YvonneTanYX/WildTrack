<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Penguin — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <style>
    :root{--nav:#4e7a51;--green:#2d5a30;--green-md:#3e7a3e;--green-lt:#5a9e52;--teal:#76d7c4;--teal-dk:#4eb8a8;--bg:#f1f8e9;--white:#fff;--text:#1a2e1a;--muted:#5a7a5a;--border:#c8e0c8;--card-sh:0 4px 24px rgba(46,90,48,.10)}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
    .hero{position:relative;height:520px;overflow:hidden}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(135deg,#0a1a2e 0%,#1a3a5a 45%,#2a5a7a 100%)}
    .hero-pattern{position:absolute;inset:0;opacity:.05;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:40px 40px}
    .hero-deco{position:absolute;right:-60px;top:-60px;width:540px;height:540px;border-radius:50%;background:radial-gradient(circle,rgba(200,230,255,.12) 0%,transparent 70%)}
    .hero-emoji{position:absolute;right:7%;top:50%;transform:translateY(-50%);font-size:210px;opacity:.16;animation:waddle 4s ease-in-out infinite}
    @keyframes waddle{0%,100%{transform:translateY(-50%) rotate(-4deg)}50%{transform:translateY(calc(-50% - 14px)) rotate(4deg)}}
    .hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 8%;max-width:700px}
    .back-btn{display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.65);text-decoration:none;font-size:.82rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2rem;transition:color .2s}
    .back-btn:hover{color:#fff}
    .hero-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--teal);margin-bottom:.8rem;animation:fadeUp .5s ease both}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(3rem,6vw,5rem);font-weight:800;color:#fff;line-height:1.05;margin-bottom:1rem;animation:fadeUp .6s .1s ease both}
    .hero-latin{font-family:'Playfair Display',serif;font-style:italic;font-size:1.1rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;animation:fadeUp .6s .15s ease both}
    .hero-badges{display:flex;flex-wrap:wrap;gap:.6rem;animation:fadeUp .6s .2s ease both}
    .badge{padding:.35rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700}
    .badge-vu{background:rgba(232,160,32,.22);color:#f0c040;border:1px solid rgba(232,160,32,.45)}
    .badge-zone{background:rgba(200,230,255,.15);color:#b0d8f8;border:1px solid rgba(200,230,255,.3)}
    .badge-diet{background:rgba(217,79,61,.18);color:#f08070;border:1px solid rgba(217,79,61,.35)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
    .stats-bar{background:#1a3a5a;padding:0 8%}
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
    .species-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:20px}
    .species-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:18px 20px;transition:transform .2s,box-shadow .2s}
    .species-card:hover{transform:translateY(-3px);box-shadow:var(--card-sh)}
    .species-name{font-size:.92rem;font-weight:700;color:var(--green);margin-bottom:4px}
    .species-detail{font-size:.78rem;color:var(--muted);line-height:1.5}
    .species-status{display:inline-block;margin-top:6px;padding:.2rem .65rem;border-radius:20px;font-size:.72rem;font-weight:700}
    .s-en{background:#fef3c7;color:#92400e}
    .s-vu{background:#fff3e0;color:#b45309}
    .s-lc{background:#d1fae5;color:#065f46}
    .s-cr{background:#fde8e8;color:#b91c1c}
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
    .fact-card{background:var(--white);border-left:4px solid var(--teal-dk);border-radius:0 14px 14px 0;padding:20px 22px;box-shadow:var(--card-sh)}
    .fact-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:800;color:var(--teal-dk);opacity:.3;line-height:1;margin-bottom:4px}
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
    .zoo-card{background:linear-gradient(135deg,#1a3a5a 0%,#2a5a7a 100%);border-radius:20px;padding:36px;color:#fff;display:flex;gap:32px;flex-wrap:wrap;align-items:center}
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
  <div class="hero-emoji">🐧</div>
  <div class="hero-content">
    <a href="meetTheAnimals.php" class="back-btn">← BACK</a>
    <div class="hero-eyebrow">WildTrack Malaysia · Polar Zone</div>
    <h1 class="hero-title">Penguin</h1>
    <div class="hero-latin">Spheniscidae (family)</div>
    <div class="hero-badges">
      <span class="badge badge-vu">🟡 Varies by Species</span>
      <span class="badge badge-zone">❄️ Polar Zone</span>
      <span class="badge badge-diet">🐟 Carnivore</span>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item"><div class="stat-num">18 species</div><div class="stat-lbl">Total Species</div></div>
    <div class="stat-item"><div class="stat-num">40 kg</div><div class="stat-lbl">Max Weight</div></div>
    <div class="stat-item"><div class="stat-num">25 km/h</div><div class="stat-lbl">Swim Speed</div></div>
    <div class="stat-item"><div class="stat-num">20 yrs</div><div class="stat-lbl">Avg Lifespan</div></div>
    <div class="stat-item"><div class="stat-num">500 m</div><div class="stat-lbl">Max Dive Depth</div></div>
  </div>
</div>

<div class="page">

  <section>
    <h2 class="sec-title">Overview</h2>
    <p class="sec-sub">The world's most beloved flightless seabirds</p>
    <div class="overview-grid">
      <div class="overview-text">
        <p>Penguins are a family of <strong>flightless seabirds</strong> (Spheniscidae) found almost exclusively in the Southern Hemisphere — from Antarctica to the Galápagos Islands, and the coasts of South America, South Africa, New Zealand and Australia. Despite being birds, penguins have evolved over millions of years into <strong>supreme underwater swimmers</strong>, using their wings as powerful flippers.</p>
        <p>There are <strong>18 recognised species</strong> of penguin, ranging from the tiny Little Blue Penguin (just 33 cm tall) to the Emperor Penguin, which stands over a metre tall and weighs up to 40 kg. All species share the iconic <strong>black-and-white "tuxedo" colouring</strong> — dark on the back and white on the front — which provides camouflage from predators both above and below in the water.</p>
        <p>Penguins are <strong>highly social birds</strong> that breed in colonies called rookeries, sometimes containing hundreds of thousands of individuals. They are monogamous within a breeding season and cooperate to raise their chicks in some of the harshest environments on Earth.</p>
      </div>
      <div class="info-chips">
        <div class="chip"><div class="chip-lbl">Class</div><div class="chip-val">Aves (Birds)</div></div>
        <div class="chip"><div class="chip-lbl">Order</div><div class="chip-val">Sphenisciformes</div></div>
        <div class="chip"><div class="chip-lbl">Habitat</div><div class="chip-val">Coastal & Marine</div></div>
        <div class="chip"><div class="chip-lbl">Weight</div><div class="chip-val">1–40 kg</div></div>
        <div class="chip"><div class="chip-lbl">Height</div><div class="chip-val">33 cm – 1.1 m</div></div>
        <div class="chip"><div class="chip-lbl">Lifespan</div><div class="chip-val">15–20 years (wild)</div></div>
        <div class="chip"><div class="chip-lbl">Incubation</div><div class="chip-val">30–65 days</div></div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Notable Species</h2>
    <p class="sec-sub">18 species — each adapted to a unique environment</p>
    <div class="species-grid">
      <div class="species-card"><div class="species-name">Emperor Penguin</div><div class="species-detail">Largest penguin. Breeds in Antarctica during winter, withstanding −60°C.</div><span class="species-status s-lc">Least Concern</span></div>
      <div class="species-card"><div class="species-name">African Penguin</div><div class="species-detail">Only penguin on the African continent. Found along South Africa and Namibia.</div><span class="species-status s-en">Endangered</span></div>
      <div class="species-card"><div class="species-name">Little Blue Penguin</div><div class="species-detail">World's smallest penguin at 33 cm. Found in New Zealand and southern Australia.</div><span class="species-status s-lc">Least Concern</span></div>
      <div class="species-card"><div class="species-name">Galápagos Penguin</div><div class="species-detail">Only penguin found north of the equator. Critically threatened by El Niño events.</div><span class="species-status s-cr">Critically Endangered</span></div>
      <div class="species-card"><div class="species-name">Chinstrap Penguin</div><div class="species-detail">Named for the thin black line under the chin. One of the most numerous species.</div><span class="species-status s-lc">Least Concern</span></div>
      <div class="species-card"><div class="species-name">Macaroni Penguin</div><div class="species-detail">Recognisable by striking yellow-orange crest feathers. World's most numerous penguin.</div><span class="species-status s-vu">Vulnerable</span></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Status</h2>
    <p class="sec-sub">Conservation status varies widely across the 18 species</p>
    <div class="cons-banner">
      <div class="cons-icon-big">🟡</div>
      <div class="cons-text">
        <h3>Varies by Species — Spheniscidae</h3>
        <p>Of the 18 penguin species, <strong>10 are currently listed as Vulnerable, Endangered, or Critically Endangered</strong> by the IUCN. The African penguin has declined by over 70% in 30 years. Even species listed as Least Concern face serious long-term threats from climate change altering their ocean habitat.</p>
        <p>The primary threats are interlinked: warming oceans reduce sea ice and alter prey distribution; overfishing directly competes with penguins for anchovies, sardines, and krill; oil spills and plastic pollution kill thousands annually.</p>
        <span class="iucn-pill">10 of 18 species are Threatened</span>
      </div>
    </div>
    <div class="threats-list">
      <div class="threat"><div class="threat-icon">🌡️</div><div class="threat-name">Climate Change</div><div class="threat-desc">Warming oceans and loss of sea ice reduce krill populations and alter prey distribution, forcing penguins to swim farther to find food.</div></div>
      <div class="threat"><div class="threat-icon">🎣</div><div class="threat-name">Overfishing</div><div class="threat-desc">Industrial fishing depletes anchovies, sardines, and krill — the fish penguins rely on — particularly near breeding colonies.</div></div>
      <div class="threat"><div class="threat-icon">🛢️</div><div class="threat-name">Oil Spills</div><div class="threat-desc">Oil destroys feather waterproofing, causing hypothermia. Penguins also ingest oil while preening. Thousands are killed by spills each year.</div></div>
      <div class="threat"><div class="threat-icon">🦊</div><div class="threat-name">Introduced Predators</div><div class="threat-desc">Cats, rats, and dogs introduced by humans raid nests and kill chicks on island breeding colonies, devastating reproductive success.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Diet & Feeding</h2>
    <p class="sec-sub">Expert underwater hunters — all prey swallowed whole</p>
    <div class="diet-grid">
      <div class="diet-item"><div class="diet-emoji">🐟</div><div class="diet-name">Fish</div><div class="diet-amt">Anchovies, sardines</div></div>
      <div class="diet-item"><div class="diet-emoji">🦐</div><div class="diet-name">Krill</div><div class="diet-amt">Critical for small species</div></div>
      <div class="diet-item"><div class="diet-emoji">🦑</div><div class="diet-name">Squid</div><div class="diet-amt">Especially Emperor penguins</div></div>
      <div class="diet-item"><div class="diet-emoji">🌊</div><div class="diet-name">Salt Water</div><div class="diet-amt">Desalinated by supraorbital gland</div></div>
      <div class="diet-item"><div class="diet-emoji">🧊</div><div class="diet-name">Snow & Ice</div><div class="diet-amt">Fresh water source</div></div>
      <div class="diet-item"><div class="diet-emoji">🐙</div><div class="diet-name">Octopus</div><div class="diet-amt">Occasional prey</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Behaviour & Adaptations</h2>
    <p class="sec-sub">Perfectly engineered for life at sea and on ice</p>
    <div class="behaviour-grid">
      <div class="bcard"><div class="bcard-num">01</div><div class="bcard-icon">🤿</div><div class="bcard-title">Masters of Diving</div><div class="bcard-desc">Emperor penguins can dive to 500 metres and hold their breath for over 20 minutes. Their bones are denser than flying birds' — reducing buoyancy to help them dive deeper.</div></div>
      <div class="bcard"><div class="bcard-num">02</div><div class="bcard-icon">🖤🤍</div><div class="bcard-title">Countershading Camouflage</div><div class="bcard-desc">The black back blends with dark ocean depths when viewed from above; the white belly matches the bright surface light when viewed from below — hiding penguins from predators on both sides.</div></div>
      <div class="bcard"><div class="bcard-num">03</div><div class="bcard-icon">🤝</div><div class="bcard-title">Huddling for Warmth</div><div class="bcard-desc">Emperor penguins form tightly-packed huddles of thousands to survive Antarctic winter storms, rotating continuously so every penguin takes a turn at the cold outer edge.</div></div>
      <div class="bcard"><div class="bcard-num">04</div><div class="bcard-icon">🎵</div><div class="bcard-title">Vocal Recognition</div><div class="bcard-desc">In a colony of hundreds of thousands of identical-looking birds, penguin pairs locate each other using unique personal calls. Chicks recognise their parents' voices from among the crowd.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Remarkable Facts</h2>
    <p class="sec-sub">Penguins are full of surprises</p>
    <div class="facts-grid">
      <div class="fact-card"><div class="fact-num">01</div><p>Penguins are the <strong>only birds that walk upright on two legs</strong> on land — their characteristic waddling gait is actually energy-efficient, conserving more energy per step than a normal walking pattern.</p></div>
      <div class="fact-card"><div class="fact-num">02</div><p>Male Emperor penguins <strong>incubate eggs entirely on their feet</strong> through the Antarctic winter — fasting for up to 115 days in temperatures of −60°C while females are far out at sea feeding.</p></div>
      <div class="fact-card"><div class="fact-num">03</div><p>Penguins have a <strong>supraorbital gland</strong> above their eyes that filters excess salt from seawater — allowing them to drink seawater and excrete the concentrated salt through their beaks.</p></div>
      <div class="fact-card"><div class="fact-num">04</div><p>A penguin's feathers are packed at <strong>70 per square centimetre</strong> — far denser than any flying bird — creating a waterproof, windproof insulating layer that traps warm air against the skin.</p></div>
      <div class="fact-card"><div class="fact-num">05</div><p>Penguins can <strong>"porpoise"</strong> — leaping repeatedly out of the water while swimming at full speed — to breathe without slowing down, reaching sustained speeds of up to 25 km/h.</p></div>
      <div class="fact-card"><div class="fact-num">06</div><p>The Galápagos penguin is the <strong>only penguin species that lives north of the equator</strong>, surviving thanks to the cold Humboldt Current bringing cold, fish-rich water to the island chain.</p></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Efforts</h2>
    <p class="sec-sub">Protecting penguins and their ocean habitat</p>
    <div class="efforts-list">
      <div class="effort"><div class="effort-icon">🛢️</div><div class="effort-name">Oil Spill Rehabilitation</div><div class="effort-desc">Centres like SANCCOB in South Africa have cleaned and released over 100,000 oiled penguins, giving the African penguin population a vital lifeline.</div></div>
      <div class="effort"><div class="effort-icon">🐟</div><div class="effort-name">Fishing Exclusion Zones</div><div class="effort-desc">Marine protected areas around major penguin colonies prevent industrial fishing from depleting prey species during the critical breeding season.</div></div>
      <div class="effort"><div class="effort-icon">🏝️</div><div class="effort-name">Predator Control</div><div class="effort-desc">Removing introduced predators (cats, rats, weasels) from island breeding colonies dramatically increases chick survival rates in affected populations.</div></div>
      <div class="effort"><div class="effort-icon">🏗️</div><div class="effort-name">Artificial Nesting</div><div class="effort-desc">Providing artificial nest boxes and burrows in areas where natural nest sites are scarce helps boost breeding success for threatened species like the African penguin.</div></div>
    </div>
  </section>

  <hr class="divider">

  <div class="back-cta"><a href="meetTheAnimals.php">Meet The Other Animals</a></div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
