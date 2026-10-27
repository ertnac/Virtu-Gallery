<?php
session_start();
require_once __DIR__ . '/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? null;
$comment_text = trim($data['comment_text'] ?? '');

if (!$post_id || !$comment_text) {
    echo json_encode(['success' => false, 'message' => 'Post ID and comment text required']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];

    // Insert comment
    $stmt = $pdo->prepare('INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)');
    $stmt->execute([$post_id, $user_id, $comment_text]);

    // Get username for the comment
    $stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $username = $stmt->fetchColumn();

    // Get comment details
    $comment_id = $pdo->lastInsertId();
    $created_at = (new DateTime())->format('Y-m-d H:i:s');

    echo json_encode([
        'success' => true,
        'comment' => [
            'id' => $comment_id,
            'post_id' => $post_id,
            'username' => $username,
            'comment_text' => $comment_text,
            'created_at' => $created_at
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
