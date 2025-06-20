<?php
require_once '../includes/auth.php';
require_once '../includes/sidebar.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email) {
        createPasswordResetToken($email);
    }
    $message = 'Jeśli podany adres istnieje, wysłaliśmy link do resetu hasła.';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>MOKO - Reset hasła</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<?php require '../views/navbar.php'; ?>
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
<script src="assets/js/script_burger_menu.js"></script>
<script src="assets/js/script_cart_dropdown.js"></script>
</body>
</html>