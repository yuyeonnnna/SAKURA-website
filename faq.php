<?php require_once __DIR__.'/includes/config.php'; $pageTitle='FAQ — SAKURA';
include __DIR__.'/includes/header.php'; ?>
<h1 class="my-4">Frequently asked questions</h1>
<div class="accordion" id="faq">
<?php
$qa = [
  ['How long does delivery take?','Yangon: next-day. Other cities: 2–4 working days.'],
  ['Do you offer Cash on Delivery?','Yes — available nationwide. KPay, Wave Pay, AYA Pay, Visa & Master also accepted at checkout.'],
  ['What is your return policy?','7-day exchange on defects. Earrings cannot be returned for hygiene reasons.'],
  ['When do new products drop?','Every Friday at 7 PM (MMT) on our shop page.'],
  ['Are the pieces hypoallergenic?','Most jewelry is nickel-free; sensitive skin friendly tags are noted on each product.'],
];
foreach ($qa as $i=>[$q,$a]): ?>
<div class="accordion-item">
  <h2 class="accordion-header"><button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#q<?= $i ?>"><?= e($q) ?></button></h2>
  <div id="q<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faq"><div class="accordion-body text-muted small"><?= e($a) ?></div></div>
</div>
<?php endforeach; ?>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
