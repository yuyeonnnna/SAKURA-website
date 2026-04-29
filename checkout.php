<?php
require_once __DIR__.'/includes/db_helpers.php';
$items = cart_items();
if (!$items) { redirect('cart.php'); }
$subtotal = cart_subtotal();
$shipping = $subtotal >= FREE_SHIPPING_MMK ? 0 : SHIPPING_FEE_MMK;
$total    = $subtotal + $shipping;
$u = current_user();
$err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $addr    = trim($_POST['address'] ?? '');
    $pay     = $_POST['payment']      ?? 'Cash on Delivery';
    if (!$name || !$email || !$addr) { $err = 'Please fill in name, email and shipping address.'; }
    else {
        $uid = $u['id'] ?? null;
        $stmt = $conn->prepare("INSERT INTO orders (user_id,customer_name,customer_email,customer_phone,shipping_address,payment_method,subtotal,shipping,total) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('isssssiii',$uid,$name,$email,$phone,$addr,$pay,$subtotal,$shipping,$total);
        $stmt->execute();
        $orderId = $conn->insert_id;
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id,product_id,product_name,price,qty) VALUES (?,?,?,?,?)");
        foreach ($items as $it) {
            $stmt2->bind_param('iisii',$orderId,$it['id'],$it['name'],$it['price'],$it['qty']);
            $stmt2->execute();
        }
        $_SESSION['cart'] = [];
        redirect('order-success.php?id='.$orderId);
    }
}
$pageTitle = 'Checkout — SAKURA';
include __DIR__.'/includes/header.php';
?>
<h1 class="my-4">Checkout</h1>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<form method="post" class="row g-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm rounded-3xl p-4">
      <h5 class="mb-3">Shipping details</h5>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label small text-muted">Full name</label>
          <input class="form-control form-control-sakura" name="name"  value="<?= e($u['name']  ?? '') ?>" required></div>
        <div class="col-md-6"><label class="form-label small text-muted">Email</label>
          <input class="form-control form-control-sakura" name="email" value="<?= e($u['email'] ?? '') ?>" type="email" required></div>
        <div class="col-md-6"><label class="form-label small text-muted">Phone</label>
          <input class="form-control form-control-sakura" name="phone" value="<?= e($u['phone'] ?? '') ?>"></div>
        <div class="col-md-6"><label class="form-label small text-muted">Payment method</label>
          <select class="form-select" name="payment">
            <option>Cash on Delivery</option><option>KPay</option><option>Wave Pay</option><option>AYA Pay</option><option>Visa / Master</option>
          </select></div>
        <div class="col-12"><label class="form-label small text-muted">Shipping address</label>
          <textarea class="form-control form-control-sakura" name="address" rows="3" required><?= e($u['address'] ?? '') ?></textarea></div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm rounded-3xl p-4">
      <h5>Order summary</h5>
      <?php foreach ($items as $it): ?>
        <div class="d-flex justify-content-between small py-1">
          <span><?= e($it['name']) ?> × <?= (int)$it['qty'] ?></span>
          <span><?= fmt_mmk($it['price']*$it['qty']) ?></span>
        </div>
      <?php endforeach; ?>
      <hr>
      <div class="d-flex justify-content-between"><span>Subtotal</span><b><?= fmt_mmk($subtotal) ?></b></div>
      <div class="d-flex justify-content-between"><span>Shipping</span><b><?= $shipping?fmt_mmk($shipping):'Free ✿' ?></b></div>
      <div class="d-flex justify-content-between fs-5 mt-2"><b>Total</b><b><?= fmt_mmk($total) ?></b></div>
      <button class="btn btn-sakura mt-3 w-100">Place order ✿</button>
    </div>
  </div>
</form>
<?php include __DIR__.'/includes/footer.php'; ?>
