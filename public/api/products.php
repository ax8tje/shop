<?php
require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id) {
    $stmt = $pdo->prepare('SELECT p.*, pi.image_path FROM products p LEFT JOIN product_images pi ON p.id = pi.product_id WHERE p.id = ? ORDER BY pi.id');
    $stmt->execute([$id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }
    $prod = [
        'id'          => (int)$rows[0]['id'],
        'title'       => $rows[0]['title'],
        'price'       => (float)$rows[0]['price'],
        'quantity'    => (int)$rows[0]['quantity'],
        'description' => $rows[0]['description'],
        'category_id' => $rows[0]['category_id'],
        'created_at'  => $rows[0]['created_at'],
        'images'      => []
    ];
    foreach ($rows as $r) {
        if (!empty($r['image_path'])) {
            $prod['images'][] = $r['image_path'];
        }
    }
    echo json_encode($prod);
} else {
    $stmt = $pdo->query('SELECT id, title, price FROM products ORDER BY id');
    $products = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['id'] = (int)$row['id'];
        $row['price'] = (float)$row['price'];
        $products[] = $row;
    }
    echo json_encode($products);
}
