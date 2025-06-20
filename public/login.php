<?php
require_once '../includes/auth.php';
require_once '../includes/sidebar.php';

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
            header('Location: landing_page.php');
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
<?php require '../views/navbar.php'; ?>
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
        <p><a href="forgot_password.php">Zapomniałeś hasła?</a></p>
    </div>
</div>
<script src="assets/js/script_burger_menu.js"></script>
<script src="assets/js/script_cart_dropdown.js"></script>
</body>
</html>
