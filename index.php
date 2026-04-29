<?php
require_once __DIR__.'/includes/db_helpers.php';
$pageTitle = 'SAKURA — Fancy & Accessories for main-character moments';
$pageDesc  = 'Hand-picked fancy accessories — soft pink, Gen Z energy. Free shipping over 200,000 MMK.';
$active    = 'home';
$trending  = array_slice(get_products(), 0, 8);
include __DIR__.'/includes/header.php';
?>
<section class="hero">
  <div class="row align-items-center g-4">
    <div class="col-md-6">
      <p class="hero-eyebrow">new spring drop ✿</p>
      <h1>Fancy little things for <em>main-character</em> moments.</h1>
      <p class="text-muted mb-4" style="max-width:440px;">
        Hand-picked accessories that sparkle, swirl, and soften every outfit. Free shipping over 200,000 MMK.
      </p>
      <div class="d-flex gap-2 flex-wrap">
        <a href="shop.php" class="btn btn-sakura">Shop the drop →</a>
        <a href="about.php" class="btn btn-sakura-outline">Our story</a>
      </div>
      <div class="d-flex gap-4 mt-4 small text-muted">
        <span>★ 4.9 / 5  ·  2k+ happy babes</span>
      </div>
    </div>
    <div class="col-md-6"><img src="images/hero.jpg" alt="SAKURA hero" class="hero-img"></div>
  </div>
</section>

<section class="my-5 py-4">
  <div class="d-flex align-items-end justify-content-between mb-4">
    <div>
      <p class="section-eyebrow mb-0">trending now</p>
      <h2 class="section-title">Loved by the SAKURA babes</h2>
    </div>
    <a href="shop.php" class="btn btn-sakura-outline d-none d-md-inline-block">See all</a>
  </div>
  <div class="row g-3">
    <?php foreach ($trending as $p): ?>
      <div class="col-6 col-md-3">
        <div class="product-card">
          <a href="product.php?slug=<?= e($p['slug']) ?>">
            <div class="product-card-img-wrap">
              <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" class="product-card-img">
              <?php if ($p['badge']): ?><span class="product-badge"><?= e($p['badge']) ?></span><?php endif; ?>
            </div>
          </a>
          <div class="p-3">
            <div class="text-muted small"><?= e($p['category']) ?></div>
            <a href="product.php?slug=<?= e($p['slug']) ?>" class="text-decoration-none text-dark">
              <div class="fw-semibold"><?= e($p['name']) ?></div>
            </a>
            <div class="mt-1 fw-bold text-pink"><?= fmt_mmk($p['price']) ?>
              <?php if ($p['old_price']): ?><span class="text-muted text-decoration-line-through small ms-1"><?= fmt_mmk($p['old_price']) ?></span><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="my-5 py-4">
  <p class="section-eyebrow text-center">shop by vibe</p>
  <h2 class="section-title text-center mb-4">Pick your moment</h2>
  <div class="row g-3">
    <?php foreach ([['🎀','Hair'],['💍','Jewelry'],['🕶','Eyewear'],['👜','Bags']] as [$ic,$cat]): ?>
      <div class="col-6 col-md-3">
        <a href="shop.php?cat=<?= $cat ?>" class="d-block text-decoration-none">
          <div class="bg-blush rounded-3xl p-4 text-center">
            <div style="font-size:2.5rem;"><?= $ic ?></div>
            <div class="fw-semibold mt-2 text-dark"><?= $cat ?></div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
