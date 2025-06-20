<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/cart.php';
require_once '../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_id'])) {
    addToCart((int)$_POST['buy_id'], 1);
    header('Location: cart.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Produkt nie został podany.";
    exit;
}

$pid = (int)$_GET['id'];

$product = Product::findById($pdo, $pid);

if (!$product) {
    echo "Nie znaleziono produktu.";
    exit;
}

$images = $product->images($pdo);

if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}
$recent = &$_SESSION['recently_viewed'];
$recent = array_values(array_diff($recent, [$pid]));
array_unshift($recent, $pid);
$_SESSION['recently_viewed'] = array_slice($recent, 0, 5);

$cookieName = 'recently_viewed';
$cookieData = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];
if (!is_array($cookieData)) {
    $cookieData = [];
}
$cookieData = array_values(array_diff($cookieData, [$pid]));
array_unshift($cookieData, $pid);
$cookieData = array_slice($cookieData, 0, 5);
setcookie($cookieName, json_encode($cookieData), time() + 60*60*24*30, '/');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($product->title) ?> – MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-content">
    <?php require '../includes/navbar.php'; ?>
    <div class="side-menu" id="sideMenu">
        <div class="slide-menu-content">
            <a href="landing_page.php">Strona główna</a>
            <a href="products.php">Produkty</a>
            <a href="contact.php">Kontakt</a>
        </div>
    </div>

    <div class="product-page-box hidden">
        <div class="image-section">
            <div class="image-slider2">
                <button class="slide-arrow2 left-arrow2">&lt;</button>
                <div class="slider-images">
                    <?php foreach ($images as $img): ?>
                        <img src="assets/img/<?php echo htmlspecialchars($img); ?>"
                             alt="<?php echo htmlspecialchars($product->title); ?>"
                             class="slide-image2" />
                    <?php endforeach; ?>
                </div>
                <button class="slide-arrow2 right-arrow2">&gt;</button>
            </div>
            <div class="thumbnail-slider-wrapper">
                <button class="thumb-arrow left-thumb">&lt;</button>
                <div class="thumbnail-slider">
                    <?php foreach ($images as $index => $img): ?>
                        <img
                                src="assets/img/<?php echo htmlspecialchars($img); ?>"
                                class="thumbnail-image <?php echo $index === 0 ? 'active-thumbnail' : ''; ?>"
                                data-index="<?= $index ?>"
                        />
                    <?php endforeach; ?>
                </div>
                <button class="thumb-arrow right-thumb">&gt;</button>
            </div>
        </div>

        <div class="product-details">
            <h1 class="product-title"><?= htmlspecialchars($product->title) ?></h1>
            <p class="product-description"><?= nl2br(htmlspecialchars($product->description)) ?></p>
            <p class="product-price"><strong>Cena:</strong> <?= number_format($product->price, 2) ?> zł</p>
            <form method="post" class="buy-form">
                <input type="hidden" name="buy_id" value="<?= $pid ?>">
                <button type="submit" class="buy-button">Dodaj do koszyka</button>
            </form>
        </div>
    </div>
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
            <a href="#" aria-label="Facebook" class="social-link"><img src="assets/img/facebook.png" alt="Facebook" /></a>
            <a href="#" aria-label="Instagram" class="social-link"><img src="assets/img/instagram.png" alt="Instagram" /></a>
            <a href="#" aria-label="Pinterest" class="social-link"><img src="assets/img/pinterest.png" alt="Pinterest" /></a>
        </div>
        <div class="footer-copy">
            <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>

<script src="assets/js/script_product_animations.js"></script>
<script src="assets/js/script_profile_dropdown.js"></script>
<script src="assets/js/script_product_page.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
<script src="assets/js/script_cart_dropdown.js"></script>
</body>
</html>
