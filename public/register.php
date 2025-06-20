<?php
require_once '../includes/auth.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$email || !$password || !$password_confirm) {
        $message = 'Wszystkie pola są wymagane.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Nieprawidłowy format adresu e-mail.';
    } elseif ($password !== $password_confirm) {
        $message = 'Hasła nie są identyczne.';
    } else {
        $result = registerUser($email, $password);
        if ($result === true) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $message = $result;
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
    <div class="register-box">
        <div class="register">
            <h1>Rejestracja</h1>
            <?php if ($message): ?>
                <p style="color:red;"><?=htmlspecialchars($message)?></p>
            <?php endif; ?>
            <form method="post" action="register.php">
                <label>Email<br><input type="email" name="email" required></label><br>
                <label>Hasło<br><input type="password" name="password" required></label><br>
                <label>Potwierdź hasło<br><input type="password" name="password_confirm" required></label><br>
                <button type="submit">Zarejestruj się</button>
            </form>
            <p>Masz konto? <a href="login.php">Zaloguj się</a></p>
        </div>
    </div>
<?php
require '../views/footer.php';
?>
