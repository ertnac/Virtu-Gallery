<?php
require_once __DIR__ . '/../db_connect.php';

try {
    $sql = "
        SELECT 
            u.id,
            u.first_name,
            u.middle_name,
            u.last_name,
            u.suffix,
            u.email,
            u.username,
            u.municipality,
            u.province,
            u.bio,
            u.website,
            u.profile_picture,
            u.created_at,
            a.stage_name,
            a.art_style,
            a.portfolio_link,
            a.verified,
            (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS artworks,
            (SELECT COUNT(*) FROM follows f WHERE f.followed_id = u.id) AS followers,
            (SELECT COUNT(*) FROM follows f WHERE f.follower_id = u.id) AS following
        FROM users u
        LEFT JOIN artists a ON u.id = a.user_id
    ";

    $stmt = $pdo->query($sql);
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($artists);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>