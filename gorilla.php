<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gorilla — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="shared.css">
  <style>
    :root{--nav:#4e7a51;--green:#2d5a30;--green-md:#3e7a3e;--green-lt:#5a9e52;--teal:#76d7c4;--teal-dk:#4eb8a8;--bg:#f1f8e9;--white:#fff;--text:#1a2e1a;--muted:#5a7a5a;--border:#c8e0c8;--accent:#e8a020;--card-sh:0 4px 24px rgba(46,90,48,.10)}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
    .hero{position:relative;height:520px;overflow:hidden;background:#1a2e1a}
    .hero-bg{position:absolute;inset:0;background:linear-gradient(135deg,#0f1f0f 0%,#1a3a1a 40%,#2d5a30 100%)}
    .hero-pattern{position:absolute;inset:0;opacity:.05;background-image:radial-gradient(circle,#fff 1px,transparent 1px);background-size:40px 40px}
    .hero-deco{position:absolute;right:-60px;top:-60px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(118,215,196,.12) 0%,transparent 70%)}
    .hero-emoji{position:absolute;right:8%;top:50%;transform:translateY(-50%);font-size:200px;opacity:.14;animation:float 6s ease-in-out infinite}
    @keyframes float{0%,100%{transform:translateY(-50%) rotate(-3deg)}50%{transform:translateY(calc(-50% - 16px)) rotate(3deg)}}
    .hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 8%;max-width:700px}
    .back-btn{display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.65);text-decoration:none;font-size:.82rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:2rem;transition:color .2s}
    .back-btn:hover{color:#fff}
    .hero-eyebrow{font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--teal);margin-bottom:.8rem;animation:fadeUp .5s ease both}
    .hero-title{font-family:'Playfair Display',serif;font-size:clamp(3rem,6vw,5rem);font-weight:800;color:#fff;line-height:1.05;margin-bottom:1rem;animation:fadeUp .6s .1s ease both}
    .hero-latin{font-family:'Playfair Display',serif;font-style:italic;font-size:1.1rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;animation:fadeUp .6s .15s ease both}
    .hero-badges{display:flex;flex-wrap:wrap;gap:.6rem;animation:fadeUp .6s .2s ease both}
    .badge{padding:.35rem 1rem;border-radius:20px;font-size:.78rem;font-weight:700}
    .badge-cr{background:rgba(217,79,61,.2);color:#f08070;border:1px solid rgba(217,79,61,.4)}
    .badge-zone{background:rgba(118,215,196,.15);color:var(--teal);border:1px solid rgba(118,215,196,.3)}
    .badge-diet{background:rgba(90,158,82,.18);color:#8fd48f;border:1px solid rgba(90,158,82,.35)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
    .stats-bar{background:var(--green);padding:0 8%}
    .stats-inner{display:flex;overflow-x:auto;border-top:1px solid rgba(255,255,255,.1)}
    .stat-item{flex:1;min-width:130px;padding:22px 20px;text-align:center;border-right:1px solid rgba(255,255,255,.1)}
    .stat-item:last-child{border-right:none}
    .stat-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#fff;line-height:1}
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
    .cons-banner{background:linear-gradient(135deg,#fff0ee,#fde8e8);border:2px solid #fca5a5;border-radius:18px;padding:28px 32px;display:flex;align-items:flex-start;gap:20px;margin-bottom:36px}
    .cons-icon-big{font-size:3rem;flex-shrink:0}
    .cons-text h3{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:#991b1b;margin-bottom:6px}
    .cons-text p{font-size:.88rem;color:#7f1d1d;line-height:1.7}
    .iucn-pill{display:inline-block;background:#d94f3d;color:#fff;padding:.3rem .9rem;border-radius:20px;font-size:.75rem;font-weight:700;margin-top:10px}
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
    .zoo-card .zoo-emoji{font-size:5rem;flex-shrink:0}
    .zoo-text h3{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;margin-bottom:8px}
    .zoo-text p{font-size:.9rem;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:16px}
    .zoo-pills{display:flex;flex-wrap:wrap;gap:8px}
    .zoo-pill{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:.3rem .9rem;font-size:.78rem;font-weight:600;color:#fff}
    .back-cta{text-align:center;margin-top:56px}
    .back-cta a{display:inline-flex;align-items:center;gap:.5rem;background:var(--nav);color:#fff;padding:.85rem 2rem;border-radius:40px;text-decoration:none;font-weight:600;font-size:.95rem;transition:all .2s;box-shadow:0 6px 20px rgba(46,90,48,.3)}
    .back-cta a:hover{background:var(--green);transform:translateY(-2px)}
    .dna-bar{display:flex;align-items:center;gap:16px;background:var(--white);border:1px solid var(--border);border-radius:14px;padding:20px 24px;margin-bottom:20px}
    .dna-label{font-size:.82rem;font-weight:600;color:var(--muted);min-width:80px}
    .dna-track{flex:1;height:12px;background:#e8f0e8;border-radius:10px;overflow:hidden}
    .dna-fill{height:100%;border-radius:10px;background:linear-gradient(90deg,var(--teal-dk),var(--green-lt))}
    .dna-pct{font-size:.88rem;font-weight:700;color:var(--green);min-width:50px;text-align:right}
  </style>
</head>
<body>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-pattern"></div>
  <div class="hero-deco"></div>
  <div class="hero-emoji">🦍</div>
  <div class="hero-content">
    <a href="meetTheAnimals.php" class="back-btn">← BACK</a>
    <div class="hero-eyebrow">WildTrack Malaysia · Africa Zone</div>
    <h1 class="hero-title">Gorilla</h1>
    <div class="hero-latin">Gorilla gorilla</div>
    <div class="hero-badges">
      <span class="badge badge-cr">🔴 Critically Endangered</span>
      <span class="badge badge-zone">🌍 Africa Zone</span>
      <span class="badge badge-diet">🌿 Herbivore</span>
    </div>
  </div>
</section>

<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item"><div class="stat-num">200 kg</div><div class="stat-lbl">Max Weight</div></div>
    <div class="stat-item"><div class="stat-num">40 yrs</div><div class="stat-lbl">Lifespan</div></div>
    <div class="stat-item"><div class="stat-num">30 kg</div><div class="stat-lbl">Food / Day</div></div>
    <div class="stat-item"><div class="stat-num">98.3%</div><div class="stat-lbl">DNA Shared w/ Humans</div></div>
    <div class="stat-item"><div class="stat-num">300,000</div><div class="stat-lbl">Wild Population (est.)</div></div>
  </div>
</div>

<div class="page">

  <section>
    <h2 class="sec-title">Overview</h2>
    <p class="sec-sub">Earth's largest living primates</p>
    <div class="overview-grid">
      <div class="overview-text">
        <p>Gorillas are the <strong>largest primates on Earth</strong> and our closest relatives after chimpanzees and bonobos. There are two species: the Western gorilla (Gorilla gorilla) and the Eastern gorilla (Gorilla beringei), each with two subspecies. Gorillas are found only in the <strong>tropical forests of equatorial Africa</strong>.</p>
        <p>Despite their imposing size, gorillas are <strong>gentle, social, and highly intelligent</strong>. They live in stable family groups led by a dominant male — the silverback — whose grey-streaked back is a sign of maturity. Gorillas communicate through a rich repertoire of vocalisations, gestures, and facial expressions.</p>
        <p>Gorillas share <strong>98.3% of their DNA with humans</strong> — a proximity that underlies their remarkable cognitive abilities, including the capacity to learn sign language and understand symbolic communication.</p>
      </div>
      <div class="info-chips">
        <div class="chip"><div class="chip-lbl">Class</div><div class="chip-val">Mammalia</div></div>
        <div class="chip"><div class="chip-lbl">Family</div><div class="chip-val">Hominidae</div></div>
        <div class="chip"><div class="chip-lbl">Habitat</div><div class="chip-val">Tropical Rainforest</div></div>
        <div class="chip"><div class="chip-lbl">Weight</div><div class="chip-val">100–200 kg</div></div>
        <div class="chip"><div class="chip-lbl">Height</div><div class="chip-val">1.25–1.8 m (upright)</div></div>
        <div class="chip"><div class="chip-lbl">Lifespan</div><div class="chip-val">Up to 40 years</div></div>
        <div class="chip"><div class="chip-lbl">Gestation</div><div class="chip-val">8.5 months</div></div>
      </div>
    </div>
  </section>

  <hr class="divider">

  <!-- DNA SIMILARITY SECTION -->
  <section>
    <h2 class="sec-title">Our Closest Relatives</h2>
    <p class="sec-sub">Genetic similarity between humans and great apes</p>
    <div class="dna-bar"><span class="dna-label">Chimpanzee</span><div class="dna-track"><div class="dna-fill" style="width:98.7%"></div></div><span class="dna-pct">98.7%</span></div>
    <div class="dna-bar"><span class="dna-label">Gorilla</span><div class="dna-track"><div class="dna-fill" style="width:98.3%"></div></div><span class="dna-pct">98.3%</span></div>
    <div class="dna-bar"><span class="dna-label">Orangutan</span><div class="dna-track"><div class="dna-fill" style="width:96.9%"></div></div><span class="dna-pct">96.9%</span></div>
    <div class="dna-bar"><span class="dna-label">Gibbon</span><div class="dna-track"><div class="dna-fill" style="width:96%"></div></div><span class="dna-pct">96%</span></div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Status</h2>
    <p class="sec-sub">IUCN Red List Assessment</p>
    <div class="cons-banner">
      <div class="cons-icon-big">🔴</div>
      <div class="cons-text">
        <h3>Critically Endangered — Gorilla gorilla</h3>
        <p>Western gorillas are classified as <strong>Critically Endangered</strong> on the IUCN Red List. Fewer than <strong>300,000 western gorillas</strong> remain in the wild, and their numbers continue to decline. Eastern gorillas are in an even more precarious position — the Mountain gorilla subspecies has a total population of only around 1,000 individuals.</p>
        <p>Gorillas are threatened by poaching for the bushmeat trade, habitat destruction from logging and agriculture, disease transmission from humans (particularly respiratory viruses), and civil conflict disrupting conservation work in Central Africa.</p>
        <span class="iucn-pill">IUCN: Critically Endangered (CR)</span>
      </div>
    </div>
    <div class="threats-list">
      <div class="threat"><div class="threat-icon">🌲</div><div class="threat-name">Deforestation</div><div class="threat-desc">Logging, mining, and agricultural expansion destroy the rainforest gorillas depend on, forcing them into smaller, isolated patches.</div></div>
      <div class="threat"><div class="threat-icon">🍖</div><div class="threat-name">Bushmeat Poaching</div><div class="threat-desc">Gorillas are killed for the illegal bushmeat trade across Central Africa, where their meat is considered a luxury by some communities.</div></div>
      <div class="threat"><div class="threat-icon">🦠</div><div class="threat-name">Disease</div><div class="threat-desc">Gorillas are highly susceptible to human diseases. Ebola outbreaks have killed thousands of gorillas; respiratory infections pose ongoing risks.</div></div>
      <div class="threat"><div class="threat-icon">⚔️</div><div class="threat-name">Armed Conflict</div><div class="threat-desc">Civil unrest in gorilla range countries disrupts conservation efforts, displaces rangers, and enables poaching to go unchecked.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Diet & Feeding</h2>
    <p class="sec-sub">Primarily herbivorous with occasional insect protein</p>
    <div class="diet-grid">
      <div class="diet-item"><div class="diet-emoji">🍃</div><div class="diet-name">Leaves</div><div class="diet-amt">Primary food source</div></div>
      <div class="diet-item"><div class="diet-emoji">🌿</div><div class="diet-name">Stems & Shoots</div><div class="diet-amt">Young growth preferred</div></div>
      <div class="diet-item"><div class="diet-emoji">🍌</div><div class="diet-name">Wild Fruits</div><div class="diet-amt">Seasonal abundance</div></div>
      <div class="diet-item"><div class="diet-emoji">🌰</div><div class="diet-name">Seeds & Bark</div><div class="diet-amt">During dry season</div></div>
      <div class="diet-item"><div class="diet-emoji">🐛</div><div class="diet-name">Insects</div><div class="diet-amt">Termites & ants (rare)</div></div>
      <div class="diet-item"><div class="diet-emoji">💧</div><div class="diet-name">Water</div><div class="diet-amt">Mostly from vegetation</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Behaviour & Intelligence</h2>
    <p class="sec-sub">Complex social beings with remarkable cognitive abilities</p>
    <div class="behaviour-grid">
      <div class="bcard"><div class="bcard-num">01</div><div class="bcard-icon">🤟</div><div class="bcard-title">Sign Language</div><div class="bcard-desc">Gorillas like Koko learned hundreds of American Sign Language signs, demonstrating the ability to communicate symbolically, feel emotions, and even express self-reflection.</div></div>
      <div class="bcard"><div class="bcard-num">02</div><div class="bcard-icon">🛠️</div><div class="bcard-title">Tool Use</div><div class="bcard-desc">Wild gorillas have been filmed using sticks to gauge water depth before crossing streams, and using rocks as anvils to crack open tough palm nuts.</div></div>
      <div class="bcard"><div class="bcard-num">03</div><div class="bcard-icon">🥁</div><div class="bcard-title">Chest Beating</div><div class="bcard-desc">Silverbacks beat their chests — not out of aggression, but to communicate status, strength, and identity to other gorillas across long distances in the forest.</div></div>
      <div class="bcard"><div class="bcard-num">04</div><div class="bcard-icon">🏠</div><div class="bcard-title">Nest Building</div><div class="bcard-desc">Every evening, gorillas construct fresh sleeping nests from branches and leaves — on the ground or in trees — demonstrating planning ability and manual dexterity.</div></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Remarkable Facts</h2>
    <p class="sec-sub">What makes gorillas extraordinary</p>
    <div class="facts-grid">
      <div class="fact-card"><div class="fact-num">01</div><p>Every gorilla has a <strong>unique nose print</strong> — the pattern of wrinkles and shape of the nostrils is as individual as a human fingerprint, used by researchers to identify individuals.</p></div>
      <div class="fact-card"><div class="fact-num">02</div><p>A silverback gorilla can lift <strong>over 800 kg</strong> — roughly 10 times its own body weight — making them one of the strongest animals on Earth relative to size.</p></div>
      <div class="fact-card"><div class="fact-num">03</div><p>Gorillas build a <strong>brand-new sleeping nest every night</strong>. Infants sleep in their mother's nest until they are about 3 years old and can build their own.</p></div>
      <div class="fact-card"><div class="fact-num">04</div><p>Wild gorillas have been observed showing <strong>altruistic behaviour</strong> — caring for orphaned infants that are not their own biological offspring.</p></div>
      <div class="fact-card"><div class="fact-num">05</div><p>Gorillas are <strong>knuckle-walkers</strong> — they walk on all fours using the knuckles of their hands, though they can stand upright and walk bipedally for short distances.</p></div>
      <div class="fact-card"><div class="fact-num">06</div><p>Despite their intimidating size, gorillas are <strong>almost entirely vegetarian</strong> — they very rarely consume any animal protein and are not predators.</p></div>
    </div>
  </section>

  <hr class="divider">

  <section>
    <h2 class="sec-title">Conservation Efforts</h2>
    <p class="sec-sub">Global action to protect great apes</p>
    <div class="efforts-list">
      <div class="effort"><div class="effort-icon">🏕️</div><div class="effort-name">Ranger Patrols</div><div class="effort-desc">Anti-poaching rangers monitor gorilla groups daily in protected areas across Central Africa, providing critical ground-level protection.</div></div>
      <div class="effort"><div class="effort-icon">💉</div><div class="effort-name">Veterinary Care</div><div class="effort-desc">Mobile vet teams treat injured and sick gorillas in the wild, including removing snares and vaccinating habituated gorillas against measles.</div></div>
      <div class="effort"><div class="effort-icon">🌍</div><div class="effort-name">Habitat Preservation</div><div class="effort-desc">International organisations work with governments to gazette and protect critical gorilla forest, often working with local communities.</div></div>
      <div class="effort"><div class="effort-icon">🎓</div><div class="effort-name">Eco-tourism</div><div class="effort-desc">Carefully managed gorilla trekking provides vital income for conservation and gives local communities financial reasons to protect gorillas.</div></div>
    </div>
  </section>

  <hr class="divider">

  <div class="back-cta"><a href="meetTheAnimals.php">Meet The Other Animals</a></div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
