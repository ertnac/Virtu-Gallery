<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Current user info
    $uStmt = $pdo->prepare("SELECT id, username, first_name, last_name, profile_picture, municipality, province FROM users WHERE id = ?");
    $uStmt->execute([$user_id]);
    $currentUser = $uStmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentUser) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    // Fetch creators for stories/spotlight
    $creatorsStmt = $pdo->query("
        SELECT id, username, first_name, last_name, profile_picture 
        FROM users 
        WHERE (SELECT COUNT(*) FROM posts WHERE user_id = users.id) > 0 
        LIMIT 8
    ");
    $spotlightCreators = $creatorsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch live posts
    $postStmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.title, 
            p.description, 
            p.category, 
            p.post_type, 
            p.price, 
            p.tags, 
            p.image_path, 
            p.created_at, 
            u.id AS author_id,
            u.username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            u.municipality,
            u.province,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes_count,
            EXISTS (SELECT 1 FROM likes l WHERE l.post_id = p.id AND l.user_id = ?) AS is_liked,
            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comments_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    $postStmt->execute([$user_id]);
    $posts = $postStmt->fetchAll(PDO::FETCH_ASSOC);

    // Suggested Artists to follow
    $suggStmt = $pdo->prepare("
        SELECT u.id, u.username, u.first_name, u.last_name, u.profile_picture,
               COALESCE(a.stage_name, u.first_name || ' ' || u.last_name) AS stage_name,
               COALESCE(a.art_style, 'Artist') AS art_style,
               EXISTS (SELECT 1 FROM follows f WHERE f.follower_id = ? AND f.followed_id = u.id) AS is_following
        FROM users u
        LEFT JOIN artists a ON u.id = a.user_id
        WHERE u.id != ?
        LIMIT 4
    ");
    $suggStmt->execute([$user_id, $user_id]);
    $suggestedArtists = $suggStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $posts = [];
    $spotlightCreators = [];
    $suggestedArtists = [];
}

function timeAgoFeed($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm';
    if ($diff < 86400) return floor($diff / 3600) . 'h';
    return floor($diff / 86400) . 'd';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Community Feed — VirtuGallery</title>
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
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gallery-50 text-gray-800 font-sans antialiased min-h-screen">

    <!-- Global Toast -->
    <div id="toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none flex items-center space-x-3 bg-gallery-900 text-white px-5 py-3.5 rounded-xl shadow-2xl border border-gallery-700/50">
        <div id="toast-icon" class="text-gallery-gold"><i class="fas fa-check-circle text-lg"></i></div>
        <p id="toast-message" class="text-sm font-medium">Notification</p>
    </div>

    <!-- Top Navigation Header -->
    <nav class="fixed top-0 left-0 right-0 z-40 backdrop-glass border-b border-gray-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Logo -->
                <a href="../index.php" class="flex items-center space-x-2.5">
                    <img src="../assets/images/logo-icon.svg" alt="VirtuGallery Emblem" class="w-9 h-9 rounded-lg shadow-sm">
                    <span class="font-serif text-xl sm:text-2xl font-bold tracking-tight text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                </a>

                <!-- Search Input on Header (Medium screens up) -->
                <div class="hidden sm:block flex-1 max-w-md mx-6">
                    <div class="relative">
                        <input type="text" id="feed-search-input" oninput="filterFeedPosts()" placeholder="Explore by title, artist, or tag..." 
                               class="w-full pl-10 pr-4 py-2 rounded-full bg-gray-100 border border-transparent focus:border-gallery-800 focus:bg-white text-xs focus:outline-none transition-all">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <button onclick="openUploadModal()" class="inline-flex items-center space-x-2 text-xs font-bold bg-gallery-800 hover:bg-gallery-900 text-white px-4 py-2.5 rounded-full shadow-sm transition-all">
                        <i class="fas fa-cloud-upload-alt text-gallery-gold"></i>
                        <span class="hidden sm:inline">Upload Piece</span>
                    </button>

                    <a href="cart.html" class="p-2 text-gray-700 hover:text-gallery-800" title="Cart">
                        <i class="fas fa-shopping-bag text-lg"></i>
                    </a>

                    <a href="UserProfilePage.php" class="w-9 h-9 rounded-full overflow-hidden border border-gray-300 ring-2 ring-gallery-100" title="Profile">
                        <img src="<?= !empty($currentUser['profile_picture']) ? (strpos($currentUser['profile_picture'], 'profile_image/') === 0 ? $currentUser['profile_picture'] : $currentUser['profile_picture']) : 'profile_image/default.jpg' ?>" 
                             alt="Avatar" class="w-full h-full object-cover">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sidebar Navigation (Desktop, 3 cols) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-28 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-gallery-gold p-0.5 mb-3 bg-gray-100">
                        <img src="<?= !empty($currentUser['profile_picture']) ? (strpos($currentUser['profile_picture'], 'profile_image/') === 0 ? $currentUser['profile_picture'] : $currentUser['profile_picture']) : 'profile_image/default.jpg' ?>" 
                             alt="Avatar" class="w-full h-full object-cover rounded-full">
                    </div>
                    <h3 class="font-serif text-lg font-bold text-gallery-900"><?= htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']) ?></h3>
                    <p class="text-xs text-gray-400">@<?= htmlspecialchars($currentUser['username']) ?></p>
                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-[10px] mr-1 text-gallery-gold"></i><?= htmlspecialchars($currentUser['municipality'] . ', ' . $currentUser['province']) ?></p>
                    
                    <a href="UserProfilePage.php" class="inline-block mt-4 w-full py-2 bg-gallery-100 hover:bg-gallery-200 text-gallery-800 rounded-xl text-xs font-semibold transition-colors">
                        Manage Profile
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="bg-white rounded-3xl p-4 border border-gray-100 shadow-sm space-y-1 text-sm font-medium">
                    <a href="home.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl bg-gallery-800 text-white font-semibold">
                        <i class="fas fa-compass text-gallery-gold"></i>
                        <span>Community Feed</span>
                    </a>
                    <a href="Marketplace.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-gray-600 hover:bg-gallery-50 hover:text-gallery-900 transition-colors">
                        <i class="fas fa-store"></i>
                        <span>Marketplace</span>
                    </a>
                    <a href="ranking.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-gray-600 hover:bg-gallery-50 hover:text-gallery-900 transition-colors">
                        <i class="fas fa-trophy"></i>
                        <span>Rankings</span>
                    </a>
                    <a href="notifications.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-gray-600 hover:bg-gallery-50 hover:text-gallery-900 transition-colors">
                        <i class="far fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="settings.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-gray-600 hover:bg-gallery-50 hover:text-gallery-900 transition-colors">
                        <i class="fas fa-sliders-h"></i>
                        <span>Settings</span>
                    </a>
                    <a href="../logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-2xl text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </aside>

            <!-- Center Feed Column (6 cols) -->
            <main class="lg:col-span-6 space-y-6">
                
                <!-- Creators Spotlight Bar -->
                <div class="bg-white rounded-3xl p-4 sm:p-5 border border-gray-100 shadow-sm overflow-hidden">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-gallery-gold block mb-3">Featured Creators in Laguna</span>
                    <div class="flex items-center space-x-4 overflow-x-auto hide-scrollbar pb-1">
                        <?php foreach ($spotlightCreators as $creator): ?>
                            <div class="flex flex-col items-center flex-shrink-0 space-y-1.5 cursor-pointer group" onclick="filterByArtist('<?= htmlspecialchars(strtolower($creator['username'])) ?>')">
                                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full p-0.5 bg-gradient-to-tr from-gallery-gold via-gallery-800 to-amber-200 group-hover:scale-105 transition-transform">
                                    <div class="w-full h-full rounded-full overflow-hidden bg-white p-0.5">
                                        <img src="<?= !empty($creator['profile_picture']) ? (strpos($creator['profile_picture'], 'profile_image/') === 0 ? $creator['profile_picture'] : $creator['profile_picture']) : 'profile_image/default.jpg' ?>" 
                                             alt="Creator" class="w-full h-full object-cover rounded-full">
                                    </div>
                                </div>
                                <span class="text-[11px] font-medium text-gray-700 max-w-[64px] truncate text-center"><?= htmlspecialchars($creator['first_name']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Feed Posts Stream -->
                <div id="feed-stream" class="space-y-6">
                    <?php if (empty($posts)): ?>
                        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                            <i class="fas fa-palette text-5xl text-gray-300 mb-3"></i>
                            <h3 class="font-serif text-xl font-bold text-gray-700">No artwork posts yet</h3>
                            <p class="text-xs text-gray-400 mt-1">Be the first artist to publish a piece!</p>
                            <button onclick="openUploadModal()" class="mt-4 px-6 py-2.5 bg-gallery-800 text-white text-xs font-bold rounded-full">Upload Now</button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="feed-post-card bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden"
                                     data-title="<?= htmlspecialchars(strtolower($post['title'])) ?>"
                                     data-artist="<?= htmlspecialchars(strtolower($post['first_name'] . ' ' . $post['last_name'])) ?>"
                                     data-username="<?= htmlspecialchars(strtolower($post['username'])) ?>"
                                     data-tags="<?= htmlspecialchars(strtolower($post['tags'] ?? '')) ?>"
                                     data-category="<?= htmlspecialchars(strtolower($post['category'])) ?>">
                                
                                <!-- Card Header -->
                                <div class="p-4 sm:p-5 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                            <img src="<?= !empty($post['profile_picture']) ? (strpos($post['profile_picture'], 'profile_image/') === 0 ? $post['profile_picture'] : $post['profile_picture']) : 'profile_image/default.jpg' ?>" 
                                                 alt="Author" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-sm text-gallery-900 leading-tight">
                                                <?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?>
                                            </h4>
                                            <p class="text-[11px] text-gray-400">@<?= htmlspecialchars($post['username']) ?> • <?= timeAgoFeed($post['created_at']) ?></p>
                                        </div>
                                    </div>

                                    <span class="text-[10px] uppercase font-bold tracking-wider text-gallery-800 bg-gallery-100 px-3 py-1 rounded-full">
                                        <?= htmlspecialchars($post['category']) ?>
                                    </span>
                                </div>

                                <!-- Artwork Image with Double Click Heart -->
                                <div class="relative bg-gray-900 overflow-hidden group select-none">
                                    <img src="../<?= htmlspecialchars($post['image_path']) ?>" 
                                         alt="<?= htmlspecialchars($post['title']) ?>" 
                                         loading="lazy"
                                         ondblclick="handleDoubleTapLike(<?= (int)$post['id'] ?>)"
                                         class="w-full max-h-[580px] object-contain mx-auto transition-transform duration-300">
                                    
                                    <!-- Double Tap Like Flash Heart -->
                                    <div id="heart-anim-<?= $post['id'] ?>" class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 scale-50 transition-all duration-300">
                                        <i class="fas fa-heart text-6xl text-rose-500 drop-shadow-lg"></i>
                                    </div>
                                </div>

                                <!-- Post Action Bar & Description -->
                                <div class="p-5 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Like Button -->
                                            <button onclick="toggleLikePost(<?= (int)$post['id'] ?>)" 
                                                    id="like-btn-<?= $post['id'] ?>"
                                                    class="flex items-center space-x-1.5 text-sm font-semibold transition-transform active:scale-125 <?= $post['is_liked'] ? 'text-rose-500' : 'text-gray-500 hover:text-rose-500' ?>">
                                                <i class="fas fa-heart text-lg"></i>
                                                <span id="likes-count-<?= $post['id'] ?>"><?= $post['likes_count'] ?></span>
                                            </button>

                                            <!-- Comment Trigger -->
                                            <button onclick="openCommentsDrawer(<?= (int)$post['id'] ?>, '<?= htmlspecialchars(addslashes($post['title'])) ?>')" class="flex items-center space-x-1.5 text-sm font-semibold text-gray-500 hover:text-gallery-800">
                                                <i class="far fa-comment text-lg"></i>
                                                <span id="comments-count-<?= $post['id'] ?>"><?= $post['comments_count'] ?></span>
                                            </button>
                                        </div>

                                        <?php if (!empty($post['price']) && $post['price'] > 0): ?>
                                            <div class="flex items-center space-x-2">
                                                <span class="font-bold text-gallery-900 text-sm">₱<?= number_format($post['price'], 2) ?></span>
                                                <a href="Marketplace.php" class="text-xs font-semibold bg-gallery-100 hover:bg-gallery-800 text-gallery-800 hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                                                    Collect
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div>
                                        <h3 class="font-serif text-lg font-bold text-gallery-900"><?= htmlspecialchars($post['title']) ?></h3>
                                        <p class="text-xs sm:text-sm text-gray-600 font-light mt-1 leading-relaxed">
                                            <?= htmlspecialchars($post['description']) ?>
                                        </p>
                                        <?php if (!empty($post['tags'])): ?>
                                            <p class="text-xs text-gallery-500 mt-2 font-medium">
                                                <?= htmlspecialchars($post['tags']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Quick comment input -->
                                    <div class="pt-3 border-t border-gray-100 flex items-center space-x-2">
                                        <input type="text" id="quick-comment-input-<?= $post['id'] ?>" placeholder="Add an appreciative remark..." 
                                               onkeydown="if(event.key==='Enter') submitQuickComment(<?= (int)$post['id'] ?>)"
                                               class="flex-1 text-xs bg-gallery-50 border border-gray-200 rounded-full px-4 py-2 focus:outline-none focus:border-gallery-800">
                                        <button onclick="submitQuickComment(<?= (int)$post['id'] ?>)" class="text-xs font-bold text-gallery-800 px-2 py-1 hover:text-gallery-gold">Post</button>
                                    </div>
                                </div>

                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </main>

            <!-- Right Suggestions Column (3 cols) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-28 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <span class="font-serif text-base font-bold text-gallery-900">Featured Creators</span>
                        <a href="ranking.php" class="text-xs text-gallery-gold font-bold hover:underline">All</a>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($suggestedArtists as $sugg): ?>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                        <img src="<?= !empty($sugg['profile_picture']) ? (strpos($sugg['profile_picture'], 'profile_image/') === 0 ? $sugg['profile_picture'] : $sugg['profile_picture']) : 'profile_image/default.jpg' ?>" 
                                             alt="Artist" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-xs text-gallery-900 truncate"><?= htmlspecialchars($sugg['stage_name']) ?></h4>
                                        <p class="text-[11px] text-gray-400 truncate"><?= htmlspecialchars($sugg['art_style']) ?></p>
                                    </div>
                                </div>
                                <button onclick="toggleFollow(<?= (int)$sugg['id'] ?>, this)" 
                                        class="flex-shrink-0 text-[11px] font-bold px-3 py-1.5 rounded-full transition-all <?= $sugg['is_following'] ? 'bg-gray-100 text-gray-600' : 'bg-gallery-800 text-white' ?>">
                                    <?= $sugg['is_following'] ? 'Following' : 'Follow' ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="text-xs text-gray-400 p-2 space-y-2">
                    <p>© <?= date('Y') ?> VirtuGallery Community</p>
                    <div class="flex space-x-3">
                        <a href="Marketplace.php" class="hover:text-gray-600">Market</a>
                        <a href="ranking.php" class="hover:text-gray-600">Rankings</a>
                        <a href="../shanaya/index.php" class="hover:text-gray-600">Curator CMS</a>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar (< 1024px) -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 backdrop-glass border-t border-gray-200/80 px-6 py-2.5 flex items-center justify-around text-gray-500">
        <a href="home.php" class="flex flex-col items-center text-gallery-800 font-bold">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[10px] mt-0.5">Feed</span>
        </a>
        <a href="Marketplace.php" class="flex flex-col items-center hover:text-gallery-800">
            <i class="fas fa-store text-lg"></i>
            <span class="text-[10px] mt-0.5">Market</span>
        </a>
        <button onclick="openUploadModal()" class="w-10 h-10 rounded-full bg-gallery-800 text-gallery-gold flex items-center justify-center text-base shadow-md">
            <i class="fas fa-plus"></i>
        </button>
        <a href="ranking.php" class="flex flex-col items-center hover:text-gallery-800">
            <i class="fas fa-trophy text-lg"></i>
            <span class="text-[10px] mt-0.5">Ranking</span>
        </a>
        <a href="UserProfilePage.php" class="flex flex-col items-center hover:text-gallery-800">
            <i class="far fa-user text-lg"></i>
            <span class="text-[10px] mt-0.5">Profile</span>
        </a>
    </div>

    <!-- Upload Artwork Modal -->
    <div id="upload-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeUploadModal()"></div>
        <div class="fixed inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-xl sm:w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="font-serif text-2xl font-bold text-gallery-900">Publish New Masterpiece</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>

            <form id="upload-form" onsubmit="submitUpload(event)" class="mt-6 space-y-4" enctype="multipart/form-data">
                
                <!-- Image Drag / Select -->
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-gallery-800 transition-colors cursor-pointer" onclick="document.getElementById('artwork-file').click()">
                    <input type="file" id="artwork-file" name="image" required accept="image/png, image/jpeg" class="hidden" onchange="previewUploadImage(this)">
                    <div id="upload-placeholder">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gallery-gold mb-2"></i>
                        <p class="text-xs font-semibold text-gray-700">Click to upload artwork photograph</p>
                        <p class="text-[10px] text-gray-400 mt-1">PNG, JPG up to 5MB</p>
                    </div>
                    <img id="upload-preview" src="" alt="Preview" class="hidden max-h-48 mx-auto rounded-xl object-contain shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Artwork Title *</label>
                    <input type="text" id="artwork-title" name="title" required placeholder="e.g. Sunrise Over Mt. Banahaw" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Category *</label>
                        <select id="artwork-category" name="category" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none bg-white">
                            <option value="paintings">Paintings</option>
                            <option value="sculpture">Sculptures</option>
                            <option value="photography">Photography</option>
                            <option value="pottery">Pottery</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Type *</label>
                        <select id="artwork-post-type" name="post_type" onchange="togglePriceField(this.value)" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none bg-white">
                            <option value="content">Exhibition (Not for sale)</option>
                            <option value="product">For Sale in Marketplace</option>
                        </select>
                    </div>
                </div>

                <div id="price-container" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Selling Price (₱) *</label>
                    <input type="number" id="artwork-price" name="price" step="0.01" placeholder="15000.00" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Story & Description *</label>
                    <textarea id="artwork-description" name="description" rows="3" required placeholder="Describe the medium, inspiration, and technique..." class="w-full px-4 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Tags (comma separated)</label>
                    <input type="text" id="artwork-tags" name="tags" placeholder="oil, canvas, landscape, laguna" class="w-full px-4 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                </div>

                <button type="submit" id="upload-submit-btn" class="w-full py-3.5 bg-gallery-800 hover:bg-gallery-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                    Publish Artwork
                </button>
            </form>
        </div>
    </div>

    <!-- Comments Slide-over Drawer -->
    <div id="comments-drawer" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCommentsDrawer()"></div>
        <div class="fixed right-0 top-0 bottom-0 w-full max-w-md bg-white p-6 shadow-2xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gallery-gold">Community Dialogue</span>
                        <h3 id="comments-drawer-title" class="font-serif text-lg font-bold text-gallery-900">Comments</h3>
                    </div>
                    <button onclick="closeCommentsDrawer()" class="p-1 text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
                </div>

                <!-- Comments List Container -->
                <div id="comments-list" class="mt-4 space-y-4 max-h-[60vh] overflow-y-auto pr-1">
                    <!-- Populated dynamically via AJAX -->
                </div>
            </div>

            <!-- Comment Input -->
            <div class="pt-4 border-t border-gray-100">
                <div class="flex items-center space-x-2">
                    <input type="text" id="drawer-comment-input" placeholder="Write a comment..." 
                           onkeydown="if(event.key==='Enter') submitDrawerComment()"
                           class="flex-1 px-4 py-2.5 rounded-full border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    <button onclick="submitDrawerComment()" class="px-5 py-2.5 bg-gallery-800 text-white text-xs font-bold rounded-full hover:bg-gallery-900">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let activeCommentPostId = null;

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3000);
        }

        // Toggle Like
        function toggleLikePost(postId) {
            fetch('../like_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const btn = document.getElementById(`like-btn-${postId}`);
                    const countEl = document.getElementById(`likes-count-${postId}`);
                    countEl.innerText = data.like_count;
                    if (data.is_liked) {
                        btn.className = "flex items-center space-x-1.5 text-sm font-semibold transition-transform active:scale-125 text-rose-500";
                    } else {
                        btn.className = "flex items-center space-x-1.5 text-sm font-semibold transition-transform active:scale-125 text-gray-500 hover:text-rose-500";
                    }
                }
            });
        }

        function handleDoubleTapLike(postId) {
            const heart = document.getElementById(`heart-anim-${postId}`);
            heart.classList.remove('opacity-0', 'scale-50');
            heart.classList.add('opacity-100', 'scale-125');
            setTimeout(() => {
                heart.classList.remove('opacity-100', 'scale-125');
                heart.classList.add('opacity-0', 'scale-50');
            }, 600);
            toggleLikePost(postId);
        }

        // Quick Comments
        function submitQuickComment(postId) {
            const input = document.getElementById(`quick-comment-input-${postId}`);
            const text = input.value.trim();
            if (!text) return;

            fetch('../add_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId, comment_text: text })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    const countEl = document.getElementById(`comments-count-${postId}`);
                    countEl.innerText = parseInt(countEl.innerText) + 1;
                    showToast('Comment published!');
                }
            });
        }

        // Comments Drawer
        function openCommentsDrawer(postId, title) {
            activeCommentPostId = postId;
            document.getElementById('comments-drawer-title').innerText = title;
            const container = document.getElementById('comments-list');
            container.innerHTML = '<p class="text-xs text-gray-400 text-center py-8">Loading dialogue...</p>';
            document.getElementById('comments-drawer').classList.remove('hidden');

            fetch(`../get_comments.php?post_id=${postId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.comments.length > 0) {
                        container.innerHTML = data.comments.map(c => `
                            <div class="p-3 rounded-2xl bg-gallery-50 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-gallery-900">@${c.username}</span>
                                    <span class="text-[10px] text-gray-400">${c.created_at}</span>
                                </div>
                                <p class="text-gray-700 font-light">${c.comment_text}</p>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p class="text-xs text-gray-400 text-center py-8">No comments yet. Share your thoughts!</p>';
                    }
                });
        }

        function closeCommentsDrawer() {
            document.getElementById('comments-drawer').classList.add('hidden');
        }

        function submitDrawerComment() {
            if (!activeCommentPostId) return;
            const input = document.getElementById('drawer-comment-input');
            const text = input.value.trim();
            if (!text) return;

            fetch('../add_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: activeCommentPostId, comment_text: text })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    openCommentsDrawer(activeCommentPostId, document.getElementById('comments-drawer-title').innerText);
                    const countEl = document.getElementById(`comments-count-${activeCommentPostId}`);
                    if (countEl) countEl.innerText = parseInt(countEl.innerText) + 1;
                }
            });
        }

        // Upload Modal
        function openUploadModal() {
            document.getElementById('upload-modal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('upload-modal').classList.add('hidden');
        }

        function togglePriceField(val) {
            document.getElementById('price-container').classList.toggle('hidden', val !== 'product');
        }

        function previewUploadImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('upload-preview');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function submitUpload(e) {
            e.preventDefault();
            const btn = document.getElementById('upload-submit-btn');
            btn.disabled = true;
            btn.innerText = 'Publishing...';

            const formData = new FormData(document.getElementById('upload-form'));

            fetch('../upload.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Artwork published successfully!');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    alert((data.errors || ['Upload failed']).join('\n'));
                    btn.disabled = false;
                    btn.innerText = 'Publish Artwork';
                }
            })
            .catch(() => {
                alert('Connection error.');
                btn.disabled = false;
                btn.innerText = 'Publish Artwork';
            });
        }

        // Search Filter
        function filterFeedPosts() {
            const query = document.getElementById('feed-search-input').value.toLowerCase().trim();
            document.querySelectorAll('.feed-post-card').forEach(card => {
                const title = card.dataset.title;
                const artist = card.dataset.artist;
                const tags = card.dataset.tags;
                const match = !query || title.includes(query) || artist.includes(query) || tags.includes(query);
                card.style.display = match ? 'block' : 'none';
            });
        }

        function filterByArtist(username) {
            document.getElementById('feed-search-input').value = username;
            filterFeedPosts();
        }

        function toggleFollow(artistId, btn) {
            fetch('../api/toggle_follow.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ followed_id: artistId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.is_following) {
                        btn.innerText = 'Following';
                        btn.className = 'flex-shrink-0 text-[11px] font-bold px-3 py-1.5 rounded-full transition-all bg-gray-100 text-gray-600';
                    } else {
                        btn.innerText = 'Follow';
                        btn.className = 'flex-shrink-0 text-[11px] font-bold px-3 py-1.5 rounded-full transition-all bg-gallery-800 text-white';
                    }
                }
            });
        }
    </script>
</body>

</html>
