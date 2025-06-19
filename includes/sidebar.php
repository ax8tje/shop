<?php
require_once 'db.php';
require_once 'cart.php';
function renderSidebar(PDO $pdo) {
    $items = getCartItems($pdo);
    $count = count($items);
    $total = calculateCartTotal($items);
    ?>
    <div class="side-menu" id="sideMenu">
        <div class="side-cart">
            <p>Koszyk: <?= $count ?> szt., <?= number_format($total,2) ?> zł</p>
            <a href="cart.php">Pokaż koszyk</a>
        </div>
        <div class="slide-menu-content">
            <a href="landing_page.php">Strona główna</a>
            <a href="products.php">Produkty</a>
            <a href="contact.php">Kontakt</a>
        </div>
    </div>
<?php } ?>
