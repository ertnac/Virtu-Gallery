<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

$current_user_id = $_SESSION['user_id'] ?? 0;
$isLoggedIn = isset($_SESSION['user_id']);

try {
    // 1. Top Artists
    $artistsStmt = $pdo->prepare("
        SELECT 
            u.id,
            u.username,
            u.first_name,
            u.last_name,
            u.profile_picture,
            u.municipality,
            u.province,
            COALESCE(a.stage_name, u.first_name || ' ' || u.last_name) AS stage_name,
            COALESCE(a.art_style, 'Visual Arts') AS art_style,
            COALESCE(a.verified, 0) AS verified,
            (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS artworks_count,
            (SELECT COUNT(*) FROM follows f WHERE f.followed_id = u.id) AS followers_count,
            EXISTS (SELECT 1 FROM follows f WHERE f.follower_id = ? AND f.followed_id = u.id) AS is_following
        FROM users u
        LEFT JOIN artists a ON u.id = a.user_id
        ORDER BY followers_count DESC, artworks_count DESC
        LIMIT 10
    ");
    $artistsStmt->execute([$current_user_id]);
    $topArtists = $artistsStmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Top Artworks
    $artworksStmt = $pdo->query("
        SELECT 
            p.id,
            p.title,
            p.category,
            p.price,
            p.image_path,
            p.created_at,
            u.id AS artist_id,
            u.username,
            u.first_name,
            u.last_name,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes_count,
            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comments_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY likes_count DESC, comments_count DESC
        LIMIT 10
    ");
    $topArtworks = $artworksStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $topArtists = [];
    $topArtworks = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Artist & Artwork Rankings — VirtuGallery</title>
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
                    <a href="../index.php" class="hover:text-gallery-800">Home</a>
                    <a href="Marketplace.php" class="hover:text-gallery-800">Marketplace</a>
                    <a href="ranking.php" class="text-gallery-800 font-bold border-b-2 border-gallery-800 pb-1">Rankings</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="home.php" class="hover:text-gallery-800">Community Feed</a>
                        <a href="notifications.php" class="hover:text-gallery-800">Notifications</a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="cart.html" class="p-2 text-gray-700 hover:text-gallery-800">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </a>
                    <?php if ($isLoggedIn): ?>
                        <a href="UserProfilePage.php" class="text-sm font-medium text-gallery-800 bg-gallery-100 hover:bg-gallery-200 px-4 py-2 rounded-full">Profile</a>
                    <?php else: ?>
                        <a href="../index.php" class="text-sm font-medium text-gallery-800">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28 pb-20 flex-grow max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs uppercase font-bold tracking-widest text-gallery-gold">Community Leaderboards</span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-gallery-900 mt-1">Hall of Creators & Works</h1>
            <p class="text-sm text-gray-500 mt-2 font-light">Celebrating the most celebrated living artists and beloved artworks on VirtuGallery.</p>
            
            <!-- Switch Tabs -->
            <div class="inline-flex p-1.5 rounded-2xl bg-white border border-gray-200 mt-6 shadow-sm">
                <button id="tab-artists-btn" onclick="switchRankingTab('artists')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all bg-gallery-800 text-white shadow-sm">
                    <i class="fas fa-users mr-1.5"></i> Top Artists
                </button>
                <button id="tab-artworks-btn" onclick="switchRankingTab('artworks')" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-gray-600 hover:text-gallery-800 transition-all">
                    <i class="fas fa-paint-brush mr-1.5"></i> Trending Artworks
                </button>
            </div>
        </div>

        <!-- 1. Top Artists Leaderboard List -->
        <div id="section-artists" class="space-y-4">
            <?php foreach ($topArtists as $index => $artist): ?>
                <?php 
                    $pos = $index + 1;
                    $badgeBg = $pos === 1 ? 'bg-amber-400 text-amber-950 ring-4 ring-amber-100' : 
                              ($pos === 2 ? 'bg-slate-300 text-slate-800 ring-4 ring-slate-100' : 
                              ($pos === 3 ? 'bg-amber-700 text-amber-100 ring-4 ring-amber-50' : 'bg-gray-100 text-gray-600'));
                ?>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4 min-w-0">
                        <!-- Rank Position -->
                        <span class="w-8 h-8 rounded-full font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 <?= $badgeBg ?>">
                            <?= $pos ?>
                        </span>

                        <!-- Avatar -->
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                            <img src="<?= !empty($artist['profile_picture']) ? (strpos($artist['profile_picture'], 'profile_image/') === 0 ? $artist['profile_picture'] : $artist['profile_picture']) : 'profile_image/default.jpg' ?>" 
                                 alt="<?= htmlspecialchars($artist['first_name']) ?>" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Artist Info -->
                        <div class="min-w-0">
                            <div class="flex items-center space-x-2">
                                <h3 class="font-serif text-base sm:text-lg font-bold text-gallery-900 truncate">
                                    <?= htmlspecialchars($artist['stage_name']) ?>
                                </h3>
                                <?php if ($artist['verified']): ?>
                                    <i class="fas fa-check-circle text-gallery-gold text-xs" title="Verified Creator"></i>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-400">@<?= htmlspecialchars($artist['username']) ?> • <span class="text-gray-600"><?= htmlspecialchars($artist['art_style']) ?></span></p>
                            <p class="text-[11px] text-gray-500 mt-1">
                                <span class="font-bold text-gallery-900"><?= $artist['followers_count'] ?></span> followers • 
                                <span class="font-bold text-gallery-900"><?= $artist['artworks_count'] ?></span> published pieces
                            </p>
                        </div>
                    </div>

                    <!-- Follow Button -->
                    <?php if ($isLoggedIn && $artist['id'] != $current_user_id): ?>
                        <button onclick="toggleFollow(<?= (int)$artist['id'] ?>, this)" 
                                class="follow-action-btn flex-shrink-0 px-4 sm:px-5 py-2 rounded-xl text-xs font-semibold transition-all <?= $artist['is_following'] ? 'bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600' : 'bg-gallery-800 text-white hover:bg-gallery-900' ?>">
                            <?= $artist['is_following'] ? 'Following' : 'Follow' ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- 2. Trending Artworks Leaderboard List -->
        <div id="section-artworks" class="space-y-4 hidden">
            <?php foreach ($topArtworks as $index => $art): ?>
                <?php 
                    $pos = $index + 1;
                    $badgeBg = $pos === 1 ? 'bg-amber-400 text-amber-950 ring-4 ring-amber-100' : 
                              ($pos === 2 ? 'bg-slate-300 text-slate-800 ring-4 ring-slate-100' : 
                              ($pos === 3 ? 'bg-amber-700 text-amber-100 ring-4 ring-amber-50' : 'bg-gray-100 text-gray-600'));
                ?>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4 min-w-0">
                        <!-- Rank Position -->
                        <span class="w-8 h-8 rounded-full font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 <?= $badgeBg ?>">
                            <?= $pos ?>
                        </span>

                        <!-- Artwork Thumbnail -->
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                            <img src="../<?= htmlspecialchars($art['image_path']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="w-full h-full object-cover">
                        </div>

                        <!-- Info -->
                        <div class="min-w-0">
                            <h3 class="font-serif text-base sm:text-lg font-bold text-gallery-900 truncate">
                                <?= htmlspecialchars($art['title']) ?>
                            </h3>
                            <p class="text-xs text-gray-500">by <?= htmlspecialchars($art['first_name'] . ' ' . $art['last_name']) ?> (@<?= htmlspecialchars($art['username']) ?>)</p>
                            <span class="inline-block text-[10px] uppercase font-bold text-gallery-gold mt-1 tracking-wider"><?= htmlspecialchars($art['category']) ?></span>
                        </div>
                    </div>

                    <!-- Stats / Price -->
                    <div class="text-right flex-shrink-0">
                        <div class="flex items-center justify-end space-x-1.5 text-rose-500 font-bold text-sm">
                            <i class="fas fa-heart text-xs"></i>
                            <span><?= $art['likes_count'] ?></span>
                        </div>
                        <span class="text-xs font-bold text-gallery-900 block mt-1">
                            <?= $art['price'] > 0 ? '₱' . number_format($art['price'], 2) : 'Exhibition' ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-500">
        <p>© <?= date('Y') ?> VirtuGallery — Hall of Creative Excellence</p>
    </footer>

    <script>
        function switchRankingTab(tab) {
            const artistsBtn = document.getElementById('tab-artists-btn');
            const artworksBtn = document.getElementById('tab-artworks-btn');
            const secArtists = document.getElementById('section-artists');
            const secArtworks = document.getElementById('section-artworks');

            if (tab === 'artists') {
                artistsBtn.className = "px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all bg-gallery-800 text-white shadow-sm";
                artworksBtn.className = "px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-gray-600 hover:text-gallery-800 transition-all";
                secArtists.classList.remove('hidden');
                secArtworks.classList.add('hidden');
            } else {
                artworksBtn.className = "px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all bg-gallery-800 text-white shadow-sm";
                artistsBtn.className = "px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-gray-600 hover:text-gallery-800 transition-all";
                secArtworks.classList.remove('hidden');
                secArtists.classList.add('hidden');
            }
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
                        btn.className = 'follow-action-btn flex-shrink-0 px-4 sm:px-5 py-2 rounded-xl text-xs font-semibold transition-all bg-gray-100 text-gray-700 hover:bg-red-50 hover:text-red-600';
                    } else {
                        btn.innerText = 'Follow';
                        btn.className = 'follow-action-btn flex-shrink-0 px-4 sm:px-5 py-2 rounded-xl text-xs font-semibold transition-all bg-gallery-800 text-white hover:bg-gallery-900';
                    }
                } else {
                    alert(data.message || 'Action failed');
                }
            })
            .catch(() => alert('Network error.'));
        }
    </script>
</body>

</html>
