<?php

function addToCart(int $productId, int $quantity = 1): void {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function updateCartItem(int $productId, int $quantity): void {
    if (!isset($_SESSION['cart'])) return;
    if ($quantity > 0) {
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        unset($_SESSION['cart'][$productId]);
    }
}

function removeCartItem(int $productId): void {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function getCartItems(PDO $pdo): array {
    $items = $_SESSION['cart'] ?? [];
    $result = [];
    foreach ($items as $productId => $qty) {
        $stmt = $pdo->prepare("SELECT id, title, price FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($p) {
            $p['quantity'] = $qty;
            $stmt2 = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? LIMIT 1");
            $stmt2->execute([$productId]);
            $img = $stmt2->fetchColumn();
            $p['image'] = $img ? "assets/img/{$img}" : 'assets/img/no-image.png';
            $result[] = $p;
        }
    }
    return $result;
}

function calculateCartTotal(array $items): float {
    $sum = 0;
    foreach ($items as $it) {
        $sum += $it['price'] * $it['quantity'];
    }
    return $sum;
}
