<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/auth.php';

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
                <a href="../public/profile.php">Profil</a>
                <a href="../public/logout.php">Wyloguj</a>
                <?php if (isAdmin() || isSeller()): ?>
                    <a href="../admin/dashboard.php">Panel admina</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="cart-wrapper">
            <a href="#" id="cartToggle" class="navbar-icon">
                <img src="assets/img/shopping-cart1.png" alt="Cart">
            </a>
            <div id="cartDropdown" class="cart-dropdown">
                Koszyk: <?= $count ?> szt., <?= number_format($total, 2) ?> zł
                <a href="../public/cart.php" class="view-cart-link">Pokaż koszyk</a>
            </div>
        </div>
    </div>
</div>