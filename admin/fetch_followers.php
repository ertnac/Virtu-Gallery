<?php
require_once __DIR__ . '/../db_connect.php';

try {
    // SQL query to join follows with users for follower and artist names
    $sql = "
        SELECT 
            follows.id,
            CONCAT(followers.first_name, ' ', followers.last_name) AS follower,
            CONCAT(artists.first_name, ' ', artists.last_name) AS followed_artist,
            follows.follow_date,
            follows.follow_timestamp,
            follows.flagged,
            (SELECT COUNT(*) FROM follows f2 WHERE f2.follower_id = follows.follower_id) AS follow_count
        FROM follows
        INNER JOIN users AS followers ON follows.follower_id = followers.id
        INNER JOIN users AS artists ON follows.followed_id = artists.id
    ";

    $stmt = $pdo->query($sql);
    $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'followers' => $followers
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Query failed: ' . $e->getMessage()]);
}
?>