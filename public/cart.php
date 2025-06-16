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
        <h1>
            <a href="landing_page.php" class="logo-link">moko.store</a>
        </h1>
    </div>

    <div class="navbar-end">
        <a href="profile.php" class="navbar-icon">
            <img src="assets/img/profile-icon.png" alt="Profile" />
        </a>

        <a href="cart.php" class="navbar-icon">
            <img src="assets/img/shopping-cart1.png" alt="Cart" />
        </a>
    </div>
</div>

<div class="side-menu" id="sideMenu">
    <div class="slide-menu-content">
        <a href="landing_page.php">Strona główna</a>
        <a href="products.php">Produkty</a>
        <a href="contact.php">Kontakt</a>
    </div>
</div>
<script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
