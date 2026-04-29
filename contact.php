<?php
require_once __DIR__.'/includes/db_helpers.php';
$sent=false; $err=null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $n=trim($_POST['name']??''); $em=trim($_POST['email']??''); $s=trim($_POST['subject']??''); $m=trim($_POST['message']??'');
  if (!$n||!$em||!$m) $err='Please fill in name, email, and message.';
  else {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$n,$em,$s,$m); $stmt->execute(); $sent=true;
  }
}
$pageTitle='Contact — SAKURA'; $active='contact';
include __DIR__.'/includes/header.php';
?>
<section class="py-4 text-center">
  <p class="section-eyebrow">say hi ✿</p>
  <h1 class="section-title">Let's be friends</h1>
  <p class="text-muted">Questions, custom orders, or just wanna chat? We answer every message.</p>
</section>
<div class="row g-4 my-2">
  <div class="col-md-8">
    <div class="card border-0 shadow-sm rounded-3xl p-4 p-md-5">
      <?php if ($sent): ?><div class="alert alert-success">Message sent ✿ We'll get back soon.</div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
      <form method="post" class="row g-3">
        <div class="col-md-6"><label class="small text-uppercase text-muted">Your name</label>
          <input class="form-control form-control-sakura" name="name" required></div>
        <div class="col-md-6"><label class="small text-uppercase text-muted">Email</label>
          <input class="form-control form-control-sakura" name="email" type="email" required></div>
        <div class="col-12"><label class="small text-uppercase text-muted">Subject</label>
          <input class="form-control form-control-sakura" name="subject"></div>
        <div class="col-12"><label class="small text-uppercase text-muted">Message</label>
          <textarea class="form-control form-control-sakura" name="message" rows="5" required></textarea></div>
        <div class="col-12"><button class="btn btn-sakura">Send message</button></div>
      </form>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3xl p-4 mb-3"><h6>Email</h6><p class="text-muted small mb-0">hello@sakura-shop.com</p></div>
    <div class="card border-0 shadow-sm rounded-3xl p-4 mb-3"><h6>Studio</h6><p class="text-muted small mb-0">Yangon, Myanmar</p></div>
    <div class="card border-0 shadow-sm rounded-3xl p-4"><h6>Find us online</h6><p class="text-muted small mb-0">Instagram · TikTok · Facebook</p></div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
