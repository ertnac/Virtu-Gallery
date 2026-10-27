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

if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'Post ID required']);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];

    // Check if the user already liked the post
    $stmt = $pdo->prepare('SELECT id FROM likes WHERE post_id = ? AND user_id = ?');
    $stmt->execute([$post_id, $user_id]);
    $existing_like = $stmt->fetch();

    if ($existing_like) {
        // Unlike: Remove the like
        $stmt = $pdo->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->execute([$post_id, $user_id]);
        $is_liked = false;
    } else {
        // Like: Add the like
        $stmt = $pdo->prepare('INSERT INTO likes (post_id, user_id) VALUES (?, ?)');
        $stmt->execute([$post_id, $user_id]);
        $is_liked = true;
    }

    // Get updated like count
    $stmt = $pdo->prepare('SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?');
    $stmt->execute([$post_id]);
    $like_count = $stmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'is_liked' => $is_liked,
        'like_count' => $like_count
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>