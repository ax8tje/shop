<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$recentIds = [];
if (!empty($_COOKIE['recently_viewed'])) {
    $recentIds = json_decode($_COOKIE['recently_viewed'], true);
    if (!is_array($recentIds)) {
        $recentIds = [];
    }
}

$recentProducts = [];
if (!empty($recentIds)) {
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
$pageScripts = [
    "assets/js/script_landing_animations.js",
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
    <main class="landing-content">
        <section class="hero hidden">
            <h2>Unikalna ceramika z pasją</h2>
            <p>Witaj w <strong>moko.store</strong> – miejscu, gdzie każda gliniana praca to ręcznie tworzony mały świat.</p><br>
            <a href="products.php" class="hero-button">Zobacz nasze produkty</a>
        </section>

        <section class="about hidden">
            <h3>O nas</h3>
            <p>Jesteśmy rodzinną pracownią ceramiki, w której każda filiżanka, misa czy wazon powstaje z miłością do detalu. Wierzymy w prostotę, jakość i piękno przedmiotów codziennego użytku.</p>
        </section>

        <section class="highlights">
            <div class="highlight-item hidden">
                <img src="assets/img/glina1/1.jpg" alt="Produkt" />
                <h4>Ręczne wykonanie</h4>
                <p>Każdy produkt tworzony jest ręcznie – nie znajdziesz dwóch identycznych.</p>
            </div>
            <div class="highlight-item hidden">
                <img src="assets/img/glina2/2.jpg" alt="Produkt" />
                <h4>Naturalne materiały</h4>
                <p>Używamy tylko gliny i szkliw przyjaznych środowisku.</p>
            </div>
            <div class="highlight-item hidden">
                <img src="assets/img/glina3/2.jpg" alt="Produkt" />
                <h4>Warsztaty ceramiczne</h4>
                <p>Dołącz do naszych warsztatów i stwórz własne unikalne dzieło z gliny!</p>
            </div>
        </section>
    </main>
<?php
require '../views/footer.php';
?>
