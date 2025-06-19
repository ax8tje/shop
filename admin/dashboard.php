<?php
require_once '../includes/auth.php';
if (!isAdmin()) {
    header('Location: ../public/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
<h1>Panel administracyjny</h1>
<ul>
    <li><a href="add_product.php">Zarządzaj produktami</a></li>
    <li><a href="users.php">Zarządzaj użytkownikami</a></li>
    <li><a href="orders.php">Zarządzaj zamówieniami</a></li>
</ul>
</body>
</html>