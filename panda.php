<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Giant Panda — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <style>
    :root{--nav:#4e7a51;--green:#2d5a30;--green-md:#3e7a3e;--green-lt:#5a9e52;--teal:#76d7c4;--teal-dk:#4eb8a8;--bg:#f1f8e9;--white:#fff;--text:#1a2e1a;--muted:#5a7a5a;--border:#c8e0c8;--card-sh:0 4px 24px rgba(46,90,48,.10)}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
    .hero{position:relative;height:520px;overflow:hidden}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(135deg,#1a2e1a 0%,#2d5a30 50%,#3a6a5a 100%)}
    .hero-pattern{position:absolute;inset:0;opacity:.06;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:40px 40px}
    .hero-deco{position:absolute;right:-60px;top:-60px;width:520px;height:520px;border-radius:50%;background:radial-gradient(circle,rgba(118,215,196,.14) 0%,transparent 70%)}
    .hero-emoji{position:absolute;right:7%;top:50%;transform:translateY(-50%);font-size:210px;opacity:.15;animation:float 7s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(-50%) rotate(-2deg)}50%{transform:translateY(calc(-50% - 16px)) rotate(2deg)}}
    .hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 8%;max-width:700px}
    .back-btn{display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.65);text-decoration:none;font-size:.82rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2rem;transition:color .2s}
    .back-btn:hover{color:#fff}
    .hero-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--teal);margin-bottom:.8rem;animation:fadeUp .5s ease both}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(3rem,6vw,5rem);font-weight:800;color:#fff;line-height:1.05;margin-bottom:1rem;animation:fadeUp .6s .1s ease both}
    .hero-latin{font-family:'Playfair Display',serif;font-style:italic;font-size:1.1rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;animation:fadeUp .6s .15s ease both}
    .hero-badges{display:flex;flex-wrap:wrap;gap:.6rem;animation:fadeUp .6s .2s ease both}
    .badge{padding:.35rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700}
    .badge-vu{background:rgba(232,160,32,.22);color:#f0c040;border:1px solid rgba(232,160,32,.45)}
    .badge-zone{background:rgba(118,215,196,.15);color:var(--teal);border:1px solid rgba(118,215,196,.3)}
    .badge-diet{background:rgba(90,158,82,.18);color:#8fd48f;border:1px solid rgba(90,158,82,.35)}
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
    .bamboo-bar-wrap{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:24px;margin-bottom:20px}
    .bamboo-bar-wrap h4{font-size:.88rem;font-weight:700;color:var(--green);margin-bottom:14px}
    .bbar-row{display:flex;align-items:center;gap:12px;margin-bottom:10px}
    .bbar-label{font-size:.8rem;font-weight:600;color:var(--muted);min-width:80px}
    .bbar-track{flex:1;height:10px;background:#e8f0e8;border-radius:10px;overflow:hidden}
    .bbar-fill{height:100%;border-radius:10px;background:linear-gradient(90deg,var(--teal-dk),var(--green-lt))}
    .bbar-pct{font-size:.8rem;font-weight:700;color:var(--green);min-width:38px;text-align:right}
    .behaviour-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px}
    .bcard{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:24px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s}
    .bcard:hover{transform:translateY(-4px);box-shadow:0 10px 30px rgba(46,90,48,.14)}
    .bcard-num{position:absolute;top:12px;right:16px;font-family:'Playfair Display',serif;font-size:3.5rem;font-weight:800;color:rgba(78,122,81,.07);line-height:1}
    .bcard-icon{font-size:1.8rem;margin-bottom:10px}
    .bcard-title{font-size:.96rem;font-weight:700;color:var(--green);margin-bottom:6px}
    .bcard-desc{font-size:.82rem;color:var(--muted);line-height:1.6}
    .facts-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
    .fact-card{background:var(--white);border-left:4px solid var(--teal-dk);border-radius:0 14px 14px 0;padding:20px 22px;box-shadow:var(--card-sh)}
    .fact-card .fact-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:800;color:var(--teal-dk);opacity:.3;line-height:1;margin-bottom:4px}
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
  <div class="hero-emoji">🐼</div>
  <div class="hero-content">
    <a href="meetTheAnimals.php" class="back-btn">← BACK</a>
    <div class="hero-eyebrow">WildTrack Malaysia · Asia Zone</div>
    <h1 class="hero-title">Giant Panda</h1>
    <div class="hero-latin">Ailuropoda melanoleuca</div>
    <div class="hero-badges">
      <span class="badge badge-vu">🟡 Vulnerable</span>
      <span class="badge badge-zone">🌏 Asia Zone</span>
      <span class="badge badge-diet">🌿 Herbivore</span>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item"><div class="stat-num">125 kg</div><div class="stat-lbl">Max Weight</div></div>
    <div class="stat-item"><div class="stat-num">20 yrs</div><div class="stat-lbl">Wild Lifespan</div></div>
    <div class="stat-item"><div class="stat-num">14 kg</div><div class="stat-lbl">Bamboo / Day</div></div>
    <div class="stat-item"><div class="stat-num">1,800</div><div class="stat-lbl">Wild Population</div></div>
    <div class="stat-item"><div class="stat-num">99%</div><div class="stat-lbl">Diet is Bamboo</div></div>
  </div>
</div>

<div class="page">

  <section>
    <h2 class="sec-title">Overview</h2>
    <p class="sec-sub">China's beloved conservation icon</p>
    <div class="overview-grid">
      <div class="overview-text">
        <p>The giant panda (Ailuropoda melanoleuca) is one of the world's most <strong>recognisable and beloved animals</strong> — an icon of global conservation efforts. Native to central China, giant pandas are classified in the bear family (Ursidae) but have evolved a highly specialised diet centred almost entirely on <strong>bamboo</strong>, a nutritionally poor food source that requires them to eat enormous quantities daily.</p>
        <p>Despite being classified as <strong>carnivores by evolutionary biology</strong>, giant pandas have adapted physically and metabolically to a near-vegetarian lifestyle. Their paws feature a <strong>modified wrist bone</strong> that functions as a "pseudo-thumb," allowing them to grasp bamboo shoots with extraordinary dexterity.</p>
        <p>Once listed as Endangered, decades of intensive conservation effort have resulted in a genuine recovery — giant pandas were downlisted to <strong>Vulnerable in 2016</strong>, a rare conservation success story. However, habitat fragmentation continues to pose serious long-term threats to wild populations.</p>
      </div>
      <div class="info-chips">
        <div class="chip"><div class="chip-lbl">Class</div><div class="chip-val">Mammalia</div></div>
        <div class="chip"><div class="chip-lbl">Family</div><div class="chip-val">Ursidae (Bears)</div></div>
        <div class="chip"><div class="chip-lbl">Habitat</div><div class="chip-val">Temperate Forest</div></div>
        <div class="chip"><div class="chip-lbl">Weight</div><div class="chip-val">70–125 kg</div></div>
        <div class="chip"><div class="chip-lbl">Height</div><div class="chip-val">60–90 cm (shoulder)</div></div>
        <div class="chip"><div class="chip-lbl">Lifespan</div><div class="chip-val">Up to 20 years (wild)</div></div>
        <div class="chip"><div class="chip-lbl">Gestation</div><div class="chip-val">95–160 days</div></div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Status</h2>
    <p class="sec-sub">A rare conservation success story</p>
    <div class="cons-banner">
      <div class="cons-icon-big">🟡</div>
      <div class="cons-text">
        <h3>Vulnerable — Ailuropoda melanoleuca</h3>
        <p>Giant pandas were downlisted from <strong>Endangered to Vulnerable</strong> by the IUCN in 2016, reflecting genuine population recovery due to extensive habitat protection, breeding programmes, and reforestation of bamboo forests. Wild populations are estimated at <strong>around 1,800 individuals</strong> — a significant increase from the low of around 1,000 in the 1970s.</p>
        <p>Despite this progress, giant pandas remain at serious risk. Climate change is predicted to destroy over <strong>one-third of bamboo habitat</strong> by 2080. Population fragmentation due to infrastructure development means isolated groups cannot interbreed, reducing genetic diversity.</p>
        <span class="iucn-pill">IUCN: Vulnerable (VU)</span>
      </div>
    </div>
    <div class="threats-list">
      <div class="threat"><div class="threat-icon">🌡️</div><div class="threat-name">Climate Change</div><div class="threat-desc">Rising temperatures and altered rainfall patterns are predicted to destroy up to 35% of current bamboo forest habitat within decades.</div></div>
      <div class="threat"><div class="threat-icon">🏗️</div><div class="threat-name">Habitat Fragmentation</div><div class="threat-desc">Roads and settlements divide panda populations into isolated groups that cannot interbreed, reducing genetic diversity and resilience.</div></div>
      <div class="threat"><div class="threat-icon">🌿</div><div class="threat-name">Bamboo Die-off</div><div class="threat-desc">Bamboo species flower synchronously and then die — events that periodically eliminate entire food sources from panda territories.</div></div>
      <div class="threat"><div class="threat-icon">👶</div><div class="threat-name">Low Reproduction Rate</div><div class="threat-desc">Females are only fertile for 24–72 hours per year, and typically raise only one cub at a time — making population growth inherently slow.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Diet: The Bamboo Specialist</h2>
    <p class="sec-sub">Up to 16 hours of eating per day</p>
    <div class="bamboo-bar-wrap">
      <h4>🐼 What makes up a panda's daily diet?</h4>
      <div class="bbar-row"><span class="bbar-label">Bamboo</span><div class="bbar-track"><div class="bbar-fill" style="width:99%"></div></div><span class="bbar-pct">99%</span></div>
      <div class="bbar-row"><span class="bbar-label">Other Plants</span><div class="bbar-track"><div class="bbar-fill" style="width:0.8%;background:#c8e0c8"></div></div><span class="bbar-pct">&lt;1%</span></div>
      <div class="bbar-row"><span class="bbar-label">Insects/Eggs</span><div class="bbar-track"><div class="bbar-fill" style="width:0.2%;background:#e0e8e0"></div></div><span class="bbar-pct">Rare</span></div>
    </div>
    <div class="diet-grid">
      <div class="diet-item"><div class="diet-emoji">🎋</div><div class="diet-name">Bamboo Shoots</div><div class="diet-amt">Most nutritious part</div></div>
      <div class="diet-item"><div class="diet-emoji">🍃</div><div class="diet-name">Bamboo Leaves</div><div class="diet-amt">Year-round staple</div></div>
      <div class="diet-item"><div class="diet-emoji">🌱</div><div class="diet-name">Bamboo Stems</div><div class="diet-amt">Eaten in large qty</div></div>
      <div class="diet-item"><div class="diet-emoji">🌸</div><div class="diet-name">Wild Flowers</div><div class="diet-amt">Occasional supplement</div></div>
      <div class="diet-item"><div class="diet-emoji">🍄</div><div class="diet-name">Fungi & Roots</div><div class="diet-amt">Very rare</div></div>
      <div class="diet-item"><div class="diet-emoji">💧</div><div class="diet-name">Fresh Water</div><div class="diet-amt">Daily from streams</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Behaviour & Biology</h2>
    <p class="sec-sub">Surprising secrets of the world's most famous bear</p>
    <div class="behaviour-grid">
      <div class="bcard"><div class="bcard-num">01</div><div class="bcard-icon">✋</div><div class="bcard-title">The Pseudo-Thumb</div><div class="bcard-desc">Pandas possess a modified radial sesamoid bone in their wrist that acts as a "false thumb," allowing them to grip bamboo stalks with precision unmatched by other bears.</div></div>
      <div class="bcard"><div class="bcard-num">02</div><div class="bcard-icon">🖤🤍</div><div class="bcard-title">Camouflage Mystery</div><div class="bcard-desc">Scientists believe the black-and-white colouring serves dual purposes: the dark patches provide camouflage in snowy environments, while the white helps regulate body temperature.</div></div>
      <div class="bcard"><div class="bcard-num">03</div><div class="bcard-icon">🔇</div><div class="bcard-title">Solitary Nature</div><div class="bcard-desc">Unlike most bears, giant pandas are largely solitary outside of mating season. They communicate through scent marking and vocalisation rather than direct social interaction.</div></div>
      <div class="bcard"><div class="bcard-num">04</div><div class="bcard-icon">👶</div><div class="bcard-title">Tiny Cubs</div><div class="bcard-desc">Giant panda cubs are born extraordinarily small — weighing only 90–130 grams at birth, roughly 1/900th of their mother's weight. No other placental mammal has such a size disparity.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Remarkable Facts</h2>
    <p class="sec-sub">The giant panda is full of surprises</p>
    <div class="facts-grid">
      <div class="fact-card"><div class="fact-num">01</div><p>Giant pandas eat up to <strong>14 kg of bamboo per day</strong> and spend 10–16 hours eating — yet bamboo is so low in nutrition that pandas must consume enormous quantities to survive.</p></div>
      <div class="fact-card"><div class="fact-num">02</div><p>Despite being taxonomically <strong>carnivores</strong> — classified in the order Carnivora — giant pandas have evolved to be almost entirely vegetarian, with only a tiny fraction of their diet from animal matter.</p></div>
      <div class="fact-card"><div class="fact-num">03</div><p>Giant panda cubs are born <strong>pink, blind, and hairless</strong>, weighing as little as 90 grams — one of the most altricial births of any mammal relative to adult size.</p></div>
      <div class="fact-card"><div class="fact-num">04</div><p>The <strong>black eye patches</strong> of giant pandas — far from being merely decorative — likely serve a communication function, helping pandas read each other's emotional state and intentions.</p></div>
      <div class="fact-card"><div class="fact-num">05</div><p>Giant pandas have a <strong>very slow metabolism</strong> — about 50% lower than expected for their size — an adaptation to their low-energy bamboo diet that also means they don't need to hibernate.</p></div>
      <div class="fact-card"><div class="fact-num">06</div><p>The scientific name <em>Ailuropoda melanoleuca</em> translates as <strong>"black-and-white cat-foot"</strong> — a nod to the panda's superficially cat-like appearance despite being a true bear.</p></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Efforts</h2>
    <p class="sec-sub">How pandas went from Endangered to Vulnerable</p>
    <div class="efforts-list">
      <div class="effort"><div class="effort-icon">🏞️</div><div class="effort-name">Nature Reserves</div><div class="effort-desc">China has established 67 panda nature reserves covering over 1.4 million hectares, protecting more than two-thirds of the wild panda population.</div></div>
      <div class="effort"><div class="effort-icon">🐣</div><div class="effort-name">Breeding Programmes</div><div class="effort-desc">Captive breeding and artificial insemination techniques have dramatically improved reproductive success, with cubs being reintroduced to the wild.</div></div>
      <div class="effort"><div class="effort-icon">🎋</div><div class="effort-name">Bamboo Corridors</div><div class="effort-desc">Reforestation projects create bamboo corridors connecting fragmented panda habitats, allowing isolated populations to interbreed and exchange genes.</div></div>
      <div class="effort"><div class="effort-icon">🌍</div><div class="effort-name">International Loans</div><div class="effort-desc">China's "panda diplomacy" loan programme funds conservation research in exchange for pandas visiting international zoos, raising global awareness and funding.</div></div>
    </div>
  </section>

  <hr class="divider">

  <div class="back-cta"><a href="meetTheAnimals.php">Meet The Other Animals</a></div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
