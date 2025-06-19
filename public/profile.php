<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['user']['id'] ?? null;
$orders = [];
$lastAddress = null;
if ($uid) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$uid]);
    $orders = $stmt->fetchAll();
    if ($orders) {
        $lastAddress = $orders[0];
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="navbar">
    <div class="navbar-start">
        <div class="burger-menu" id="burgerToggle">
            <img src="assets/img/burger-menu-white.png" id="burgerIcon">
        </div>
        <h1>
            <a href="landing_page.php" class="logo-link">moko.store</a>
        </h1>
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

        <a href="cart.php" class="navbar-icon">
            <img src="assets/img/shopping-cart1.png" alt="Cart" />
        </a>
    </div>
</div>

<div class="side-menu" id="sideMenu">
    <div class="slide-menu-content">
        <a href="landing_page.php">Strona główna</a>
        <a href="products.php">Produkty</a>
        <a href="contact.php">Kontakt</a>
    </div>
</div>
<main class="profile-container">
    <div class="profile-nav">
        <button data-target="info" class="active">Profil</button>
        <button data-target="address">Adres</button>
        <button data-target="history">Historia</button>
    </div>

    <div id="info" class="profile-view active">
        <p><strong>Email:</strong> <?=htmlspecialchars($_SESSION['user_email'] ?? '')?></p>
        <!-- Tutaj można dodać więcej danych użytkownika -->
    </div>

    <div id="address" class="profile-view">
        <?php if ($lastAddress): ?>
            <p><?=htmlspecialchars($lastAddress['full_name'])?></p>
            <p><?=htmlspecialchars($lastAddress['address'])?></p>
            <p><?=htmlspecialchars($lastAddress['postal_code'])?> <?=htmlspecialchars($lastAddress['city'])?></p>
            <p><?=htmlspecialchars($lastAddress['country'])?></p>
        <?php else: ?>
            <p>Brak zapisanych adresów.</p>
        <?php endif; ?>
    </div>

    <div id="history" class="profile-view">
        <?php if ($orders): ?>
            <ul class="order-history">
                <?php foreach ($orders as $o): ?>
                    <li>Zamówienie #<?=$o['id']?> – <?=number_format($o['total'],2)?> zł</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Brak zamówień.</p>
        <?php endif; ?>
    </div>
    <p style="text-align:center; margin-top:1rem;"><a href="logout.php" class="hero-button">Wyloguj się</a></p>
</main>
<script src="assets/js/script_profile_dropdown.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
<script src="assets/js/script_profile.js"></script>
</body>
</html>

