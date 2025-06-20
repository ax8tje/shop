<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';
require_once 'log.php';

function registerUser($email, $password) {
    global $pdo;
    $result = User::register($pdo, $email, $password);
    if ($result === true) {
        $user = User::findByEmail($pdo, $email);
        if ($user && $user->verification_token) {
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $link = sprintf('http://%s/verify_email.php?token=%s', $host, $user->verification_token);
            @mail($email, 'Weryfikacja adresu e-mail', "Kliknij w link aby potwierdzić e-mail: $link");
        }
    }
    return $result;
}

function loginUser($email, $password) {
    global $pdo;
    $user = User::authenticate($pdo, $email, $password);
    if (!$user) {
        return "Nieprawidłowy e-mail lub hasło.";
    }
    if (!$user->email_verified) {
        return "Adres e-mail nie został zweryfikowany.";
    }
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user->id;
    $_SESSION['user_role'] = $user->role;
    addLog($user->id, 'login', $_SERVER['REMOTE_ADDR'] ?? '');

    return true;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function isSeller() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'seller';
}

function requireAdmin(): void {
    if (!isAdmin()) {
        if (!isLoggedIn()) {
            header('Location: ../public/login.php');
            exit;
        }
        http_response_code(403);
        echo 'Brak uprawnień do tej strony.';
        exit;
    }
}

function requireSeller(): void {
    if (!isSeller() && !isAdmin()) {
        if (!isLoggedIn()) {
            header('Location: ../public/login.php');
            exit;
        }
        http_response_code(403);
        echo 'Brak uprawnień do tej strony.';
        exit;
    }
}

function logoutUser() {
    $uid = $_SESSION['user_id'] ?? null;
    if ($uid) {
        addLog($uid, 'logout', '');
    }
    session_unset();
    session_destroy();
}

function getUserAddress(int $userId): ?array {
    global $pdo;
    return User::getAddress($pdo, $userId);
}

function updateUserAddress(int $userId, array $data): void {
    global $pdo;
    User::updateAddress($pdo, $userId, $data);
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
    User::resetPassword($pdo, $userId, $newPassword);
    $pdo->prepare('DELETE FROM password_resets WHERE user_id = ?')->execute([$userId]);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ../public/login.php');
        exit;
    }
}
