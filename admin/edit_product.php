<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $price       = $_POST['price'] ?? '';
    $quantity    = (int)($_POST['quantity'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $category    = $_POST['category'] === '' ? null : (int)$_POST['category'];

    if ($title !== '' && $price !== '' && $description !== '') {
        $pdo->prepare('UPDATE products SET title=?, price=?, quantity=?, description=?, category_id=? WHERE id=?')
            ->execute([$title, $price, $quantity, $description, $category, $id]);

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../public/assets/img/';
            foreach ($_FILES['images']['tmp_name'] as $idx => $tmp) {
                if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
                    $name = basename($_FILES['images']['name'][$idx]);
                    $target = $uploadDir . $name;
                    if (move_uploaded_file($tmp, $target)) {
                        $pdo->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?,?)')
                            ->execute([$id, $name]);
                    }
                }
            }
        }

        header('Location: edit_product.php?id=' . $id);
        exit;
    } else {
        $message = 'Wszystkie pola oprócz kategorii są wymagane.';
    }
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$imgStmt = $pdo->prepare('SELECT id, image_path FROM product_images WHERE product_id = ?');
$imgStmt->execute([$id]);
$images = $imgStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj produkt</title>
</head>
<body>
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
</body>
</html>
