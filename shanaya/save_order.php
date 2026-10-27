<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = !empty($data['id']) ? (int)$data['id'] : null;
    $status = trim($data['status'] ?? 'Completed');

    if ($id) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Order ID is required']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
