<?php
require_once '../includes/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products");
    $products = $stmt->fetchAll();

    foreach ($products as $product) {
        echo "<h2>" . htmlspecialchars($product['title']) . "</h2>";
        echo "<img src='assets/" . htmlspecialchars($product['image_path']) . "' width='200'><br>";
    }
} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
