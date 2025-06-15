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
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="navbar-start">
            <div class="burger-menu" id="burgerToggle">
                <img src="assets/img/burger-menu-white.png" id="burgerIcon">
            </div>
            <h1>moko.store</h1>
        </div>

        <div class="navbar-end">
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <div class="slide-menu-content">
            <a href="landing_page.php">Strona główna</a>
            <a href="products.php">Produkty</a>
            <a href="contact.php">Kontakt</a>
        </div>
    </div>
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
    <div>
    <script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
