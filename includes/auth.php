<?php
session_start();
require_once 'db.php';

function registerUser($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()){
        return "Użytkownik z takim adresem e-mail już istnieje.";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'user')");
    $stmt->execute([$email, $hashedPassword]);

    return true;
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return "Nieprawidłowy e-mail lub hasło.";
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_role'] = $user['role'];

    return true;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin(): void {
    if (!isAdmin()) {
        if (!isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
        http_response_code(403);
        echo 'Brak uprawnień do tej strony.';
        exit;
    }
}

function logoutUser() {
    session_unset();
    session_destroy();
}

function getUserAddress(int $userId): ?array {
    global $pdo;
    $stmt = $pdo->prepare(
        'SELECT full_name, address, city, postal_code, country, email FROM users WHERE id = ?'
    );
    $stmt->execute([$userId]);
    $addr = $stmt->fetch(PDO::FETCH_ASSOC);
    return $addr ?: null;
}

function updateUserAddress(int $userId, array $data): void {
    global $pdo;
    $stmt = $pdo->prepare(
        'UPDATE users SET full_name = :fn, address = :addr, city = :city, postal_code = :zip, country = :country WHERE id = :id'
    );
    $stmt->execute([
        'fn' => $data['full_name'],
        'addr' => $data['address'],
        'city' => $data['city'],
        'zip' => $data['postal_code'],
        'country' => $data['country'],
        'id' => $userId
    ]);
}

function createPasswordResetToken(string $email): bool {
    global $pdo;
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $userId = $stmt->fetchColumn();
    if (!$userId) {
        return false;
    }

    $token      = bin2hex(random_bytes(16));
    $tokenHash  = hash('sha256', $token);
    $expires    = date('Y-m-d H:i:s', time() + 3600);

    $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$userId]);
    $ins = $pdo->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
    $ins->execute([$userId, $tokenHash, $expires]);

    $link = sprintf('http://%s/reset_password.php?token=%s', $_SERVER['HTTP_HOST'] ?? 'localhost', $token);
    @mail($email, 'Resetowanie hasła', "Kliknij w link aby zresetować hasło: $link");
    return true;
}

function validatePasswordResetToken(string $token): ?int {
    global $pdo;
    $hash = hash('sha256', $token);
    $stmt = $pdo->prepare('SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()');
    $stmt->execute([$hash]);
    $uid = $stmt->fetchColumn();
    return $uid ?: null;
}

function resetPassword(int $userId, string $newPassword): void {
    global $pdo;
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hash, $userId]);
    $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$userId]);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}
