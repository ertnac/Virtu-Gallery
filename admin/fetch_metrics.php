<?php
// fetch_metrics.php

header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';

try {
    $response = [
        "totalArtists" => 0,
        "artworks" => 0,
        "orders" => 0,       // Placeholder
        "likes" => 0,
        "comments" => 0,
        "followers" => 0,    // Placeholder
        "users" => 0         // New: total users
    ];

    // Total Artists
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM artists");
    $response["totalArtists"] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Artworks
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM posts WHERE post_type = 'product'");
    $response["artworks"] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Likes
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM likes");
    $response["likes"] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Comments
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM comments");
    $response["comments"] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Total Users
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users");
    $response["users"] = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

    // Output JSON
    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $e->getMessage()]);
}
