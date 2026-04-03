<?php
require_once __DIR__ . '/check_session.php';
$currentPage     = 'contact';
$visitorLoggedIn = isVisitor();

// Pre-fill from session — the session stores 'username' (set by auth.php)
$sessionName  = '';
$sessionEmail = '';
if ($visitorLoggedIn) {
    $u = $_SESSION['user'];
    // Try full name fields first, fall back to username
    $first = trim($u['first_name'] ?? $u['firstName'] ?? '');
    $last  = trim($u['last_name']  ?? $u['lastName']  ?? '');
    $sessionName  = htmlspecialchars($first && $last ? "$first $last" : ($u['username'] ?? 'You'));
    $sessionEmail = htmlspecialchars($u['email'] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="hero.css"/>
  <link rel="stylesheet" href="shared.css">
  <title>Get in Touch — WildTrack Zoo</title>
  <style>
    .page-img { width:100%; max-height:420px; object-fit:cover; display:block; }

    .git-intro { max-width:700px; margin:0 auto 48px; text-align:center; }
    .git-intro h1 { color:#2a5a2e; font-size:38px; margin-bottom:12px; }
    .git-intro p  { font-size:17px; color:#666; line-height:1.8; }

    .git-layout { display:flex; gap:40px; align-items:flex-start; flex-wrap:wrap; margin-bottom:56px; }
    .git-left  { flex:1; min-width:280px; }
    .git-right { flex:1; min-width:280px; }

    .contact-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08); padding:24px 26px; margin-bottom:18px; display:flex; gap:18px; align-items:flex-start; }
    .contact-card-icon { font-size:32px; flex-shrink:0; margin-top:2px; }
    .contact-card h3 { font-size:16px; color:#2a5a2e; margin:0 0 6px; font-weight:700; }
    .contact-card p  { font-size:14px; color:#555; margin:0 0 4px; line-height:1.6; }
    .contact-card a  { font-size:14px; color:#4caf50; text-decoration:none; word-break:break-all; }
    .contact-card a:hover { text-decoration:underline; }

    .contact-skeleton { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08); padding:24px 26px; margin-bottom:18px; display:flex; gap:18px; align-items:flex-start; }
    .skel-circle { width:46px;height:46px;border-radius:50%;background:#e8f5e9;flex-shrink:0;animation:skel 1.2s ease infinite; }
    .skel-line   { height:14px;border-radius:6px;background:#e8f5e9;margin-bottom:8px;animation:skel 1.2s ease infinite; }
    @keyframes skel { 0%,100%{opacity:.5} 50%{opacity:1} }

    .feedback-card { background:#fff; border-radius:18px; box-shadow:0 2px 16px rgba(0,0,0,0.09); padding:36px 38px; }
    .feedback-card h2 { color:#2a5a2e; font-size:24px; margin-bottom:24px; }

    /* Logged-in user identity strip */
    .user-info-strip {
      display: flex;
      align-items: center;
      gap: 12px;
      background: #f0f7ef;
      border: 1.5px solid #a5d6a7;
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 20px;
    }
    .user-info-strip .ustrip-avatar {
      width: 40px; height: 40px; border-radius: 50%;
      background: #2a5a2e; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; font-weight: 700; flex-shrink: 0;
    }
    .user-info-strip .ustrip-detail strong {
      display: block; font-size: 14px; font-weight: 700; color: #2a5a2e;
    }
    .user-info-strip .ustrip-detail span {
      font-size: 13px; color: #666;
    }

    .form-group { margin-bottom:20px; position:relative; }
    .form-group label { display:block; margin-bottom:7px; font-weight:600; font-size:14px; color:#333; }
    .form-group input,
    .form-group textarea { width:100%; padding:11px 14px; border:1.5px solid #ddd; border-radius:9px; font-size:15px; transition:border-color .2s,box-shadow .2s; box-sizing:border-box; font-family:inherit; background:#fafafa; }
    .form-group input:focus,
    .form-group textarea:focus { outline:none; border-color:#4caf50; box-shadow:0 0 0 3px rgba(76,175,80,0.12); background:#fff; }
    .form-group input.error,
    .form-group textarea.error { border-color:#e53935; box-shadow:0 0 0 3px rgba(229,57,53,0.10); }
    .form-group textarea { resize:vertical; min-height:110px; }

    .field-error { display:none; color:#e53935; font-size:12px; font-weight:600; margin-top:5px; align-items:center; gap:4px; }
    .field-error.show { display:flex; }
    .field-error::before { content:'⚠'; font-size:13px; }

    .rating-label { display:block; margin-bottom:10px; font-weight:600; font-size:14px; color:#333; }
    .rating { display:flex; flex-direction:row-reverse; justify-content:flex-end; gap:4px; margin-bottom:6px; }
    .rating input { display:none; }
    .rating label { font-size:2rem; color:#ddd; cursor:pointer; transition:color .2s,transform .2s; padding:2px; }
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label { color:#ffc107; }
    .rating label:hover { transform:scale(1.2); }
    .rating-error { display:none; color:#e53935; font-size:12px; font-weight:600; margin-top:2px; align-items:center; gap:4px; }
    .rating-error.show { display:flex; }
    .rating-error::before { content:'⚠'; font-size:13px; }

    .btn-submit { width:100%; padding:14px; background:#2a5a2e; color:#fff; border:none; border-radius:10px; font-size:16px; font-weight:700; cursor:pointer; transition:background .2s,transform .15s; margin-top:6px; }
    .btn-submit:hover:not(:disabled) { background:#1e4222; transform:translateY(-1px); }
    .btn-submit:disabled { opacity:.7; cursor:not-allowed; }

    .success-box { display:none; text-align:center; padding:48px 20px; }
    .success-box .success-icon { font-size:64px; margin-bottom:16px; }
    .success-box h2 { color:#2a5a2e; font-size:26px; margin-bottom:10px; }
    .success-box p  { color:#666; font-size:16px; }

    .submit-error { display:none; background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; border-radius:9px; padding:12px 16px; font-size:14px; margin-bottom:16px; }
    .submit-error.show { display:block; }

    .my-feedback-section { max-width:860px; margin:0 auto 60px; }
    .my-feedback-section h2 { color:#2a5a2e; font-size:26px; margin-bottom:6px; }
    .my-feedback-section .section-sub { font-size:14px; color:#888; margin-bottom:24px; }

    .mf-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.08); padding:22px 26px; margin-bottom:16px; border-left:4px solid #e8f5e9; transition:box-shadow .2s; }
    .mf-card:hover { box-shadow:0 4px 20px rgba(0,0,0,0.12); }
    .mf-card.has-reply { border-left-color:#4caf50; }
    .mf-card.flagged   { border-left-color:#e53935; }

    .mf-top { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px; }
    .mf-stars { color:#ffc107; font-size:18px; }
    .mf-date  { font-size:12px; color:#999; }
    .mf-status { font-size:11px; font-weight:700; padding:3px 10px; border-radius:20px; }
    .mf-status.pending { background:#FEF3C7; color:#92400E; }
    .mf-status.replied { background:#e8f5e9; color:#2a5a2e; }
    .mf-status.flagged { background:#FEE2E2; color:#b91c1c; }

    .mf-message { font-size:15px; color:#444; line-height:1.7; margin:0 0 10px; }
    .mf-reply-box { background:#f0f7ef; border-left:3px solid #4caf50; border-radius:0 10px 10px 0; padding:12px 16px; margin-top:10px; }
    .mf-reply-box .reply-label { font-size:12px; font-weight:700; color:#2a5a2e; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; display:flex; align-items:center; gap:6px; }
    .mf-reply-box .reply-date  { font-size:11px; color:#888; font-weight:400; text-transform:none; letter-spacing:0; }
    .mf-reply-box p { font-size:14px; color:#3d5234; line-height:1.7; margin:0; }

    .mf-empty { text-align:center; padding:48px 20px; color:#aaa; font-size:15px; }
    .mf-empty .mf-empty-icon { font-size:48px; margin-bottom:12px; }

    .login-prompt { background:#f0f7ef; border:1.5px dashed #a5d6a7; border-radius:14px; padding:28px 24px; text-align:center; color:#555; font-size:15px; line-height:1.7; }
    .login-prompt a { color:#2a5a2e; font-weight:700; text-decoration:none; }
    .login-prompt a:hover { text-decoration:underline; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>
<!-- HERO -->
<section class="hero">
  <img class="hero-img" src="images/rabbit.avif" alt="Get in Touch — WildTrack Zoo">/>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <p class="hero-eyebrow">WildTrack Malaysia</p>
    <h1 class="hero-title">Get in<em> Touch</em></h1>
    <p class="hero-sub"> We Value Your Voice: Contact Us and Share Your Experience </p>
  </div>
  <div class="paw1 paw">🐾</div>
  <div class="paw2 paw">🐾</div>
  <div class="paw3 paw">🐾</div>
</section>
<div class="content-section">

  <div class="git-intro">
    <h1>Contact Us and Send Your Feedbacks</h1>
    <p>Our team receives a high volume of calls and emails and always does its best to
       respond as quickly as possible — thank you for your patience!</p>
  </div>

  <div class="git-layout">

    <!-- LEFT: Contact cards -->
    <div class="git-left" id="contactCardsContainer">
      <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="contact-skeleton">
        <div class="skel-circle"></div>
        <div style="flex:1;">
          <div class="skel-line" style="width:60%;"></div>
          <div class="skel-line" style="width:80%;"></div>
          <div class="skel-line" style="width:50%;"></div>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <!-- RIGHT: Feedback form -->
    <div class="git-right">
      <div class="feedback-card">
        <div id="formBox">
          <h2>💬 Customer Feedback</h2>

          <div class="submit-error" id="submitError"></div>

          <?php if ($visitorLoggedIn): ?>
          <!-- Logged-in: show avatar strip, no manual name/email entry -->
          <div class="user-info-strip">
            <div class="ustrip-avatar">
              <?= strtoupper(mb_substr($sessionName, 0, 1)) ?>
            </div>
            <div class="ustrip-detail">
              <strong><?= $sessionName ?></strong>
              <span><?= $sessionEmail ?></span>
            </div>
          </div>
          <!-- Hidden inputs carry the values to JS -->
          <input type="hidden" id="username" value="<?= $sessionName ?>">
          <input type="hidden" id="email"    value="<?= $sessionEmail ?>">

          <?php else: ?>
          <!-- Guest: show name & email fields -->
          <div class="form-group">
            <label for="username">Your Name <span style="color:#e53935">*</span></label>
            <input type="text" id="username" placeholder="Enter your name">
            <div class="field-error" id="err-name">Name is required</div>
          </div>
          <div class="form-group">
            <label for="email">Your Email <span style="color:#e53935">*</span></label>
            <input type="email" id="email" placeholder="Enter your email">
            <div class="field-error" id="err-email">A valid email address is required</div>
          </div>
          <?php endif; ?>

          <!-- Star rating -->
          <div class="form-group">
            <span class="rating-label">How would you rate our service? <span style="color:#e53935">*</span></span>
            <div class="rating">
              <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="Excellent">★</label>
              <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="Good">★</label>
              <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="Okay">★</label>
              <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="Poor">★</label>
              <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="Very poor">★</label>
            </div>
            <div style="font-size:12px;color:#888;margin-bottom:4px;">1 = Poor &nbsp;·&nbsp; 5 = Excellent</div>
            <div class="rating-error" id="err-rating">Please select a star rating</div>
          </div>

          <!-- Message -->
          <div class="form-group">
            <label for="experience">Your Message <span style="color:#e53935">*</span></label>
            <textarea id="experience" rows="5" placeholder="Tell us what you think..."></textarea>
            <div class="field-error" id="err-message">Message cannot be empty</div>
          </div>

          <button class="btn-submit" id="submitBtn" onclick="submitFeedback()">Submit Feedback</button>
        </div>

        <!-- Success state -->
        <div class="success-box" id="successBox">
          <div class="success-icon">🎉</div>
          <h2>Thank You!</h2>
          <p>Your feedback has been received. We'll get back to you soon!</p>
          <?php if (!$visitorLoggedIn): ?>
          <p style="margin-top:12px;font-size:14px;color:#888;">
            Want to track replies? <a href="login.html" style="color:#2a5a2e;font-weight:700;">Log in or create an account</a>.
          </p>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div><!-- end .git-layout -->

  <!-- My Feedback History -->
  <div class="my-feedback-section">
    <h2>📋 My Feedback History</h2>
    <p class="section-sub">View your past submissions and any replies from our team.</p>

    <?php if ($visitorLoggedIn): ?>
    <div id="myFeedbackList">
      <div class="mf-empty"><div class="mf-empty-icon">⏳</div>Loading your feedback…</div>
    </div>
    <?php else: ?>
    <div class="login-prompt">
      <div style="font-size:36px;margin-bottom:10px;">🔒</div>
      <strong>Want to track your feedback &amp; see admin replies?</strong><br>
      <a href="login.html?portal=visitor&tab=login">Log in</a> or <a href="login.html?portal=visitor&tab=signup">create an account</a>
      to view your past submissions and any replies from our team.
    </div>
    <?php endif; ?>
  </div>

</div><!-- end .content-section -->

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [{ label: 'Get in Touch' }];

  const visitorLoggedIn = <?= $visitorLoggedIn ? 'true' : 'false' ?>;

  /* ── Load contact cards ── */
  async function loadContactCards() {
    try {
      const res  = await fetch('api/contact_info.php?action=get_public');
      const data = await res.json();
      const container = document.getElementById('contactCardsContainer');
      if (!data.success || !data.contacts.length) {
        container.innerHTML = '<p style="color:#888;font-size:14px;">No contact information available.</p>';
        return;
      }
      container.innerHTML = data.contacts.map(c => `
        <div class="contact-card">
          <div class="contact-card-icon">${c.icon}</div>
          <div>
            <h3>${esc(c.department)}</h3>
            ${c.phone ? `<p>📞 ${esc(c.phone)}</p>` : ''}
            ${c.email ? `<a href="mailto:${esc(c.email)}">${esc(c.email)}</a>` : ''}
          </div>
        </div>
      `).join('');
    } catch (e) { console.error('Failed to load contact cards', e); }
  }

  function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
  }

  loadContactCards();

  /* ── Submit feedback ── */
  async function submitFeedback() {
    var valid = true;

    function validate(inputId, errorId, condition) {
      var inp = document.getElementById(inputId);
      var err = document.getElementById(errorId);
      if (!inp || inp.type === 'hidden') return; // hidden inputs always valid
      if (condition) { inp.classList.add('error'); err.classList.add('show'); valid = false; }
      else           { inp.classList.remove('error'); err.classList.remove('show'); }
    }

    var name     = document.getElementById('username').value.trim();
    var email    = document.getElementById('email').value.trim();
    var message  = document.getElementById('experience').value.trim();
    var ratingEl = document.querySelector('input[name="rating"]:checked');

    if (!visitorLoggedIn) {
      validate('username', 'err-name',  name === '');
      validate('email',    'err-email', email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email));
    }
    validate('experience', 'err-message', message === '');

    var ratingErr = document.getElementById('err-rating');
    if (!ratingEl) { ratingErr.classList.add('show'); valid = false; }
    else           { ratingErr.classList.remove('show'); }

    if (!valid) return;

    var btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting…';
    document.getElementById('submitError').classList.remove('show');

    try {
      var res  = await fetch('api/feedback.php?action=submit', {
        method: 'POST', credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, rating: parseInt(ratingEl.value), message }),
      });
      var data = await res.json();
      if (data.success) {
        document.getElementById('formBox').style.display    = 'none';
        document.getElementById('successBox').style.display = 'block';
        if (visitorLoggedIn) setTimeout(loadMyFeedback, 600);
      } else {
        var errBox = document.getElementById('submitError');
        errBox.textContent = data.message || 'Submission failed. Please try again.';
        errBox.classList.add('show');
        btn.disabled    = false;
        btn.textContent = 'Submit Feedback';
      }
    } catch (e) {
      document.getElementById('submitError').textContent = 'Network error. Please check your connection and try again.';
      document.getElementById('submitError').classList.add('show');
      btn.disabled    = false;
      btn.textContent = 'Submit Feedback';
    }
  }

  // Clear errors on input (guest fields only)
  ['username','email','experience'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el && el.type !== 'hidden') {
      el.addEventListener('input', function() {
        this.classList.remove('error');
        var errMap = { username:'err-name', email:'err-email', experience:'err-message' };
        var errEl = document.getElementById(errMap[id]);
        if (errEl) errEl.classList.remove('show');
      });
    }
  });
  document.querySelectorAll('input[name="rating"]').forEach(function(r) {
    r.addEventListener('change', function() { document.getElementById('err-rating').classList.remove('show'); });
  });

  /* ── My Feedback History (logged-in only) ── */
  <?php if ($visitorLoggedIn): ?>
  async function loadMyFeedback() {
    const container = document.getElementById('myFeedbackList');
    if (!container) return;
    try {
      const res  = await fetch('api/feedback.php?action=my_feedback', { credentials: 'include' });
      const data = await res.json();
      if (!data.success) {
        container.innerHTML = '<div class="mf-empty"><div class="mf-empty-icon">⚠️</div>Could not load your feedback.</div>';
        return;
      }
      const rows = data.feedback || [];
      if (!rows.length) {
        container.innerHTML = `<div class="mf-empty"><div class="mf-empty-icon">💬</div>You haven't submitted any feedback yet.<br><small style="font-size:13px;">Use the form above to share your experience!</small></div>`;
        return;
      }
      container.innerHTML = rows.map(fb => {
        const stars     = '★'.repeat(fb.rating) + '☆'.repeat(5 - fb.rating);
        const date      = new Date(fb.created_at).toLocaleDateString('en-MY', { day:'numeric', month:'short', year:'numeric' });
        const statusMap = { pending:'Pending Reply', replied:'Replied ✓', flagged:'Under Review' };
        const replyBox  = fb.admin_reply ? `
          <div class="mf-reply-box">
            <div class="reply-label">🐾 Zoo Team Reply
              <span class="reply-date">— ${new Date(fb.replied_at).toLocaleDateString('en-MY',{day:'numeric',month:'short',year:'numeric'})}</span>
            </div>
            <p>${esc(fb.admin_reply)}</p>
          </div>` : '';
        return `
          <div class="mf-card ${fb.admin_reply ? 'has-reply' : ''} ${fb.status === 'flagged' ? 'flagged' : ''}">
            <div class="mf-top">
              <div><span class="mf-stars">${stars}</span><span class="mf-date" style="margin-left:10px;">${date}</span></div>
              <span class="mf-status ${fb.status}">${statusMap[fb.status] || fb.status}</span>
            </div>
            <p class="mf-message">${esc(fb.message)}</p>
            ${replyBox}
          </div>`;
      }).join('');
    } catch (e) {
      const c = document.getElementById('myFeedbackList');
      if (c) c.innerHTML = '<div class="mf-empty"><div class="mf-empty-icon">⚠️</div>Network error. Please try again.</div>';
    }
  }
  loadMyFeedback();
  <?php endif; ?>
</script>
<script src="FinalProject.js"></script>
</body>
</html>
