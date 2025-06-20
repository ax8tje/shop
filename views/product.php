<div class="product-page-box hidden">
    <div class="image-section">
        <div class="image-slider2">
            <button class="slide-arrow2 left-arrow2">&lt;</button>
            <div class="slider-images">
                <?php foreach ($product['images'] as $img): ?>
                    <img src="assets/img/<?= htmlspecialchars($img) ?>"
                         alt="<?= htmlspecialchars($product['title']) ?>"
                         class="slide-image2" />
                <?php endforeach; ?>
            </div>
            <button class="slide-arrow2 right-arrow2">&gt;</button>
        </div>
        <div class="thumbnail-slider-wrapper">
            <button class="thumb-arrow left-thumb">&lt;</button>
            <div class="thumbnail-slider">
                <?php foreach ($product['images'] as $index => $img): ?>
                    <img
                        src="assets/img/<?= htmlspecialchars($img) ?>"
                        class="thumbnail-image <?= $index === 0 ? 'active-thumbnail' : '' ?>"
                        data-index="<?= $index ?>"
                    />
                <?php endforeach; ?>
            </div>
            <button class="thumb-arrow right-thumb">&gt;</button>
        </div>
    </div>

    <div class="product-details">
        <h1 class="product-title"><?= htmlspecialchars($product['title']) ?></h1>
        <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p class="product-price"><strong>Cena:</strong> <?= number_format($product['price'], 2) ?> zł</p>
        <form method="post" class="buy-form">
            <input type="hidden" name="buy_id" value="<?= $pid ?>">
            <button type="submit" class="buy-button">Dodaj do koszyka</button>
        </form>
    </div>
</div>