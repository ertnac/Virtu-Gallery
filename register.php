<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract form data
    $first_name = trim($data['first_name'] ?? '');
    $middle_name = trim($data['middle_name'] ?? '');
    $last_name = trim($data['last_name'] ?? '');
    $suffix = trim($data['suffix'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';
    $username = trim($data['username'] ?? '');
    $municipality = trim($data['municipality'] ?? '');
    $province = trim($data['province'] ?? '');

    // Validation
    $errors = [];
    if (empty($first_name)) $errors[] = 'First name is required';
    if (empty($last_name)) $errors[] = 'Last name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
    if (strlen($password) < 8 || !preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/', $password)) {
        $errors[] = 'Password must be at least 8 characters and contain at least one number, one uppercase, and one lowercase letter';
    }
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match';
    if (empty($municipality)) $errors[] = 'Municipality is required';
    if (empty($province)) $errors[] = 'Province is required';

    // Check if email or username already exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR (username = ? AND username != "")');
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $errors[] = 'Email or username already exists';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    try {
        $stmt = $pdo->prepare('
            INSERT INTO users (first_name, middle_name, last_name, suffix, email, password, username, municipality, province, profile_picture)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$first_name, $middle_name, $last_name, $suffix, $email, $hashed_password, $username, $municipality, $province, NULL]);

        // Start session and store user data
        session_start();
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['email'] = $email;

        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Registration failed: ' . $e->getMessage()]]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Method not allowed']]);
}
?>