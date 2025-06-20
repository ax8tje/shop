<?php
class User {
    public ?int $id;
    public string $email;
    public string $password;
    public string $role;
    public int $email_verified;
    public ?string $verification_token;
    public ?string $full_name;
    public ?string $address;
    public ?string $city;
    public ?string $postal_code;
    public ?string $country;
    public ?string $created_at;


    public function __construct(array $data = []) {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->email_verified = isset($data['email_verified']) ? (int)$data['email_verified'] : 0;
        $this->verification_token = $data['verification_token'] ?? null;
        $this->full_name = $data['full_name'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->postal_code = $data['postal_code'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }

    public static function findById(PDO $pdo, int $id): ?self {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findByEmail(PDO $pdo, string $email): ?self {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    public static function findAll(PDO $pdo): array {
        $stmt = $pdo->query('SELECT * FROM users');
        return array_map(fn($r)=>new self($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('UPDATE users SET email=:email, password=:password, role=:role, email_verified=:email_verified, verification_token=:verification_token, full_name=:full_name, address=:address, city=:city, postal_code=:postal_code, country=:country WHERE id=:id');
            $stmt->execute([
                'email'=>$this->email,
                'password'=>$this->password,
                'role'=>$this->role,
                'email_verified'=>$this->email_verified,
                'verification_token'=>$this->verification_token,
                'full_name'=>$this->full_name,
                'address'=>$this->address,
                'city'=>$this->city,
                'postal_code'=>$this->postal_code,
                'country'=>$this->country,
                'id'=>$this->id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (email, password, role, email_verified, verification_token, full_name, address, city, postal_code, country) VALUES (:email, :password, :role, :email_verified, :verification_token, :full_name, :address, :city, :postal_code, :country)');
            $stmt->execute([
                'email'=>$this->email,
                'password'=>$this->password,
                'role'=>$this->role,
                'email_verified'=>$this->email_verified,
                'verification_token'=>$this->verification_token,
                'full_name'=>$this->full_name,
                'address'=>$this->address,
                'city'=>$this->city,
                'postal_code'=>$this->postal_code,
                'country'=>$this->country
            ]);
            $this->id = (int)$pdo->lastInsertId();
        }
    }

    public function delete(PDO $pdo): void {
        if ($this->id) {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$this->id]);
        }
    }

    public static function register(PDO $pdo, string $email, string $password) {
        if (self::findByEmail($pdo, $email)) {
            return 'Użytkownik z takim adresem e-mail już istnieje.';
        }
        $token = bin2hex(random_bytes(16));
        $user = new self([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'verification_token' => $token,
            'email_verified' => 0
        ]);
        $user->save($pdo);
        return true;
    }

    public static function authenticate(PDO $pdo, string $email, string $password): ?self {
        $user = self::findByEmail($pdo, $email);
        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }
        return $user;
    }

    public static function getAddress(PDO $pdo, int $userId): ?array {
        $u = self::findById($pdo, $userId);
        if (!$u) return null;
        return [
            'full_name'=>$u->full_name,
            'address'=>$u->address,
            'city'=>$u->city,
            'postal_code'=>$u->postal_code,
            'country'=>$u->country,
            'email'=>$u->email
        ];
    }

    public static function updateAddress(PDO $pdo, int $userId, array $data): void {
        $u = self::findById($pdo, $userId);
        if (!$u) return;
        $u->full_name = $data['full_name'];
        $u->address = $data['address'];
        $u->city = $data['city'];
        $u->postal_code = $data['postal_code'];
        $u->country = $data['country'];
        $u->save($pdo);
    }

    public static function resetPassword(PDO $pdo, int $userId, string $newPassword): void {
        $u = self::findById($pdo, $userId);
        if (!$u) return;
        $u->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $u->save($pdo);
    }

    public static function verifyByToken(PDO $pdo, string $token): bool {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE verification_token = ?');
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
        $user = new self($row);
        $user->email_verified = 1;
        $user->verification_token = null;
        $user->save($pdo);
        return true;
    }
}