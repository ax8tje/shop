<?php
require_once '../includes/auth.php';

$token = $_GET['token'] ?? '';
$verified = false;
if ($token) {
    $verified = User::verifyByToken($pdo, $token);
}

$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
<div class="login-box">
    <div class="login">
        <h1>Weryfikacja e-mail</h1>
        <?php if ($verified): ?>
            <p style="color:green;">Adres e-mail został potwierdzony. Możesz się zalogować.</p>
        <?php else: ?>
            <p style="color:red;">Nieprawidłowy lub wykorzystany token.</p>
        <?php endif; ?>
    </div>
</div>
<?php
require '../views/footer.php';
?>
