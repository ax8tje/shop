<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $price       = $_POST['price'] ?? '';
    $quantity    = (int)($_POST['quantity'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category    = $_POST['category'] === '' ? null : (int)$_POST['category'];

    if ($title !== '' && $price !== '' && $description !== '') {
        $stmt = $pdo->prepare('INSERT INTO products (title, price, quantity, description, category_id) VALUES (?,?,?,?,?)');
        $stmt->execute([$title, $price, $quantity, $description, $category]);
        $pid = $pdo->lastInsertId();

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../public/assets/img/';
            foreach ($_FILES['images']['tmp_name'] as $idx => $tmp) {
                if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $name = basename($_FILES['images']['name'][$idx]);
                    $target = $uploadDir . $name;
                    if (move_uploaded_file($tmp, $target)) {
                        $pdo->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?,?)')
                            ->execute([$pid, $name]);
                    }
                }
            }
        }

        header('Location: edit_product.php?id=' . $pid);
        exit;
    } else {
        $message = 'Wszystkie pola oprócz kategorii są wymagane.';
    }
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj produkt</title>
</head>
<body>
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
</body>
</html>