<?php
require_once 'db.php';

function addLog(?int $userId, string $action, string $details = ''): void {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)');
    $stmt->execute([$userId, $action, $details]);
}
