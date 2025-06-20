<?php
class OrderItem {
    public ?int $id;
    public int $order_id;
    public int $product_id;
    public int $quantity;
    public float $price;

    public function __construct(array $data = []) {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->order_id = (int)($data['order_id'] ?? 0);
        $this->product_id = (int)($data['product_id'] ?? 0);
        $this->quantity = (int)($data['quantity'] ?? 0);
        $this->price = isset($data['price']) ? (float)$data['price'] : 0.0;
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare('SELECT * FROM order_items WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query('SELECT * FROM order_items');
        return array_map(fn($r)=>new self($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('UPDATE order_items SET order_id=:order_id, product_id=:product_id, quantity=:quantity, price=:price WHERE id=:id');
            $stmt->execute([
                'order_id'=>$this->order_id,
                'product_id'=>$this->product_id,
                'quantity'=>$this->quantity,
                'price'=>$this->price,
                'id'=>$this->id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id,:product_id,:quantity,:price)');
            $stmt->execute([
                'order_id'=>$this->order_id,
                'product_id'=>$this->product_id,
                'quantity'=>$this->quantity,
                'price'=>$this->price
            ]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public function delete(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('DELETE FROM order_items WHERE id = ?');
            $stmt->execute([$this->id]);
        }
    }
}