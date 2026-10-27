<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';
session_start();

$follower_id = $_SESSION['user_id'] ?? null;
if (!$follower_id) {
    echo json_encode(['success' => false, 'message' => 'Please log in to follow artists']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$followed_id = !empty($input['followed_id']) ? (int)$input['followed_id'] : null;

if (!$followed_id || $followed_id === $follower_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid artist ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM follows WHERE follower_id = ? AND followed_id = ?");
    $stmt->execute([$follower_id, $followed_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $del = $pdo->prepare("DELETE FROM follows WHERE id = ?");
        $del->execute([$existing['id']]);
        $is_following = false;
    } else {
        $ins = $pdo->prepare("INSERT INTO follows (follower_id, followed_id, follow_date, follow_timestamp) VALUES (?, ?, CURRENT_DATE, CURRENT_TIMESTAMP)");
        $ins->execute([$follower_id, $followed_id]);
        $is_following = true;
    }

    $cStmt = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = ?");
    $cStmt->execute([$followed_id]);
    $followers_count = $cStmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'is_following' => $is_following,
        'followers_count' => $followers_count
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
