<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    removeCartItem((int)$_POST['remove_id']);
    header('Location: cart.php');
    exit;
}

$items = getCartItems($pdo);
$total = calculateCartTotal($items);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Twój koszyk – MOKO</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <div class="navbar-start">
            <div class="burger-menu" id="burgerToggle">
                <img src="assets/img/burger-menu-white.png" id="burgerIcon">
            </div>
            <h1><a href="landing_page.php" class="logo-link">moko.store</a></h1>
        </div>
        <div class="navbar-end">
            <a href="profile.php" class="navbar-icon">
                <img src="assets/img/profile-icon.png" alt="Profile">
            </a>
            <a href="cart.php" class="navbar-icon">
                <img src="assets/img/shopping-cart1.png" alt="Cart">
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

    <main class="cart-container">
        <div class="title1 hidden">
            <h1>Twój koszyk</h1>
        </div>

        <?php if (empty($items)): ?>
            <p>Twój koszyk jest pusty. <a href="products.php">Wróć do sklepu</a></p>
        <?php else: ?>
            <div class="cart-box hidden">
                <table class="cart-table">
                    <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Nazwa</th>
                        <th>Cena</th>
                        <th>Usuń</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($item['image']) ?>"
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     width="80">
                            </td>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td><?= number_format($item['price'], 2) ?> zł</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="remove_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="remove-button">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <p class="button"><strong>Łącznie:</strong> <?= number_format($total, 2) ?> zł</p>
                    <a href="products.php" class="button">Wróć do sklepu</a>
                    <a href="checkout.php" class="button">Przejdź do zamówienia</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-contact">
            <h3>Kontakt</h3>
            <p>Email: <a href="mailto:kontakt@moko.store">kontakt@moko.store</a></p>
            <p>Telefon: <a href="tel:+48123456789">+48 123 456 789</a></p>
        </div>

        <div class="footer-social">
            <h3>Znajdź nas</h3>
            <a href="#" aria-label="Facebook" class="social-link">
                <img src="assets/img/facebook.png" alt="Facebook" />
            </a>
            <a href="#" aria-label="Instagram" class="social-link">
                <img src="assets/img/instagram.png" alt="Instagram" />
            </a>
            <a href="#" aria-label="Pinterest" class="social-link">
                <img src="assets/img/pinterest.png" alt="Pinterest" />
            </a>
        </div>

        <div class="footer-copy">
            <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>
<script src="assets/js/script_cart_animations.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
