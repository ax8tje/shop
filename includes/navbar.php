<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/cart.php';

$items = getCartItems($pdo);
$count = count($items);
$total = calculateCartTotal($items);
?>
<div class="navbar">
    <div class="navbar-start">
        <div class="burger-menu" id="burgerToggle">
            <img src="assets/img/burger-menu-white.png" id="burgerIcon" alt="Menu">
        </div>
        <h1><a href="landing_page.php" class="logo-link">moko.store</a></h1>
    </div>
    <div class="navbar-end">
        <div class="profile-wrapper">
            <a href="#" id="profileToggle" class="navbar-icon">
                <img src="assets/img/profile-icon.png" alt="Profile">
            </a>
            <div id="profileDropdown" class="profile-dropdown">
                <a href="profile.php">Profil</a>
                <a href="logout.php">Wyloguj</a>
            </div>
        </div>
        <div class="cart-wrapper">
            <a href="#" id="cartToggle" class="navbar-icon">
                <img src="assets/img/shopping-cart1.png" alt="Cart">
            </a>
            <div id="cartDropdown" class="cart-dropdown">
                Koszyk: <?= $count ?> szt., <?= number_format($total, 2) ?> zł
            </div>
        </div>
    </div>
</div>