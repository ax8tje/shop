<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_id'])) {
    addToCart((int)$_POST['buy_id'], 1);
    header('Location: cart.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Produkt nie został podany.";
    exit;
}

$pid = (int)$_GET['id'];

$product = Product::findById($pdo, $pid);

if (!$product) {
    echo "Nie znaleziono produktu.";
    exit;
}

$images = $product->images($pdo);

$stmt = $pdo->prepare('SELECT pr.rating, pr.comment, pr.created_at, u.email
                        FROM product_reviews pr
                        JOIN users u ON pr.user_id = u.id
                        WHERE pr.product_id = ?
                        ORDER BY pr.created_at DESC');
$stmt->execute([$pid]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

$avgStmt = $pdo->prepare('SELECT AVG(rating) FROM product_reviews WHERE product_id = ?');
$avgStmt->execute([$pid]);
$avgRating = $avgStmt->fetchColumn();


if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}
$recent = &$_SESSION['recently_viewed'];
$recent = array_values(array_diff($recent, [$pid]));
array_unshift($recent, $pid);
$_SESSION['recently_viewed'] = array_slice($recent, 0, 5);

$cookieName = 'recently_viewed';
$cookieData = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];
if (!is_array($cookieData)) {
    $cookieData = [];
}
$cookieData = array_values(array_diff($cookieData, [$pid]));
array_unshift($cookieData, $pid);
$cookieData = array_slice($cookieData, 0, 5);
setcookie($cookieName, json_encode($cookieData), time() + 60*60*24*30, '/');

$pageScripts = [
    "assets/js/script_product_animations.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_product_page.js",
    "assets/js/script_burger_menu.js",
    "assets/js/script_cart_dropdown.js",
];
require '../views/header.php';
?>
    <div class="product-page-box hidden">
        <div class="image-section">
            <div class="image-slider2">
                <button class="slide-arrow2 left-arrow2">&lt;</button>
                <div class="slider-images">
                    <?php foreach ($images as $img): ?>
                        <img src="assets/img/<?php echo htmlspecialchars($img); ?>"
                             alt="<?php echo htmlspecialchars($product->title); ?>"
                             class="slide-image2" />
                    <?php endforeach; ?>
                </div>
                <button class="slide-arrow2 right-arrow2">&gt;</button>
            </div>
            <div class="thumbnail-slider-wrapper">
                <button class="thumb-arrow left-thumb">&lt;</button>
                <div class="thumbnail-slider">
                    <?php foreach ($images as $index => $img): ?>
                        <img
                                src="assets/img/<?php echo htmlspecialchars($img); ?>"
                                class="thumbnail-image <?php echo $index === 0 ? 'active-thumbnail' : ''; ?>"
                                data-index="<?= $index ?>"
                        />
                    <?php endforeach; ?>
                </div>
                <button class="thumb-arrow right-thumb">&gt;</button>
            </div>
        </div>

        <div class="product-details">
            <h1 class="product-title"><?= htmlspecialchars($product->title) ?></h1>
            <p class="product-description"><?= nl2br(htmlspecialchars($product->description)) ?></p>
            <p class="product-price"><strong>Cena:</strong> <?= number_format($product->price, 2) ?> zł</p>
            <p class="product-rating"><strong>Średnia ocena:</strong>
                <?php if ($avgRating !== null): ?>
                    <?= number_format($avgRating, 1) ?>/5
                <?php else: ?>
                    brak
                <?php endif; ?>
            </p>
            <form method="post" class="buy-form">
                <input type="hidden" name="buy_id" value="<?= $pid ?>">
                <button type="submit" class="buy-button">Dodaj do koszyka</button>
            </form>
        </div>
    </div>
<div class="reviews-section">
    <h2>Opinie</h2>
    <?php foreach ($reviews as $rev): ?>
        <div class="review">
            <p><strong><?= htmlspecialchars($rev['email']) ?></strong> - <?= (int)$rev['rating'] ?>/5
                <em><?= htmlspecialchars($rev['created_at']) ?></em></p>
            <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
        </div>
    <?php endforeach; ?>

    <?php if (isLoggedIn()): ?>
        <h3>Dodaj opinię</h3>
        <form method="post" action="add_review.php">
            <input type="hidden" name="product_id" value="<?= $pid ?>">
            <label>Ocena:
                <select name="rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </label><br>
            <label>Komentarz:<br>
                <textarea name="comment" required></textarea>
            </label><br>
            <button type="submit">Wyślij</button>
        </form>
    <?php else: ?>
        <p>Aby dodać opinię, <a href="login.php">zaloguj się</a>.</p>
    <?php endif; ?>
</div>
<?php
require '../views/footer.php';
?>
