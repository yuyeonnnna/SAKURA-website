<?php require_once __DIR__.'/includes/config.php'; $pageTitle='About — SAKURA'; $active='about';
include __DIR__.'/includes/header.php'; ?>

<section class="py-5 text-center">
  <p class="section-eyebrow">our story</p>
  <h1 class="section-title">Made in Yangon, with ♡</h1>
  <p class="text-muted mx-auto" style="max-width:640px">
    SAKURA started in a tiny pink-lit studio in Yangon. We hand-curate accessories
    that feel like a soft hug — for hair, ears, eyes, and everything in between.
  </p>
</section>

<!-- Value props -->
<div class="row g-4 my-3">
  <div class="col-md-4"><div class="card border-0 shadow-sm rounded-3xl p-4 h-100"><h5>✿ Hand-picked</h5><p class="text-muted small mb-0">Every piece is chosen for quality, vibe, and that little spark of joy.</p></div></div>
  <div class="col-md-4"><div class="card border-0 shadow-sm rounded-3xl p-4 h-100"><h5>🎀 Fair pricing</h5><p class="text-muted small mb-0">Reasonable MMK pricing — accessories shouldn't break the bank.</p></div></div>
  <div class="col-md-4"><div class="card border-0 shadow-sm rounded-3xl p-4 h-100"><h5>📦 Fast delivery</h5><p class="text-muted small mb-0">Yangon next-day, nationwide 2–4 days. Free shipping above 200,000 MMK.</p></div></div>
</div>

<!-- More about SAKURA -->
<section class="my-5">
  <div class="about-info-card">
    <p class="section-eyebrow">more about us</p>
    <h2 class="section-title mb-3" style="font-size:1.8rem">A little world of pretty things</h2>
    <p class="text-muted">
      SAKURA is more than just an accessories shop — it's a soft, sparkly corner of
      the internet for everyone who loves details. From dreamy hair clips and
      delicate jewelry to playful eyewear and everyday bags, every piece is
      selected to make ordinary days feel a little more magical.
    </p>
    <div class="row g-4 mt-2">
      <div class="col-md-6">
        <h6 class="text-pink mb-2">🌸 Our mission</h6>
        <p class="text-muted small mb-0">
          To bring affordable, high-quality, joyful accessories to girls and women
          across Myanmar — and to grow a community where self-expression is celebrated.
        </p>
      </div>
      <div class="col-md-6">
        <h6 class="text-pink mb-2">💖 What we promise</h6>
        <p class="text-muted small mb-0">
          Honest descriptions, friendly customer support, secure payments, and easy
          exchanges within 7 days. If you're not smiling when your parcel arrives,
          neither are we.
        </p>
      </div>
      <div class="col-md-6">
        <h6 class="text-pink mb-2">🎁 Made for you</h6>
        <p class="text-muted small mb-0">
          New drops every week, limited-edition collections, and gift-ready packaging
          on every order — because the unboxing matters too.
        </p>
      </div>
      <div class="col-md-6">
        <h6 class="text-pink mb-2">🌏 Community first</h6>
        <p class="text-muted small mb-0">
          We work with small local creators and ship from Yangon. Every order
          supports a tiny team that genuinely loves what they do.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Reviews -->
<section class="my-5">
  <div class="text-center mb-4">
    <p class="section-eyebrow">kind words</p>
    <h2 class="section-title" style="font-size:1.8rem">What our customers say</h2>
  </div>
  <div class="row g-3">
    <?php
    $reviews = [
      ['stars'=>5,'quote'=>'The packaging is so cute I almost cried! My hair clips are even prettier in person. Will definitely order again ✿','name'=>'Ei Ei P.','meta'=>'Yangon · Hair collection'],
      ['stars'=>5,'quote'=>'Fast delivery and the earrings look exactly like the photos. Quality feels way more expensive than the price.','name'=>'Thiri H.','meta'=>'Mandalay · Jewelry'],
      ['stars'=>4,'quote'=>'Loved my sunglasses! Customer support replied super quickly when I asked about sizing. Highly recommend.','name'=>'May Su K.','meta'=>'Yangon · Eyewear'],
      ['stars'=>5,'quote'=>'Bought a bag as a birthday gift and my friend was obsessed. The little thank-you note was such a sweet touch 💖','name'=>'Hnin W.','meta'=>'Naypyidaw · Bags'],
      ['stars'=>5,'quote'=>'Honestly my favorite local shop now. Everything feels carefully chosen, not random aesthetic stuff.','name'=>'Phyu P.','meta'=>'Yangon · Repeat customer'],
      ['stars'=>5,'quote'=>'Came in 2 days, beautifully wrapped, and the quality is top-tier. SAKURA never misses!','name'=>'Su Yati M.','meta'=>'Bago · Hair & Jewelry'],
    ];
    foreach ($reviews as $r): ?>
      <div class="col-md-6 col-lg-4">
        <div class="review-card">
          <div class="stars"><?= str_repeat('★', $r['stars']) . str_repeat('☆', 5 - $r['stars']) ?></div>
          <p class="quote">“<?= htmlspecialchars($r['quote']) ?>”</p>
          <div class="author">
            — <?= htmlspecialchars($r['name']) ?>
            <small><?= htmlspecialchars($r['meta']) ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Social links -->
<section class="my-5 text-center">
  <p class="section-eyebrow">stay in touch</p>
  <h2 class="section-title mb-3" style="font-size:1.8rem">Follow SAKURA ♡</h2>
  <p class="text-muted mb-4">New drops, behind-the-scenes, and styling tips — first on socials.</p>
  <div class="d-flex flex-wrap gap-2 justify-content-center">
    <a class="social-pill fb" href="https://www.facebook.com/sakura" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 22v-8h3l1-4h-4V7.5c0-1.1.4-2 2-2h2V2.1C16.6 2 15.4 2 14.4 2 11.7 2 10 3.7 10 6.7V10H7v4h3v8h3z"/></svg>
      Facebook
    </a>
    <a class="social-pill ig" href="https://www.instagram.com/sakura" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
      Instagram
    </a>
    <a class="social-pill tt" href="https://www.tiktok.com/@sakura" target="_blank" rel="noopener">
      <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 2h-3v13.2a3 3 0 1 1-3-3v-3a6 6 0 1 0 6 6V9.5c1.2.8 2.6 1.3 4 1.3V7.7c-2.2 0-4-1.8-4-3.7V2z"/></svg>
      TikTok
    </a>
  </div>
</section>

<?php include __DIR__.'/includes/footer.php'; ?>
