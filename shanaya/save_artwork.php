<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = !empty($data['id']) ? (int)$data['id'] : null;
    $title = trim($data['title'] ?? '');
    $category = trim($data['category'] ?? 'paintings');
    $price = isset($data['price']) ? (float)$data['price'] : 0.00;
    $description = trim($data['description'] ?? '');

    if (empty($title)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Title is required']);
        exit;
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, category = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $category, $price, $description, $id]);
    } else {
        // Find default artist or first user
        $uStmt = $pdo->query("SELECT id FROM users LIMIT 1");
        $user = $uStmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $user ? $user['id'] : 1;

        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, category, post_type, price, description, image_path) VALUES (?, ?, ?, 'product', ?, ?, 'uploads/artwork_1_683afa7a5eeff.png')");
        $stmt->execute([$user_id, $title, $category, $price, $description]);
        $id = $pdo->lastInsertId();
    }

    echo json_encode(['success' => true, 'id' => $id, 'message' => 'Artwork saved successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
