<main class="main-content">
    <div class="category-filter">
        <form method="get" class="category-filter-form">
            <button type="submit" name="category" value="" <?= $filterCat===null ? 'class="active"' : '' ?>>Wszystkie</button>
            <?php foreach ($categories as $cat): ?>
                <button type="submit" name="category" value="<?= $cat['id']; ?>" <?= $filterCat===(int)$cat['id'] ? 'class="active"' : '' ?>>
                    <?= htmlspecialchars($cat['name']); ?>
                </button>
            <?php endforeach; ?>
        </form>
        <form method="get" action="products.php" class="search-form">
            <?php if($filterCat): ?>
                <input type="hidden" name="category" value="<?= $filterCat ?>">
            <?php endif; ?>
            <input type="text" name="search" placeholder="Szukaj produktów…" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Szukaj</button>
        </form>
    </div>

    <div class="products-container">
        <?php if (empty($products)): ?>
            <p>Brak produktów w tej kategorii.</p>
        <?php else: ?>
            <?php foreach ($products as $pid => $product): ?>
                <div class="product" data-product-id="<?= $pid; ?>">
                    <h2><?= htmlspecialchars($product['title']); ?></h2>
                    <?php if (!empty($product['images'])): ?>
                        <div class="image-slider1">
                            <button class="slide-arrow1 left-arrow1" aria-label="Poprzednie">&lt;</button>
                            <div class="slider-images">
                                <?php foreach ($product['images'] as $img): ?>
                                    <a href="product.php?id=<?= $pid; ?>">
                                        <img src="assets/img/<?= htmlspecialchars($img); ?>" class="slide-image1" alt="<?= htmlspecialchars($product['title']); ?>">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <button class="slide-arrow1 right-arrow1" aria-label="Następne">&gt;</button>
                        </div>
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p><strong>Cena:</strong> <?= number_format($product['price'], 2); ?> zł</p>
                    <p><strong>Ilość:</strong> <?= htmlspecialchars($product['quantity']); ?></p>
                    <div class="product-container-buttons">
                        <form method="post" action="add_to_cart.php">
                            <input type="hidden" name="product_id" value="<?= $pid; ?>">
                            <button type="submit" class="buy-button1">Kup</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<div class="recently-viewed">
    <?php if (!empty($recentProducts)): ?>
        <h2 class="title">Ostatnio oglądane</h2>
        <div class="products-container">
            <?php foreach ($recentProducts as $rid => $rprod): ?>
                <div class="product" data-product-id="<?= $rid; ?>">
                    <h2><?= htmlspecialchars($rprod['title']); ?></h2>
                    <?php if (!empty($rprod['images'])): ?>
                        <a href="product.php?id=<?= $rid; ?>">
                            <img src="assets/img/<?= htmlspecialchars($rprod['images'][0]); ?>" class="slide-image1" alt="<?= htmlspecialchars($rprod['title']); ?>">
                        </a>
                    <?php endif; ?>
                    <p><strong>Cena:</strong> <?= number_format($rprod['price'], 2); ?> zł</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>