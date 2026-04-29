<?php
require_once __DIR__.'/includes/db_helpers.php';
$err = null; $mode = $_GET['mode'] ?? 'login';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $mode  = $_POST['mode'] ?? 'login';
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  if ($mode==='register') {
    $name  = trim($_POST['name'] ?? '');
    if (!$name||!$email||!$pass) $err = 'All fields required.';
    else {
      $chk = $conn->prepare("SELECT id FROM users WHERE email=?"); $chk->bind_param('s',$email); $chk->execute();
      if ($chk->get_result()->fetch_assoc()) $err = 'That email is already registered.';
      else {
        $ins = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        $ins->bind_param('sss',$name,$email,$pass); $ins->execute();
        $uid = $conn->insert_id;
        $_SESSION['user'] = ['id'=>$uid,'name'=>$name,'email'=>$email,'phone'=>'','address'=>'','is_admin'=>0];
        redirect('account.php');
      }
    }
  } else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=? LIMIT 1");
    $stmt->bind_param('ss',$email,$pass); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) $err = 'Wrong email or password.';
    else {
      $_SESSION['user'] = ['id'=>(int)$row['id'],'name'=>$row['name'],'email'=>$row['email'],'phone'=>$row['phone'],'address'=>$row['address'],'is_admin'=>(int)$row['is_admin']];
      $r = $_GET['redirect'] ?? ($row['is_admin']?'admin.php':'account.php');
      redirect($r);
    }
  }
}
$pageTitle = ($mode==='register'?'Sign up':'Sign in').' — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-center py-5">
  <div class="card border-0 shadow-sm rounded-3xl p-4 p-md-5" style="max-width:440px;width:100%">
    <p class="section-eyebrow text-center">welcome back ✿</p>
    <h1 class="h3 text-center"><?= $mode==='register'?'Create account':'Sign in' ?></h1>
    <div class="bg-blush rounded-pill p-1 d-flex mt-3">
      <a href="?mode=login"    class="btn btn-sm flex-fill <?= $mode==='login'?'btn-light':'' ?>">Sign in</a>
      <a href="?mode=register" class="btn btn-sm flex-fill <?= $mode==='register'?'btn-light':'' ?>">Sign up</a>
    </div>
    <?php if ($err): ?><div class="alert alert-danger mt-3 small"><?= e($err) ?></div><?php endif; ?>
    <form method="post" class="mt-3">
      <input type="hidden" name="mode" value="<?= e($mode) ?>">
      <?php if ($mode==='register'): ?>
        <input class="form-control form-control-sakura mb-2" name="name" placeholder="Your name" required>
      <?php endif; ?>
      <input class="form-control form-control-sakura mb-2" name="email" type="email" placeholder="Email" required>
      <input class="form-control form-control-sakura mb-2" name="password" type="password" placeholder="Password" required>
      <button class="btn btn-sakura w-100"><?= $mode==='register'?'Create account':'Sign in' ?></button>
    </form>
    <?php if ($mode==='login'): ?>
      <p class="text-center small mt-3"><a href="forgot-password.php" class="text-pink">Forgot your password?</a></p>
      <p class="text-center small text-muted">Admin? <a href="admin-login.php" class="text-pink">Login here</a></p>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
