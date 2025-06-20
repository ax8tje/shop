<?php
require_once '../includes/auth.php';

$token = $_GET['token'] ?? ($_POST['token'] ?? '');
$userId = $token ? validatePasswordResetToken($token) : null;
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$userId) {
        $message = 'Nieprawidłowy lub przeterminowany token.';
    } else {
        $pass = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        if (!$pass || $pass !== $confirm) {
            $message = 'Hasła nie są identyczne.';
        } else {
            resetPassword($userId, $pass);
            $success = true;
        }
    }
}
$pageScripts = [
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
<div class="login-box">
    <div class="login">
        <h1>Ustaw nowe hasło</h1>
        <?php if ($success): ?>
            <p style="color:green;">Hasło zostało zmienione. Możesz się zalogować.</p>
        <?php elseif (!$userId): ?>
            <p style="color:red;">Nieprawidłowy lub przeterminowany token.</p>
        <?php else: ?>
            <?php if ($message): ?>
                <p style="color:red;"><?=htmlspecialchars($message)?></p>
            <?php endif; ?>
            <form method="post" action="reset_password.php">
                <input type="hidden" name="token" value="<?=htmlspecialchars($token)?>">
                <label>Hasło:<br><input type="password" name="password" required></label><br><br>
                <label>Potwierdź hasło:<br><input type="password" name="password_confirm" required></label><br><br>
                <button type="submit">Zapisz</button>
            </form>
        <?php endif; ?>
    </div>
<?php
require '../views/footer.php';
?>