<?php require_once __DIR__.'/includes/config.php'; $pageTitle='Find Us — SAKURA'; $active='location';
include __DIR__.'/includes/header.php'; ?>
<section class="py-4 text-center">
  <p class="section-eyebrow">find us</p>
  <h1 class="section-title">Visit the SAKURA studio</h1>
  <p class="text-muted">Walk in, browse the latest drop, and maybe grab a free sticker ✿</p>
</section>
<div class="row g-4 mb-5">
  <div class="col-md-7">
    <div class="rounded-3xl overflow-hidden shadow-sm" style="height:420px">
      <iframe width="100%" height="100%" frameborder="0" style="border:0"
        src="https://www.google.com/maps?q=Yangon%20Bogyoke%20Aung%20San%20Market&output=embed" allowfullscreen></iframe>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card border-0 shadow-sm rounded-3xl p-4 mb-3">
      <h5>📍 Studio address</h5>
      <p class="text-muted small mb-0">No.123, Bogyoke Aung San Road,<br>Pabedan Township, Yangon, Myanmar</p>
    </div>
    <div class="card border-0 shadow-sm rounded-3xl p-4 mb-3">
      <h5>🕒 Opening hours</h5>
      <p class="text-muted small mb-0">Mon – Sat &nbsp; 10:00 AM – 8:00 PM<br>Sunday &nbsp; 12:00 PM – 6:00 PM</p>
    </div>
    <div class="card border-0 shadow-sm rounded-3xl p-4">
      <h5>📞 Phone</h5>
      <p class="text-muted small mb-0">09 123 456 789 · Viber / WhatsApp available</p>
    </div>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
