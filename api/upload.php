<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'errors' => ['You must be logged in to upload artwork']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $post_type = trim($_POST['post_type'] ?? 'content');
    $price = isset($_POST['price']) && $_POST['price'] !== '' ? floatval($_POST['price']) : null;
    $tags = trim($_POST['tags'] ?? '');
    $user_id = $_SESSION['user_id'];

    $errors = [];
    if (empty($title)) $errors[] = 'Artwork title is required';
    if (empty($description)) $errors[] = 'Description is required';
    if (!in_array($category, ['paintings', 'pottery', 'sculpture', 'photography'])) {
        $errors[] = 'Invalid category';
    }
    if (!in_array($post_type, ['content', 'product'])) {
        $errors[] = 'Invalid post type';
    }
    if ($post_type === 'product' && ($price === null || $price <= 0)) {
        $errors[] = 'Price must be greater than 0 for artworks for sale';
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        if ($file['size'] > $maxFileSize) {
            $errors[] = 'Image size exceeds 10MB limit';
        }
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = 'Only JPEG, PNG, and WebP images are allowed';
        }

        if (empty($errors)) {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0755, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('artwork_' . $user_id . '_') . '.' . $ext;
            $target = $upload_dir . $filename;

            // If local directory is writable, write to uploads/
            if (@move_uploaded_file($file['tmp_name'], $target)) {
                $image_path = 'uploads/' . $filename;
            } else {
                // Serverless (Vercel) fallback: store directly as data URI
                $imgBytes = file_get_contents($file['tmp_name']);
                if ($imgBytes !== false) {
                    $image_path = 'data:' . $file['type'] . ';base64,' . base64_encode($imgBytes);
                } else {
                    $errors[] = 'Failed to process uploaded image';
                }
            }
        }
    } else {
        $errors[] = 'Image is required';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    try {
        $stmt = $pdo->prepare('
            INSERT INTO posts (user_id, title, description, category, post_type, price, tags, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$user_id, $title, $description, $category, $post_type, $price, $tags, $image_path]);
        echo json_encode(['success' => true, 'message' => 'Artwork uploaded successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Upload failed: ' . $e->getMessage()]]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Method not allowed']]);
}
