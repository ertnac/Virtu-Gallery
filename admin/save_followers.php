<?php
require_once __DIR__ . '/../db_connect.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$input = json_decode(file_get_contents('php://input'), true);

try {
    $id = $input['id'] ?? null;
    $flagged = $input['flagged'] ?? false;

    if (!$id) {
        echo json_encode(['success' => false, 'error' => 'Invalid input: ID is required']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE follows SET flagged = ? WHERE id = ?");
    $stmt->execute([(int)$flagged, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No follower found with the given ID']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $e->getMessage()]);
}
?>