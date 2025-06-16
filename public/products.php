<?php
require_once '../includes/db.php';

try {
    $stmt = $pdo->query("
        SELECT p.id, p.title, p.price, p.description, pi.image_path
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id
        ORDER BY p.id, pi.id
    ");

    $products = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $pid = $row['id'];

        if (!isset($products[$pid])) {
            $products[$pid] = [
                'title' => $row['title'],
                'price' => $row['price'],
                'description' => $row['description'],
                'images' => []
            ];
        }

        if (!empty($row['image_path'])) {
            $products[$pid]['images'][] = $row['image_path'];
        }
    }

} catch (PDOException $e) {
    echo "Błąd zapytania: " . $e->getMessage();
    exit;
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
<div class="main-content">
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

    <h1 class="title">Produkty</h1>

    <div class="products-container">
        <?php if (empty($products)) : ?>
            <p>Brak produktów w bazie.</p>
        <?php else: ?>
            <?php foreach ($products as $pid => $product): ?>
                <div class="product" data-product-id="<?php echo $pid; ?>">
                    <h2><?php echo htmlspecialchars($product['title']); ?></h2>

                    <?php if (!empty($product['images'])): ?>
                        <div class="image-slider1">
                            <button class="slide-arrow1 left-arrow1">&lt;</button>
                            <div class="slider-images">
                                <?php foreach ($product['images'] as $img): ?>
                                    <a href="product.php?id=<?php echo $pid; ?>">
                                        <img src="assets/img/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="slide-image1" />
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <button class="slide-arrow1 right-arrow1">&gt;</button>
                        </div>
                    <?php else: ?>
                        <p>Brak zdjęć produktu.</p>
                    <?php endif; ?>

                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p><strong>Cena:</strong> <?php echo htmlspecialchars($product['price']); ?> zł</p>
                    <div class="product-container-buttons">
                        <button class="buy-button">Kup</button>
                        <button class="cart-button-product no-img"></button>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
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
<script src="assets/js/script_products_container_animation.js"></script>
<script src="assets/js/script_products_container.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
