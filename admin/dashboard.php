<?php
require_once '../includes/auth.php';
requireSeller();
$pageTitle = 'Dodaj produkt';
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
<div class="admin-content">
    <h1>Panel administracyjny</h1>
    <ul>
        <li><a href="../admin/add_product.php">Dodaj produkty</a></li>
        <li><a href="../admin/edit_product.php">Edytuj produkty</a></li>
        <li><a href="../admin/users.php">Zarządzaj użytkownikami</a></li>
        <li><a href="../admin/orders.php">Zarządzaj zamówieniami</a></li>
        <li><a href="../admin/logs.php">Logi systemowe</a></li>
    </ul>
</div>
<?php
require '../views/footer.php';
?>