<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireSeller();

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $oid = (int)$_POST['order_id'];
    $status = $_POST['status'] ?? 'new';
    $allowed = ['new', 'paid', 'shipped'];
    if (isAdmin() || isSeller()) {
        $allowed[] = 'closed';
    }
    if (in_array($status, $allowed, true)) {
        $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$status, $oid]);
    }
    header('Location: orders.php');
    exit;
}

if ($action === 'view' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$id]);
    $order = $stmt->fetch();
    if ($order) {
        $it = $pdo->prepare('SELECT p.title, oi.quantity, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
        $it->execute([$id]);
        $items = $it->fetchAll();
    } else {
        $order = null;
        $items = [];
    }
} else {
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC');
    $orders = $stmt->fetchAll();
}
?>
<?php
$pageTitle = 'Zamówienia';
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js"
];
require '../views/header.php';
?>
<div class="admin-content">
    <h1>Zamówienia</h1>
    <?php if ($action === 'view' && $order): ?>
        <p><strong>Zamówienie #<?= $order['id'] ?></strong> - status: <?= $order['status'] ?></p>
        <ul>
            <?php foreach ($items as $it): ?>
                <li><?= htmlspecialchars($it['title']) ?> - <?= $it['quantity'] ?> × <?= number_format($it['price'],2) ?> zł</li>
            <?php endforeach; ?>
        </ul>
        <p>Suma: <?= number_format($order['total'],2) ?> zł</p>
        <p><a href="orders.php">Powrót</a></p>
    <?php elseif ($action === 'view'): ?>
        <p>Zamówienie nie istnieje. <a href="orders.php">Powrót</a></p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr><th>ID</th><th>Email</th><th>Suma</th><th>Status</th><th>Akcje</th></tr>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['email']) ?></td>
                    <td><?= number_format($o['total'],2) ?> zł</td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                            <select name="status">
                                <?php foreach (['new','paid','shipped','closed'] as $st): ?>
                                    <option value="<?= $st ?>" <?= $o['status']===$st?'selected':'' ?>><?= $st ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" name="update_status">OK</button>
                        </form>
                    </td>
                    <td><a href="orders.php?action=view&id=<?= $o['id'] ?>">Szczegóły</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<?php
require '../views/footer.php';
?>