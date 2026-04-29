<?php
require_once __DIR__.'/includes/db_helpers.php';
require_admin();

// Handle product CRUD
$msg = null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action = $_POST['action'] ?? '';
  if ($action==='delete') {
    $id=(int)$_POST['id'];
    $conn->query("DELETE FROM products WHERE id=$id");
    $msg='Product deleted.';
  } elseif ($action==='save') {
    $id    = (int)($_POST['id'] ?? 0);
    $name  = trim($_POST['name'] ?? '');
    $slug  = trim($_POST['slug'] ?? '');
    $cat   = $_POST['category'] ?? 'Hair';
    $price = (int)($_POST['price'] ?? 0);
    $old   = $_POST['old_price']!=='' ? (int)$_POST['old_price'] : null;
    $img   = trim($_POST['image'] ?? '');
    $badge = trim($_POST['badge'] ?? '');
    $desc  = trim($_POST['description'] ?? '');
    $det   = trim($_POST['details'] ?? '');
    $stock = (int)($_POST['stock'] ?? 50);
    if ($id) {
      $stmt = $conn->prepare("UPDATE products SET slug=?,name=?,category=?,price=?,old_price=?,image=?,badge=?,description=?,details=?,stock=? WHERE id=?");
      $stmt->bind_param('sssiisssssi',$slug,$name,$cat,$price,$old,$img,$badge,$desc,$det,$stock,$id);
      $stmt->execute(); $msg='Product updated ✿';
    } else {
      $stmt = $conn->prepare("INSERT INTO products (slug,name,category,price,old_price,image,badge,description,details,stock) VALUES (?,?,?,?,?,?,?,?,?,?)");
      $stmt->bind_param('sssiissssi',$slug,$name,$cat,$price,$old,$img,$badge,$desc,$det,$stock);
      $stmt->execute(); $msg='Product added ✿';
    }
  } elseif ($action==='order_status') {
    $id=(int)$_POST['id']; $st=$_POST['status'] ?? 'Pending';
    $stmt=$conn->prepare("UPDATE orders SET status=? WHERE id=?"); $stmt->bind_param('si',$st,$id); $stmt->execute();
    $msg='Order status updated.';
  }
}

$products = get_products();
$orders = $conn->query("SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id=o.id) items FROM orders o ORDER BY created_at DESC LIMIT 50")->fetch_all(MYSQLI_ASSOC);
$msgs = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 30")->fetch_all(MYSQLI_ASSOC);

$stats = [
  'orders'   => (int)$conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'],
  'revenue'  => (int)$conn->query("SELECT COALESCE(SUM(total),0) c FROM orders WHERE status<>'Cancelled'")->fetch_assoc()['c'],
  'products' => count($products),
  'users'    => (int)$conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'],
];

