<?php
require_once '../includes/db.php';
require_once '../includes/cart.php';
require_once '../includes/auth.php';
require_once '../includes/log.php';

requireLogin();

$items = getCartItems($pdo);
$total = calculateCartTotal($items);

$fn = '';
$email = '';
$addr = '';
$city = '';
$zip = '';
$country = '';

if (isset($_SESSION['user_id'])) {
    $uaddr = getUserAddress($_SESSION['user_id']);
    if ($uaddr) {
        $fn = $uaddr['full_name'] ?? '';
        $email = $uaddr['email'] ?? '';
        $addr = $uaddr['address'] ?? '';
        $city = $uaddr['city'] ?? '';
        $zip = $uaddr['postal_code'] ?? '';
        $country = $uaddr['country'] ?? '';
    }
}

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
        // 2) Sprawdzenie stanu magazynowego
        foreach ($items as $item) {
            $stmt = $pdo->prepare('SELECT quantity FROM products WHERE id = ?');
            $stmt->execute([$item['id']]);
            $available = (int)$stmt->fetchColumn();
            if ($available < $item['quantity']) {
                $errors['stock'] = 'Brak wystarczającej liczby sztuk produktu ' . $item['title'] . '.';
                break;
            }
        }
    }

    if (empty($errors)) {
        // 3) Zapis do orders
        $order = new Order([
            'user_id'     => $_SESSION['user_id'] ?? null,
            'full_name'   => $fn,
            'email'       => $email,
            'address'     => $addr,
            'city'        => $city,
            'postal_code' => $zip,
            'country'     => $country,
            'total'       => $total
        ]);
        $order->save($pdo);
        $orderId = $order->id;
        addLog($_SESSION['user_id'] ?? null, 'new_order', 'ID ' . $orderId);

        if (isset($_SESSION['user_id'])) {
            updateUserAddress($_SESSION['user_id'], [
                'full_name'   => $fn,
                'address'     => $addr,
                'city'        => $city,
                'postal_code' => $zip,
                'country'     => $country
            ]);
        }

        // 4) Zapis pozycji
        foreach ($items as $item) {
            $oi = new OrderItem([
                'order_id'   => $orderId,
                'product_id' => $item['id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price']
            ]);
            $oi->save($pdo);
        }

        // 5) Opróżnij koszyk i przekieruj na potwierdzenie
        clearCart();  // funkcja w includes/cart.php
        header("Location: success.php?order={$orderId}");
        exit;
    }
}
$pageScripts = [
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
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
                <button type="submit" class="hero-button">Złóż zamówienie</button>
            </form>
        <?php endif; ?>
    </main>
<?php
require '../views/footer.php';
?>