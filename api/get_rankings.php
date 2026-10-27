<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';
session_start();

$current_user_id = $_SESSION['user_id'] ?? 0;

try {
    // 1. Top Artists
    $artistsStmt = $pdo->prepare("
        SELECT 
            u.id,
            u.username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            u.municipality,
            u.province,
            COALESCE(a.stage_name, u.first_name || ' ' || u.last_name) AS stage_name,
            COALESCE(a.art_style, 'Visual Arts') AS art_style,
            COALESCE(a.verified, 0) AS verified,
            (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS artworks_count,
            (SELECT COUNT(*) FROM follows f WHERE f.followed_id = u.id) AS followers_count,
            EXISTS (SELECT 1 FROM follows f WHERE f.follower_id = ? AND f.followed_id = u.id) AS is_following
        FROM users u
        LEFT JOIN artists a ON u.id = a.user_id
        ORDER BY followers_count DESC, artworks_count DESC
        LIMIT 10
    ");
    $artistsStmt->execute([$current_user_id]);
    $artists = $artistsStmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Top Artworks
    $artworksStmt = $pdo->query("
        SELECT 
            p.id,
            p.title,
            p.category,
            p.price,
            p.image_path,
            p.created_at,
            u.id AS artist_id,
            u.username,
            u.first_name,
            u.last_name,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes_count,
            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comments_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY likes_count DESC, comments_count DESC
        LIMIT 10
    ");
    $artworks = $artworksStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'artists' => $artists,
        'artworks' => $artworks
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
