<?php

class Product
{
    private PDO $pdo;
    private string $imgDir;

    public function __construct(PDO $pdo, string $imgDir)
    {
        $this->pdo = $pdo;
        $this->imgDir = rtrim($imgDir, '/').'/';
    }

    public function listCategories(): array
    {
        $stmt = $this->pdo->query('SELECT id, name FROM categories ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validate(array $data): array
    {
        $errors = [];
        if (trim($data['title'] ?? '') === '') {
            $errors[] = 'Tytuł jest wymagany.';
        }
        if ($data['price'] === '' || !is_numeric($data['price'])) {
            $errors[] = 'Cena jest nieprawidłowa.';
        }
        if (trim($data['description'] ?? '') === '') {
            $errors[] = 'Opis jest wymagany.';
        }
        return $errors;
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            return null;
        }
        $imgStmt = $this->pdo->prepare('SELECT id, image_path FROM product_images WHERE product_id = ?');
        $imgStmt->execute([$id]);
        $product['images'] = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
        return $product;
    }

    public function create(array $data, array $images, array &$errors = []): ?int
    {
        $errors = $this->validate($data);
        if ($errors) {
            return null;
        }

        $stmt = $this->pdo->prepare('INSERT INTO products (title, price, quantity, description, category_id) VALUES (?,?,?,?,?)');
        $stmt->execute([
            trim($data['title']),
            $data['price'],
            (int)($data['quantity'] ?? 0),
            trim($data['description']),
            $data['category'] === '' ? null : (int)$data['category'],
        ]);
        $id = (int)$this->pdo->lastInsertId();
        $this->handleUploads($id, $images);
        return $id;
    }

    public function update(int $id, array $data, array $images, array &$errors = []): bool
    {
        $errors = $this->validate($data);
        if ($errors) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE products SET title=?, price=?, quantity=?, description=?, category_id=? WHERE id=?');
        $stmt->execute([
            trim($data['title']),
            $data['price'],
            (int)($data['quantity'] ?? 0),
            trim($data['description']),
            $data['category'] === '' ? null : (int)$data['category'],
            $id
        ]);
        $this->handleUploads($id, $images);
        return true;
    }

    private function handleUploads(int $productId, array $images): void
    {
        if (!isset($images['tmp_name'])) {
            return;
        }
        foreach ($images['tmp_name'] as $idx => $tmp) {
            if ($images['error'][$idx] === UPLOAD_ERR_OK && is_uploaded_file($tmp)) {
                $ext = pathinfo($images['name'][$idx], PATHINFO_EXTENSION);
                $name = uniqid('img_', true) . ($ext ? ".{$ext}" : '');
                if (move_uploaded_file($tmp, $this->imgDir . $name)) {
                    $stmt = $this->pdo->prepare('INSERT INTO product_images (product_id, image_path) VALUES (?, ?)');
                    $stmt->execute([$productId, $name]);
                }
            }
        }
    }
}

?>