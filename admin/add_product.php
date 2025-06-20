<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/product.php';
require_once '../includes/log.php';


requireSeller();

$message = '';
$productModel = new Product($pdo, __DIR__ . '/../public/assets/img');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $pid = $productModel->create($_POST, $_FILES['images'] ?? [], $errors);
    if ($pid) {
        addLog($_SESSION['user_id'] ?? null, 'add_product', 'ID ' . $pid);
        header('Location: edit_product.php?id=' . $pid);
        exit;
    }
    $message = implode(' ', $errors);
}

$categories = $productModel->listCategories();
?>
<?php
$pageTitle = 'Dodaj produkt';
$pageScripts = [
    "assets/js/script_burger_menu.js",
    "assets/js/script_profile_dropdown.js",
    "assets/js/script_cart_dropdown.js"
];
require '../views/header.php';
?>
<div class="admin-content">
    <h1>Dodaj produkt</h1>
    <?php if ($message): ?>
        <p style="color:red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Tytuł: <input type="text" name="title" required></label><br>
        <label>Cena: <input type="number" step="0.01" name="price" required></label><br>
        <label>Ilość: <input type="number" name="quantity" value="0"></label><br>
        <label>Kategoria:
            <select name="category">
                <option value="">-- brak --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Opis:<br>
            <textarea name="description" rows="5" cols="50" required></textarea>
        </label><br>
        <label>Zdjęcia: <input type="file" name="images[]" multiple></label><br>
        <button type="submit">Dodaj produkt</button>
    </form>
    <p><a href="dashboard.php">Powrót</a></p>
</div>
<?php
require '../views/footer.php';
?>