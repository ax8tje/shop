<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    if ($productId && $rating >= 1 && $rating <= 5 && $comment !== '') {
        $stmt = $pdo->prepare('INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?,?,?,?)');
        $stmt->execute([$productId, $_SESSION['user_id'], $rating, $comment]);
    }
    header('Location: product.php?id=' . $productId);
    exit;
}

header('Location: products.php');

