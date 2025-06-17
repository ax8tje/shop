<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    addToCart($pid, 1);
}

header('Location: products.php');
exit;
