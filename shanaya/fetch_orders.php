<?php
require_once __DIR__ . '/../db_connect.php';

try {
    // SQL query to join orders with users and posts
    $sql = "
        SELECT 
            orders.id,
            CONCAT(users.first_name, ' ', users.last_name) AS customer,
            posts.title AS artwork,
            CONCAT(artists.first_name, ' ', artists.last_name) AS artist,
            orders.date,
            orders.total,
            orders.status,
            orders.payment_method,
            orders.address
        FROM orders
        INNER JOIN users ON orders.customer_id = users.id
        INNER JOIN posts ON orders.post_id = posts.id
        INNER JOIN users AS artists ON posts.user_id = artists.id
    ";

    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate metrics
    $totalRevenue = 0;
    $quantitySold = count($orders);
    $revenueChange = 0; // Placeholder: Calculate based on previous month if needed
    $quantityChange = 0; // Placeholder: Calculate based on previous month if needed

    foreach ($orders as $order) {
        $totalRevenue += $order['total'];
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'orders' => $orders,
        'metrics' => [
            'totalRevenue' => $totalRevenue,
            'revenueChange' => $revenueChange,
            'quantitySold' => $quantitySold,
            'quantityChange' => $quantityChange
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $e->getMessage()]);
}
?>