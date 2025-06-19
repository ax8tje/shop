<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/cart.php';

$items = getCartItems($pdo);
$total = calculateCartTotal($items);

$errors = [];
// Obsługa formularza:
if ($_SERVER['REQUEST_METHOD']==='POST') {
    // 1) Walidacja
    $fn    = trim($_POST['full_name']    ?? '');
    $email = trim($_POST['email']        ?? '');
    $addr  = trim($_POST['address']      ?? '');
    $city  = trim($_POST['city']         ?? '');
    $zip   = trim($_POST['postal_code']  ?? '');
    $country = trim($_POST['country']    ?? '');

    if ($fn==='')    $errors['full_name']   = 'Podaj imię i nazwisko.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email']        = 'Nieprawidłowy e-mail.';
    if ($addr==='')  $errors['address']     = 'Podaj adres.';
    if ($city==='')  $errors['city']        = 'Podaj miasto.';
    if ($zip==='')   $errors['postal_code'] = 'Podaj kod pocztowy.';
    if ($country==='') $errors['country']   = 'Podaj kraj.';

    if (empty($errors)) {
        // 2) Zapis do orders
        $stmt = $pdo->prepare("
          INSERT INTO orders
            (user_id, full_name, email, address, city, postal_code, country, total)
          VALUES
            (:uid, :fn, :email, :addr, :city, :zip, :country, :total)
        ");
        $stmt->execute([
            'uid'     => $_SESSION['user']['id'] ?? null,
            'fn'      => $fn,
            'email'   => $email,
            'addr'    => $addr,
            'city'    => $city,
            'zip'     => $zip,
            'country' => $country,
            'total'   => $total
        ]);
        $orderId = $pdo->lastInsertId();

        // 3) Zapis pozycji
        $stmtItem = $pdo->prepare("
          INSERT INTO order_items
            (order_id, product_id, quantity, price)
          VALUES
            (:oid, :pid, :qty, :price)
        ");
        foreach ($items as $item) {
            $stmtItem->execute([
                'oid'   => $orderId,
                'pid'   => $item['id'],
                'qty'   => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // 4) Opróżnij koszyk i przekieruj na potwierdzenie
        clearCart();  // funkcja w includes/cart.php
        header("Location: success.php?order={$orderId}");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Checkout – MOKO</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <div class="navbar-start">
            <div class="burger-menu" id="burgerToggle">
                <img src="assets/img/burger-menu-white.png" id="burgerIcon" alt="Menu">
            </div>
            <h1><a href="landing_page.php" class="logo-link">moko.store</a></h1>
        </div>
        <div class="navbar-end">
            <a href="profile.php" class="navbar-icon"><img src="assets/img/profile-icon.png" alt="Profile"></a>
            <a href="cart.php"    class="navbar-icon"><img src="assets/img/shopping-cart1.png" alt="Cart"></a>
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <div class="slide-menu-content">
            <a href="landing_page.php">Strona główna</a>
            <a href="products.php">Produkty</a>
            <a href="contact.php">Kontakt</a>
        </div>
    </div>

    <main class="checkout-container">
        <h1>Podsumowanie zamówienia</h1>

        <!-- lista produktów -->
        <?php if (empty($items)): ?>
            <p>Twój koszyk jest pusty.</p>
        <?php else: ?>
            <ul class="checkout-items">
                <?php foreach ($items as $it): ?>
                    <li>
                        <?= htmlspecialchars($it['title']) ?> —
                        <?= $it['quantity'] ?> × <?= number_format($it['price'],2) ?> zł
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="checkout-total"><strong>Razem:</strong> <?= number_format($total,2) ?> zł</p>
        <?php endif; ?>

        <!-- formularz -->
        <?php if ($items): ?>
            <?php if ($errors): ?>
                <ul class="form-errors">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="post" class="checkout-form">
                <label>Imię i nazwisko:
                    <input id="full_name" type="text" name="full_name" required aria-describedby="err-full_name" value="<?=htmlspecialchars($fn ?? '')?>">
                    <span class="error-text" id="err-full_name" role="alert"><?= htmlspecialchars($errors['full_name'] ?? '') ?></span>
                </label>
                <label>E-mail:
                    <input id="email" type="email" name="email" required aria-describedby="err-email" value="<?=htmlspecialchars($email ?? '')?>">
                    <span class="error-text" id="err-email" role="alert"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
                </label>
                <label>Ulica, nr domu:
                    <input id="address" type="text" name="address" required aria-describedby="err-address" value="<?=htmlspecialchars($addr ?? '')?>">
                    <span class="error-text" id="err-address" role="alert"><?= htmlspecialchars($errors['address'] ?? '') ?></span>
                </label>
                <label>Miasto:
                    <input id="city" type="text" name="city" required aria-describedby="err-city" value="<?=htmlspecialchars($city ?? '')?>">
                    <span class="error-text" id="err-city" role="alert"><?= htmlspecialchars($errors['city'] ?? '') ?></span>
                </label>
                <label>Kod pocztowy:
                    <input id="postal_code" type="text" name="postal_code" pattern="[0-9]{2}-[0-9]{3}" required aria-describedby="err-postal_code" value="<?=htmlspecialchars($zip ?? '')?>">
                    <span class="error-text" id="err-postal_code" role="alert"><?= htmlspecialchars($errors['postal_code'] ?? '') ?></span>
                </label>
                <label>Kraj:
                    <input id="country" type="text" name="country" required aria-describedby="err-country" value="<?=htmlspecialchars($country ?? '')?>">
                    <span class="error-text" id="err-country" role="alert"><?= htmlspecialchars($errors['country'] ?? '') ?></span>
                </label>
            </form>
        <?php endif; ?>

    </main>
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
            <a href="#" aria-label="Facebook"><img src="assets/img/facebook.png" alt="Facebook"></a>
            <a href="#" aria-label="Instagram"><img src="assets/img/instagram.png" alt="Instagram"></a>
            <a href="#" aria-label="Pinterest"><img src="assets/img/pinterest.png" alt="Pinterest"></a>
        </div>
        <div class="footer-copy">
            <p>© 2025 moko.store. Wszelkie prawa zastrzeżone.</p>
        </div>
    </div>
</footer>
</body>
</html>
