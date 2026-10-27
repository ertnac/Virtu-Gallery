<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle profile update POST directly or via fetch
$updateMessage = '';
$updateError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $municipality = trim($_POST['municipality'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $website = trim($_POST['website'] ?? '');

    try {
        $profile_picture = null;
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['profile_pic'];
            $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
            $target = 'profile_image/profile_' . $user_id . '_' . time() . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], __DIR__ . '/' . $target)) {
                $profile_picture = $target;
            }
        }

        if ($profile_picture) {
            $uStmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, username=?, phone=?, municipality=?, province=?, bio=?, website=?, profile_picture=? WHERE id=?");
            $uStmt->execute([$first_name, $last_name, $username, $phone, $municipality, $province, $bio, $website, $profile_picture, $user_id]);
        } else {
            $uStmt = $pdo->prepare("UPDATE users SET first_name=?, last_name=?, username=?, phone=?, municipality=?, province=?, bio=?, website=? WHERE id=?");
            $uStmt->execute([$first_name, $last_name, $username, $phone, $municipality, $province, $bio, $website, $user_id]);
        }
        $updateMessage = 'Profile updated successfully!';
    } catch (PDOException $e) {
        $updateError = 'Update failed: ' . $e->getMessage();
    }
}

// Fetch user profile info
try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    // Counts
    $artCount = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
    $artCount->execute([$user_id]);
    $totalArtworks = $artCount->fetchColumn();

    $folCount = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE followed_id = ?");
    $folCount->execute([$user_id]);
    $totalFollowers = $folCount->fetchColumn();

    $fingCount = $pdo->prepare("SELECT COUNT(*) FROM follows WHERE follower_id = ?");
    $fingCount->execute([$user_id]);
    $totalFollowing = $fingCount->fetchColumn();

    // User's Artworks
    $myArtworksStmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $myArtworksStmt->execute([$user_id]);
    $myArtworks = $myArtworksStmt->fetchAll(PDO::FETCH_ASSOC);

    // User's Liked/Saved Artworks
    $likedStmt = $pdo->prepare("
        SELECT p.*, u.first_name, u.last_name 
        FROM posts p 
        JOIN likes l ON p.id = l.post_id 
        JOIN users u ON p.user_id = u.id 
        WHERE l.user_id = ? 
        ORDER BY l.created_at DESC
    ");
    $likedStmt->execute([$user_id]);
    $likedArtworks = $likedStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?> — VirtuGallery Profile</title>
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
                    <span class="w-10 h-10 rounded-full bg-gallery-800 text-gallery-gold flex items-center justify-center font-serif font-bold text-xl shadow-md">V</span>
                    <span class="font-serif text-2xl font-bold tracking-tight text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-600">
                    <a href="home.php" class="hover:text-gallery-800">Community Feed</a>
                    <a href="Marketplace.php" class="hover:text-gallery-800">Marketplace</a>
                    <a href="ranking.php" class="hover:text-gallery-800">Rankings</a>
                    <a href="UserProfilePage.php" class="text-gallery-800 font-bold border-b-2 border-gallery-800 pb-1">My Studio</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../logout.php" class="text-xs font-bold text-red-600 hover:text-red-700 uppercase tracking-wider">Sign Out</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="pt-28 pb-20 flex-grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <?php if (!empty($updateMessage)): ?>
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span><?= htmlspecialchars($updateMessage) ?></span>
            </div>
        <?php endif; ?>

        <!-- Profile Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm relative overflow-hidden mb-10">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8 text-center sm:text-left">
                
                <!-- Avatar -->
                <div class="relative w-28 h-28 sm:w-36 sm:h-36 rounded-full overflow-hidden border-4 border-gallery-100 shadow-md flex-shrink-0 bg-gray-100">
                    <img src="<?= !empty($user['profile_picture']) ? (strpos($user['profile_picture'], 'profile_image/') === 0 ? $user['profile_picture'] : $user['profile_picture']) : 'profile_image/default.jpg' ?>" 
                         alt="Avatar" class="w-full h-full object-cover">
                </div>

                <!-- Info & Bio -->
                <div class="flex-grow min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-gallery-900 leading-tight">
                                <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                            </h1>
                            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">@<?= htmlspecialchars($user['username']) ?> • <span class="text-gray-600"><?= htmlspecialchars($user['municipality'] . ', ' . $user['province']) ?></span></p>
                        </div>
                        <button onclick="openEditModal()" class="px-5 py-2.5 rounded-full border border-gray-300 hover:border-gallery-800 text-xs font-semibold text-gray-700 hover:text-gallery-900 transition-colors flex items-center justify-center space-x-1.5">
                            <i class="fas fa-pen text-[10px]"></i>
                            <span>Edit Studio Profile</span>
                        </button>
                    </div>

                    <p class="text-sm text-gray-600 mt-4 leading-relaxed font-light max-w-2xl">
                        <?= !empty($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : 'No biography provided yet. Tap "Edit Studio Profile" to tell patrons about your inspirations.' ?>
                    </p>

                    <!-- Metrics -->
                    <div class="flex items-center justify-center sm:justify-start space-x-8 mt-6 pt-6 border-t border-gray-100 text-sm">
                        <div>
                            <span class="font-bold text-gallery-900 font-serif text-lg"><?= $totalArtworks ?></span>
                            <span class="text-xs text-gray-400 block uppercase tracking-wider">Artworks</span>
                        </div>
                        <div>
                            <span class="font-bold text-gallery-900 font-serif text-lg"><?= $totalFollowers ?></span>
                            <span class="text-xs text-gray-400 block uppercase tracking-wider">Followers</span>
                        </div>
                        <div>
                            <span class="font-bold text-gallery-900 font-serif text-lg"><?= $totalFollowing ?></span>
                            <span class="text-xs text-gray-400 block uppercase tracking-wider">Following</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Studio Navigation Tabs -->
        <div class="flex items-center justify-center space-x-2 border-b border-gray-200 mb-8">
            <button id="tab-artworks" onclick="switchProfileTab('artworks')" class="px-6 py-3 font-semibold text-sm border-b-2 border-gallery-800 text-gallery-800 flex items-center space-x-2">
                <i class="fas fa-th-large text-xs"></i>
                <span>My Works (<?= count($myArtworks) ?>)</span>
            </button>
            <button id="tab-liked" onclick="switchProfileTab('liked')" class="px-6 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gallery-800 flex items-center space-x-2">
                <i class="fas fa-heart text-xs"></i>
                <span>Appreciated (<?= count($likedArtworks) ?>)</span>
            </button>
        </div>

        <!-- Tab 1: My Artworks Grid -->
        <div id="section-artworks-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($myArtworks)): ?>
                <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-3xl border border-gray-100">
                    <i class="fas fa-paint-brush text-4xl mb-3 text-gray-300"></i>
                    <h3 class="font-serif text-lg font-bold text-gray-700">No artwork published yet</h3>
                    <p class="text-xs text-gray-400 mt-1">Publish pieces to showcase your talent in the gallery feed and marketplace.</p>
                    <a href="home.php" class="inline-block mt-4 px-6 py-2.5 bg-gallery-800 text-white rounded-full text-xs font-bold">Go to Feed to Upload</a>
                </div>
            <?php else: ?>
                <?php foreach ($myArtworks as $art): ?>
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col justify-between group">
                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                            <img src="../<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-[10px] uppercase font-bold text-gallery-800 px-2.5 py-1 rounded-full shadow-sm">
                                <?= htmlspecialchars($art['category']) ?>
                            </span>
                        </div>
                        <div class="p-4">
                            <h4 class="font-serif text-base font-bold text-gallery-900 truncate"><?= htmlspecialchars($art['title']) ?></h4>
                            <p class="text-xs text-gray-400 mt-1"><?= $art['post_type'] === 'product' ? '₱' . number_format($art['price'], 2) : 'Exhibition' ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Tab 2: Liked Artworks Grid -->
        <div id="section-liked-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
            <?php if (empty($likedArtworks)): ?>
                <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-3xl border border-gray-100">
                    <i class="far fa-heart text-4xl mb-3 text-gray-300"></i>
                    <h3 class="font-serif text-lg font-bold text-gray-700">No liked artworks yet</h3>
                    <p class="text-xs text-gray-400 mt-1">Double tap or heart pieces in the community feed to save them here.</p>
                </div>
            <?php else: ?>
                <?php foreach ($likedArtworks as $art): ?>
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col justify-between group">
                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                            <img src="../<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-[10px] uppercase font-bold text-gallery-800 px-2.5 py-1 rounded-full shadow-sm">
                                <?= htmlspecialchars($art['category']) ?>
                            </span>
                        </div>
                        <div class="p-4">
                            <h4 class="font-serif text-base font-bold text-gallery-900 truncate"><?= htmlspecialchars($art['title']) ?></h4>
                            <p class="text-xs text-gray-400 mt-1">by <?= htmlspecialchars($art['first_name'] . ' ' . $art['last_name']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

    <!-- Edit Profile Modal -->
    <div id="edit-profile-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="fixed inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-lg sm:w-full bg-white rounded-3xl p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <h3 class="font-serif text-xl font-bold text-gallery-900">Edit Studio Profile</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
            </div>

            <form method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                <input type="hidden" name="action" value="update_profile">

                <!-- Profile Pic File Upload -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">New Avatar Image</label>
                    <input type="file" name="profile_pic" accept="image/png, image/jpeg" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gallery-100 file:text-gallery-800 hover:file:bg-gallery-200">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">First Name</label>
                        <input type="text" name="first_name" required value="<?= htmlspecialchars($user['first_name']) ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Last Name</label>
                        <input type="text" name="last_name" required value="<?= htmlspecialchars($user['last_name']) ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Contact Phone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+63 912 345 6789" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Municipality</label>
                        <input type="text" name="municipality" value="<?= htmlspecialchars($user['municipality'] ?? '') ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Province</label>
                        <input type="text" name="province" value="<?= htmlspecialchars($user['province'] ?? '') ?>" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Artist Biography</label>
                    <textarea name="bio" rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="w-full py-3.5 bg-gallery-800 hover:bg-gallery-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all">
                    Save Profile Changes
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-500">
        <p>© <?= date('Y') ?> VirtuGallery — Artist Studio Management</p>
    </footer>

    <script>
        function switchProfileTab(tab) {
            const btnArt = document.getElementById('tab-artworks');
            const btnLiked = document.getElementById('tab-liked');
            const secArt = document.getElementById('section-artworks-grid');
            const secLiked = document.getElementById('section-liked-grid');

            if (tab === 'artworks') {
                btnArt.className = "px-6 py-3 font-semibold text-sm border-b-2 border-gallery-800 text-gallery-800 flex items-center space-x-2";
                btnLiked.className = "px-6 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gallery-800 flex items-center space-x-2";
                secArt.classList.remove('hidden');
                secLiked.classList.add('hidden');
            } else {
                btnLiked.className = "px-6 py-3 font-semibold text-sm border-b-2 border-gallery-800 text-gallery-800 flex items-center space-x-2";
                btnArt.className = "px-6 py-3 font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gallery-800 flex items-center space-x-2";
                secLiked.classList.remove('hidden');
                secArt.classList.add('hidden');
            }
        }

        function openEditModal() {
            document.getElementById('edit-profile-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-profile-modal').classList.add('hidden');
        }
    </script>
</body>

</html>
