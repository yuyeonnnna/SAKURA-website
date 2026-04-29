<?php
require_once __DIR__.'/includes/db_helpers.php';
$cat   = $_GET['cat']  ?? 'All';
$q     = trim($_GET['q'] ?? '');
$sort  = $_GET['sort']  ?? 'featured';
$items = get_products($cat, $q, $sort);
$pageTitle = 'Shop — SAKURA'; $active = 'shop';
include __DIR__.'/includes/header.php';
?>
<section class="py-4 text-center">
  <p class="section-eyebrow">welcome to the shop</p>
  <h1 class="section-title">Pretty things, just for you</h1>
  <p class="text-muted">Curated daily. Shipped with sparkle.</p>
</section>

<form method="get" class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
  <div class="d-flex gap-2 flex-wrap">
    <?php foreach (get_categories() as $c): ?>
      <a href="?cat=<?= $c ?>&q=<?= urlencode($q) ?>&sort=<?= e($sort) ?>"
         class="btn btn-sm cat-chip <?= $cat===$c?'cat-chip-active':'' ?>"><?= $c ?></a>
    <?php endforeach; ?>
  </div>
  <div class="d-flex gap-2">
    <input type="hidden" name="cat" value="<?= e($cat) ?>">
    <input class="form-control form-control-sakura" name="q" placeholder="Search…" value="<?= e($q) ?>" style="width:180px">
    <select name="sort" class="form-select" onchange="this.form.submit()">
      <option value="featured" <?= $sort==='featured'?'selected':'' ?>>Featured</option>
      <option value="low"      <?= $sort==='low'?'selected':'' ?>>Price: low → high</option>
      <option value="high"     <?= $sort==='high'?'selected':'' ?>>Price: high → low</option>
    </select>
    <button class="btn btn-sakura">Go</button>
  </div>
</form>

<?php if (!$items): ?>
  <p class="text-center text-muted py-5">No pretty things match your search… try another vibe ✿</p>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($items as $p): ?>
      <div class="col-6 col-md-4 col-lg-3">
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
            <div class="mt-1 fw-bold text-pink"><?= fmt_mmk($p['price']) ?></div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php include __DIR__.'/includes/footer.php'; ?>
