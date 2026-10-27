<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch real dynamic notifications
try {
    // 1. Likes on user's posts
    $likeStmt = $pdo->prepare("
        SELECT 
            'like' as type,
            l.id as id,
            l.created_at,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            p.id as post_id,
            p.title as post_title,
            p.image_path as post_image
        FROM likes l
        JOIN users u ON l.user_id = u.id
        JOIN posts p ON l.post_id = p.id
        WHERE p.user_id = ? AND l.user_id != ?
        ORDER BY l.created_at DESC
        LIMIT 15
    ");
    $likeStmt->execute([$user_id, $user_id]);
    $likes = $likeStmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Comments on user's posts
    $commentStmt = $pdo->prepare("
        SELECT 
            'comment' as type,
            c.id as id,
            c.created_at,
            c.comment_text,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            p.id as post_id,
            p.title as post_title,
            p.image_path as post_image
        FROM comments c
        JOIN users u ON c.user_id = u.id
        JOIN posts p ON c.post_id = p.id
        WHERE p.user_id = ? AND c.user_id != ?
        ORDER BY c.created_at DESC
        LIMIT 15
    ");
    $commentStmt->execute([$user_id, $user_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. New followers
    $followStmt = $pdo->prepare("
        SELECT 
            'follow' as type,
            f.id as id,
            f.follow_timestamp as created_at,
            '' as comment_text,
            u.id as actor_id,
            u.username as actor_username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            NULL as post_id,
            NULL as post_title,
            NULL as post_image
        FROM follows f
        JOIN users u ON f.follower_id = u.id
        WHERE f.followed_id = ?
        ORDER BY f.follow_timestamp DESC
        LIMIT 15
    ");
    $followStmt->execute([$user_id]);
    $follows = $followStmt->fetchAll(PDO::FETCH_ASSOC);

    $allNotifications = array_merge($likes, $comments, $follows);
    usort($allNotifications, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
} catch (PDOException $e) {
    $allNotifications = [];
}

function formatTimeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j', $time);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Notifications — VirtuGallery</title>
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
                    <a href="notifications.php" class="text-gallery-800 font-bold border-b-2 border-gallery-800 pb-1">Notifications</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="UserProfilePage.php" class="text-sm font-medium text-gallery-800 bg-gallery-100 hover:bg-gallery-200 px-4 py-2 rounded-full">Profile</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28 pb-20 flex-grow max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200/60">
            <div>
                <span class="text-xs uppercase font-bold tracking-widest text-gallery-gold">Community Activity</span>
                <h1 class="font-serif text-3xl font-bold text-gallery-900 mt-1">Notifications</h1>
            </div>
            <span class="text-xs font-semibold bg-gallery-100 text-gallery-800 px-3 py-1.5 rounded-full">
                <?= count($allNotifications) ?> Updates
            </span>
        </div>

        <div class="bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-sm divide-y divide-gray-100">
            <?php if (empty($allNotifications)): ?>
                <div class="py-16 text-center text-gray-400">
                    <i class="far fa-bell text-4xl mb-3"></i>
                    <h3 class="font-serif text-lg font-bold text-gray-700">No new notifications</h3>
                    <p class="text-xs text-gray-400 mt-1">When patrons appreciate, comment on, or follow your works, updates will appear here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($allNotifications as $notif): ?>
                    <div class="py-4 px-2 sm:px-3 flex items-center justify-between gap-4 hover:bg-gallery-50/60 rounded-2xl transition-colors">
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <!-- Icon / Avatar -->
                            <div class="relative w-12 h-12 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                <img src="<?= !empty($notif['profile_picture']) ? (strpos($notif['profile_picture'], 'profile_image/') === 0 ? $notif['profile_picture'] : $notif['profile_picture']) : 'profile_image/default.jpg' ?>" 
                                     alt="User" class="w-full h-full object-cover">
                                
                                <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-[10px] text-white 
                                    <?= $notif['type'] === 'like' ? 'bg-rose-500' : ($notif['type'] === 'comment' ? 'bg-sky-500' : 'bg-emerald-500') ?>">
                                    <i class="fas <?= $notif['type'] === 'like' ? 'fa-heart' : ($notif['type'] === 'comment' ? 'fa-comment' : 'fa-user-plus') ?>"></i>
                                </span>
                            </div>

                            <!-- Text -->
                            <div class="min-w-0 text-sm">
                                <p class="text-gray-800">
                                    <span class="font-bold text-gallery-900"><?= htmlspecialchars($notif['first_name'] . ' ' . $notif['last_name']) ?></span>
                                    <?php if ($notif['type'] === 'like'): ?>
                                        appreciated your artwork <span class="font-semibold text-gallery-800">"<?= htmlspecialchars($notif['post_title']) ?>"</span>
                                    <?php elseif ($notif['type'] === 'comment'): ?>
                                        commented: <span class="italic text-gray-600">"<?= htmlspecialchars($notif['comment_text'] ?? '') ?>"</span> on <span class="font-semibold">"<?= htmlspecialchars($notif['post_title']) ?>"</span>
                                    <?php else: ?>
                                        started following your artistic collection.
                                    <?php endif; ?>
                                </p>
                                <span class="text-[11px] text-gray-400 font-medium"><?= formatTimeAgo($notif['created_at']) ?></span>
                            </div>
                        </div>

                        <!-- Right Preview Image if post -->
                        <?php if (!empty($notif['post_image'])): ?>
                            <a href="home.php" class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200 hover:opacity-90">
                                <img src="../<?= htmlspecialchars($notif['post_image']) ?>" alt="Post" class="w-full h-full object-cover">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-500">
        <p>© <?= date('Y') ?> VirtuGallery — Community Engagement</p>
    </footer>

</body>

</html>
