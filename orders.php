<?php
require_once __DIR__.'/includes/db_helpers.php';
require_login();
$u = current_user();
$month = $_GET['month'] ?? 'all'; // 'all' or YYYY-MM

$sql = "SELECT * FROM orders WHERE user_id=?";
$params=[$u['id']]; $types='i';
if ($month!=='all' && preg_match('/^\d{4}-\d{2}$/',$month)) {
  $sql .= " AND DATE_FORMAT(created_at,'%Y-%m')=?";
  $params[]=$month; $types.='s';
}
$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql); $stmt->bind_param($types,...$params); $stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$months = $conn->query("SELECT DISTINCT DATE_FORMAT(created_at,'%Y-%m') ym FROM orders WHERE user_id=".(int)$u['id']." ORDER BY ym DESC")->fetch_all(MYSQLI_ASSOC);

$pageTitle='My Orders — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-end my-4">
  <div><h1 class="mb-0">Order history</h1><p class="text-muted small mb-0">All your SAKURA orders in one place.</p></div>
  <form method="get" class="d-flex gap-2">
    <select name="month" class="form-select" onchange="this.form.submit()">
      <option value="all" <?= $month==='all'?'selected':'' ?>>All time</option>
      <?php foreach ($months as $m): ?>
        <option value="<?= e($m['ym']) ?>" <?= $month===$m['ym']?'selected':'' ?>><?= e(date('F Y', strtotime($m['ym'].'-01'))) ?></option>
      <?php endforeach; ?>
    </select>
  </form>
</div>

<?php if (!$orders): ?>
  <div class="text-center text-muted py-5">No orders yet for this period. <a href="shop.php">Start shopping →</a></div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead class="text-muted small text-uppercase"><tr><th>Order</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach ($orders as $o):
          $oi = $conn->query("SELECT product_name,qty FROM order_items WHERE order_id=".(int)$o['id'])->fetch_all(MYSQLI_ASSOC);
          $items_str = implode(', ', array_map(fn($x)=>$x['product_name'].' ×'.$x['qty'], $oi));
          $color = ['Pending'=>'warning','Paid'=>'info','Shipped'=>'primary','Delivered'=>'success','Cancelled'=>'secondary'][$o['status']] ?? 'secondary';
        ?>
        <tr>
          <td>#<?= (int)$o['id'] ?></td>
          <td><?= e(date('d M Y', strtotime($o['created_at']))) ?></td>
          <td class="small text-muted" style="max-width:340px"><?= e($items_str) ?></td>
          <td><b><?= fmt_mmk($o['total']) ?></b></td>
          <td><span class="badge bg-<?= $color ?>"><?= e($o['status']) ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<?php include __DIR__.'/includes/footer.php'; ?>
