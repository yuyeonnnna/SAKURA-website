<?php
require_once __DIR__.'/includes/db_helpers.php';
require_login();
$u = current_user();
$msg = null; $err = null;

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (($_POST['form'] ?? '')==='profile') {
    $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $phone=trim($_POST['phone']??''); $addr=trim($_POST['address']??'');
    if (!$name||!$email) $err='Name and email are required.';
    else {
      $stmt = $conn->prepare("UPDATE users SET name=?,email=?,phone=?,address=? WHERE id=?");
      $stmt->bind_param('ssssi',$name,$email,$phone,$addr,$u['id']); $stmt->execute();
      $_SESSION['user'] = array_merge($u, compact('name','email','phone','address'));
      $u = current_user(); $msg='Profile updated ✿';
    }
  } elseif (($_POST['form'] ?? '')==='password') {
    $cur=$_POST['current']??''; $new=$_POST['new']??'';
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?"); $stmt->bind_param('i',$u['id']); $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row || $row['password']!==$cur) $err='Current password is wrong.';
    elseif (strlen($new)<4) $err='New password too short.';
    else {
      $up = $conn->prepare("UPDATE users SET password=? WHERE id=?"); $up->bind_param('si',$new,$u['id']); $up->execute();
      $msg='Password changed ✿';
    }
  }
}
$pageTitle='My Account — SAKURA';
include __DIR__.'/includes/header.php';
?>
<h1 class="my-4">My account</h1>
<?php if ($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm rounded-3xl p-4">
      <h5>Profile</h5>
      <form method="post" class="row g-3 mt-1">
        <input type="hidden" name="form" value="profile">
        <div class="col-md-6"><label class="form-label small text-muted">Name</label>
          <input class="form-control form-control-sakura" name="name"  value="<?= e($u['name']) ?>" required></div>
        <div class="col-md-6"><label class="form-label small text-muted">Email</label>
          <input class="form-control form-control-sakura" name="email" type="email" value="<?= e($u['email']) ?>" required></div>
        <div class="col-md-6"><label class="form-label small text-muted">Phone</label>
          <input class="form-control form-control-sakura" name="phone" value="<?= e($u['phone']) ?>"></div>
        <div class="col-12"><label class="form-label small text-muted">Default shipping address</label>
          <textarea class="form-control form-control-sakura" name="address" rows="3"><?= e($u['address']) ?></textarea></div>
        <div class="col-12"><button class="btn btn-sakura">Save changes</button></div>
      </form>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm rounded-3xl p-4">
      <h5>Change password</h5>
      <form method="post" class="mt-2">
        <input type="hidden" name="form" value="password">
        <input class="form-control form-control-sakura mb-2" name="current" type="password" placeholder="Current password" required>
        <input class="form-control form-control-sakura mb-2" name="new"     type="password" placeholder="New password" required>
        <button class="btn btn-sakura w-100">Update password</button>
      </form>
      <hr class="my-4">
      <a href="orders.php" class="btn btn-sakura-outline w-100">📦 View order history</a>
    </div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
