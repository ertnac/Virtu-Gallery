<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 1; // Default to first user if guest checkout

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$items = $input['items'] ?? [];
$shipping = $input['shipping'] ?? [];
$payment_method = $input['payment_method'] ?? 'Credit Card';

if (empty($items)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Cart is empty']);
    exit;
}

try {
    $orderIds = [];
    $totalOrderAmount = 0;
    $address = trim(($shipping['address'] ?? '') . ', ' . ($shipping['city'] ?? '') . ', ' . ($shipping['province'] ?? ''));

    foreach ($items as $item) {
        $post_id = !empty($item['id']) ? (int)$item['id'] : 1;
        $price = !empty($item['price']) ? (float)$item['price'] : 0.00;
        $totalOrderAmount += $price;

        $stmt = $pdo->prepare("
            INSERT INTO orders (customer_id, post_id, date, total, status, payment_method, address)
            VALUES (?, ?, CURRENT_TIMESTAMP, ?, 'Processing', ?, ?)
        ");
        $stmt->execute([$user_id, $post_id, $price, $payment_method, $address ?: 'San Pablo, Laguna']);
        $orderIds[] = $pdo->lastInsertId();
    }

    echo json_encode([
        'success' => true,
        'order_number' => 'VG-' . strtoupper(substr(md5(uniqid()), 0, 8)),
        'order_ids' => $orderIds,
        'total' => $totalOrderAmount,
        'message' => 'Order placed successfully!'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Order placement failed: ' . $e->getMessage()]);
}
