<?php
require_once __DIR__.'/includes/db_helpers.php';
$items    = cart_items();
$subtotal = cart_subtotal();
$shipping = ($subtotal === 0 || $subtotal >= FREE_SHIPPING_MMK) ? 0 : SHIPPING_FEE_MMK;
$total    = $subtotal + $shipping;
$pageTitle = 'Your Bag — SAKURA'; $active = '';
include __DIR__.'/includes/header.php';
?>
<h1 class="my-4">Your bag</h1>
<?php if (!$items): ?>
  <div class="text-center py-5">
    <p class="section-eyebrow">it's empty in here</p>
    <h2>Your bag is feeling lonely</h2>
    <a href="shop.php" class="btn btn-sakura mt-3">Start shopping</a>
  </div>
<?php else: ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-3xl">
        <?php foreach ($items as $it): ?>
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
          <img src="<?= e($it['image']) ?>" alt="" style="width:84px;height:84px;object-fit:cover;border-radius:14px">
          <div class="flex-grow-1">
            <a href="product.php?slug=<?= e($it['slug']) ?>" class="fw-semibold text-dark text-decoration-none"><?= e($it['name']) ?></a>
            <div class="text-muted small"><?= e($it['category']) ?></div>
            <div class="fw-bold text-pink mt-1"><?= fmt_mmk($it['price']) ?></div>
          </div>
          <form method="post" action="cart-action.php" class="d-flex align-items-center gap-1">
            <input type="hidden" name="action" value="set">
            <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
            <input type="number" name="qty" min="0" max="20" value="<?= (int)$it['qty'] ?>" class="form-control form-control-sm" style="width:72px" onchange="this.form.submit()">
          </form>
          <a href="cart-action.php?action=remove&id=<?= (int)$it['id'] ?>" class="btn btn-sm btn-outline-danger">✕</a>
        </div>
        <?php endforeach; ?>
        <div class="text-end p-3"><a href="cart-action.php?action=clear" class="text-muted small">Clear bag</a></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-3xl p-4">
        <h5>Order summary</h5>
        <div class="d-flex justify-content-between"><span class="text-muted">Subtotal</span><b><?= fmt_mmk($subtotal) ?></b></div>
        <div class="d-flex justify-content-between mt-2"><span class="text-muted">Shipping</span><b><?= $shipping?fmt_mmk($shipping):'Free ✿' ?></b></div>
        <?php if ($subtotal>0 && $subtotal < FREE_SHIPPING_MMK): ?>
          <div class="bg-blush rounded-3xl p-2 text-pink small mt-2">Add <?= fmt_mmk(FREE_SHIPPING_MMK-$subtotal) ?> more for free shipping!</div>
        <?php endif; ?>
        <hr>
        <div class="d-flex justify-content-between fs-5"><b>Total</b><b><?= fmt_mmk($total) ?></b></div>
        <a href="checkout.php" class="btn btn-sakura mt-3">Checkout →</a>
        <a href="shop.php" class="text-center small text-muted mt-2">← Continue shopping</a>
      </div>
    </div>
  </div>
<?php endif; ?>
<?php include __DIR__.'/includes/footer.php'; ?>
