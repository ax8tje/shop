<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/log.php';

requireAdmin();

$actionFilter = trim($_GET['action'] ?? '');
$userFilter   = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$sql = 'SELECT l.*, u.email FROM logs l LEFT JOIN users u ON l.user_id = u.id WHERE 1';
$params = [];
if ($actionFilter !== '') {
    $sql .= ' AND l.action LIKE ?';
    $params[] = $actionFilter;
}
if ($userFilter) {
    $sql .= ' AND l.user_id = ?';
    $params[] = $userFilter;
}
$sql .= ' ORDER BY l.id DESC LIMIT 100';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

$users = $pdo->query('SELECT id, email FROM users ORDER BY email')->fetchAll();

$pageTitle = 'Logi';
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
    <div class="admin-content">
        <h1>Logi systemowe</h1>
        <form method="get">
            <label>Użytkownik:
                <select name="user_id">
                    <option value="">-- wszyscy --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $userFilter==$u['id']?'selected':'' ?>><?= htmlspecialchars($u['email']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Akcja: <input type="text" name="action" value="<?= htmlspecialchars($actionFilter) ?>"></label>
            <button type="submit">Filtruj</button>
        </form>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr><th>ID</th><th>Użytkownik</th><th>Akcja</th><th>Szczegóły</th><th>Data</th></tr>
            <?php foreach ($logs as $l): ?>
                <tr>
                    <td><?= $l['id'] ?></td>
                    <td><?= htmlspecialchars($l['email'] ?? '---') ?></td>
                    <td><?= htmlspecialchars($l['action']) ?></td>
                    <td><?= htmlspecialchars($l['details']) ?></td>
                    <td><?= $l['created_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><a href="dashboard.php">Powrót</a></p>
    </div>
<?php
require '../views/footer.php';
