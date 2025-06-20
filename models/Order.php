<?php
class Order {
    public ?int $id;
    public ?int $user_id;
    public string $full_name;
    public string $email;
    public string $address;
    public string $city;
    public string $postal_code;
    public string $country;
    public float $total;
    public string $status;
    public ?string $created_at;

    public function __construct(array $data = []) {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->user_id = isset($data['user_id']) ? (int)$data['user_id'] : null;
        $this->full_name = $data['full_name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->postal_code = $data['postal_code'] ?? '';
        $this->country = $data['country'] ?? '';
        $this->total = isset($data['total']) ? (float)$data['total'] : 0.0;
        $this->status = $data['status'] ?? 'new';
        $this->created_at = $data['created_at'] ?? null;
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query('SELECT * FROM orders');
        return array_map(fn($r)=>new self($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('UPDATE orders SET user_id=:user_id, full_name=:full_name, email=:email, address=:address, city=:city, postal_code=:postal_code, country=:country, total=:total, status=:status WHERE id=:id');
            $stmt->execute([
                'user_id'=>$this->user_id,
                'full_name'=>$this->full_name,
                'email'=>$this->email,
                'address'=>$this->address,
                'city'=>$this->city,
                'postal_code'=>$this->postal_code,
                'country'=>$this->country,
                'total'=>$this->total,
                'status'=>$this->status,
                'id'=>$this->id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO orders (user_id, full_name, email, address, city, postal_code, country, total, status) VALUES (:user_id,:full_name,:email,:address,:city,:postal_code,:country,:total,:status)');
            $stmt->execute([
                'user_id'=>$this->user_id,
                'full_name'=>$this->full_name,
                'email'=>$this->email,
                'address'=>$this->address,
                'city'=>$this->city,
                'postal_code'=>$this->postal_code,
                'country'=>$this->country,
                'total'=>$this->total,
                'status'=>$this->status
            ]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public function delete(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('DELETE FROM orders WHERE id = ?');
            $stmt->execute([$this->id]);
        }
    }
}