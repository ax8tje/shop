<main class="checkout-container">
    <h1>Podsumowanie zamówienia</h1>
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
                <input id="full_name" type="text" name="full_name" required aria-describedby="err-full_name" value="<?=htmlspecialchars($fn) ?>">
                <span class="error-text" id="err-full_name" role="alert"><?= htmlspecialchars($errors['full_name'] ?? '') ?></span>
            </label>
            <label>E-mail:
                <input id="email" type="email" name="email" required aria-describedby="err-email" value="<?=htmlspecialchars($email) ?>">
                <span class="error-text" id="err-email" role="alert"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
            </label>
            <label>Ulica, nr domu:
                <input id="address" type="text" name="address" required aria-describedby="err-address" value="<?=htmlspecialchars($addr) ?>">
                <span class="error-text" id="err-address" role="alert"><?= htmlspecialchars($errors['address'] ?? '') ?></span>
            </label>
            <label>Miasto:
                <input id="city" type="text" name="city" required aria-describedby="err-city" value="<?=htmlspecialchars($city) ?>">
                <span class="error-text" id="err-city" role="alert"><?= htmlspecialchars($errors['city'] ?? '') ?></span>
            </label>
            <label>Kod pocztowy:
                <input id="postal_code" type="text" name="postal_code" pattern="[0-9]{2}-[0-9]{3}" required aria-describedby="err-postal_code" value="<?=htmlspecialchars($zip) ?>">
                <span class="error-text" id="err-postal_code" role="alert"><?= htmlspecialchars($errors['postal_code'] ?? '') ?></span>
            </label>
            <label>Kraj:
                <input id="country" type="text" name="country" required aria-describedby="err-country" value="<?=htmlspecialchars($country) ?>">
                <span class="error-text" id="err-country" role="alert"><?= htmlspecialchars($errors['country'] ?? '') ?></span>
            </label>
            <button type="submit" class="hero-button">Złóż zamówienie</button>
        </form>
    <?php endif; ?>
</main>