$pageTitle='Admin Dashboard — SAKURA';
include __DIR__.'/includes/header.php';
?>
<h1 class="my-4">Admin dashboard</h1>
<?php if ($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm rounded-3xl p-3"><div class="text-muted small">Orders</div><div class="fs-3 fw-bold text-pink"><?= $stats['orders'] ?></div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm rounded-3xl p-3"><div class="text-muted small">Revenue</div><div class="fs-5 fw-bold text-pink"><?= fmt_mmk($stats['revenue']) ?></div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm rounded-3xl p-3"><div class="text-muted small">Products</div><div class="fs-3 fw-bold text-pink"><?= $stats['products'] ?></div></div></div>
  <div class="col-6 col-md-3"><div class="card border-0 shadow-sm rounded-3xl p-3"><div class="text-muted small">Users</div><div class="fs-3 fw-bold text-pink"><?= $stats['users'] ?></div></div></div>
</div>

<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-products">Products</button></li>
  <li class="nav-item"><button class="nav-link"        data-bs-toggle="tab" data-bs-target="#tab-orders">Orders</button></li>
  <li class="nav-item"><button class="nav-link"        data-bs-toggle="tab" data-bs-target="#tab-msgs">Messages</button></li>
</ul>
<div class="tab-content border border-top-0 rounded-bottom-3xl bg-white p-3">

  <!-- PRODUCTS -->
  <div class="tab-pane fade show active" id="tab-products">
    <button class="btn btn-sakura btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#productModal" onclick="fillProduct()">+ Add product</button>
    <div class="table-responsive">
      <table class="table align-middle table-sm">
        <thead class="text-muted small text-uppercase"><tr><th></th><th>Name</th><th>Cat</th><th>Price</th><th>Stock</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
          <tr>
            <td><img src="<?= e($p['image']) ?>" style="width:42px;height:42px;object-fit:cover;border-radius:8px"></td>
            <td><b><?= e($p['name']) ?></b><br><span class="text-muted small"><?= e($p['slug']) ?></span></td>
            <td><span class="badge bg-light text-dark"><?= e($p['category']) ?></span></td>
            <td><?= fmt_mmk($p['price']) ?></td>
            <td><?= (int)$p['stock'] ?></td>
            <td class="text-end">
              <button class="btn btn-sm btn-sakura-outline" data-bs-toggle="modal" data-bs-target="#productModal"
                      onclick='fillProduct(<?= json_encode($p, JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>Edit</button>
              <form method="post" class="d-inline" onsubmit="return confirm('Delete this product?')">
                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ORDERS -->
  <div class="tab-pane fade" id="tab-orders">
    <div class="table-responsive">
      <table class="table align-middle table-sm">
        <thead class="text-muted small text-uppercase"><tr><th>#</th><th>Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
          <tr>
            <td>#<?= (int)$o['id'] ?></td>
            <td><?= e($o['customer_name']) ?><br><span class="text-muted small"><?= e($o['customer_email']) ?></span></td>
            <td class="small"><?= e(date('d M Y H:i',strtotime($o['created_at']))) ?></td>
            <td><?= (int)$o['items'] ?></td>
            <td><?= fmt_mmk($o['total']) ?></td>
            <td>
              <form method="post" class="d-flex gap-1">
                <input type="hidden" name="action" value="order_status">
                <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
                <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                  <?php foreach (['Pending','Paid','Shipped','Delivered','Cancelled'] as $s): ?>
                    <option <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MESSAGES -->
  <div class="tab-pane fade" id="tab-msgs">
    <?php if (!$msgs): ?><p class="text-muted">No messages yet.</p>
    <?php else: foreach ($msgs as $m): ?>
      <div class="card border-0 shadow-sm rounded-3xl p-3 mb-2">
        <div class="d-flex justify-content-between"><b><?= e($m['name']) ?></b><span class="text-muted small"><?= e($m['created_at']) ?></span></div>
        <div class="text-muted small"><?= e($m['email']) ?> · <?= e($m['subject']) ?></div>
        <p class="mb-0 mt-2"><?= nl2br(e($m['message'])) ?></p>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>

<!-- Product modal -->
<div class="modal fade" id="productModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content rounded-3xl">
  <form method="post">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" id="f-id">
    <div class="modal-header"><h5 class="modal-title" id="f-title">Add product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><div class="row g-3">
      <div class="col-md-7"><label class="small text-muted">Name</label><input class="form-control form-control-sakura" name="name" id="f-name" required></div>
      <div class="col-md-5"><label class="small text-muted">Slug</label><input class="form-control form-control-sakura" name="slug" id="f-slug" required></div>
      <div class="col-md-4"><label class="small text-muted">Category</label>
        <select class="form-select" name="category" id="f-cat">
          <option>Hair</option><option>Jewelry</option><option>Eyewear</option><option>Bags</option>
        </select></div>
      <div class="col-md-4"><label class="small text-muted">Price (MMK)</label><input class="form-control form-control-sakura" type="number" name="price" id="f-price" required></div>
      <div class="col-md-4"><label class="small text-muted">Old price (optional)</label><input class="form-control form-control-sakura" type="number" name="old_price" id="f-old"></div>
      <div class="col-md-8"><label class="small text-muted">Image (path or URL)</label><input class="form-control form-control-sakura" name="image" id="f-image" placeholder="images/product-xxx.jpg" required></div>
      <div class="col-md-4"><label class="small text-muted">Badge</label><input class="form-control form-control-sakura" name="badge" id="f-badge" placeholder="New / Trending / Sale"></div>
      <div class="col-12"><label class="small text-muted">Description</label><textarea class="form-control form-control-sakura" name="description" id="f-desc" rows="2"></textarea></div>
      <div class="col-md-9"><label class="small text-muted">Details (separate with |)</label><input class="form-control form-control-sakura" name="details" id="f-det"></div>
      <div class="col-md-3"><label class="small text-muted">Stock</label><input class="form-control form-control-sakura" type="number" name="stock" id="f-stock" value="50"></div>
    </div></div>
    <div class="modal-footer"><button class="btn btn-sakura">Save</button></div>
  </form>
</div></div></div>

<script>
function fillProduct(p){
  p = p || {};
  document.getElementById('f-title').textContent = p.id ? 'Edit product' : 'Add product';
  document.getElementById('f-id').value    = p.id    || '';
  document.getElementById('f-name').value  = p.name  || '';
  document.getElementById('f-slug').value  = p.slug  || '';
  document.getElementById('f-cat').value   = p.category || 'Hair';
  document.getElementById('f-price').value = p.price || '';
  document.getElementById('f-old').value   = p.old_price || '';
  document.getElementById('f-image').value = p.image || '';
  document.getElementById('f-badge').value = p.badge || '';
  document.getElementById('f-desc').value  = p.description || '';
  document.getElementById('f-det').value   = p.details || '';
  document.getElementById('f-stock').value = (p.stock!==undefined?p.stock:50);
}
</script>
<?php include __DIR__.'/includes/footer.php'; ?>
