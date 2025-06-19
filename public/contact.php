<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-content"></div>
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
                <div class="profile-wrapper">
                    <a href="#" id="profileToggle" class="navbar-icon">
                        <img src="assets/img/profile-icon.png" alt="Profile">
                    </a>
                    <div id="profileDropdown" class="profile-dropdown">
                        <a href="profile.php">Profil</a>
                        <a href="logout.php">Wyloguj</a>
                    </div>
                </div>

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
    </div>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-contact">
                <h3>Kontakt</h3>
                <p>Email: <a href="mailto:kontakt@moko.store">kontakt@moko.store</a></p>
                <p>Telefon: <a href="tel:+48123456789">+48 123 456 789</a></p>
            </div>

            <div class="footer-social">
                <h3>Znajdź nas</h3>
                <a href="#" aria-label="Facebook" class="social-link">
                    <img src="assets/img/facebook.png" alt="Facebook" />
                </a>
                <a href="#" aria-label="Instagram" class="social-link">
                    <img src="assets/img/instagram.png" alt="Instagram" />
                </a>
                <a href="#" aria-label="Pinterest" class="social-link">
                    <img src="assets/img/pinterest.png" alt="Pinterest" />
                </a>
            </div>

            <div class="footer-copy">
                <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
            </div>
        </div>
    </footer>
    <script src="assets/js/script_profile_dropdown.js"></script>
    <script src="assets/js/script_burger_menu.js"></script>
</body>
</html>
