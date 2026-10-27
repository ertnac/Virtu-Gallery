<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'notifications' => []]);
    exit;
}

try {
    // 1. Likes on user's posts
    $likeStmt = $pdo->prepare("
        SELECT 
            'like' as type,
            l.id as id,
            l.created_at,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            p.id as post_id,
            p.title as post_title,
            p.image_path as post_image
        FROM likes l
        JOIN users u ON l.user_id = u.id
        JOIN posts p ON l.post_id = p.id
        WHERE p.user_id = ? AND l.user_id != ?
        ORDER BY l.created_at DESC
        LIMIT 15
    ");
    $likeStmt->execute([$user_id, $user_id]);
    $likes = $likeStmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Comments on user's posts
    $commentStmt = $pdo->prepare("
        SELECT 
            'comment' as type,
            c.id as id,
            c.created_at,
            c.comment_text,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            p.id as post_id,
            p.title as post_title,
            p.image_path as post_image
        FROM comments c
        JOIN users u ON c.user_id = u.id
        JOIN posts p ON c.post_id = p.id
        WHERE p.user_id = ? AND c.user_id != ?
        ORDER BY c.created_at DESC
        LIMIT 15
    ");
    $commentStmt->execute([$user_id, $user_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. New followers
    $followStmt = $pdo->prepare("
        SELECT 
            'follow' as type,
            f.id as id,
            f.follow_timestamp as created_at,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            NULL as post_id,
            NULL as post_title,
            NULL as post_image
        FROM follows f
        JOIN users u ON f.follower_id = u.id
        WHERE f.followed_id = ?
        ORDER BY f.follow_timestamp DESC
        LIMIT 15
    ");
    $followStmt->execute([$user_id]);
    $follows = $followStmt->fetchAll(PDO::FETCH_ASSOC);

    $all = array_merge($likes, $comments, $follows);
    usort($all, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    echo json_encode([
        'success' => true,
        'notifications' => array_slice($all, 0, 20)
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
