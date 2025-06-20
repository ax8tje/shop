<?php
require_once '../includes/auth.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email) {
        createPasswordResetToken($email);
    }
    $message = 'Jeśli podany adres istnieje, wysłaliśmy link do resetu hasła.';
}
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
    <div class="login-box">
        <div class="login">
            <h1>Reset hasła</h1>
            <?php if ($message): ?>
                <p style="color:green;"><?=htmlspecialchars($message)?></p>
            <?php endif; ?>
            <form method="post" action="forgot_password.php">
                <label>Email:<br><input type="email" name="email" required></label><br><br>
                <button type="submit">Wyślij link</button>
            </form>
        </div>
    </div>
<?php
require '../views/footer.php';
?>