<?php
require_once __DIR__.'/includes/db_helpers.php';
$slug = $_GET['slug'] ?? '';
$p = get_product_by_slug($slug);
if (!$p) { http_response_code(404); echo "Product not found"; exit; }
$pageTitle = e($p['name']).' — SAKURA'; $pageDesc = e(mb_substr($p['description'],0,150)); $active = 'shop';
include __DIR__.'/includes/header.php';
?>
<div class="row g-5 my-4">
  <div class="col-md-6">
    <img src="<?= e($p['image']) ?>" class="img-fluid rounded-3xl shadow-sm w-100" alt="<?= e($p['name']) ?>">
  </div>
  <div class="col-md-6">
    <div class="text-muted small"><?= e($p['category']) ?></div>
    <h1 class="display-6"><?= e($p['name']) ?></h1>
    <div class="text-warning mb-2">★ <?= e($p['rating']) ?> <span class="text-muted small">(<?= (int)$p['reviews'] ?> reviews)</span></div>
    <div class="fs-3 fw-bold text-pink mb-3">
      <?= fmt_mmk($p['price']) ?>
      <?php if ($p['old_price']): ?><span class="text-muted text-decoration-line-through fs-5 ms-2"><?= fmt_mmk($p['old_price']) ?></span><?php endif; ?>
    </div>
    <p class="text-muted"><?= e($p['description']) ?></p>
    <ul class="small text-muted">
      <?php foreach (explode('|', $p['details'] ?? '') as $d): if(trim($d)): ?>
        <li><?= e(trim($d)) ?></li>
      <?php endif; endforeach; ?>
    </ul>
    <form action="cart-action.php" method="post" class="d-flex gap-2 mt-4">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <input type="number" name="qty" value="1" min="1" max="20" class="form-control" style="width:90px">
      <button class="btn btn-sakura px-4">Add to bag ✿</button>
    </form>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
