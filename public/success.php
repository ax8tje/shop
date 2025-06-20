<?php
require_once '../includes/db.php';

requireLogin();

$orderId = isset($_GET['order']) ? (int)$_GET['order'] : 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

$items = [];
if ($order) {
    $stmtItems = $pdo->prepare(
        "SELECT p.title, oi.quantity, oi.price
         FROM order_items oi
         JOIN products p ON oi.product_id = p.id
         WHERE oi.order_id = ?"
    );
    $stmtItems->execute([$orderId]);
    $items = $stmtItems->fetchAll();
}
$pageScripts = [
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
    <main class="checkout-container">
        <?php if ($order): ?>
            <h1>Dziękujemy za zakupy!</h1>
            <p>Twoje zamówienie nr <?= $orderId ?> zostało przyjęte.</p>
            <ul class="checkout-items">
                <?php foreach ($items as $it): ?>
                    <li>
                        <?= htmlspecialchars($it['title']) ?> —
                        <?= $it['quantity'] ?> × <?= number_format($it['price'], 2) ?> zł
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="checkout-total"><strong>Razem:</strong> <?= number_format($order['total'], 2) ?> zł</p>
        <?php else: ?>
            <p>Nie znaleziono zamówienia.</p>
        <?php endif; ?>
        <p><a href="products.php" class="hero-button">Powrót do produktów</a></p>
    </main>
<?php
require '../views/footer.php';
?>
