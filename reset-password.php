<?php
require_once __DIR__.'/includes/db_helpers.php';
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$email = $_GET['email'] ?? $_POST['email'] ?? '';
$msg = null; $ok = false;

$stmt = $conn->prepare("SELECT id FROM password_resets WHERE email=? AND token=? AND used=0 AND created_at > DATE_SUB(NOW(),INTERVAL 1 DAY) LIMIT 1");
$stmt->bind_param('ss',$email,$token); $stmt->execute();
$valid = $stmt->get_result()->fetch_assoc();

if (!$valid) { $msg = 'This reset link is invalid or expired.'; }
elseif ($_SERVER['REQUEST_METHOD']==='POST') {
  $pw = $_POST['password'] ?? '';
  if (strlen($pw)<4) $msg = 'Password too short.';
  else {
    $up = $conn->prepare("UPDATE users SET password=? WHERE email=?"); $up->bind_param('ss',$pw,$email); $up->execute();
    $conn->prepare("UPDATE password_resets SET used=1 WHERE id=".(int)$valid['id'])->execute();
    $ok = true;
  }
}
$pageTitle='Reset Password — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-center py-5">
  <div class="card border-0 shadow-sm rounded-3xl p-4 p-md-5" style="max-width:440px;width:100%">
    <h1 class="h3">Reset password</h1>
    <?php if ($ok): ?>
      <div class="alert alert-success">Password updated. <a href="login.php">Sign in →</a></div>
    <?php elseif ($msg): ?>
      <div class="alert alert-warning small"><?= e($msg) ?></div>
      <?php if(!$valid): ?><a href="forgot-password.php" class="btn btn-sakura w-100">Request a new link</a><?php endif; ?>
    <?php endif; ?>
    <?php if ($valid && !$ok): ?>
    <form method="post">
      <input type="hidden" name="token" value="<?= e($token) ?>">
      <input type="hidden" name="email" value="<?= e($email) ?>">
      <p class="small text-muted">Setting new password for <b><?= e($email) ?></b></p>
      <input class="form-control form-control-sakura mb-2" type="password" name="password" placeholder="New password" required>
      <button class="btn btn-sakura w-100">Update password</button>
    </form>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
