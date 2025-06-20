<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$user = null;

if ($action === 'delete' && $id) {
    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        if ($email && $password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (email, password, role) VALUES (?,?,?)');
            $stmt->execute([$email, $hash, $role]);
            header('Location: users.php');
            exit;
        } else {
            $message = 'Wszystkie pola są wymagane.';
        }
    } elseif ($action === 'edit' && $id) {
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'user';
        if ($email) {
            $stmt = $pdo->prepare('UPDATE users SET email = ?, role = ? WHERE id = ?');
            $stmt->execute([$email, $role, $id]);
            if (!empty($_POST['password'])) {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $id]);
            }
            header('Location: users.php');
            exit;
        } else {
            $message = 'Email jest wymagany.';
        }
    }
}

if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        header('Location: users.php');
        exit;
    }
}

$users = $pdo->query('SELECT id, email, role, created_at FROM users ORDER BY id DESC')->fetchAll();
?>
<?php
$pageTitle = 'Użytkownicy';
$pageScripts = [
        "assets/js/script_burger_menu.js",
        "assets/js/script_profile_dropdown.js",
        "assets/js/script_cart_dropdown.js"
];
require '../views/header.php';
?>
<div class="admin-content">
    <h1>Użytkownicy</h1>
    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <form method="post">
            <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></label><br>
            <label>Hasło: <input type="password" name="password" <?= $action === 'add' ? 'required' : '' ?>></label><br>
            <label>Rola:
                <select name="role">
                    <option value="user" <?= ($user['role'] ?? '') === 'user' ? 'selected' : '' ?>>user</option>
                    <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>admin</option>
                </select>
            </label><br>
            <button type="submit">Zapisz</button>
        </form>
        <p><a href="users.php">Powrót</a></p>
    <?php else: ?>
        <p><a href="users.php?action=add">Dodaj użytkownika</a></p>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr><th>ID</th><th>Email</th><th>Rola</th><th>Utworzono</th><th>Akcje</th></tr>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td><?= $u['created_at'] ?></td>
                    <td>
                        <a href="users.php?action=edit&id=<?= $u['id'] ?>">Edytuj</a> |
                        <a href="users.php?action=delete&id=<?= $u['id'] ?>" onclick="return confirm('Usunąć użytkownika?');">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</div>
<?php
require '../views/footer.php';
?>