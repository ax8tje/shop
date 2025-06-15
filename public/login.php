<?php
require_once '../includes/auth.php';

$message = '';
if (isset($_GET['registered'])) {
    $message = 'Rejestracja zakończona sukcesem. Możesz się zalogować.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $message = 'Podaj e-mail i hasło.';
    } else {
        $result = loginUser($email, $password);
        if ($result === true) {
            header('Location: index.php'); // Przekieruj do strony głównej lub dashboardu
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
    <div class="login-box">
        <div class="login">
            <h1>Logowanie</h1>
            <?php if ($message): ?>
                <p style="color:red;"><?=htmlspecialchars($message)?></p>
            <?php endif; ?>
            <form method="post" action="login.php">
                <label>Email:<br><input type="email" name="email" required></label><br><br>
                <label>Hasło:<br><input type="password" name="password" required></label><br><br>
                <button type="submit">Zaloguj się</button>
            </form>
            <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
        </div>
    </div>
    <script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
