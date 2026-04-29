<?php
require_once __DIR__.'/includes/db_helpers.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { redirect('index.php'); }
$pageTitle='Thank you — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="text-center py-5">
  <div style="font-size:4rem">✿</div>
  <h1>Thank you, <?= e($order['customer_name']) ?>!</h1>
  <p class="text-muted">Order <b>#<?= (int)$order['id'] ?></b> received. Total <b><?= fmt_mmk($order['total']) ?></b>.</p>
  <p class="text-muted">We'll contact you on <?= e($order['customer_phone'] ?: $order['customer_email']) ?> to confirm shipping.</p>
  <a href="shop.php" class="btn btn-sakura mt-3">Continue shopping</a>
  <?php if (current_user()): ?><a href="orders.php" class="btn btn-sakura-outline mt-3 ms-2">View my orders</a><?php endif; ?>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
