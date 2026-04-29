<?php
require_once __DIR__.'/includes/db_helpers.php';
$err=null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = trim($_POST['email']??''); $pass = $_POST['password']??'';
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=? AND is_admin=1 LIMIT 1");
  $stmt->bind_param('ss',$email,$pass); $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();
  if ($row) {
    $_SESSION['user'] = ['id'=>(int)$row['id'],'name'=>$row['name'],'email'=>$row['email'],'phone'=>$row['phone'],'address'=>$row['address'],'is_admin'=>1];
    redirect('admin.php');
  } else $err='Invalid admin credentials.';
}
$pageTitle='Admin Login — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-center py-5">
  <div class="card border-0 shadow-sm rounded-3xl p-4 p-md-5" style="max-width:420px;width:100%">
    <h1 class="h3 text-center">Admin sign in</h1>
    <p class="text-muted small text-center">Default: admin@sakura.local / sakura123</p>
    <?php if ($err): ?><div class="alert alert-danger small"><?= e($err) ?></div><?php endif; ?>
    <form method="post">
      <input class="form-control form-control-sakura mb-2" name="email"    type="email"    placeholder="Admin email" required>
      <input class="form-control form-control-sakura mb-2" name="password" type="password" placeholder="Password"     required>
      <button class="btn btn-sakura w-100">Sign in</button>
    </form>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
