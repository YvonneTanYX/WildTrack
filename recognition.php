<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'animal'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Animal Recognition — WildTrack Malaysia</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="hero.css"/>
  <link rel="stylesheet" href="shared.css"/>
  <link rel="stylesheet" href="mainPage.css"/>
  <style>
    :root {
      --nav:      #4e7a51;
      --nav-hover:#76d7c4;
      --green:    #2d5a30;
      --green-md: #3e7a3e;
      --green-lt: #5a9e52;
      --teal:     #76d7c4;
      --teal-dk:  #4eb8a8;
      --bg:       #f1f8e9;
      --white:    #ffffff;
      --text:     #1a2e1a;
      --muted:    #5a7a5a;
      --amber:    #e8a020;
      --red:      #d94f3d;
      --border:   #c8e0c8;
      --card-sh:  0 4px 24px rgba(46,90,48,.10);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    /* ── MAIN LAYOUT ── */
    .page-body {
      max-width: 1100px;
      margin: 0 auto;
      padding: 60px 24px 80px;
    }

    /* ── UPLOAD ZONE ── */
    .upload-section {
      display: flex;
      justify-content: center;
      margin-bottom: 48px;
    }
    .upload-section .upload-card {
      max-width: 560px;
      width: 100%;
    }

    .upload-card {
      background: var(--white);
      border-radius: 20px;
      border: 2px dashed var(--border);
      padding: 40px 32px;
      text-align: center;
      cursor: pointer;
      transition: all .25s ease;
      position: relative;
      overflow: hidden;
    }
    .upload-card::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(118,215,196,.06) 0%, transparent 60%);
      opacity: 0; transition: opacity .3s;
    }
    .upload-card:hover { border-color: var(--teal-dk); transform: translateY(-3px); box-shadow: var(--card-sh); }
    .upload-card:hover::before { opacity: 1; }
    .upload-card.drag-over { border-color: var(--green-lt); background: rgba(118,215,196,.06); }

    .upload-icon {
      width: 72px; height: 72px;
      background: linear-gradient(135deg, var(--nav), var(--green-lt));
      border-radius: 18px; margin: 0 auto 20px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem;
      box-shadow: 0 6px 20px rgba(46,90,48,.2);
    }
    .upload-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem; font-weight: 700;
      color: var(--green); margin-bottom: .4rem;
    }
    .upload-sub { font-size: .85rem; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }
    .upload-btn {
      display: inline-block;
      background: var(--nav);
      color: #fff; border: none;
      padding: .65rem 1.6rem;
      border-radius: 30px;
      font-family: 'DM Sans', sans-serif;
      font-size: .88rem; font-weight: 600;
      cursor: pointer; transition: all .2s;
      box-shadow: 0 3px 12px rgba(46,90,48,.25);
    }
    .upload-btn:hover { background: var(--green); transform: translateY(-1px); box-shadow: 0 6px 18px rgba(46,90,48,.3); }
    .upload-accept { font-size: .75rem; color: #aaa; margin-top: 10px; }

    /* preview inside upload card */
    .preview-wrap { position: relative; display: none; }
    .preview-wrap.show { display: block; }
    .preview-img {
      width: 100%; max-height: 260px;
      object-fit: contain; border-radius: 12px;
      margin-bottom: 14px;
      box-shadow: 0 4px 16px rgba(0,0,0,.1);
    }
    .preview-clear {
      position: absolute; top: 8px; right: 8px;
      width: 28px; height: 28px;
      background: rgba(0,0,0,.55); border: none;
      border-radius: 50%; color: #fff; font-size: 1rem;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .preview-clear:hover { background: var(--red); }

    /* ── IDENTIFY BUTTON ── */
    .identify-wrap { text-align: center; margin-bottom: 48px; }
    #btn-identify {
      background: linear-gradient(135deg, var(--nav), var(--green-lt));
      color: #fff; border: none;
      padding: 1rem 3rem;
      border-radius: 50px;
      font-family: 'DM Sans', sans-serif;
      font-size: 1.05rem; font-weight: 600;
      cursor: pointer; transition: all .2s;
      box-shadow: 0 6px 24px rgba(46,90,48,.3);
      display: inline-flex; align-items: center; gap: .6rem;
      opacity: .45; pointer-events: none;
    }
    #btn-identify.ready { opacity: 1; pointer-events: all; }
    #btn-identify:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(46,90,48,.38); }
    #btn-identify.loading { pointer-events: none; filter: brightness(.85); }
    .id-spinner {
      display: none; width: 18px; height: 18px;
      border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
      border-radius: 50%; animation: spin .7s linear infinite;
    }
    #btn-identify.loading .id-spinner { display: block; }
    #btn-identify.loading .id-text::after { content: '…'; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── RESULT PANEL ── */
    #result-section { display: none; }
    #result-section.show { display: block; animation: fadeUp .5s ease both; }

    .result-card {
      background: var(--white);
      border-radius: 20px;
      padding: 36px 40px;
      box-shadow: var(--card-sh);
      border: 1px solid var(--border);
      margin-bottom: 32px;
    }

    .result-header {
      display: flex; align-items: center; gap: 18px;
      margin-bottom: 28px;
      padding-bottom: 24px;
      border-bottom: 1px solid var(--border);
    }
    .result-icon {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, var(--nav), var(--teal-dk));
      border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
      box-shadow: 0 4px 16px rgba(46,90,48,.2);
      flex-shrink: 0;
    }
    .result-label {
      font-size: .72rem; font-weight: 600; letter-spacing: .12em;
      text-transform: uppercase; color: var(--teal-dk); margin-bottom: .3rem;
    }
    .result-animal-name {
      font-family: 'Playfair Display', serif;
      font-size: 2rem; font-weight: 700; color: var(--green);
      line-height: 1.1;
    }
    .result-species { font-size: .9rem; color: var(--muted); margin-top: 2px; font-style: italic; }

    /* confidence bar */
    .confidence-section { margin-bottom: 24px; }
    .conf-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .conf-label { font-size: .8rem; font-weight: 600; color: var(--muted); letter-spacing: .06em; text-transform: uppercase; }
    .conf-pct { font-size: 1.4rem; font-weight: 700; color: var(--green); }
    .conf-bar-bg {
      width: 100%; height: 10px;
      background: #e8f0e8; border-radius: 10px; overflow: hidden;
    }
    .conf-bar-fill {
      height: 100%; border-radius: 10px;
      background: linear-gradient(90deg, var(--teal-dk), var(--green-lt));
      transition: width 1s cubic-bezier(.22,1,.36,1);
      width: 0%;
    }
    .conf-note { font-size: .78rem; color: var(--muted); margin-top: 6px; }

    /* top predictions table */
    .predictions-title {
      font-size: .8rem; font-weight: 600; letter-spacing: .08em;
      text-transform: uppercase; color: var(--muted);
      margin-bottom: 12px;
    }
    .pred-row {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 0;
      border-bottom: 1px solid #f0f5f0;
    }
    .pred-row:last-child { border-bottom: none; }
    .pred-rank {
      width: 26px; height: 26px;
      background: var(--bg); border-radius: 50%;
      font-size: .78rem; font-weight: 700; color: var(--muted);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .pred-rank.top { background: var(--nav); color: #fff; }
    .pred-name { flex: 1; font-size: .92rem; font-weight: 500; color: var(--text); }
    .pred-mini-bar-bg {
      width: 100px; height: 6px;
      background: #e8f0e8; border-radius: 6px; overflow: hidden;
    }
    .pred-mini-fill {
      height: 100%; border-radius: 6px;
      background: var(--teal-dk);
      transition: width 1.2s ease;
    }
    .pred-pct { font-size: .82rem; font-weight: 600; color: var(--muted); min-width: 42px; text-align: right; }

    /* animal info card */
    .animal-info-card {
      background: linear-gradient(135deg, #eaf4ea 0%, #f1f8f1 100%);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 28px 32px;
      margin-top: 8px;
    }
    .animal-info-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 16px; margin-top: 16px;
    }
    .info-chip {
      background: var(--white);
      border-radius: 12px; padding: 14px 16px;
      border: 1px solid var(--border);
    }
    .info-chip-label {
      font-size: .68rem; font-weight: 600; letter-spacing: .1em;
      text-transform: uppercase; color: var(--muted); margin-bottom: 4px;
    }
    .info-chip-val { font-size: .92rem; font-weight: 600; color: var(--green); }
    .conservation-badge {
      display: inline-block;
      padding: .25rem .75rem; border-radius: 20px;
      font-size: .78rem; font-weight: 700;
      letter-spacing: .04em;
    }
    .badge-critical  { background: #fde8e8; color: #b91c1c; }
    .badge-vulnerable{ background: #fff3e0; color: #b45309; }
    .badge-endangered{ background: #fef3c7; color: #92400e; }
    .badge-least     { background: #d1fae5; color: #065f46; }
    .view-profile-btn {
      display: inline-flex; align-items: center; gap: .5rem;
      margin-top: 20px;
      background: var(--nav); color: #fff;
      padding: .7rem 1.6rem; border-radius: 30px;
      text-decoration: none; font-size: .88rem; font-weight: 600;
      transition: all .2s;
      box-shadow: 0 3px 12px rgba(46,90,48,.22);
    }
    .view-profile-btn:hover { background: var(--green); transform: translateY(-1px); }

    /* low confidence warning */
    .low-conf-banner {
      background: #fff8e8;
      border: 1px solid #f0d080;
      border-radius: 12px;
      padding: 16px 20px;
      display: flex; align-items: flex-start; gap: 12px;
      margin-bottom: 20px;
    }
    .low-conf-banner span { font-size: 1.2rem; }
    .low-conf-banner p { font-size: .88rem; color: #7a5a10; line-height: 1.5; }

    /* try again */
    .try-again-btn {
      background: none; border: 2px solid var(--border);
      color: var(--muted); padding: .65rem 1.6rem;
      border-radius: 30px; cursor: pointer;
      font-family: 'DM Sans', sans-serif; font-size: .88rem; font-weight: 600;
      transition: all .2s; display: inline-flex; align-items: center; gap: .4rem;
    }
    .try-again-btn:hover { border-color: var(--nav); color: var(--nav); }

    /* ── HOW IT WORKS ── */
    .how-section {
      margin-top: 64px;
      padding-top: 48px;
      border-top: 2px solid var(--border);
    }
    .section-heading {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem; font-weight: 700; color: var(--green);
      margin-bottom: .4rem;
    }
    .section-sub { font-size: .9rem; color: var(--muted); margin-bottom: 36px; }

    .steps-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }
    .step-card {
      background: var(--white);
      border-radius: 16px; padding: 28px 24px;
      border: 1px solid var(--border);
      box-shadow: var(--card-sh);
      position: relative; overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }
    .step-card:hover { transform: translateY(-4px); box-shadow: 0 10px 32px rgba(46,90,48,.14); }
    .step-num {
      font-family: 'Playfair Display', serif;
      font-size: 3.5rem; font-weight: 800;
      color: rgba(78,122,81,.08);
      position: absolute; top: 10px; right: 16px;
      line-height: 1;
    }
    .step-icon {
      width: 48px; height: 48px;
      background: var(--bg); border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; margin-bottom: 14px;
    }
    .step-title { font-size: 1rem; font-weight: 700; color: var(--green); margin-bottom: 6px; }
    .step-desc { font-size: .84rem; color: var(--muted); line-height: 1.6; }

 /* ── TIPS SECTION ── */
    .tips-section {
      margin-top: 48px;
      background: var(--white);
      border-radius: 20px;
      padding: 36px 40px;
      border: 1px solid var(--border);
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 32px;
    }
    @media (max-width: 640px) { .tips-section { grid-template-columns: 1fr; } }
    .tips-col h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem; font-weight: 700; color: var(--green);
      margin-bottom: 16px;
      display: flex; align-items: center; gap: 8px;
    }
    .tip-item {
      display: flex; align-items: flex-start; gap: 10px;
      margin-bottom: 12px;
    }
    .tip-dot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--teal-dk); flex-shrink: 0; margin-top: 6px;
    }
    .tip-text { font-size: .86rem; color: var(--muted); line-height: 1.55; }

    /* ── UNKNOWN ANIMAL ERROR ── */
    .unknown-banner {
      background: #fef2f2;
      border: 2px solid #fca5a5;
      border-radius: 16px;
      padding: 32px;
      text-align: center;
      margin-bottom: 24px;
      animation: fadeUp .4s ease both;
    }
    .unknown-banner .unk-icon { font-size: 3.5rem; display: block; margin-bottom: 12px; }
    .unknown-banner h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem; color: #991b1b; margin-bottom: 8px;
    }
    .unknown-banner p { font-size: .9rem; color: #7f1d1d; line-height: 1.6; max-width: 480px; margin: 0 auto 20px; }
    .unknown-tips {
      display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 20px;
    }
    .unknown-tip {
      background: #fff; border: 1px solid #fca5a5; border-radius: 20px;
      padding: .35rem .9rem; font-size: .8rem; color: #991b1b;
    }

    /* ── RICH ANIMAL DETAIL CARD ── */
    .animal-detail-card {
      background: var(--white);
      border-radius: 20px;
      border: 2px solid #b7ddb5;
      overflow: hidden;
      margin-bottom: 24px;
      box-shadow: 0 8px 32px rgba(46,90,48,.12);
    }
    .adc-header {
      background: linear-gradient(135deg, var(--nav) 0%, var(--green-md) 100%);
      padding: 28px 32px;
      display: flex; align-items: center; gap: 20px;
    }
    .adc-emoji {
      width: 72px; height: 72px;
      background: rgba(255,255,255,.18); border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.4rem; flex-shrink: 0;
      border: 2px solid rgba(255,255,255,.3);
    }
    .adc-title { color: #fff; }
    .adc-found { font-size: .72rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: 4px; }
    .adc-name { font-family: 'Playfair Display', serif; font-size: 1.9rem; font-weight: 700; line-height: 1.1; }
    .adc-species { font-size: .9rem; font-style: italic; color: rgba(255,255,255,.8); margin-top: 3px; }
    .adc-body { padding: 28px 32px; }

    /* Info grid */
    .adc-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 12px; margin-bottom: 24px;
    }
    .adc-chip {
      background: #f4faf4; border: 1px solid var(--border);
      border-radius: 12px; padding: 14px 16px;
      transition: transform .15s, box-shadow .15s;
    }
    .adc-chip:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(46,90,48,.1); }
    .adc-chip-label {
      font-size: .66rem; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--muted); margin-bottom: 5px;
    }
    .adc-chip-val { font-size: .94rem; font-weight: 600; color: var(--green); }

    /* Fun fact block */
    .adc-fact {
      background: linear-gradient(135deg, #e8f5e8, #f1faf1);
      border: 1px solid #b7ddb5; border-radius: 12px;
      padding: 16px 20px; margin-bottom: 20px;
      display: flex; align-items: flex-start; gap: 12px;
    }
    .adc-fact-icon { font-size: 1.4rem; flex-shrink: 0; }
    .adc-fact-text { font-size: .88rem; color: var(--green); line-height: 1.6; }
    .adc-fact-text strong { color: var(--green); font-weight: 700; }

    /* Conservation status banner */
    .adc-conservation {
      border-radius: 12px; padding: 16px 20px;
      display: flex; align-items: center; gap: 14px; margin-bottom: 20px;
    }
    .adc-conservation.cr { background: #fde8e8; border: 1px solid #fca5a5; }
    .adc-conservation.en { background: #fef3c7; border: 1px solid #fcd34d; }
    .adc-conservation.vu { background: #fff3e0; border: 1px solid #fdba74; }
    .adc-conservation.lc { background: #d1fae5; border: 1px solid #6ee7b7; }
    .cons-icon { font-size: 1.6rem; flex-shrink: 0; }
    .cons-body h5 { font-size: .88rem; font-weight: 700; margin-bottom: 2px; }
    .cons-body p  { font-size: .8rem; line-height: 1.5; }
    .adc-conservation.cr .cons-body h5, .adc-conservation.cr .cons-body p { color: #991b1b; }
    .adc-conservation.en .cons-body h5, .adc-conservation.en .cons-body p { color: #92400e; }
    .adc-conservation.vu .cons-body h5, .adc-conservation.vu .cons-body p { color: #b45309; }
    .adc-conservation.lc .cons-body h5, .adc-conservation.lc .cons-body p { color: #065f46; }
    .btn-profile{flex:1;min-width:160px;background:var(--nav);color:#fff;border:none;border-radius:30px;padding:.75rem 1.5rem;font-family:'DM Sans',sans-serif;font-size:.9rem;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:.4rem;transition:all .2s;box-shadow:0 4px 14px rgba(46,90,48,.25)}
    .btn-profile:hover { background: var(--green); transform: translateY(-1px); }
    .btn-share {
      background: #f4faf4; color: var(--green);
      border: 2px solid var(--border); border-radius: 30px;
      padding: .75rem 1.5rem; font-family: 'DM Sans', sans-serif;
      font-size: .9rem; font-weight: 600; cursor: pointer;
      display: inline-flex; align-items: center; gap: .4rem;
      transition: all .2s;
    }
    .btn-share:hover { border-color: var(--nav); background: #eaf5ea; }

    /* ── MODEL NOT LOADED WARNING ── */
    .model-status {
      text-align: center; padding: 12px 20px;
      border-radius: 10px; font-size: .84rem; font-weight: 500;
      margin-bottom: 20px; display: none;
    }
    .model-status.loading { display: block; background: #e8f4fd; color: #1a6a9a; border: 1px solid #b3d8f0; }
    .model-status.ready   { display: block; background: #eafaf0; color: #1a6a3a; border: 1px solid #b0e0c0; }
    .model-status.error   { display: block; background: #fdeaea; color: #9a1a1a; border: 1px solid #f0b0b0; }

  </style>
</head>
<body>
<?php include 'nav.php'; 
$currentPage = 'visit';?>

<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="https://images.unsplash.com/photo-1605092676920-8ac5ae40c7c8?q=80&w=1600&auto=format&fit=crop" alt="Wildlife"/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Animal<br/><em>Recognition</em></h1>
    <p class="hero-sub">Spotted an animal in the park? Upload a photo and our AI will identify the species instantly — and tell you everything about it.</p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>
<!-- ── PAGE BODY ── -->
<div class="page-body">

  <!-- Model status -->
  <div class="model-status loading" id="model-status">
    ⏳ Loading AI recognition model… please wait a moment.
  </div>

  <!-- Upload section -->
  <div class="upload-section">

    <!-- Upload from file -->
    <div class="upload-card" id="upload-drop" onclick="document.getElementById('file-input').click()">
      <input type="file" id="file-input" accept="image/*" style="display:none"/>

      <div id="upload-placeholder">
        <div class="upload-icon">📷</div>
        <div class="upload-title">Upload a Photo</div>
        <div class="upload-sub">Drag &amp; drop your image here, or click to browse from your device. Best results with clear, close-up shots.</div>
        <button class="upload-btn" type="button">Choose Photo</button>
        <div class="upload-accept">JPG, PNG, WEBP — max 10 MB</div>
      </div>

      <div class="preview-wrap" id="preview-wrap">
        <img id="preview-img" class="preview-img" src="" alt="Preview"/>
        <button class="preview-clear" id="preview-clear" title="Remove">✕</button>
        <div style="font-size:.84rem;color:var(--muted);">Photo ready — click <strong>Identify Animal</strong> below</div>
      </div>
    </div>

  </div>

  <!-- Identify button -->
  <div class="identify-wrap">
    <button id="btn-identify" type="button">
      <span class="id-spinner"></span>
      <span class="id-text">🔍 Identify Animal</span>
    </button>
  </div>

  <!-- DEBUG PANEL — shows raw model output, remove after testing -->
  <div id="debug-panel" style="display:none;background:#fffbea;border:2px solid #f0d060;border-radius:12px;padding:16px 20px;margin-bottom:20px;font-family:monospace;font-size:.85rem;line-height:1.6;"></div>

  <!-- Result section -->
  <div id="result-section">

    <!-- ❌ UNKNOWN ANIMAL ERROR (shown when image doesn't match any zoo animal) -->
    <div class="unknown-banner" id="unknown-banner" style="display:none;">
      <span class="unk-icon">🔍</span>
      <h3>Animal Not Recognised</h3>
      <p>This photo doesn't match any of the animals in WildTrack Malaysia's collection, or the image isn't clear enough. Please try one of the following:</p>
      <div class="unknown-tips">
        <span class="unknown-tip">📸 Take a clearer, closer photo</span>
        <span class="unknown-tip">☀️ Improve the lighting</span>
        <span class="unknown-tip">🎯 Centre the animal in frame</span>
        <span class="unknown-tip">🚫 Avoid photos through glass or fencing</span>
      </div>
    </div>

    <!-- ⚠️ LOW CONFIDENCE WARNING -->
    <div class="low-conf-banner" id="low-conf-banner" style="display:none;">
      <span>⚠️</span>
      <p>The AI matched an animal but isn't very confident. The result below may not be accurate — try a clearer, better-lit photo for a more reliable result.</p>
    </div>

    <!-- CONFIDENCE + PREDICTIONS CARD -->
    <div class="result-card" id="result-card">
      <div class="result-header">
        <div class="result-icon" id="result-icon">🦁</div>
        <div>
          <div class="result-label">Identified Animal</div>
          <div class="result-animal-name" id="result-name">—</div>
          <div class="result-species" id="result-species"></div>
        </div>
      </div>
      <div class="confidence-section">
        <div class="conf-row">
          <span class="conf-label">Confidence</span>
          <span class="conf-pct" id="conf-pct">0%</span>
        </div>
        <div class="conf-bar-bg">
          <div class="conf-bar-fill" id="conf-bar"></div>
        </div>
        <div class="conf-note" id="conf-note"></div>
      </div>
      <div id="predictions-wrap">
        <div class="predictions-title">All Predictions</div>
        <div id="predictions-list"></div>
      </div>
    </div>

    <!-- 🟢 RICH ANIMAL DETAIL CARD (shown when matched in DB with confidence ≥ 60%) -->
    <div class="animal-detail-card" id="animal-detail" style="display:none;">
      <div class="adc-header">
        <div class="adc-emoji" id="adc-emoji">🦁</div>
        <div class="adc-title">
          <div class="adc-found">Found in WildTrack Malaysia</div>
          <div class="adc-name" id="adc-name">—</div>
          <div class="adc-species" id="adc-species">—</div>
        </div>
      </div>
      <div class="adc-body">
        <!-- Info chips -->
        <div class="adc-grid" id="adc-grid"></div>
        <!-- Fun fact -->
        <div class="adc-fact" id="adc-fact"></div>
        <!-- Conservation status -->
        <div class="adc-conservation" id="adc-conservation">
          <span class="cons-icon" id="cons-icon"></span>
          <div class="cons-body">
            <h5 id="cons-title"></h5>
            <p id="cons-desc"></p>
          </div>
        </div>
        <!-- Actions -->
        <div class="adc-actions">
          <a href="#" id="adc-profile-link" class="btn-profile">📖 View Full Animal Profile</a>
          <button class="btn-share" onclick="shareResult()">🔗 Share Discovery</button>
        </div>
      </div>
    </div>

    <!-- Try again (always shown at bottom of result) -->
    <div style="text-align:center;margin-top:24px;" id="try-again-row">
      <button class="try-again-btn" onclick="resetAll()">↺ Try Another Photo</button>
    </div>
  </div>

  <!-- How it works -->
  <div class="how-section">
    <h2 class="section-heading">How It Works</h2>
    <p class="section-sub">Our AI model is trained on photos of animals in WildTrack Malaysia. Here's the process:</p>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">1</div>
        <div class="step-icon">📷</div>
        <div class="step-title">Upload or capture</div>
        <div class="step-desc">Choose a photo from your gallery .</div>
      </div>
      <div class="step-card">
        <div class="step-num">2</div>
        <div class="step-icon">🧠</div>
        <div class="step-title">AI analyses</div>
        <div class="step-desc">The model runs entirely in your browser — no data is sent to a server.</div>
      </div>
      <div class="step-card">
        <div class="step-num">3</div>
        <div class="step-icon">🎯</div>
        <div class="step-title">Result returned</div>
        <div class="step-desc">The top predicted species is shown with a confidence score.</div>
      </div>
      <div class="step-card">
        <div class="step-num">4</div>
        <div class="step-icon">📖</div>
        <div class="step-title">Learn more</div>
        <div class="step-desc">If matched to a zoo animal, view its full profile, habitat, and conservation status.</div>
      </div>
    </div>
  </div>

  <!-- Tips -->
  <div class="tips-section">
    <div class="tips-col">
      <h3>✅ For best results</h3>
      <div class="tip-item"><div class="tip-dot"></div><p class="tip-text">Get as close to the animal as safely possible</p></div>
      <div class="tip-item"><div class="tip-dot"></div><p class="tip-text">Ensure the animal is the main subject in the frame</p></div>
      <div class="tip-item"><div class="tip-dot"></div><p class="tip-text">Use good lighting — avoid harsh shadows or backlight</p></div>
      <div class="tip-item"><div class="tip-dot"></div><p class="tip-text">Take the photo when the animal is still</p></div>
      <div class="tip-item"><div class="tip-dot"></div><p class="tip-text">Portrait orientation works slightly better</p></div>
    </div>
    <div class="tips-col">
      <h3>❌ Common issues</h3>
      <div class="tip-item"><div class="tip-dot" style="background:#d94f3d;"></div><p class="tip-text">Blurry or out-of-focus photos will reduce accuracy</p></div>
      <div class="tip-item"><div class="tip-dot" style="background:#d94f3d;"></div><p class="tip-text">Photos taken through glass or fencing confuse the model</p></div>
      <div class="tip-item"><div class="tip-dot" style="background:#d94f3d;"></div><p class="tip-text">Very dark or overexposed images lower confidence</p></div>
      <div class="tip-item"><div class="tip-dot" style="background:#d94f3d;"></div><p class="tip-text">Multiple animals in frame may cause incorrect results</p></div>
      <div class="tip-item"><div class="tip-dot" style="background:#d94f3d;"></div><p class="tip-text">Animals outside WildTrack's collection won't be matched</p></div>
    </div>
  </div>
</div>
</div> 
</div>

<?php include 'footer.php'; ?>

<!-- TensorFlow.js + Teachable Machine -->
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>

<script>
// ─────────────────────────────────────────
//  CONFIG — update MODEL_PATH after you
//  export from Teachable Machine
// ─────────────────────────────────────────
const MODEL_PATH = './model/'; // folder containing model.json + metadata.json

// Zoo animals DB — names must exactly match your Teachable Machine class labels
const ZOO_ANIMALS = {
  'Gorilla': {
    emoji: '🦍', species: 'Gorilla gorilla',
    habitat: 'Tropical Rainforest', diet: 'Herbivore',
    status: 'Critically Endangered', statusKey: 'cr',
    location: 'Gorilla Forest', zone: 'Africa Zone',
    weight: '100–200 kg', lifespan: 'Up to 40 years',
    fact: 'Gorillas share <strong>98.3% of their DNA</strong> with humans, making them our closest relatives after chimpanzees. They can learn sign language and use simple tools.',
    consDesc: 'Critically endangered due to habitat destruction, poaching, and disease. Fewer than 300,000 western gorillas remain in the wild.',
    profile: 'gorilla.php'
  },
  'Elephant': {
    emoji: '🐘', species: 'Elephas maximus',
    habitat: 'Grassland & Forest', diet: 'Herbivore',
    status: 'Endangered', statusKey: 'en',
    location: 'Elephant Savannah', zone: 'Africa Zone',
    weight: '2,700–5,000 kg', lifespan: 'Up to 70 years',
    fact: 'Elephants have the <strong>largest brain of any land animal</strong> and are one of the few species that can recognise themselves in a mirror. They mourn their dead.',
    consDesc: 'Asian elephants are endangered; African forest elephants are critically endangered. Habitat loss and ivory poaching are the primary threats.',
    profile: 'elephant.php'
  },
  'Lion': {
    emoji: '🦁', species: 'Panthera leo',
    habitat: 'Savannah', diet: 'Carnivore',
    status: 'Vulnerable', statusKey: 'vu',
    location: 'Savannah Enclosure', zone: 'Africa Zone',
    weight: '120–190 kg', lifespan: '10–14 years (wild)',
    fact: 'Lions are the only truly social wild cats — they live in groups called <strong>prides</strong> of up to 30 individuals. The male\'s roar can be heard up to 8 km away.',
    consDesc: 'Lion populations have declined by over 40% in the last three generations due to habitat loss and conflict with humans.',
    profile: 'lion.php'
  },
  'Penguin': {
    emoji: '🐧', species: 'Spheniscidae (family)',
    habitat: 'Coastal & Marine', diet: 'Carnivore',
    status: 'Varies by species', statusKey: 'vu',
    location: 'Penguin Cove', zone: 'Polar Zone',
    weight: '1–40 kg', lifespan: '15–20 years',
    fact: 'Penguins are <strong>flightless birds</strong> that are exceptional swimmers — they can reach speeds of 25 km/h underwater. They use their wings as flippers.',
    consDesc: 'Several penguin species are threatened by climate change reducing sea ice, overfishing depleting food sources, and oil spills.',
    profile: 'penguin.php'
  },
  'Panda': {
    emoji: '🐼', species: 'Ailuropoda melanoleuca',
    habitat: 'Temperate Forest', diet: 'Herbivore',
    status: 'Vulnerable', statusKey: 'vu',
    location: 'Panda Bamboo Grove', zone: 'Asia Zone',
    weight: '70–125 kg', lifespan: 'Up to 20 years (wild)',
    fact: 'Giant pandas eat up to <strong>14 kg of bamboo per day</strong> and spend 10–16 hours eating. Despite being carnivores by classification, 99% of their diet is bamboo.',
    consDesc: 'Once critically endangered, conservation efforts have helped recover wild populations to around 1,800. Still listed as Vulnerable due to habitat fragmentation.',
    profile: 'panda.php'
  },
  // 'other' is intentionally NOT in ZOO_ANIMALS — triggers the unknown banner
};

const CONFIDENCE_THRESHOLD = 0.85;

// ── State ──
let model = null;
let currentImage = null;

// ── DOM refs ──
const modelStatus   = document.getElementById('model-status');
const fileInput     = document.getElementById('file-input');
const previewWrap   = document.getElementById('preview-wrap');
const previewImg    = document.getElementById('preview-img');
const previewClear  = document.getElementById('preview-clear');
const uploadPlaceholder = document.getElementById('upload-placeholder');
const btnIdentify   = document.getElementById('btn-identify');
const resultSection = document.getElementById('result-section');
const lowConfBanner = document.getElementById('low-conf-banner');
const resultCard    = document.getElementById('result-card');

// ── Load model ──
async function loadModel() {
  modelStatus.className = 'model-status loading';
  modelStatus.style.display = 'block';
  modelStatus.textContent = '⏳ Loading AI recognition model… please wait.';
  try {
    model = await tmImage.load(MODEL_PATH + 'model.json', MODEL_PATH + 'metadata.json');
    modelStatus.className = 'model-status ready';
    modelStatus.textContent = '✅ AI model loaded and ready!';
    setTimeout(() => { modelStatus.style.display = 'none'; }, 3000);
  } catch(e) {
    modelStatus.className = 'model-status error';
    modelStatus.textContent = '⚠️ Could not load AI model. Make sure the model/ folder exists with model.json and metadata.json.';
    console.error('Model load error:', e);
  }
}

// ── File input ──
fileInput.addEventListener('change', (e) => {
  if (e.target.files[0]) setImageFromFile(e.target.files[0]);
});

function setImageFromFile(file) {
  const url = URL.createObjectURL(file);
  previewImg.src = url;
  previewImg.onload = () => {
    // Draw to an offscreen canvas so TF reads clean pixels regardless of layout
    const canvas = document.createElement('canvas');
    canvas.width  = 224;
    canvas.height = 224;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(previewImg, 0, 0, 224, 224);
    currentImage = canvas;   // pass canvas, not the <img> element
    showPreview();
  };
}

function showPreview() {
  uploadPlaceholder.style.display = 'none';
  previewWrap.classList.add('show');
  btnIdentify.classList.add('ready');
  resultSection.classList.remove('show');
}

previewClear.addEventListener('click', (e) => {
  e.stopPropagation();
  resetUpload();
});

function resetUpload() {
  fileInput.value = '';
  previewWrap.classList.remove('show');
  uploadPlaceholder.style.display = '';
  currentImage = null;
  btnIdentify.classList.remove('ready');
}

// ── Drag and drop ──
const dropZone = document.getElementById('upload-drop');
dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  dropZone.classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) setImageFromFile(file);
});



// ── Identify ──
btnIdentify.addEventListener('click', async () => {
  if (!currentImage || !model) {
    if (!model) alert('AI model not loaded yet. Please wait or check the model/ folder.');
    return;
  }
  btnIdentify.classList.add('loading');
  btnIdentify.querySelector('.id-text').textContent = 'Analysing';

  try {
    const predictions = await model.predict(currentImage);
    predictions.sort((a, b) => b.probability - a.probability);
    showResult(predictions);
  } catch(e) {
    alert('Recognition failed. Please try a different image.');
    console.error(e);
  } finally {
    btnIdentify.classList.remove('loading');
    btnIdentify.querySelector('.id-text').textContent = '🔍 Identify Animal';
  }
});

function showResult(predictions) {
  const top    = predictions[0];
  const pct    = Math.round(top.probability * 100);
  const isLow  = top.probability < CONFIDENCE_THRESHOLD;
  const isOther = top.className === 'Other' || top.className.toLowerCase() === 'other';
  const animal  = isOther ? null : (ZOO_ANIMALS[top.className] || ZOO_ANIMALS[top.className.charAt(0).toUpperCase() + top.className.slice(1).toLowerCase()]);
  console.log('Model returned:', top.className, '| Confidence:', Math.round(top.probability*100)+'%', '| Matched:', !!animal);


  document.getElementById('unknown-banner').style.display  = 'none';
  document.getElementById('low-conf-banner').style.display = 'none';
  document.getElementById('result-card').style.display     = 'none';
  document.getElementById('animal-detail').style.display   = 'none';
  document.getElementById('try-again-row').style.display   = 'none';

  // Treat as unknown if: no match found, OR confidence is low, OR "Other" class won
  if (!animal || isLow || isOther) {
    document.getElementById('unknown-banner').style.display = 'block';
    document.getElementById('try-again-row').style.display  = 'block';
    resultSection.classList.add('show');
    resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    logToServer(top.className, top.probability);
    return;
  }

document.getElementById('result-card').style.display   = 'block';
document.getElementById('try-again-row').style.display = 'block';

  document.getElementById('result-icon').textContent    = animal.emoji;
  document.getElementById('result-name').textContent    = top.className;
  document.getElementById('result-species').textContent = animal.species;
  document.getElementById('conf-pct').textContent       = pct + '%';
  const bar = document.getElementById('conf-bar');
  bar.style.width = '0%';
  setTimeout(() => { bar.style.width = pct + '%'; }, 50);
  bar.style.background =
    pct >= 80 ? 'linear-gradient(90deg,#4eb8a8,#5a9e52)' :
    pct >= 60 ? 'linear-gradient(90deg,#e8a020,#e8c040)' :
                'linear-gradient(90deg,#d94f3d,#e87a70)';
  document.getElementById('conf-note').textContent =
    pct >= 80 ? '✅ High confidence — result is reliable.' :
    pct >= 60 ? '⚠️ Moderate confidence — result is likely correct.' :
                '❌ Low confidence — try a clearer photo.';

  const list = document.getElementById('predictions-list');
  list.innerHTML = '';
  predictions.slice(0, 5).forEach((p, i) => {
    const pPct = Math.round(p.probability * 100);
    const row  = document.createElement('div');
    row.className = 'pred-row';
    row.innerHTML = `<div class="pred-rank ${i===0?'top':''}">${i+1}</div>
      <div class="pred-name">${p.className}</div>
      <div class="pred-mini-bar-bg"><div class="pred-mini-fill" style="width:0%" data-w="${pPct}"></div></div>
      <div class="pred-pct">${pPct}%</div>`;
    list.appendChild(row);
  });
  setTimeout(() => {
    list.querySelectorAll('.pred-mini-fill').forEach(el => { el.style.width = el.dataset.w + '%'; });
  }, 80);

  if (!isLow) {
    buildAnimalDetailCard(top.className, animal);
    document.getElementById('animal-detail').style.display = 'block';
  }

  resultSection.classList.add('show');
  resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
  logToServer(top.className, top.probability);
}

function buildAnimalDetailCard(name, a) {
  document.getElementById('adc-emoji').textContent   = a.emoji;
  document.getElementById('adc-name').textContent    = name;
  document.getElementById('adc-species').textContent = a.species;
  document.getElementById('adc-profile-link').href   = a.profile || 'animalMain.php';

  document.getElementById('adc-grid').innerHTML = `
    <div class="adc-chip"><div class="adc-chip-label">Habitat</div><div class="adc-chip-val">${a.habitat}</div></div>
    <div class="adc-chip"><div class="adc-chip-label">Diet</div><div class="adc-chip-val">${a.diet}</div></div>
    <div class="adc-chip"><div class="adc-chip-label">Weight</div><div class="adc-chip-val">${a.weight}</div></div>
    <div class="adc-chip"><div class="adc-chip-label">Lifespan</div><div class="adc-chip-val">${a.lifespan}</div></div>
    <div class="adc-chip"><div class="adc-chip-label">Location in Zoo</div><div class="adc-chip-val">${a.location}</div></div>
    <div class="adc-chip"><div class="adc-chip-label">Zone</div><div class="adc-chip-val">${a.zone}</div></div>`;

  document.getElementById('adc-fact').innerHTML =
    `<span class="adc-fact-icon">💡</span><div class="adc-fact-text"><strong>Did you know?</strong> ${a.fact}</div>`;

  const consIcons  = { cr:'🔴', en:'🟠', vu:'🟡', lc:'🟢' };
  const consTitles = { cr:'Critically Endangered', en:'Endangered', vu:'Vulnerable', lc:'Least Concern' };
  const consEl = document.getElementById('adc-conservation');
  consEl.className = 'adc-conservation ' + a.statusKey;
  document.getElementById('cons-icon').textContent   = consIcons[a.statusKey]  || '⚪';
  document.getElementById('cons-title').textContent  = consTitles[a.statusKey] || a.status;
  document.getElementById('cons-desc').textContent   = a.consDesc;
}

function shareResult() {
  const name    = document.getElementById('adc-name').textContent;
  const species = document.getElementById('adc-species').textContent;
  const status  = document.getElementById('cons-title').textContent;
  const animal  = ZOO_ANIMALS[name] || ZOO_ANIMALS[name.charAt(0).toUpperCase() + name.slice(1).toLowerCase()];
  const factRaw = animal ? animal.fact.replace(/<[^>]+>/g, '') : ''; // strip HTML tags

  const text = `🐾 I just spotted a ${name} at WildTrack Malaysia!\n\n` +
               `🔬 Species: ${species}\n` +
               `🌿 Conservation Status: ${status}\n` +
               (factRaw ? `💡 Fun fact: ${factRaw}\n\n` : '\n') +
               `📍 Visit WildTrack Malaysia to see it in person!\n` +
               window.location.href;

  if (navigator.share) {
    navigator.share({
      title: `I found a ${name} at WildTrack Malaysia! 🐾`,
      text: text,
    });
  } else {
    navigator.clipboard.writeText(text)
      .then(() => {
        const btn = document.querySelector('.btn-share');
        const orig = btn.innerHTML;
        btn.innerHTML = '✅ Copied!';
        btn.style.borderColor = 'var(--teal-dk)';
        setTimeout(() => { btn.innerHTML = orig; btn.style.borderColor = ''; }, 2000);
      });
  }
}


async function logToServer(animalName, confidence) {
  try {
    await fetch('api/recognize.php', {
      method: 'POST', credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ animal_name: animalName, confidence: confidence })
    });
  } catch(e) {}
}

function resetAll() {
  resetUpload();
  resultSection.classList.remove('show');
  document.getElementById('unknown-banner').style.display  = 'none';
  document.getElementById('low-conf-banner').style.display = 'none';
  document.getElementById('result-card').style.display     = 'none';
  document.getElementById('animal-detail').style.display   = 'none';
  document.getElementById('try-again-row').style.display   = 'none';
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── Breadcrumb ──
document.addEventListener('DOMContentLoaded', function() {
  var trail = document.getElementById('breadcrumb-trail');
  if (trail) {
    trail.innerHTML =
      '<a href="mainPage.php">Home</a>' +
      '<span class="sep">›</span>' +
      '<a href="animalMain.php">Animal</a>' +
      '<span class="sep">›</span>' +
      '<span>Animal Recognition</span>';
  }
});

// ── Nav dropdowns ──
document.querySelectorAll('.dropdown').forEach(dd => {
  const btn = dd.querySelector('.dropbutton') || dd.querySelector('a');
  const menu = dd.querySelector('.dropdown-menu');
  if (!btn || !menu) return;
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    const open = menu.classList.contains('active');
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
    if (!open) menu.classList.add('active');
  });
});
document.addEventListener('click', (e) => {
  if (!e.target.closest('.dropdown'))
    document.querySelectorAll('.dropdown-menu.active').forEach(m => m.classList.remove('active'));
});

// ── Init ──
loadModel();

</script>
</body>
</html>
