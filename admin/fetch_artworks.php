<?php
require_once __DIR__ . '/../db_connect.php';

// SQL query to join posts with users and count likes & comments
$sql = "
    SELECT 
        posts.id,
        posts.title,
        posts.category,
        posts.price,
        posts.image_path,
        CONCAT(users.first_name, ' ', users.last_name) AS artist,
        (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS likes,
        (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comments,
        0 AS reviews -- Placeholder until reviews are implemented
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
";

try {
    $stmt = $pdo->query($sql);
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($artworks);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
}
?>
