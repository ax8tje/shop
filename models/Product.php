<?php
class Product
{
    public ?int $id;
    public string $title;
    public float $price;
    public int $quantity;
    public string $description;
    public ?int $category_id;
    public ?string $created_at;

    public function __construct(array $data = [])
    {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->title = $data['title'] ?? '';
        $this->price = isset($data['price']) ? (float)$data['price'] : 0.0;
        $this->quantity = isset($data['quantity']) ? (int)$data['quantity'] : 0;
        $this->description = $data['description'] ?? '';
        $this->category_id = isset($data['category_id']) ? (int)$data['category_id'] : null;
        $this->created_at = $data['created_at'] ?? null;
    }

    public static function findById(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findAll(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM products');
        return array_map(fn($r) => new self($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(PDO $pdo): void
    {
        if ($this->id) {
            $stmt = $pdo->prepare('UPDATE products SET title=:title, price=:price, quantity=:quantity, description=:description, category_id=:category_id WHERE id=:id');
            $stmt->execute([
                'title' => $this->title,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'id' => $this->id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (title, price, quantity, description, category_id) VALUES (:title, :price, :quantity, :description, :category_id)');
            $stmt->execute([
                'title' => $this->title,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'description' => $this->description,
                'category_id' => $this->category_id
            ]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public function delete(PDO $pdo): void
    {
        if ($this->id) {
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$this->id]);
        }
    }

    public function images(PDO $pdo): array
    {
        if (!$this->id) return [];
        $stmt = $pdo->prepare('SELECT image_path FROM product_images WHERE product_id = ?');
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}