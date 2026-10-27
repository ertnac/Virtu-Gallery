<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract form data
    $email_or_username = trim($data['email_or_username'] ?? '');
    $password = $data['password'] ?? '';
    $remember_me = $data['remember_me'] ?? false;

    // Validation
    $errors = [];
    if (empty($email_or_username)) $errors[] = 'Email or username is required';
    if (empty($password)) $errors[] = 'Password is required';

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Check user credentials
    try {
        $stmt = $pdo->prepare('SELECT id, email, password FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email_or_username, $email_or_username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Handle "Remember Me" with a cookie (optional, lasts 7 days)
            if ($remember_me) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_token', $token, time() + (7 * 24 * 60 * 60), '/');
                // In a real app, store the token in the database and associate it with the user
            }

            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'errors' => ['Invalid email/username or password']]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Login failed: ' . $e->getMessage()]]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Method not allowed']]);
}
?>