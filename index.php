<?php
session_start();
require_once __DIR__ . '/db_connect.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userEmail = $_SESSION['email'] ?? '';
$currentUserId = $_SESSION['user_id'] ?? null;

// Fetch live statistics
try {
    $statsArtworks = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $statsArtists = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM posts")->fetchColumn();
    $statsUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    // Fetch trending artworks dynamically
    $artStmt = $pdo->query("
        SELECT 
            p.id,
            p.title,
            p.description,
            p.category,
            p.post_type,
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
        ORDER BY likes_count DESC, p.created_at DESC
        LIMIT 8
    ");
    $trendingArtworks = $artStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch featured artists
    $artistStmt = $pdo->query("
        SELECT 
            u.id,
            u.first_name,
            u.last_name,
            u.username,
            u.profile_picture,
            u.municipality,
            u.province,
            COALESCE(a.stage_name, u.first_name || ' ' || u.last_name) AS stage_name,
            COALESCE(a.art_style, 'Contemporary Art') AS art_style,
            (SELECT COUNT(*) FROM posts WHERE user_id = u.id) AS artwork_count,
            (SELECT COUNT(*) FROM follows WHERE followed_id = u.id) AS followers_count
        FROM users u
        LEFT JOIN artists a ON u.id = a.user_id
        WHERE (SELECT COUNT(*) FROM posts WHERE user_id = u.id) > 0
        ORDER BY followers_count DESC, artwork_count DESC
        LIMIT 4
    ");
    $featuredArtists = $artistStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $statsArtworks = 10;
    $statsArtists = 3;
    $statsUsers = 4;
    $trendingArtworks = [];
    $featuredArtists = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <link rel="icon" type="image/svg+xml" href="assets/images/logo-icon.svg">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>VirtuGallery — Curated Fine Art & Contemporary Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

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
                            accent: '#d4af37',
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
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hero-gradient {
            background: radial-gradient(circle at 50% 20%, rgba(212, 175, 55, 0.08) 0%, rgba(32, 43, 29, 0.04) 50%, rgba(247, 248, 246, 1) 100%);
        }
        .artwork-card-hover {
            transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .artwork-card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px -10px rgba(32, 43, 29, 0.12);
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gallery-50 text-gray-800 font-sans antialiased selection:bg-gallery-800 selection:text-white">

    <!-- Global Toast Notification -->
    <div id="toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 ease-out pointer-events-none flex items-center space-x-3 bg-gallery-900 text-white px-5 py-3.5 rounded-xl shadow-2xl border border-gallery-700/50">
        <div id="toast-icon" class="text-gallery-gold"><i class="fas fa-check-circle text-lg"></i></div>
        <p id="toast-message" class="text-sm font-medium">Notification</p>
    </div>

    <!-- Navigation Header -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 border-b border-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-3 group">
                    <img src="assets/images/logo-icon.svg" alt="VirtuGallery Emblem" class="w-10 h-10 rounded-xl shadow-md group-hover:scale-105 transition-transform">
                    <span class="font-serif text-2xl font-bold tracking-tight text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-600">
                    <a href="#hero" class="hover:text-gallery-800 transition-colors">Home</a>
                    <a href="#gallery" class="hover:text-gallery-800 transition-colors">Gallery</a>
                    <a href="Pages/Marketplace.php" class="hover:text-gallery-800 transition-colors flex items-center gap-1.5">
                        Marketplace <span class="text-[10px] uppercase font-bold tracking-wider bg-gallery-100 text-gallery-800 px-2 py-0.5 rounded-full">Live</span>
                    </a>
                    <a href="#artists" class="hover:text-gallery-800 transition-colors">Artists</a>
                    <a href="#about" class="hover:text-gallery-800 transition-colors">About</a>
                </nav>

                <!-- Actions: Cart + Auth -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Shopping Cart Icon -->
                    <a href="Pages/cart.html" class="relative p-2 text-gray-700 hover:text-gallery-800 transition-colors" title="View Cart">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span id="nav-cart-badge" class="absolute top-0 right-0 w-5 h-5 bg-gallery-800 text-white rounded-full text-xs font-bold flex items-center justify-center scale-0 transition-transform">0</span>
                    </a>

                    <?php if ($isLoggedIn): ?>
                        <div class="hidden sm:flex items-center space-x-3">
                            <a href="Pages/home.php" class="inline-flex items-center space-x-2 text-sm font-semibold text-gallery-800 bg-gallery-100 hover:bg-gallery-200 px-4 py-2.5 rounded-full transition-all">
                                <i class="fas fa-compass text-gallery-gold"></i>
                                <span>Feed & Community</span>
                            </a>
                            <a href="Pages/UserProfilePage.php" class="p-2 text-gray-700 hover:text-gallery-800" title="Profile">
                                <i class="far fa-user-circle text-2xl"></i>
                            </a>
                            <button onclick="handleLogout()" class="text-xs uppercase tracking-wider text-red-600 hover:text-red-700 font-semibold px-2">Logout</button>
                        </div>
                    <?php else: ?>
                        <div class="hidden sm:flex items-center space-x-3">
                            <button onclick="openModal('login')" class="text-sm font-medium text-gray-700 hover:text-gallery-800 px-4 py-2 transition-colors">Sign In</button>
                            <button onclick="openModal('register')" class="text-sm font-semibold text-white bg-gallery-800 hover:bg-gallery-900 px-5 py-2.5 rounded-full shadow-sm hover:shadow transition-all">Join Gallery</button>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile Hamburger Button -->
                    <button id="mobile-menu-toggle" class="md:hidden p-2 text-gray-700 hover:text-gallery-800 focus:outline-none" aria-label="Toggle Navigation">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0"></div>
        <!-- Drawer Panel -->
        <div id="mobile-drawer-panel" class="fixed right-0 top-0 bottom-0 w-5/6 max-w-sm bg-white shadow-2xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out">
            <div>
                <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                    <span class="font-serif text-xl font-bold text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                    <button id="mobile-drawer-close" class="p-2 text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <nav class="mt-6 flex flex-col space-y-4 text-base font-medium text-gray-700">
                    <a href="#hero" class="mobile-nav-link py-2 hover:text-gallery-800">Home</a>
                    <a href="#gallery" class="mobile-nav-link py-2 hover:text-gallery-800">Trending Gallery</a>
                    <a href="Pages/Marketplace.php" class="mobile-nav-link py-2 hover:text-gallery-800 flex items-center justify-between">
                        <span>Marketplace</span>
                        <span class="text-[10px] bg-gallery-100 text-gallery-800 px-2 py-0.5 rounded-full font-bold">LIVE</span>
                    </a>
                    <a href="#artists" class="mobile-nav-link py-2 hover:text-gallery-800">Featured Artists</a>
                    <a href="Pages/cart.html" class="mobile-nav-link py-2 hover:text-gallery-800 flex items-center justify-between">
                        <span>Shopping Cart</span>
                        <span id="mobile-cart-badge" class="text-xs bg-gallery-800 text-white px-2 py-0.5 rounded-full">0</span>
                    </a>
                    <a href="#about" class="mobile-nav-link py-2 hover:text-gallery-800">About Art Collective</a>
                    <?php if ($isLoggedIn): ?>
                        <div class="pt-4 border-t border-gray-100 flex flex-col space-y-3">
                            <a href="Pages/home.php" class="w-full text-center py-2.5 rounded-xl bg-gallery-800 text-white font-medium">Community Feed</a>
                            <a href="Pages/UserProfilePage.php" class="w-full text-center py-2.5 rounded-xl bg-gallery-100 text-gallery-800 font-medium">My Profile</a>
                            <button onclick="handleLogout()" class="w-full py-2 text-center text-red-600 text-sm font-semibold">Sign Out</button>
                        </div>
                    <?php endif; ?>
                </nav>
            </div>
            <?php if (!$isLoggedIn): ?>
                <div class="pt-6 border-t border-gray-100 space-y-3">
                    <button onclick="closeMobileDrawer(); openModal('login')" class="w-full py-3 rounded-xl border border-gray-300 font-medium text-gray-700 hover:bg-gray-50">Sign In</button>
                    <button onclick="closeMobileDrawer(); openModal('register')" class="w-full py-3 rounded-xl bg-gallery-800 text-white font-medium hover:bg-gallery-900 shadow-md">Create Collector Account</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="hero" class="relative pt-32 pb-20 md:pt-40 md:pb-28 hero-gradient overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-gallery-100/80 border border-gallery-200 text-gallery-800 text-xs font-semibold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-gallery-gold animate-pulse"></span>
                        <span>Premier Philippine Digital Art Gallery</span>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold text-gallery-900 leading-[1.15] tracking-tight">
                        Discover Art That Speaks Directly To Your Soul.
                    </h1>

                    <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                        Explore certified authentic original masterpieces, support emerging and acclaimed independent artists, and curate your personalized gallery collection with nationwide delivery.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="Pages/Marketplace.php" class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 bg-gallery-800 hover:bg-gallery-900 text-white px-8 py-4 rounded-full font-medium shadow-lg hover:shadow-xl transition-all duration-300 group">
                            <span>Explore Marketplace</span>
                            <i class="fas fa-arrow-right text-gallery-gold group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <a href="Pages/home.php" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-white hover:bg-gallery-100 text-gallery-900 border border-gallery-200 px-7 py-4 rounded-full font-medium transition-all">
                                <i class="fas fa-layer-group text-gallery-gold"></i>
                                <span>Go to Community Feed</span>
                            </a>
                        <?php else: ?>
                            <button onclick="openModal('register')" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-white hover:bg-gallery-100 text-gallery-900 border border-gallery-200 px-7 py-4 rounded-full font-medium transition-all">
                                <i class="fas fa-sparkles text-gallery-gold"></i>
                                <span>Become a Collector</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Live Metric Counters -->
                    <div class="pt-8 border-t border-gallery-200/60 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0 text-center lg:text-left">
                        <div>
                            <span class="block font-serif text-2xl sm:text-3xl font-bold text-gallery-900"><?= number_format($statsArtworks) ?>+</span>
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Curated Works</span>
                        </div>
                        <div>
                            <span class="block font-serif text-2xl sm:text-3xl font-bold text-gallery-900"><?= number_format($statsArtists) ?></span>
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Verified Artists</span>
                        </div>
                        <div>
                            <span class="block font-serif text-2xl sm:text-3xl font-bold text-gallery-900"><?= number_format($statsUsers) ?></span>
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Art Enthusiasts</span>
                        </div>
                    </div>
                </div>

                <!-- Right Artwork Mosaic / Carousel Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-sm sm:max-w-md lg:max-w-none">
                        <!-- Ambient glow -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-gallery-gold/20 via-gallery-500/10 to-transparent rounded-3xl blur-2xl"></div>

                        <!-- Main Featured Piece -->
                        <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl border border-gray-100 p-3 sm:p-4 transition-transform hover:scale-[1.01] duration-500">
                            <div class="relative aspect-[4/5] rounded-xl overflow-hidden bg-gray-100">
                                <img id="hero-showcase-img" 
                                     src="<?= !empty($trendingArtworks[0]['image_path']) ? htmlspecialchars($trendingArtworks[0]['image_path']) : 'uploads/artwork_1_683f1187c6f7e.jpg' ?>" 
                                     alt="Featured masterpiece" 
                                     class="w-full h-full object-cover transition-opacity duration-500" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex flex-col justify-end p-5 text-white">
                                    <span class="text-xs uppercase tracking-widest text-gallery-gold font-semibold">Spotlight Collection</span>
                                    <h3 id="hero-showcase-title" class="font-serif text-xl sm:text-2xl font-bold text-white"><?= !empty($trendingArtworks[0]['title']) ? htmlspecialchars($trendingArtworks[0]['title']) : 'Monalisa Reimagined' ?></h3>
                                    <p id="hero-showcase-artist" class="text-sm text-gray-200">by <?= !empty($trendingArtworks[0]['first_name']) ? htmlspecialchars($trendingArtworks[0]['first_name'] . ' ' . $trendingArtworks[0]['last_name']) : 'Cedrick Albuero' ?></p>
                                </div>
                            </div>
                            
                            <!-- Thumbnail Switchers -->
                            <div class="flex items-center space-x-2 pt-3 overflow-x-auto hide-scrollbar">
                                <?php foreach (array_slice($trendingArtworks, 0, 5) as $idx => $art): ?>
                                    <button onclick="switchHeroPiece('<?= htmlspecialchars($art['image_path']) ?>', '<?= htmlspecialchars(addslashes($art['title'])) ?>', '<?= htmlspecialchars(addslashes($art['first_name'] . ' ' . $art['last_name'])) ?>', this)" 
                                            class="hero-thumb flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border-2 <?= $idx === 0 ? 'border-gallery-gold scale-105' : 'border-transparent opacity-70 hover:opacity-100' ?> transition-all">
                                        <img src="<?= htmlspecialchars($art['image_path']) ?>" alt="thumbnail" class="w-full h-full object-cover" />
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Trending Masterpieces (Dynamic Live Gallery) -->
    <section id="gallery" class="py-20 md:py-28 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-gallery-gold">Authentic Works</span>
                    <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-gallery-900 mt-2">Trending Masterpieces</h2>
                    <p class="text-gray-500 mt-2 text-sm sm:text-base font-light">Explore original artworks collected and appreciated by our vibrant community.</p>
                </div>

                <!-- Category Filtering Controls -->
                <div class="flex items-center space-x-2 overflow-x-auto hide-scrollbar pb-2">
                    <button onclick="filterGallery('all', this)" class="gallery-filter-btn active px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-gallery-800 text-white shadow-sm transition-all">All</button>
                    <button onclick="filterGallery('paintings', this)" class="gallery-filter-btn px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-gallery-100 text-gray-700 hover:bg-gallery-200 transition-all">Paintings</button>
                    <button onclick="filterGallery('sculpture', this)" class="gallery-filter-btn px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-gallery-100 text-gray-700 hover:bg-gallery-200 transition-all">Sculptures</button>
                    <button onclick="filterGallery('photography', this)" class="gallery-filter-btn px-4 py-2 rounded-full text-xs sm:text-sm font-medium bg-gallery-100 text-gray-700 hover:bg-gallery-200 transition-all">Photography</button>
                </div>
            </div>

            <!-- Dynamic Artworks Grid -->
            <div id="artworks-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                <?php if (empty($trendingArtworks)): ?>
                    <div class="col-span-full py-16 text-center text-gray-400">
                        <i class="fas fa-palette text-5xl mb-4"></i>
                        <p class="text-lg font-serif">No artworks published yet. Be the first to upload!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($trendingArtworks as $art): ?>
                        <div class="gallery-card artwork-card-hover bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col justify-between"
                             data-category="<?= htmlspecialchars($art['category']) ?>">
                            
                            <!-- Image Container -->
                            <div class="relative aspect-square overflow-hidden bg-gray-50 group">
                                <img src="<?= htmlspecialchars($art['image_path']) ?>" 
                                     alt="<?= htmlspecialchars($art['title']) ?>" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-gallery-800 text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                                    <?= htmlspecialchars($art['category']) ?>
                                </span>

                                <!-- Quick Actions Overlay -->
                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-3">
                                    <button onclick="quickViewArtwork(<?= htmlspecialchars(json_encode($art)) ?>)" 
                                            class="w-10 h-10 rounded-full bg-white text-gray-800 flex items-center justify-center hover:bg-gallery-gold hover:text-white transition-colors shadow-lg"
                                            title="Quick View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Card Details -->
                            <div class="p-5 flex flex-col flex-grow justify-between">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-serif text-lg font-bold text-gallery-900 truncate" title="<?= htmlspecialchars($art['title']) ?>">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </h3>
                                        <div class="flex items-center space-x-1 text-xs text-rose-500 font-semibold">
                                            <i class="fas fa-heart"></i>
                                            <span><?= $art['likes_count'] ?></span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">
                                        by <span class="text-gray-800"><?= htmlspecialchars($art['first_name'] . ' ' . $art['last_name']) ?></span>
                                    </p>
                                </div>

                                <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider block">Price</span>
                                        <span class="text-base font-bold text-gallery-800">
                                            <?= !empty($art['price']) && $art['price'] > 0 ? '₱' . number_format($art['price'], 2) : 'Not for sale' ?>
                                        </span>
                                    </div>

                                    <?php if (!empty($art['price']) && $art['price'] > 0): ?>
                                        <button onclick="addToCart({
                                                    id: <?= (int)$art['id'] ?>,
                                                    title: '<?= htmlspecialchars(addslashes($art['title'])) ?>',
                                                    artist: '<?= htmlspecialchars(addslashes($art['first_name'] . ' ' . $art['last_name'])) ?>',
                                                    price: <?= (float)$art['price'] ?>,
                                                    image: '<?= htmlspecialchars(addslashes($art['image_path'])) ?>'
                                                })" 
                                                class="inline-flex items-center space-x-1.5 text-xs font-semibold bg-gallery-100 hover:bg-gallery-800 text-gallery-800 hover:text-white px-3.5 py-2 rounded-xl transition-all">
                                            <i class="fas fa-cart-plus"></i>
                                            <span>Add</span>
                                        </button>
                                    <?php else: ?>
                                        <a href="Pages/home.php" class="text-xs font-semibold text-gray-500 hover:text-gallery-800 px-3 py-1.5">View</a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Explore More CTA -->
            <div class="mt-14 text-center">
                <a href="Pages/Marketplace.php" class="inline-flex items-center space-x-2 text-sm font-semibold text-gallery-800 hover:text-gallery-900 border-2 border-gallery-800 hover:bg-gallery-800 hover:text-white px-8 py-3.5 rounded-full transition-all duration-300">
                    <span>View Complete Art Collection</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- Featured Artists Showcase -->
    <section id="artists" class="py-20 bg-gallery-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-gallery-gold">Creative Minds</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-gallery-900 mt-2">Featured Independent Artists</h2>
                <p class="text-gray-500 mt-2 text-sm sm:text-base font-light">Meet the visionary creators contributing original artwork to our contemporary gallery.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($featuredArtists as $artist): ?>
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-center flex flex-col justify-between">
                        <div>
                            <div class="relative w-20 h-20 mx-auto rounded-full overflow-hidden ring-4 ring-gallery-100 mb-4 bg-gray-100">
                                <img src="<?= !empty($artist['profile_picture']) ? htmlspecialchars($artist['profile_picture']) : 'Pages/profile_image/default.jpg' ?>" 
                                     alt="<?= htmlspecialchars($artist['first_name']) ?>" 
                                     class="w-full h-full object-cover" />
                            </div>
                            <h3 class="font-serif text-lg font-bold text-gallery-900"><?= htmlspecialchars($artist['stage_name']) ?></h3>
                            <p class="text-xs text-gray-500">@<?= htmlspecialchars($artist['username']) ?></p>
                            <span class="inline-block mt-2 text-[11px] font-medium text-gallery-800 bg-gallery-100 px-3 py-1 rounded-full">
                                <?= htmlspecialchars($artist['art_style']) ?>
                            </span>
                        </div>

                        <div class="pt-5 mt-5 border-t border-gray-100 flex items-center justify-around text-xs text-gray-600">
                            <div>
                                <span class="font-bold text-gallery-900 block"><?= $artist['artwork_count'] ?></span>
                                <span class="text-[10px] uppercase text-gray-400">Works</span>
                            </div>
                            <div class="w-px h-6 bg-gray-100"></div>
                            <div>
                                <span class="font-bold text-gallery-900 block"><?= $artist['followers_count'] ?></span>
                                <span class="text-[10px] uppercase text-gray-400">Followers</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why VirtuGallery Features -->
    <section id="about" class="py-20 bg-gallery-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-widest text-gallery-gold">Our Philosophy</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white mt-2">Art Collecting, Redefined</h2>
                <p class="text-gray-400 mt-2 text-sm sm:text-base font-light">Connecting art lovers and creators through a seamless, transparent platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gallery-800/60 p-8 rounded-2xl border border-gallery-700/50">
                    <div class="w-12 h-12 rounded-xl bg-gallery-700/60 text-gallery-gold flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">Authenticity Certified</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light">
                        Every piece on VirtuGallery is directly published and shipped by verified artists, with detailed provenance and digital ownership logs.
                    </p>
                </div>

                <div class="bg-gallery-800/60 p-8 rounded-2xl border border-gallery-700/50">
                    <div class="w-12 h-12 rounded-xl bg-gallery-700/60 text-gallery-gold flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">Protective Fine Art Delivery</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light">
                        Nationwide museum-grade packaging ensures paintings, sculptures, and framed prints reach your doorstep in immaculate pristine condition.
                    </p>
                </div>

                <div class="bg-gallery-800/60 p-8 rounded-2xl border border-gallery-700/50">
                    <div class="w-12 h-12 rounded-xl bg-gallery-700/60 text-gallery-gold flex items-center justify-center text-xl mb-6">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3 class="font-serif text-xl font-bold mb-3">Direct Artist Support</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light">
                        Our platform empowers creators by giving independent talent fair compensation, transparent pricing, and direct patron engagement.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gallery-900 border-t border-gallery-800 py-12 text-gray-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-full bg-gallery-gold text-gallery-900 flex items-center justify-center font-serif font-bold">V</span>
                <span class="font-serif text-lg font-bold text-white tracking-tight">VirtuGallery</span>
            </div>
            <div class="flex items-center space-x-6 text-xs uppercase tracking-wider text-gray-500">
                <a href="#hero" class="hover:text-white transition-colors">Home</a>
                <a href="Pages/Marketplace.php" class="hover:text-white transition-colors">Marketplace</a>
                <a href="Pages/ranking.php" class="hover:text-white transition-colors">Rankings</a>
                <a href="shanaya/index.php" class="hover:text-white transition-colors">Curator CMS</a>
            </div>
            <p class="text-xs text-gray-500">© <?= date('Y') ?> VirtuGallery Art Collective. All rights reserved.</p>
        </div>
    </footer>

    <!-- Quick View Artwork Modal -->
    <div id="quick-view-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeQuickView()"></div>
        <div class="fixed inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-3xl sm:w-full bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]">
            <div class="w-full md:w-1/2 bg-gray-100 relative aspect-square md:aspect-auto">
                <img id="qv-image" src="" alt="Artwork" class="w-full h-full object-cover" />
            </div>
            <div class="w-full md:w-1/2 p-6 sm:p-8 flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span id="qv-category" class="text-xs font-bold uppercase tracking-wider text-gallery-gold"></span>
                        <button onclick="closeQuickView()" class="text-gray-400 hover:text-gray-600 p-1"><i class="fas fa-times text-lg"></i></button>
                    </div>
                    <h3 id="qv-title" class="font-serif text-2xl font-bold text-gallery-900"></h3>
                    <p id="qv-artist" class="text-sm text-gray-500 mt-1"></p>
                    <p id="qv-description" class="text-gray-600 text-sm mt-4 leading-relaxed font-light"></p>
                </div>
                <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase text-gray-400 font-semibold block">Investment</span>
                        <span id="qv-price" class="text-xl font-bold text-gallery-900"></span>
                    </div>
                    <button id="qv-cart-btn" class="bg-gallery-800 hover:bg-gallery-900 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Auth Modal (Login / Sign Up) -->
    <div id="auth-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="fixed inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-md sm:w-full bg-white rounded-2xl shadow-2xl p-6 sm:p-8 max-h-[95vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                    <span class="w-7 h-7 rounded-full bg-gallery-800 text-gallery-gold flex items-center justify-center font-serif text-xs font-bold">V</span>
                    <span class="font-serif text-lg font-bold text-gallery-900">VirtuGallery</span>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>

            <!-- Login View -->
            <div id="modal-login-view" class="pt-6">
                <h3 class="font-serif text-2xl font-bold text-gallery-900">Welcome Back</h3>
                <p class="text-xs text-gray-500 mt-1">Sign in to manage your collection and favorites.</p>

                <form id="form-login" onsubmit="submitLogin(event)" class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email or Username</label>
                        <input type="text" id="login-identifier" required placeholder="ertnac or james@example.com" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-gallery-800 focus:outline-none transition-colors" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" id="login-password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-gallery-800 focus:outline-none transition-colors" />
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-gallery-800 hover:bg-gallery-900 text-white rounded-xl text-sm font-semibold shadow-md transition-all">Sign In</button>
                </form>

                <p class="text-center text-xs text-gray-500 mt-6">
                    Don't have an account? 
                    <button onclick="switchModalView('register')" class="text-gallery-gold font-bold hover:underline">Create one</button>
                </p>
            </div>

            <!-- Register View -->
            <div id="modal-register-view" class="pt-6 hidden">
                <h3 class="font-serif text-2xl font-bold text-gallery-900">Join VirtuGallery</h3>
                <p class="text-xs text-gray-500 mt-1">Discover, collect, and connect with living artists.</p>

                <form id="form-register" onsubmit="submitRegister(event)" class="mt-5 space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="reg-fname" required placeholder="First Name" class="px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                        <input type="text" id="reg-lname" required placeholder="Last Name" class="px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    </div>
                    <input type="text" id="reg-username" placeholder="Username (optional)" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    <input type="email" id="reg-email" required placeholder="Email Address" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="reg-municipality" required placeholder="Municipality (e.g. Alaminos)" class="px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                        <input type="text" id="reg-province" required placeholder="Province (e.g. Laguna)" class="px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    </div>
                    <input type="password" id="reg-password" required placeholder="Password (Min 8 chars, 1 upper, 1 number)" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    <input type="password" id="reg-confirm-password" required placeholder="Confirm Password" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs focus:border-gallery-800 focus:outline-none" />
                    
                    <button type="submit" class="w-full py-3.5 bg-gallery-800 hover:bg-gallery-900 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all mt-2">Complete Registration</button>
                </form>

                <p class="text-center text-xs text-gray-500 mt-4">
                    Already registered? 
                    <button onclick="switchModalView('login')" class="text-gallery-gold font-bold hover:underline">Sign In</button>
                </p>
            </div>

        </div>
    </div>

    <!-- Core Interactive Client Scripts -->
    <script>
        // Shared Cart Helpers
        function getCart() {
            try {
                return JSON.parse(localStorage.getItem('virtugallery_cart')) || [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem('virtugallery_cart', JSON.stringify(cart));
            updateCartBadges();
        }

        function updateCartBadges() {
            const cart = getCart();
            const totalCount = cart.length;
            const badge = document.getElementById('nav-cart-badge');
            const mobileBadge = document.getElementById('mobile-cart-badge');
            
            if (badge) {
                badge.innerText = totalCount;
                badge.classList.toggle('scale-0', totalCount === 0);
            }
            if (mobileBadge) {
                mobileBadge.innerText = totalCount;
            }
        }

        function addToCart(item) {
            const cart = getCart();
            const exists = cart.some(i => i.id === item.id);
            if (exists) {
                showToast(`"${item.title}" is already in your cart.`, 'info');
                return;
            }
            cart.push(item);
            saveCart(cart);
            showToast(`Added "${item.title}" to cart!`);
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            toastMsg.innerText = message;
            toastIcon.innerHTML = type === 'success' 
                ? '<i class="fas fa-check-circle text-lg text-emerald-400"></i>'
                : '<i class="fas fa-info-circle text-lg text-amber-400"></i>';

            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3000);
        }

        // Hero Showcase Switcher
        function switchHeroPiece(imgSrc, title, artist, btn) {
            const img = document.getElementById('hero-showcase-img');
            const titleEl = document.getElementById('hero-showcase-title');
            const artistEl = document.getElementById('hero-showcase-artist');

            img.style.opacity = '0';
            setTimeout(() => {
                img.src = imgSrc;
                titleEl.innerText = title;
                artistEl.innerText = `by ${artist}`;
                img.style.opacity = '1';
            }, 200);

            document.querySelectorAll('.hero-thumb').forEach(b => {
                b.classList.remove('border-gallery-gold', 'scale-105');
                b.classList.add('border-transparent', 'opacity-70');
            });
            btn.classList.add('border-gallery-gold', 'scale-105');
            btn.classList.remove('border-transparent', 'opacity-70');
        }

        // Category Filter
        function filterGallery(category, btn) {
            document.querySelectorAll('.gallery-filter-btn').forEach(b => {
                b.classList.remove('bg-gallery-800', 'text-white');
                b.classList.add('bg-gallery-100', 'text-gray-700');
            });
            btn.classList.add('bg-gallery-800', 'text-white');
            btn.classList.remove('bg-gallery-100', 'text-gray-700');

            const cards = document.querySelectorAll('.gallery-card');
            cards.forEach(card => {
                const cardCat = card.getAttribute('data-category');
                if (category === 'all' || cardCat === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Quick View Modal
        function quickViewArtwork(art) {
            document.getElementById('qv-image').src = art.image_path;
            document.getElementById('qv-title').innerText = art.title;
            document.getElementById('qv-artist').innerText = `by ${art.first_name} ${art.last_name}`;
            document.getElementById('qv-category').innerText = art.category;
            document.getElementById('qv-description').innerText = art.description || 'Authentic original artwork from the artist collection.';
            document.getElementById('qv-price').innerText = art.price > 0 ? `₱${parseFloat(art.price).toLocaleString('en-US', {minimumFractionDigits: 2})}` : 'Not for sale';
            
            const cartBtn = document.getElementById('qv-cart-btn');
            if (art.price > 0) {
                cartBtn.style.display = 'block';
                cartBtn.onclick = function() {
                    addToCart({
                        id: art.id,
                        title: art.title,
                        artist: `${art.first_name} ${art.last_name}`,
                        price: parseFloat(art.price),
                        image: art.image_path
                    });
                    closeQuickView();
                };
            } else {
                cartBtn.style.display = 'none';
            }

            document.getElementById('quick-view-modal').classList.remove('hidden');
        }

        function closeQuickView() {
            document.getElementById('quick-view-modal').classList.add('hidden');
        }

        // Auth Modal Controls
        function openModal(view = 'login') {
            document.getElementById('auth-modal').classList.remove('hidden');
            switchModalView(view);
        }

        function closeModal() {
            document.getElementById('auth-modal').classList.add('hidden');
        }

        function switchModalView(view) {
            const loginView = document.getElementById('modal-login-view');
            const regView = document.getElementById('modal-register-view');
            if (view === 'login') {
                loginView.classList.remove('hidden');
                regView.classList.add('hidden');
            } else {
                loginView.classList.add('hidden');
                regView.classList.remove('hidden');
            }
        }

        // Login Handler
        function submitLogin(e) {
            e.preventDefault();
            const identifier = document.getElementById('login-identifier').value.trim();
            const password = document.getElementById('login-password').value;

            fetch('login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email_or_username: identifier, password: password })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Welcome back! Redirecting...');
                    setTimeout(() => {
                        window.location.href = 'Pages/home.php';
                    }, 800);
                } else {
                    alert((data.errors || ['Invalid credentials']).join('\n'));
                }
            })
            .catch(() => alert('Network error during login. Please try again.'));
        }

        // Register Handler
        function submitRegister(e) {
            e.preventDefault();
            const payload = {
                first_name: document.getElementById('reg-fname').value.trim(),
                last_name: document.getElementById('reg-lname').value.trim(),
                username: document.getElementById('reg-username').value.trim(),
                email: document.getElementById('reg-email').value.trim(),
                municipality: document.getElementById('reg-municipality').value.trim(),
                province: document.getElementById('reg-province').value.trim(),
                password: document.getElementById('reg-password').value,
                confirm_password: document.getElementById('reg-confirm-password').value
            };

            fetch('register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('Account created successfully!');
                    setTimeout(() => {
                        window.location.href = 'Pages/home.php';
                    }, 800);
                } else {
                    alert((data.errors || ['Registration failed']).join('\n'));
                }
            })
            .catch(() => alert('Network error during registration.'));
        }

        function handleLogout() {
            fetch('logout.php')
                .then(res => res.json())
                .then(() => {
                    window.location.href = 'index.php';
                })
                .catch(() => {
                    window.location.href = 'index.php';
                });
        }

        // Mobile Drawer
        const drawer = document.getElementById('mobile-drawer');
        const backdrop = document.getElementById('mobile-drawer-backdrop');
        const panel = document.getElementById('mobile-drawer-panel');

        function openMobileDrawer() {
            drawer.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('translate-x-full');
            }, 10);
        }

        function closeMobileDrawer() {
            backdrop.classList.add('opacity-0');
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }

        document.getElementById('mobile-menu-toggle')?.addEventListener('click', openMobileDrawer);
        document.getElementById('mobile-drawer-close')?.addEventListener('click', closeMobileDrawer);
        backdrop?.addEventListener('click', closeMobileDrawer);

        // Header Scroll Shadow
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (window.scrollY > 40) {
                header.classList.add('backdrop-glass', 'shadow-sm', 'border-gray-200/50');
            } else {
                header.classList.remove('backdrop-glass', 'shadow-sm', 'border-gray-200/50');
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadges();
        });
    </script>
</body>

</html>
