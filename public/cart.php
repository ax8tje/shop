<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    removeCartItem((int)$_POST['remove_id']);
    header('Location: cart.php');
    exit;
}

$items = getCartItems($pdo);
$total = calculateCartTotal($items);
$pageScripts = [
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js",
    "assets/js/script_cart_animations.js",
    "assets/js/script_burger_menu.js",
];
require '../views/header.php';
?>
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
                        <th>Ilość</th>
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
                            <td><?= (int)$item['quantity'] ?></td>
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

<?php
require '../views/footer.php';
?>