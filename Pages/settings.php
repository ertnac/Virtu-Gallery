<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$statusMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (strlen($new_pass) < 8) {
        $errorMsg = 'New password must be at least 8 characters long.';
    } elseif ($new_pass !== $confirm_pass) {
        $errorMsg = 'New passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $existingHash = $stmt->fetchColumn();

        if ($existingHash && password_verify($current_pass, $existingHash)) {
            $newHash = password_hash($new_pass, PASSWORD_BCRYPT);
            $uStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $uStmt->execute([$newHash, $user_id]);
            $statusMsg = 'Password successfully updated!';
        } else {
            $errorMsg = 'Incorrect current password.';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Account Settings — VirtuGallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gallery: {
                            50: '#f7f8f6',
                            100: '#edf0eb',
                            200: '#d7ded3',
                            500: '#485e43',
                            700: '#2b3b28',
                            800: '#202b1d',
                            900: '#172015',
                            gold: '#c5a059'
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .backdrop-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-gallery-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-40 backdrop-glass border-b border-gray-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="../index.php" class="flex items-center space-x-3">
                    <img src="../assets/images/logo-icon.svg" alt="VirtuGallery Emblem" class="w-10 h-10 rounded-xl shadow-md">
                    <span class="font-serif text-2xl font-bold tracking-tight text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-600">
                    <a href="home.php" class="hover:text-gallery-800">Community Feed</a>
                    <a href="Marketplace.php" class="hover:text-gallery-800">Marketplace</a>
                    <a href="ranking.php" class="hover:text-gallery-800">Rankings</a>
                    <a href="UserProfilePage.php" class="hover:text-gallery-800">My Studio</a>
                </div>
                <div>
                    <a href="../logout.php" class="text-xs font-bold text-red-600 hover:text-red-700 uppercase tracking-wider">Sign Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28 pb-20 flex-grow max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="mb-8">
            <span class="text-xs uppercase font-bold tracking-widest text-gallery-gold">Preferences & Security</span>
            <h1 class="font-serif text-3xl font-bold text-gallery-900 mt-1">Account Settings</h1>
        </div>

        <?php if ($statusMsg): ?>
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($statusMsg) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($errorMsg) ?></span>
            </div>
        <?php endif; ?>

        <div class="space-y-8">
            <!-- Account Overview -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-serif text-xl font-bold text-gallery-900 pb-3 border-b border-gray-100">Collector Account</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 uppercase font-semibold block">Registered Name</span>
                        <span class="text-sm font-bold text-gallery-900"><?= htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-semibold block">Account Email</span>
                        <span class="text-sm font-bold text-gallery-900"><?= htmlspecialchars($currentUser['email']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-semibold block">Studio Username</span>
                        <span class="text-sm font-bold text-gallery-900">@<?= htmlspecialchars($currentUser['username']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 uppercase font-semibold block">Registered Province</span>
                        <span class="text-sm font-bold text-gallery-900"><?= htmlspecialchars($currentUser['province'] ?: 'Laguna') ?></span>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="UserProfilePage.php" class="inline-flex items-center space-x-2 text-xs font-bold text-gallery-800 hover:underline">
                        <span>Edit profile details in My Studio</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Password Change -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                <h3 class="font-serif text-xl font-bold text-gallery-900 pb-3 border-b border-gray-100 mb-6">Security & Password</h3>

                <form method="POST" class="space-y-4">
                    <input type="hidden" name="change_password" value="1">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Current Password *</label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">New Password (Min 8 chars) *</label>
                            <input type="password" name="new_password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Confirm New Password *</label>
                            <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-gallery-800 hover:bg-gallery-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-500">
        <p>© <?= date('Y') ?> VirtuGallery — Collector Privacy & Security</p>
    </footer>

</body>

</html>
