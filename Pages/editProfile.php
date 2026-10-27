<?php
require_once __DIR__ . '/../db_connect.php';
session_start();

$defaultProfilePic = 'profile_image/default.jpg';
$errors = [];
$profile_pic = $defaultProfilePic;

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['username'];
        $first_name = $user['first_name'];
        $middle_name = $user['middle_name'] ?? '';
        $last_name = $user['last_name'];
        $suffix = $user['suffix'] ?? '';
        $email = $user['email'];
        $phone = $user['phone'] ?? '';
        $bio = $user['bio'] ?? '';
        $profile_pic = $user['profile_picture'] ?? $defaultProfilePic;
    }
} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $middle_name = filter_input(INPUT_POST, 'middle_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);

    // Handle file upload only if a file was actually uploaded
    if (isset($_FILES['image'])) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $maxFileSize = 5 * 1024 * 1024; // 5MB
            $allowedTypes = ['image/jpeg', 'image/png'];

            // Validate file
            if ($file['size'] > $maxFileSize) {
                $errors[] = 'Image size exceeds 5MB limit';
            }
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = 'Only JPEG and PNG images are allowed';
            }

            if (empty($errors)) {
                $upload_dir = 'profile_image/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('profile_' . $username . '_') . '.' . $ext;
                $new_profile_pic = $upload_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $new_profile_pic)) {
                    // Delete old profile picture if it's not the default
                    if ($profile_pic !== $defaultProfilePic && file_exists($profile_pic)) {
                        unlink($profile_pic);
                    }
                    $profile_pic = $new_profile_pic;
                } else {
                    $errors[] = 'Failed to upload image';
                }
            }
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Error uploading file: ' . $_FILES['image']['error'];
        }
    } elseif (isset($_POST['remove_profile_pic'])) {
        // Handle profile picture removal
        if ($profile_pic !== $defaultProfilePic && file_exists($profile_pic)) {
            unlink($profile_pic);
        }
        $profile_pic = $defaultProfilePic;
    }

    // Update database if no errors
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET 
                username = ?, 
                first_name = ?, 
                middle_name = ?, 
                last_name = ?, 
                suffix = ?,
                email = ?, 
                phone = ?, 
                bio = ?, 
                profile_picture = ? 
                WHERE id = ?");
            $stmt->execute([
                $username,
                $first_name,
                $middle_name,
                $last_name,
                $suffix,
                $email,
                $phone,
                $bio,
                $profile_pic,
                $_SESSION['user_id']
            ]);

            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: UserProfilePage.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Error updating profile: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtu Gallery - Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #f9f9f9;
            overflow-x: hidden;
            padding-top: 90px;
        }

        h1,
        h2,
        h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* Navigation Bar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #eee;
            height: 90px;
        }

        .nav-left,
        .nav-right {
            display: flex;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #202b1d;
            font-weight: bold;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .profile-icon,
        .cart-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #202b1d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: 10px;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #333;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Main Content Container */
        .edit-profile-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
        }

        .edit-profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .back-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #202b1d;
            margin-right: 15px;
            cursor: pointer;
        }

        .edit-profile-title {
            font-family: 'Playfair Display', serif;
            color: #202b1d;
            font-size: 1.8rem;
        }

        /* Profile Picture Section */
        .profile-pic-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-pic-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #eee;
            margin-right: 30px;
        }

        .profile-pic-actions {
            display: flex;
            flex-direction: column;
        }

        .change-pic-btn {
            background-color: #202b1d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .change-pic-btn:hover {
            background-color: #1a2318;
        }

        .remove-pic-btn {
            background: none;
            border: none;
            color: #e74c3c;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
        }

        /* Form Styles */
        .edit-profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #202b1d;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #202b1d;
        }

        .form-textarea {
            width: 100%;
            height: 120px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            transition: border-color 0.3s;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #202b1d;
        }

        /* Form Actions */
        .form-actions {
            grid-column: span 2;
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .cancel-btn {
            background: none;
            border: 1px solid #ddd;
            color: #333;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            margin-right: 15px;
            transition: all 0.3s;
        }

        .cancel-btn:hover {
            border-color: #999;
        }

        .save-btn {
            background-color: #202b1d;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .save-btn:hover {
            background-color: #1a2318;
        }

        /* Error Messages */
        .error-message {
            color: #e74c3c;
            background-color: #fdecea;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
        }

        /* Success Messages */
        .success-message {
            color: #2e7d32;
            background-color: #edf7ed;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                padding: 10px 5%;
            }

            .logo {
                font-size: 1.5rem;
            }

            .mobile-menu-btn {
                display: block;
            }

            .edit-profile-container {
                padding: 25px;
                margin: 20px;
            }

            .edit-profile-form {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            .form-actions {
                grid-column: span 1;
            }
        }

        @media (max-width: 480px) {
            .profile-pic-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-pic-preview {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .form-actions {
                flex-direction: column;
            }

            .cancel-btn {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-left">
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="logo">VirtuGallery</div>
        <div class="nav-right">
            <a href="cart.html" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>
            <div class="profile-icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="edit-profile-container">
        <div class="edit-profile-header">
            <button class="back-btn" id="backButton">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="edit-profile-title">Edit Profile</h1>
        </div>

        <!-- Display error messages if any -->
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Display success message if any -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Profile Form -->
        <form class="edit-profile-form" method="POST" enctype="multipart/form-data">
            <!-- Profile Picture Section -->
            <div class="profile-pic-section">
                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-pic-preview" id="profilePicPreview">
                <div class="profile-pic-actions">
                    <input type="file" id="profilePicInput" name="image" accept="image/jpeg,image/png" style="display: none;">
                    <button type="button" class="change-pic-btn" id="changePicBtn">Change Profile Photo</button>
                    <button type="button" class="remove-pic-btn" id="removePicBtn">Remove Photo</button>
                    <input type="hidden" name="remove_profile_pic" id="removeProfilePic" value="0">
                </div>
            </div>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="form-group">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-input" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>

            <div class="form-group">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text" id="middle_name" name="middle_name" class="form-input" value="<?php echo htmlspecialchars($middle_name); ?>">
            </div>

            <div class="form-group">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-input" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>

            <div class="form-group">
                <label for="suffix" class="form-label">Suffix</label>
                <input type="text" id="suffix" name="suffix" class="form-input" value="<?php echo htmlspecialchars($suffix); ?>">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-input" value="<?php echo htmlspecialchars($phone); ?>">
            </div>

            <div class="form-group full-width">
                <label for="bio" class="form-label">Bio</label>
                <textarea id="bio" name="bio" class="form-textarea"><?php echo htmlspecialchars($bio); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Back Button
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function() {
                    window.history.back();
                });
            }

            // Cancel Button
            const cancelBtn = document.getElementById('cancelBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to discard your changes?')) {
                        window.location.href = 'profile.php';
                    }
                });
            }

            // Profile Picture Handling
            const changePicBtn = document.getElementById('changePicBtn');
            const removePicBtn = document.getElementById('removePicBtn');
            const profilePicInput = document.getElementById('profilePicInput');
            const profilePicPreview = document.getElementById('profilePicPreview');
            const removeProfilePicInput = document.getElementById('removeProfilePic');

            if (changePicBtn && profilePicInput) {
                changePicBtn.addEventListener('click', function() {
                    profilePicInput.click();
                });
            }

            if (profilePicInput) {
                profilePicInput.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            profilePicPreview.src = event.target.result;
                        }

                        reader.readAsDataURL(e.target.files[0]);
                        // Reset remove photo flag if new photo is selected
                        removeProfilePicInput.value = '0';
                    }
                });
            }

            if (removePicBtn) {
                removePicBtn.addEventListener('click', function() {
                    // Set the default profile picture preview
                    profilePicPreview.src = '<?php echo $defaultProfilePic; ?>';
                    // Set the hidden input value to indicate removal
                    removeProfilePicInput.value = '1';
                    // Clear any selected file in the file input
                    profilePicInput.value = '';
                });
            }
            // Mobile Menu Toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                });
            }

            // Navbar scroll effect
            const navbar = document.querySelector('nav');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.style.backgroundColor = '#202b1d';
                        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.2)';
                    } else {
                        navbar.style.backgroundColor = 'white';
                        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                    }
                });
            }
        });
    </script>
</body>

</html>