<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

$isLoggedIn = isset($_SESSION['user_id']);
$userEmail = $_SESSION['email'] ?? '';
$currentUserId = $_SESSION['user_id'] ?? null;

// Query all marketplace products
try {
    $stmt = $pdo->query("
        SELECT 
            p.id,
            p.title,
            p.description,
            p.category,
            p.price,
            p.tags,
            p.image_path,
            p.created_at,
            u.id AS artist_id,
            u.username,
            u.first_name,
            u.last_name,
            u.municipality,
            u.province,
            u.profile_picture,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        WHERE p.post_type = 'product' AND p.price IS NOT NULL
        ORDER BY p.created_at DESC
    ");
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $artworks = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/svg+xml" href="../assets/images/logo-icon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>VirtuGallery — Curated Fine Art Marketplace</title>
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
        .artwork-card {
            transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .artwork-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(32, 43, 29, 0.1);
        }
    </style>
</head>

<body class="bg-gallery-50 text-gray-800 font-sans antialiased selection:bg-gallery-800 selection:text-white min-h-screen flex flex-col justify-between">

    <!-- Global Toast -->
    <div id="toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none flex items-center space-x-3 bg-gallery-900 text-white px-5 py-3.5 rounded-xl shadow-2xl border border-gallery-700/50">
        <div id="toast-icon" class="text-gallery-gold"><i class="fas fa-check-circle text-lg"></i></div>
        <p id="toast-message" class="text-sm font-medium">Notification</p>
    </div>

    <!-- Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 z-40 backdrop-glass border-b border-gray-200/60 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="../index.php" class="flex items-center space-x-3 group">
                    <img src="../assets/images/logo-icon.svg" alt="VirtuGallery Emblem" class="w-10 h-10 rounded-xl shadow-md">
                    <span class="font-serif text-2xl font-bold tracking-tight text-gallery-900">Virtu<span class="text-gallery-gold font-light italic">Gallery</span></span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium text-gray-600">
                    <a href="../index.php" class="hover:text-gallery-800 transition-colors">Home</a>
                    <a href="Marketplace.php" class="text-gallery-800 font-semibold border-b-2 border-gallery-800 pb-1">Marketplace</a>
                    <a href="ranking.php" class="hover:text-gallery-800 transition-colors">Ranking</a>
                    <?php if ($isLoggedIn): ?>
                        <a href="home.php" class="hover:text-gallery-800 transition-colors">Community Feed</a>
                    <?php endif; ?>
                    <a href="../index.php#about" class="hover:text-gallery-800 transition-colors">About</a>
                </div>

                <!-- Right Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Cart -->
                    <a href="cart.html" class="relative p-2 text-gray-700 hover:text-gallery-800" title="Shopping Cart">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span id="nav-cart-badge" class="absolute top-0 right-0 w-5 h-5 bg-gallery-800 text-white rounded-full text-xs font-bold flex items-center justify-center scale-0 transition-transform">0</span>
                    </a>

                    <?php if ($isLoggedIn): ?>
                        <a href="UserProfilePage.php" class="hidden sm:inline-flex items-center space-x-2 text-sm font-medium text-gallery-800 bg-gallery-100 hover:bg-gallery-200 px-4 py-2 rounded-full transition-colors">
                            <i class="far fa-user"></i>
                            <span>Profile</span>
                        </a>
                        <a href="../logout.php" class="text-xs uppercase tracking-wider font-semibold text-red-600 hover:text-red-700">Logout</a>
                    <?php else: ?>
                        <a href="../index.php" class="text-sm font-medium text-gray-700 hover:text-gallery-800">Sign In</a>
                    <?php endif; ?>

                    <button id="mobile-toggle" class="md:hidden p-2 text-gray-700 hover:text-gallery-800">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Drawer -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 hidden">
        <div id="mobile-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="fixed right-0 top-0 bottom-0 w-3/4 max-w-xs bg-white p-6 shadow-2xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <span class="font-serif text-xl font-bold">Menu</span>
                    <button id="mobile-close" class="p-1 text-gray-400"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="mt-6 flex flex-col space-y-4 text-sm font-medium">
                    <a href="../index.php" class="py-2">Home</a>
                    <a href="Marketplace.php" class="py-2 text-gallery-800 font-bold">Marketplace</a>
                    <a href="ranking.php" class="py-2">Ranking</a>
                    <a href="cart.html" class="py-2 flex items-center justify-between">
                        <span>Cart</span>
                        <span id="mobile-cart-count" class="bg-gallery-800 text-white text-xs px-2 py-0.5 rounded-full">0</span>
                    </a>
                    <?php if ($isLoggedIn): ?>
                        <a href="home.php" class="py-2">Community Feed</a>
                        <a href="UserProfilePage.php" class="py-2">My Profile</a>
                        <a href="../logout.php" class="py-2 text-red-600">Sign Out</a>
                    <?php else: ?>
                        <a href="../index.php" class="py-2 text-gallery-800 font-bold">Sign In / Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="pt-28 pb-20 flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        
        <!-- Marketplace Banner & Search -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="text-xs uppercase tracking-widest text-gallery-gold font-bold">Curated Catalog</span>
                <h1 class="font-serif text-3xl sm:text-4xl font-bold text-gallery-900 mt-1">Art Marketplace</h1>
                <p class="text-gray-500 text-sm sm:text-base mt-2 font-light">
                    Direct from certified studios in Laguna and contemporary creators nationwide. Certified original works ready for safe home delivery.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="w-full lg:w-96">
                <div class="relative">
                    <input type="text" id="search-input" oninput="applyFilters()" placeholder="Search title, artist, or tags..." 
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gallery-50 border border-gray-200 text-sm focus:border-gallery-800 focus:bg-white focus:outline-none transition-all shadow-inner" />
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Filter Bar & Sort Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            
            <!-- Category Filter Buttons -->
            <div class="flex items-center space-x-2 overflow-x-auto pb-2 sm:pb-0" id="category-filters">
                <button onclick="setCategory('all', this)" class="cat-pill active px-4 py-2 rounded-full text-xs font-semibold bg-gallery-800 text-white shadow-sm transition-all flex-shrink-0">All Works</button>
                <button onclick="setCategory('paintings', this)" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all flex-shrink-0">Paintings</button>
                <button onclick="setCategory('sculpture', this)" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all flex-shrink-0">Sculptures</button>
                <button onclick="setCategory('photography', this)" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all flex-shrink-0">Photography</button>
                <button onclick="setCategory('pottery', this)" class="cat-pill px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all flex-shrink-0">Pottery</button>
            </div>

            <!-- Sort By Dropdown -->
            <div class="flex items-center space-x-2 text-xs font-medium text-gray-500 self-end sm:self-auto">
                <span>Sort by:</span>
                <select id="sort-select" onchange="applyFilters()" class="bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-800 focus:outline-none focus:border-gallery-800">
                    <option value="newest">Newest Additions</option>
                    <option value="popular">Most Liked</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Product Artworks Grid -->
        <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            <?php foreach ($artworks as $art): ?>
                <div class="art-item artwork-card bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col justify-between"
                     data-id="<?= (int)$art['id'] ?>"
                     data-category="<?= htmlspecialchars($art['category']) ?>"
                     data-title="<?= htmlspecialchars(strtolower($art['title'])) ?>"
                     data-artist="<?= htmlspecialchars(strtolower($art['first_name'] . ' ' . $art['last_name'])) ?>"
                     data-tags="<?= htmlspecialchars(strtolower($art['tags'] ?? '')) ?>"
                     data-price="<?= (float)$art['price'] ?>"
                     data-likes="<?= (int)$art['likes_count'] ?>">

                    <!-- Artwork Thumbnail -->
                    <div class="relative aspect-square overflow-hidden bg-gray-50 group">
                        <img src="../<?= htmlspecialchars($art['image_path']) ?>" 
                             alt="<?= htmlspecialchars($art['title']) ?>" 
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-gallery-800 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                            <?= htmlspecialchars($art['category']) ?>
                        </span>

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-3">
                            <button onclick="openModalQuickView(<?= htmlspecialchars(json_encode($art)) ?>)" 
                                    class="w-10 h-10 rounded-full bg-white text-gray-800 flex items-center justify-center hover:bg-gallery-gold hover:text-white transition-colors shadow-lg"
                                    title="View Details">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Details -->
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
                            <p class="text-xs text-gray-500 mt-1">
                                by <span class="font-medium text-gray-800"><?= htmlspecialchars($art['first_name'] . ' ' . $art['last_name']) ?></span>
                            </p>
                            <p class="text-[11px] text-gray-400 mt-0.5"><i class="fas fa-map-marker-alt text-[10px] mr-1"></i><?= htmlspecialchars($art['municipality'] . ', ' . $art['province']) ?></p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider block">Price</span>
                                <span class="text-base font-bold text-gallery-900">₱<?= number_format($art['price'], 2) ?></span>
                            </div>

                            <button onclick="addToCart({
                                        id: <?= (int)$art['id'] ?>,
                                        title: '<?= htmlspecialchars(addslashes($art['title'])) ?>',
                                        artist: '<?= htmlspecialchars(addslashes($art['first_name'] . ' ' . $art['last_name'])) ?>',
                                        price: <?= (float)$art['price'] ?>,
                                        image: '<?= htmlspecialchars(addslashes($art['image_path'])) ?>'
                                    })" 
                                    class="inline-flex items-center space-x-1.5 text-xs font-semibold bg-gallery-800 hover:bg-gallery-900 text-white px-4 py-2.5 rounded-xl shadow-sm transition-all">
                                <i class="fas fa-cart-plus text-xs text-gallery-gold"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="no-results" class="hidden py-24 text-center">
            <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
            <h3 class="font-serif text-xl font-bold text-gray-700">No matching artworks found</h3>
            <p class="text-xs text-gray-400 mt-1">Try adjusting your search criteria or category filter.</p>
        </div>

    </main>

    <!-- Quick View Details Modal -->
    <div id="detail-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="fixed inset-4 sm:inset-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:max-w-3xl sm:w-full bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]">
            <div class="w-full md:w-1/2 bg-gray-100 relative aspect-square md:aspect-auto">
                <img id="modal-img" src="" alt="Artwork" class="w-full h-full object-cover" />
            </div>
            <div class="w-full md:w-1/2 p-6 sm:p-8 flex flex-col justify-between overflow-y-auto">
                <div>
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <span id="modal-category" class="text-xs font-bold uppercase tracking-wider text-gallery-gold"></span>
                        <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-lg"></i></button>
                    </div>
                    <h3 id="modal-title" class="font-serif text-2xl sm:text-3xl font-bold text-gallery-900 mt-3"></h3>
                    <p id="modal-artist" class="text-sm text-gray-500 mt-1 font-medium"></p>
                    <p id="modal-location" class="text-xs text-gray-400 mt-0.5"><i class="fas fa-map-marker-alt text-[10px] mr-1"></i></p>
                    
                    <p id="modal-desc" class="text-gray-600 text-sm mt-5 leading-relaxed font-light"></p>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 block mb-1">Authenticity & Delivery:</span>
                        <p class="text-xs text-gray-400 leading-normal">Includes Signed Certificate of Authenticity. Handled with museum packaging and insured local courier.</p>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] uppercase text-gray-400 font-bold block">Investment</span>
                        <span id="modal-price" class="text-2xl font-bold text-gallery-900"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="modal-cart-btn" class="bg-gallery-800 hover:bg-gallery-900 text-white px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                            Add to Cart
                        </button>
                        <button id="modal-buy-btn" class="bg-gallery-gold hover:bg-amber-600 text-white px-5 py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                            Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-8 text-center text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="font-serif font-bold text-gallery-900">VirtuGallery Marketplace</span>
            <span>All artworks direct from independent living artists.</span>
            <span>© <?= date('Y') ?> VirtuGallery</span>
        </div>
    </footer>

    <script>
        // Shared Cart
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
            const total = cart.length;
            const badge = document.getElementById('nav-cart-badge');
            const mBadge = document.getElementById('mobile-cart-count');
            if (badge) {
                badge.innerText = total;
                badge.classList.toggle('scale-0', total === 0);
            }
            if (mBadge) mBadge.innerText = total;
        }

        function addToCart(item) {
            const cart = getCart();
            if (cart.some(i => i.id === item.id)) {
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

        // Filtering and Sorting
        let currentCategory = 'all';

        function setCategory(cat, btn) {
            currentCategory = cat;
            document.querySelectorAll('.cat-pill').forEach(b => {
                b.classList.remove('bg-gallery-800', 'text-white');
                b.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
            });
            btn.classList.add('bg-gallery-800', 'text-white');
            btn.classList.remove('bg-white', 'text-gray-600', 'border');
            applyFilters();
        }

        function applyFilters() {
            const search = document.getElementById('search-input').value.toLowerCase().trim();
            const sortBy = document.getElementById('sort-select').value;
            const items = Array.from(document.querySelectorAll('.art-item'));
            const container = document.getElementById('products-grid');
            let visibleCount = 0;

            items.forEach(item => {
                const title = item.dataset.title;
                const artist = item.dataset.artist;
                const tags = item.dataset.tags;
                const category = item.dataset.category;

                const matchCat = currentCategory === 'all' || category === currentCategory;
                const matchSearch = !search || title.includes(search) || artist.includes(search) || tags.includes(search);

                if (matchCat && matchSearch) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Sort visible items
            const visibleItems = items.filter(i => i.style.display !== 'none');
            visibleItems.sort((a, b) => {
                if (sortBy === 'price-asc') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                if (sortBy === 'price-desc') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                if (sortBy === 'popular') return parseInt(b.dataset.likes) - parseInt(a.dataset.likes);
                return parseInt(b.dataset.id) - parseInt(a.dataset.id);
            });

            visibleItems.forEach(item => container.appendChild(item));

            document.getElementById('no-results').classList.toggle('hidden', visibleCount > 0);
        }

        // Quick View Detail Modal
        function openModalQuickView(art) {
            document.getElementById('modal-img').src = '../' + art.image_path;
            document.getElementById('modal-title').innerText = art.title;
            document.getElementById('modal-artist').innerText = `by ${art.first_name} ${art.last_name}`;
            document.getElementById('modal-location').innerText = `${art.municipality}, ${art.province}`;
            document.getElementById('modal-category').innerText = art.category;
            document.getElementById('modal-desc').innerText = art.description || 'Authentic original masterpiece crafted with fine materials.';
            document.getElementById('modal-price').innerText = `₱${parseFloat(art.price).toLocaleString('en-US', {minimumFractionDigits: 2})}`;

            document.getElementById('modal-cart-btn').onclick = function() {
                addToCart({
                    id: art.id,
                    title: art.title,
                    artist: `${art.first_name} ${art.last_name}`,
                    price: parseFloat(art.price),
                    image: art.image_path
                });
                closeDetailModal();
            };

            document.getElementById('modal-buy-btn').onclick = function() {
                addToCart({
                    id: art.id,
                    title: art.title,
                    artist: `${art.first_name} ${art.last_name}`,
                    price: parseFloat(art.price),
                    image: art.image_path
                });
                window.location.href = 'Checkouts.html';
            };

            document.getElementById('detail-modal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }

        // Mobile drawer
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileDrawer = document.getElementById('mobile-drawer');
        const mobileBackdrop = document.getElementById('mobile-backdrop');
        const mobileClose = document.getElementById('mobile-close');

        mobileToggle?.addEventListener('click', () => mobileDrawer.classList.remove('hidden'));
        mobileBackdrop?.addEventListener('click', () => mobileDrawer.classList.add('hidden'));
        mobileClose?.addEventListener('click', () => mobileDrawer.classList.add('hidden'));

        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadges();
        });
    </script>
</body>

</html>
