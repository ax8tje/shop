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
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if(!user || password_verify($password, $user['password'])) {
        return "Nieprawidłowy e-mail lub hasło.";
    }

    $_SESSION['user'] = $user;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role'] = $user['role'];

    return true;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function logoutUser() {
    session_unset();
    session_destroy();
}