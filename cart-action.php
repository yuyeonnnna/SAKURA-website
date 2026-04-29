<?php
require_once __DIR__.'/includes/db_helpers.php';
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id     = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
$qty    = (int)($_POST['qty'] ?? 1);
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
switch ($action) {
  case 'add':    if ($id) $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1,$qty); break;
  case 'set':    if ($id) { if ($qty<=0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id]=$qty; } break;
  case 'remove': unset($_SESSION['cart'][$id]); break;
  case 'clear':  $_SESSION['cart'] = []; break;
}
$back = $_POST['back'] ?? $_GET['back'] ?? 'cart.php';
redirect($back);
