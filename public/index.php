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
<div class="navbar">
    <div class="navbar-start">
        <h1>moko.store</h1>
    </div>
    <div class="navbar-end">

    </div>
</div>
<div class="products-container">
    <?php if (empty($products)) : ?>
        <p>Brak produktów w bazie.</p>
    <?php else: ?>
        <?php foreach ($products as $pid => $product): ?>
            <div class="product" data-product-id="<?php echo $pid; ?>">
                <h2><?php echo htmlspecialchars($product['title']); ?></h2>

                <?php if (!empty($product['images'])): ?>
                    <div class="image-slider">
                        <button class="slide-arrow left-arrow">&lt;</button>
                        <div class="slider-images">
                            <?php foreach ($product['images'] as $img): ?>
                                <img src="assets/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="slide-image" />
                            <?php endforeach; ?>
                        </div>
                        <button class="slide-arrow right-arrow">&gt;</button>
                    </div>
                <?php else: ?>
                    <p>Brak zdjęć produktu.</p>
                <?php endif; ?>

                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p><strong>Cena:</strong> <?php echo htmlspecialchars($product['price']); ?> zł</p>
                <hr>
                <button class="buy-button">Kup</button> <button class="cart-button-product"></button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="footer">

</div>
<script src="assets/js/script1.js"></script>
</body>
</html>
