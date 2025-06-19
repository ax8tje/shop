<?php
session_start();
require_once '../includes/db.php';
// Ostatnio oglądane
$recentIds = $_SESSION['recently_viewed'] ?? [];

// Pobranie kategorii do filtra
$stmtCats   = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $stmtCats->fetchAll(PDO::FETCH_ASSOC);

// Odczyt parametrów GET
$filterCat  = (isset($_GET['category']) && $_GET['category'] !== '')
    ? (int) $_GET['category']
    : null;
$searchTerm = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

// Budowa podstawowego SQL
$sql = "
    SELECT
        p.id,
        p.title,
        p.price,
        p.quantity,
        p.description,
        pi.image_path
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id
";

$conditions = [];
$params     = [];

// Dodaj warunek kategorii
if ($filterCat) {
    $conditions[]     = "p.category_id = :cat";
    $params['cat']    = $filterCat;
}

// Dodaj warunek wyszukiwania
if ($searchTerm !== '') {
    $conditions[]   = "(p.title LIKE :termTitle OR p.description LIKE :termDesc)";
    $params['termTitle'] = "%{$searchTerm}%";
    $params['termDesc']  = "%{$searchTerm}%";
}

// Jeśli mamy jakiekolwiek warunki, doklej je do zapytania
if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= " ORDER BY p.id, pi.id";

// Przygotowanie i jednokrotne wykonanie z wszystkimi parametrami
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Agregacja wyników
$products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pid = $row['id'];
    if (!isset($products[$pid])) {
        $products[$pid] = [
            'title'       => $row['title'],
            'price'       => $row['price'],
            'quantity'    => $row['quantity'],
            'description' => $row['description'],
            'images'      => [],
        ];
    }
    if (!empty($row['image_path'])) {
        $products[$pid]['images'][] = $row['image_path'];
    }
}

// Ostatnio ogladane produkty w sesji
$recentProducts = [];
if (!empty($_SESSION['recently_viewed'])) {
    $recentIds = array_map('intval', $_SESSION['recently_viewed']);
    $placeholders = implode(',', array_fill(0, count($recentIds), '?'));
    $order = implode(',', $recentIds);
    $sqlRecent = "SELECT p.id, p.title, p.price, p.description, pi.image_path
                  FROM products p
                  LEFT JOIN product_images pi ON p.id = pi.product_id
                  WHERE p.id IN ($placeholders)
                  ORDER BY FIELD(p.id, $order), pi.id";
    $stmtRecent = $pdo->prepare($sqlRecent);
    $stmtRecent->execute($recentIds);
    while ($row = $stmtRecent->fetch(PDO::FETCH_ASSOC)) {
        $rid = $row['id'];
        if (!isset($recentProducts[$rid])) {
            $recentProducts[$rid] = [
                'title'       => $row['title'],
                'price'       => $row['price'],
                'description' => $row['description'],
                'images'      => [],
            ];
        }
        if (!empty($row['image_path'])) {
            $recentProducts[$rid]['images'][] = $row['image_path'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Produkty – MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-content">
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

    <main class="main-content">
        <div class="category-filter">
            <form method="get" class="category-filter-form">
                <button type="submit" name="category" value="" <?= $filterCat===null ? 'class="active"' : '' ?>>Wszystkie</button>
                <?php foreach ($categories as $cat): ?>
                    <button
                            type="submit"
                            name="category"
                            value="<?= $cat['id']; ?>"
                        <?= $filterCat===(int)$cat['id'] ? 'class="active"' : '' ?>
                    >
                        <?= htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </form>
            <form method="get" action="products.php" class="search-form">
                <?php if($filterCat): ?>
                    <input type="hidden" name="category" value="<?= $filterCat ?>">
                <?php endif; ?>
                <input type="text" name="search"
                       placeholder="Szukaj produktów…"
                       value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit">Szukaj</button>
            </form>
        </div>

        <div class="products-container">
            <?php if (empty($products)): ?>
                <p>Brak produktów w tej kategorii.</p>
            <?php else: ?>
                <?php foreach ($products as $pid => $product): ?>
                    <div class="product" data-product-id="<?= $pid; ?>">
                        <h2><?= htmlspecialchars($product['title']); ?></h2>

                        <?php if (!empty($product['images'])): ?>
                            <div class="image-slider1">
                                <button class="slide-arrow1 left-arrow1" aria-label="Poprzednie">&lt;</button>
                                <div class="slider-images">
                                    <?php foreach ($product['images'] as $img): ?>
                                        <a href="product.php?id=<?= $pid; ?>">
                                            <img
                                                    src="assets/img/<?= htmlspecialchars($img); ?>"
                                                    class="slide-image1"
                                                    alt="<?= htmlspecialchars($product['title']); ?>">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <button class="slide-arrow1 right-arrow1" aria-label="Następne">&gt;</button>
                            </div>
                        <?php endif; ?>

                        <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
                        <p><strong>Cena:</strong> <?= number_format($product['price'], 2); ?> zł</p>
                        <p><strong>Ilość:</strong> <?= htmlspecialchars($product['quantity']); ?></p>

                        <div class="product-container-buttons">
                            <form method="post" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?= $pid; ?>">
                                <button type="submit" class="buy-button1">Kup</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
        <div class="recently-viewed">
            <?php if (!empty($recentProducts)): ?>
                <h2 class="title">Ostatnio oglądane</h2>
                <div class="products-container">
                    <?php foreach ($recentProducts as $rid => $rprod): ?>
                        <div class="product" data-product-id="<?= $rid; ?>">
                            <h2><?= htmlspecialchars($rprod['title']); ?></h2>
                            <?php if (!empty($rprod['images'])): ?>
                                <a href="product.php?id=<?= $rid; ?>">
                                    <img src="assets/img/<?= htmlspecialchars($rprod['images'][0]); ?>"
                                         class="slide-image1"
                                         alt="<?= htmlspecialchars($rprod['title']); ?>">
                                </a>
                            <?php endif; ?>
                            <p><strong>Cena:</strong> <?= number_format($rprod['price'], 2); ?> zł</p>
                        </div>
                    <?php endforeach; ?>
                </div>
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
            <a href="#" aria-label="Facebook"><img src="assets/img/facebook.png" alt="Facebook"></a>
            <a href="#" aria-label="Instagram"><img src="assets/img/instagram.png" alt="Instagram"></a>
            <a href="#" aria-label="Pinterest"><img src="assets/img/pinterest.png" alt="Pinterest"></a>
        </div>
        <div class="footer-copy">
            <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>
<script src="assets/js/script_products_container_animation.js"></script>
<script src="assets/js/script_products_container.js"></script>
<script src="assets/js/script_profile_dropdown.js"></script>
<script src="assets/js/script_burger_menu.js"></script>
</body>
</html>