<?php
require_once __DIR__.'/includes/db_helpers.php';
$msg = null; $resetLink = null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email = trim($_POST['email'] ?? '');
  if ($email) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?"); $stmt->bind_param('s',$email); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
      $token = bin2hex(random_bytes(20));
      $ins = $conn->prepare("INSERT INTO password_resets (email,token) VALUES (?,?)");
      $ins->bind_param('ss',$email,$token); $ins->execute();
      $base = (isset($_SERVER['HTTPS'])?'https':'http')."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']);
      $resetLink = $base.'/reset-password.php?token='.$token.'&email='.urlencode($email);
      $msg = 'Reset link generated below (in a real site this would be emailed).';
    } else {
      $msg = 'If that email exists, a reset link will be sent.';
    }
  }
}
$pageTitle='Forgot Password — SAKURA';
include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-center py-5">
  <div class="card border-0 shadow-sm rounded-3xl p-4 p-md-5" style="max-width:480px;width:100%">
    <h1 class="h3">Forgot password</h1>
    <p class="text-muted small">Enter your email — we'll send you a link to reset your password.</p>
    <?php if ($msg): ?><div class="alert alert-info small"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($resetLink): ?><div class="alert alert-success small">Reset link: <a href="<?= e($resetLink) ?>"><?= e($resetLink) ?></a></div><?php endif; ?>
    <form method="post">
      <input class="form-control form-control-sakura mb-2" type="email" name="email" placeholder="you@email.com" required>
      <button class="btn btn-sakura w-100">Send reset link</button>
    </form>
    <p class="text-center small mt-3"><a href="login.php" class="text-pink">← Back to sign in</a></p>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
