<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/cart.php';

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

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$pid]);
$product = $stmt->fetch();

if (!$product) {
    echo "Nie znaleziono produktu.";
    exit;
}

$stmt_imgs = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$stmt_imgs->execute([$pid]);
$images = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);
$product['images'] = $images;

if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}
$recent = &$_SESSION['recently_viewed'];
$recent = array_values(array_diff($recent, [$pid]));
array_unshift($recent, $pid);
$_SESSION['recently_viewed'] = array_slice($recent, 0, 5);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($product['title']) ?> – MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
                <img src="assets/img/profile-icon.png" alt="Profile" />
            </a>
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

    <div class="product-page-box hidden">
        <div class="image-section">
            <div class="image-slider2">
                <button class="slide-arrow2 left-arrow2">&lt;</button>
                <div class="slider-images">
                    <?php foreach ($product['images'] as $img): ?>
                        <img src="assets/img/<?php echo htmlspecialchars($img); ?>"
                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                             class="slide-image2" />
                    <?php endforeach; ?>
                </div>
                <button class="slide-arrow2 right-arrow2">&gt;</button>
            </div>
            <div class="thumbnail-slider-wrapper">
                <button class="thumb-arrow left-thumb">&lt;</button>
                <div class="thumbnail-slider">
                    <?php foreach ($product['images'] as $index => $img): ?>
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
            <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
            <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p class="product-price"><strong>Cena:</strong> <?= number_format($product['price'], 2) ?> zł</p>
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
<script src="assets/js/script_product_page.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
