<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/product.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}
$productModel = new Product($pdo, __DIR__ . '/../public/assets/img');

$product = $productModel->get($id);
if (!$product) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if ($productModel->update($id, $_POST, $_FILES['images'] ?? [], $errors)) {
        header('Location: edit_product.php?id=' . $id);
        exit;
    }
    $message = implode(' ', $errors);
}

$categories = $productModel->listCategories();
$images = $product['images'];
?>
<?php
$pageTitle = 'Edytuj produkt';
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js"
];
require '../views/header.php';
?>
<div class="admin-content">
    <h1>Edytuj produkt</h1>
    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Tytuł: <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required></label><br>
        <label>Cena: <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required></label><br>
        <label>Ilość: <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>"></label><br>
        <label>Kategoria:
            <select name="category">
                <option value="">-- brak --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $product['category_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Opis:<br>
            <textarea name="description" rows="5" cols="50" required><?= htmlspecialchars($product['description']) ?></textarea>
        </label><br>
        <label>Dodaj zdjęcia: <input type="file" name="images[]" multiple></label><br>
        <button type="submit">Zapisz</button>
    </form>
    <?php if ($images): ?>
        <h2>Istniejące zdjęcia</h2>
        <ul>
            <?php foreach ($images as $img): ?>
                <li><img src="../public/assets/img/<?= htmlspecialchars($img['image_path']) ?>" alt="" width="80"></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <p><a href="dashboard.php">Powrót</a></p>
</div>
<?php
require '../views/footer.php';
?>