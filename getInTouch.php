<?php
require_once __DIR__ . '/check_session.php';
$currentPage = 'contact'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="shared.css">
  <title>Get in Touch — WildTrack Zoo</title>
  <style>
    /* ── Hero ── */
    .page-img { width:100%; max-height:420px; object-fit:cover; display:block; }

    /* ── Intro ── */
    .git-intro {
      max-width: 700px;
      margin: 0 auto 48px;
      text-align: center;
    }
    .git-intro h1 { color: #2a5a2e; font-size: 38px; margin-bottom: 12px; }
    .git-intro p  { font-size: 17px; color: #666; line-height: 1.8; }

    /* ── Two-column layout ── */
    .git-layout {
      display: flex;
      gap: 40px;
      align-items: flex-start;
      flex-wrap: wrap;
      margin-bottom: 56px;
    }
    .git-left  { flex: 1; min-width: 280px; }
    .git-right { flex: 1; min-width: 280px; }

    /* ── Contact cards ── */
    .contact-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      padding: 24px 26px;
      margin-bottom: 18px;
      display: flex;
      gap: 18px;
      align-items: flex-start;
    }
    .contact-card-icon {
      font-size: 32px;
      flex-shrink: 0;
      margin-top: 2px;
    }
    .contact-card h3 {
      font-size: 16px;
      color: #2a5a2e;
      margin: 0 0 6px;
      font-weight: 700;
    }
    .contact-card p {
      font-size: 14px;
      color: #555;
      margin: 0 0 4px;
      line-height: 1.6;
    }
    .contact-card a {
      font-size: 14px;
      color: #4caf50;
      text-decoration: none;
      word-break: break-all;
    }
    .contact-card a:hover { text-decoration: underline; }

    /* ── Feedback form ── */
    .feedback-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.09);
      padding: 36px 38px;
    }
    .feedback-card h2 {
      color: #2a5a2e;
      font-size: 24px;
      margin-bottom: 24px;
    }

    .form-group { margin-bottom: 20px; position: relative; }

    .form-group label {
      display: block;
      margin-bottom: 7px;
      font-weight: 600;
      font-size: 14px;
      color: #333;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid #ddd;
      border-radius: 9px;
      font-size: 15px;
      transition: border-color 0.2s, box-shadow 0.2s;
      box-sizing: border-box;
      font-family: inherit;
      background: #fafafa;
    }
    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #4caf50;
      box-shadow: 0 0 0 3px rgba(76,175,80,0.12);
      background: #fff;
    }
    .form-group input.error,
    .form-group textarea.error {
      border-color: #e53935;
      box-shadow: 0 0 0 3px rgba(229,57,53,0.10);
    }
    .form-group textarea { resize: vertical; min-height: 110px; }

    /* Inline error message */
    .field-error {
      display: none;
      color: #e53935;
      font-size: 12px;
      font-weight: 600;
      margin-top: 5px;
      align-items: center;
      gap: 4px;
    }
    .field-error.show { display: flex; }
    .field-error::before { content: '⚠'; font-size: 13px; }

    /* Star rating */
    .rating-label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 14px;
      color: #333;
    }
    .rating {
      display: flex;
      flex-direction: row-reverse;
      justify-content: flex-end;
      gap: 4px;
      margin-bottom: 6px;
    }
    .rating input { display: none; }
    .rating label {
      font-size: 2rem;
      color: #ddd;
      cursor: pointer;
      transition: color 0.2s, transform 0.2s;
      padding: 2px;
    }
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label { color: #ffc107; }
    .rating label:hover { transform: scale(1.2); }
    .rating-error {
      display: none;
      color: #e53935;
      font-size: 12px;
      font-weight: 600;
      margin-top: 2px;
      align-items: center;
      gap: 4px;
    }
    .rating-error.show { display: flex; }
    .rating-error::before { content: '⚠'; font-size: 13px; }

    /* Submit button */
    .btn-submit {
      width: 100%;
      padding: 14px;
      background: #2a5a2e;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s;
      margin-top: 6px;
    }
    .btn-submit:hover { background: #1e4222; transform: translateY(-1px); }

    /* Success box */
    .success-box {
      display: none;
      text-align: center;
      padding: 48px 20px;
    }
    .success-box .success-icon { font-size: 64px; margin-bottom: 16px; }
    .success-box h2 { color: #2a5a2e; font-size: 26px; margin-bottom: 10px; }
    .success-box p  { color: #666; font-size: 16px; }
  </style>
</head>
<body>

<?php include 'nav.php'; ?>

<img src="https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?q=80&w=987&auto=format&fit=crop"
     class="page-img" alt="Get in Touch — WildTrack Zoo">

<div class="content-section">

  <!-- Intro -->
  <div class="git-intro">
    <h1>Get in Touch</h1>
    <p>Our team receives a high volume of calls and emails and always does its best to
       respond as quickly as possible — thank you for your patience!</p>
  </div>

  <div class="git-layout">

    <!-- LEFT: Contact cards -->
    <div class="git-left">

      <div class="contact-card">
        <div class="contact-card-icon">🦁</div>
        <div>
          <h3>WildTrack Zoo</h3>
          <p>📞 03-4108 3422</p>
          <a href="mailto:hello@wildtrackzoo.my">hello@wildtrackzoo.my</a>
        </div>
      </div>

      <div class="contact-card">
        <div class="contact-card-icon">🎫</div>
        <div>
          <h3>Membership Department</h3>
          <p>📞 +6011-3195 7075</p>
          <a href="mailto:membership@wildtrackzoo.my">membership@wildtrackzoo.my</a>
        </div>
      </div>

      <div class="contact-card">
        <div class="contact-card-icon">🎂</div>
        <div>
          <h3>Birthday Parties &amp; Corporate Bookings</h3>
          <p>📞 +6012-2925 1251</p>
          <a href="mailto:events@wildtrackzoo.my">events@wildtrackzoo.my</a>
        </div>
      </div>

      <div class="contact-card">
        <div class="contact-card-icon">🌳</div>
        <div>
          <h3>Ranger Program</h3>
          <p>📞 +6012-4765 2658</p>
          <a href="mailto:events@wildtrackzoo.my">ranger@wildtrackzoo.my</a>
        </div>
      </div>

    </div>

    <!-- RIGHT: Feedback form -->
    <div class="git-right">
      <div class="feedback-card">

        <div id="formBox">
          <h2>💬 Customer Feedback</h2>

          <!-- Name -->
          <div class="form-group">
            <label for="username">Your Name <span style="color:#e53935">*</span></label>
            <input type="text" id="username" placeholder="Enter your name">
            <div class="field-error" id="err-name">Name is required</div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email">Your Email <span style="color:#e53935">*</span></label>
            <input type="email" id="email" placeholder="Enter your email">
            <div class="field-error" id="err-email">A valid email address is required</div>
          </div>

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
        </div>

      </div>
    </div>

  </div>
</div>

<?php include 'footer.php'; ?>

<script>
  window.breadcrumb = [{ label: 'Get in Touch' }];

  function submitFeedback() {
    var valid = true;

    // Helper: clear then optionally show error
    function validate(inputId, errorId, condition) {
      var inp = document.getElementById(inputId);
      var err = document.getElementById(errorId);
      if (condition) {
        inp.classList.add('error');
        err.classList.add('show');
        valid = false;
      } else {
        inp.classList.remove('error');
        err.classList.remove('show');
      }
    }

    var name    = document.getElementById('username').value.trim();
    var email   = document.getElementById('email').value.trim();
    var message = document.getElementById('experience').value.trim();
    var rating  = document.querySelector('input[name="rating"]:checked');

    validate('username',   'err-name',    name === '');
    validate('email',      'err-email',   email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email));
    validate('experience', 'err-message', message === '');

    // Rating error
    var ratingErr = document.getElementById('err-rating');
    if (!rating) {
      ratingErr.classList.add('show');
      valid = false;
    } else {
      ratingErr.classList.remove('show');
    }

    if (!valid) return;

    // All good — show success
    document.getElementById('formBox').style.display   = 'none';
    document.getElementById('successBox').style.display = 'block';
  }

  // Live clear errors on input
  ['username','email','experience'].forEach(function(id) {
    document.getElementById(id).addEventListener('input', function() {
      this.classList.remove('error');
      var errMap = { username:'err-name', email:'err-email', experience:'err-message' };
      document.getElementById(errMap[id]).classList.remove('show');
    });
  });
  document.querySelectorAll('input[name="rating"]').forEach(function(r) {
    r.addEventListener('change', function() {
      document.getElementById('err-rating').classList.remove('show');
    });
  });
</script>
<script src="FinalProject.js"></script>
</body>
</html>
