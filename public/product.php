<?php
require_once '../includes/db.php';

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
            <h1>moko.store</h1>
        </div>

        <div class="navbar-end">
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <div class="slide-menu-content">
            <a href="landing_page.php">Strona główna</a>
            <a href="products.php">Produkty</a>
            <a href="contact.php">Kontakt</a>
        </div>
    </div>
    <div class="product-page-content">
        <h1><?= htmlspecialchars($product['title']) ?></h1>
        <p>Cena: <?= number_format($product['price'], 2) ?> PLN</p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <?php if (!empty($product['images'])): ?>
            <div class="image-slider2">
                <button class="slide-arrow left-arrow">&lt;</button>
                <div class="slider-images">
                    <?php foreach ($product['images'] as $img): ?>
                        <a href="product.php?id=<?php echo $pid; ?>">
                            <img src="assets/img/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="slide-image2" />
                        </a>
                    <?php endforeach; ?>
                </div>
                <button class="slide-arrow right-arrow">&gt;</button>
            </div>
        <?php else: ?>
            <p>Brak zdjęć produktu.</p>
        <?php endif; ?>

    </div>
    <script src="assets/js/script_product_page.js"></script>
    <script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
