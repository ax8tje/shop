<?php
if (!isset($pageTitle)) {
    $pageTitle = 'MOKO';
}
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/auth.php';

$basePath = '';
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
if (strpos($scriptDir, '/admin') !== false) {
    $basePath = '../public/';
}


$cartItems  = getCartItems($pdo);
$cartCount  = count($cartItems);
$cartTotal  = calculateCartTotal($cartItems);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?> – MOKO</title>
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <div class="navbar-start">
            <div class="burger-menu" id="burgerToggle">
                <img src="<?= $basePath ?>assets/img/burger-menu-white.png" id="burgerIcon" alt="Menu">
            </div>
            <h1><a href="<?= $basePath ?>landing_page.php" class="logo-link">moko.store</a></h1>
        </div>
        <div class="navbar-end">
            <div class="profile-wrapper">
                <a href="#" id="profileToggle" class="navbar-icon">
                    <img src="<?= $basePath ?>assets/img/profile-icon.png" alt="Profile">
                </a>
                <div id="profileDropdown" class="profile-dropdown">
                    <a href="profile.php">Profil</a>
                    <a href="logout.php">Wyloguj</a>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/dashboard.php">Panel admina</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="cart-wrapper">
                <a href="#" id="cartToggle" class="navbar-icon">
                    <img src="<?= $basePath ?>assets/img/shopping-cart1.png" alt="Cart">
                </a>
                <div id="cartDropdown" class="cart-dropdown">
                    Koszyk: <?= $cartCount ?> szt., <?= number_format($cartTotal, 2) ?> zł
                    <a href="<?= $basePath ?>cart.php" class="view-cart-link">Pokaż koszyk</a>
                </div>
            </div>
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <div class="slide-menu-content">
            <a href="<?= $basePath ?>landing_page.php">Strona główna</a>
            <a href="<?= $basePath ?>products.php">Produkty</a>
            <a href="<?= $basePath ?>contact.php">Kontakt</a>
        </div>
    </div>