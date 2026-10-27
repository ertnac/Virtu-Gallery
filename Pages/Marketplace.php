<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtu Gallery - Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap"
          rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            color: #333;
            line-height: 1.6;
            background-color: #f9f9f9;
            padding-top: 90px;
        }

        h1,
        h2,
        h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        a {
            text-decoration: none;
            color: inherit;
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
            transition: background-color 0.3s ease, padding 0.3s ease;
            height: 90px;
        }

        .nav-left,
        .nav-right {
            display: flex;
            align-items: center;
        }

        nav.scrolled {
            background-color: #202b1d;
            padding: 10px 5%;
        }

            nav.scrolled .logo,
            nav.scrolled .nav-links a,
            nav.scrolled .profile-icon,
            nav.scrolled .cart-icon {
                color: white;
            }

                nav.scrolled .nav-links a:hover {
                    color: #ecd5a6;
                }

                nav.scrolled .profile-icon:hover,
                nav.scrolled .cart-icon:hover {
                    box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
                }

            nav.scrolled .profile-menu a:hover {
                color: #ecd5a6;
            }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #202b1d;
            font-weight: bold;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            transition: color 0.3s ease;
        }

        nav.scrolled .logo {
            color: white;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

            .nav-links li {
                margin-left: 20px;
                position: relative;
            }

            .nav-links a {
                color: #333;
                font-weight: 500;
                transition: color 0.3s;
                position: relative;
            }

                .nav-links a.active {
                    color: #202b1d;
                    font-weight: 600;
                }

                .nav-links a:hover {
                    color: #202b1d;
                }

                .nav-links a::after {
                    content: '';
                    position: absolute;
                    bottom: -5px;
                    left: 0;
                    width: 0;
                    height: 2px;
                    background-color: #202b1d;
                    transition: width 0.3s;
                }

                .nav-links a:hover::after,
                .nav-links a.active::after {
                    width: 100%;
                }

        nav.scrolled .nav-links a::after {
            background-color: #ecd5a6;
        }

        .profile-container {
            position: relative;
            margin-left: 10px;
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
            transition: all 0.3s ease;
            margin-left: 10px;
        }

            .profile-icon:hover,
            .cart-icon:hover {
                transform: scale(1.1);
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .profile-menu {
            position: absolute;
            top: 50px;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
            z-index: 1000;
            min-width: 180px;
        }

            .profile-container:hover .profile-menu,
            .profile-menu.active {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .profile-menu a {
                display: block;
                padding: 12px 20px;
                color: #333;
                font-weight: 500;
                transition: background-color 0.3s, color 0.3s;
            }

                .profile-menu a:hover {
                    background-color: #f9f9f9;
                    color: #202b1d;
                }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #333;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        nav.scrolled .mobile-menu-btn {
            color: white;
        }

        /* Marketplace Content */
        .marketplace-content {
            padding: 20px 5%;
            background-color: #f9f9f9;
            min-height: calc(100vh - 90px);
        }

        .marketplace-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .marketplace-title {
            font-size: 1.5rem;
            color: #202b1d;
        }

        .filter-controls {
            display: flex;
            gap: 8px;
            background-color: rgba(32, 43, 29, 0.1);
            border-radius: 8px;
            padding: 2px;
        }

        .filter-btn {
            padding: 8px 15px;
            background: none;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.95rem;
            color: #333;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

            .filter-btn.active {
                background-color: #202b1d;
                color: white;
                font-weight: 500;
            }

            .filter-btn:hover:not(.active) {
                background-color: rgba(32, 43, 29, 0.2);
            }

        /* Artwork Grid */
        .artwork-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .artwork-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

            .artwork-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
            }

        .artwork-image-container {
            position: relative;
            width: 100%;
            padding-top: 100%;
            overflow: hidden;
        }

        .artwork-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .artwork-card:hover .artwork-image {
            transform: scale(1.03);
        }

        .artwork-info {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .artwork-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: #202b1d;
        }

        .artwork-artist {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }

        .artwork-price {
            font-weight: 600;
            color: #202b1d;
            font-size: 1rem;
            margin-top: auto;
        }

        .artwork-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .view-btn {
            background-color: white;
            color: #202b1d;
            border: 1px solid #202b1d;
            border-radius: 4px;
            padding: 8px 15px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

            .view-btn:hover {
                background-color: #f0f0f0;
            }

        .add-to-cart {
            background-color: #202b1d;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 15px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

            .add-to-cart:hover {
                background-color: #1a2318;
                transform: translateY(-1px);
            }

        /* Artwork Modal */
        .artwork-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

            .artwork-modal.active {
                opacity: 1;
                visibility: visible;
            }

        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 80%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 2001;
        }

        .modal-body {
            display: flex;
            flex-direction: row;
            padding: 30px;
        }

        .modal-image-container {
            flex: 1;
            min-width: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-right: 30px;
        }

        .modal-image {
            max-width: 100%;
            max-height: 60vh;
            object-fit: contain;
            border-radius: 8px;
        }

        .modal-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #202b1d;
            margin-bottom: 10px;
        }

        .modal-artist {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 20px;
        }

        .modal-info-item {
            margin-bottom: 15px;
        }

        .modal-info-label {
            font-weight: 600;
            color: #202b1d;
            margin-bottom: 5px;
        }

        .modal-info-value {
            color: #333;
        }

        .modal-description {
            margin-top: 20px;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .modal-add-to-cart {
            background-color: #202b1d;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 25px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

            .modal-add-to-cart:hover {
                background-color: #1a2318;
            }

        .modal-close-btn {
            background-color: white;
            color: #202b1d;
            border: 1px solid #202b1d;
            border-radius: 4px;
            padding: 12px 25px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

            .modal-close-btn:hover {
                background-color: #f0f0f0;
            }

        @media (max-width: 992px) {
            .marketplace-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .filter-controls {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 10px 5%;
            }

            .logo {
                font-size: 1.5rem;
                position: static;
                transform: none;
            }

            .nav-links {
                position: fixed;
                top: 90px;
                left: 0;
                width: 100%;
                background-color: #202b1d;
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s;
                pointer-events: none;
            }

                .nav-links.active {
                    transform: translateY(0);
                    opacity: 1;
                    pointer-events: all;
                }

                .nav-links li {
                    margin: 15px 0;
                }

                .nav-links a {
                    color: white;
                }

                    .nav-links a:hover {
                        color: #ecd5a6;
                    }

            .mobile-menu-btn {
                display: block;
            }

            .profile-menu {
                top: 60px;
                right: 5%;
                min-width: 150px;
            }

            /* Responsive modal */
            .modal-body {
                flex-direction: column;
                padding: 20px;
            }

            .modal-image-container {
                padding-right: 0;
                padding-bottom: 20px;
                min-width: auto;
            }

            .modal-image {
                max-height: 40vh;
            }

            .modal-content {
                width: 95%;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav id="navbar">
         <div class="nav-left">
                <ul class="nav-links">
                    <li><a href="../index.php#hero">Home</a></li>
                    <li><a href="../index.php#features">Features</a></li>
                    <li><a href="../index.php#gallery">Gallery</a></li>
                    <li><a href="Marketplace.php" class="marketplace-link">Marketplace</a></li>
                    <li><a href="../index.php#about">About Us</a></li>
                </ul>
            </div>
        <div class="logo">VirtuGallery</div>
        <div class="nav-right">
            <a href="cart.html" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">0</span>
            </a>
            <div class="profile-container">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-menu">
                    <a href="UserProfilePage.php">Profile</a>
                    <a href="settings.php">Settings</a>
                    <a href="UserProfilePage.php">My Collection</a>
                    <a href="#">Logout</a>
                </div>
            </div>
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <div class="marketplace-content">
        <div class="marketplace-header">
            <h1 class="marketplace-title">Marketplace</h1>
            <div class="filter-controls">
                <button class="filter-btn active">All</button>
                <button class="filter-btn">Paintings</button>
                <button class="filter-btn">Digital</button>
                <button class="filter-btn">Photography</button>
                <button class="filter-btn">Sculptures</button>
            </div>
        </div>

        <div class="artwork-grid">
            <!-- Artwork 1 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_4_683f635f9d55e.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">Monalisa</div>
                    <div class="artwork-artist">by Clara Bennett</div>
                    <div class="artwork-price">₱25,000</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Artwork 2 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_3_68438343461c4.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">Medusa</div>
                    <div class="artwork-artist">by Marcus Lee</div>
                    <div class="artwork-price">₱17,500</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Artwork 3 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_3_6843823bdb475.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">Mountain Peak</div>
                    <div class="artwork-artist">by Sophia Chen</div>
                    <div class="artwork-price">₱32,000</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Artwork 4 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_3_684383c848806.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">Forest Path</div>
                    <div class="artwork-artist">by David Wilson</div>
                    <div class="artwork-price">₱15,000</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Artwork 5 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_3_684381ca59dde.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">Abstract Thoughts</div>
                    <div class="artwork-artist">by Emma Johnson</div>
                    <div class="artwork-price">₱34,500</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Artwork 6 -->
            <div class="artwork-card">
                <div class="artwork-image-container">
                    <img src="../uploads/artwork_3_68439f96c07df.jpg" alt="Artwork" class="artwork-image">
                </div>
                <div class="artwork-info">
                    <div class="artwork-title">City Lights</div>
                    <div class="artwork-artist">by Ryan Park</div>
                    <div class="artwork-price">₱21,000</div>
                    <div class="artwork-actions">
                        <button class="view-btn">View</button>
                        <button class="add-to-cart">
                            <i class="fas fa-cart-plus"></i> Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Artwork Modal -->
    <div class="artwork-modal" id="artworkModal">
        <button class="modal-close" id="modalClose">
            <i class="fas fa-times"></i>
        </button>
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-image-container">
                    <img src="" alt="Artwork" class="modal-image" id="modalImage">
                </div>
                <div class="modal-details">
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <div class="modal-artist" id="modalArtist"></div>

                    <div class="modal-info-item">
                        <div class="modal-info-label">Location</div>
                        <div class="modal-info-value" id="modalLocation">San Pablo, Laguna</div>
                    </div>

                    <div class="modal-info-item">
                        <div class="modal-info-label">Price</div>
                        <div class="modal-info-value" id="modalPrice"></div>
                    </div>

                    <div class="modal-info-item">
                        <div class="modal-info-label">Description</div>
                        <p class="modal-description" id="modalDescription">
                            This beautiful artwork captures the essence of nature with vibrant colors and intricate
                            details.
                            The artist has masterfully blended traditional techniques with modern sensibilities to
                            create
                            a piece that is both timeless and contemporary. Each brushstroke tells a story, inviting the
                            viewer to explore deeper meanings and personal interpretations.
                        </p>
                    </div>

                    <div class="modal-actions">
                        <button class="modal-add-to-cart" id="modalAddToCart">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                        <button class="modal-close-btn" id="modalCloseBtn">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize cart count
            let cartCount = 0;
            const cartCountElement = document.querySelector('.cart-count');
            const cartItems = [];

            // Toggle profile menu on mobile click
            const profileIcon = document.querySelector('.profile-icon');
            const profileMenu = document.querySelector('.profile-menu');
            const profileContainer = document.querySelector('.profile-container');

            profileIcon.addEventListener('click', function (e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    profileMenu.classList.toggle('active');
                }
            });

            // Close profile menu when clicking outside
            document.addEventListener('click', function (e) {
                if (!profileContainer.contains(e.target) && window.innerWidth <= 768) {
                    profileMenu.classList.remove('active');
                }
            });

            // Mobile menu toggle
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const navLinks = document.querySelector('.nav-links');
            mobileMenuBtn.addEventListener('click', function () {
                navLinks.classList.toggle('active');
                const icon = mobileMenuBtn.querySelector('i');
                if (navLinks.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Close mobile menu when clicking a link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', function () {
                    if (navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        const icon = mobileMenuBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            });

            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function () {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Add to cart functionality
            function addToCart(title, artist, price, imageSrc) {
                cartCount++;
                cartCountElement.textContent = cartCount;

                // Add item to cart array
                cartItems.push({
                    title: title,
                    artist: artist,
                    price: price,
                    image: imageSrc
                });

                console.log('Added to cart:', { title, artist, price });
                console.log('Cart items:', cartItems);

                // Show feedback
                const cartIcon = document.querySelector('.cart-icon i');
                cartIcon.classList.remove('fa-shopping-cart');
                cartIcon.classList.add('fa-check');

                setTimeout(() => {
                    cartIcon.classList.remove('fa-check');
                    cartIcon.classList.add('fa-shopping-cart');
                }, 1000);
            }

            // Cart icon click functionality
            document.querySelector('.cart-icon').addEventListener('click', function (e) {
                // Only prevent default if we have items (for demo purposes)
                if (cartItems.length > 0) {
                    e.preventDefault();
                    alert(`You have ${cartCount} items in your cart. Total: ₱${calculateTotal().toLocaleString()}`);
                }
                // If cart is empty, the normal href to cart.html will proceed
            });

            // Calculate cart total
            function calculateTotal() {
                return cartItems.reduce((total, item) => {
                    return total + parseFloat(item.price.replace('₱', '').replace(',', ''));
                }, 0);
            }

            // Add to cart buttons functionality
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', function () {
                    const card = this.closest('.artwork-card');
                    const title = card.querySelector('.artwork-title').textContent;
                    const artist = card.querySelector('.artwork-artist').textContent;
                    const price = card.querySelector('.artwork-price').textContent;
                    const imageSrc = card.querySelector('.artwork-image').src;

                    addToCart(title, artist, price, imageSrc);

                    // Button feedback
                    const icon = this.querySelector('i');
                    icon.classList.remove('fa-cart-plus');
                    icon.classList.add('fa-check');

                    setTimeout(() => {
                        icon.classList.remove('fa-check');
                        icon.classList.add('fa-cart-plus');
                    }, 1000);
                });
            });

            // Modal functionality
            const artworkModal = document.getElementById('artworkModal');
            const modalClose = document.getElementById('modalClose');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalArtist = document.getElementById('modalArtist');
            const modalPrice = document.getElementById('modalPrice');
            const modalDescription = document.getElementById('modalDescription');
            const modalAddToCart = document.getElementById('modalAddToCart');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            // Sample descriptions for each artwork
            const artworkDescriptions = {
                "Sunset Dreams": "A breathtaking depiction of a sunset over rolling hills, with vibrant oranges and purples blending seamlessly. Clara Bennett's signature style shines through in this piece, capturing the fleeting beauty of twilight.",
                "Ocean Waves": "Marcus Lee's masterful brushwork brings the ocean to life in this dynamic piece. The crashing waves seem to move before your eyes, with shades of blue and green creating a mesmerizing effect.",
                "Mountain Peak": "Sophia Chen's majestic mountain landscape transports you to the high peaks. The play of light on snow-capped mountains and the detailed foreground flora showcase her technical prowess.",
                "Forest Path": "David Wilson invites you down a mysterious forest path with dappled sunlight filtering through ancient trees. The rich greens and browns create a sense of depth and tranquility.",
                "Abstract Thoughts": "Emma Johnson's bold abstract composition challenges the viewer with its vibrant colors and geometric forms. Each viewing reveals new patterns and hidden meanings.",
                "City Lights": "Ryan Park's urban landscape captures the energy of the city at night. The glowing windows and streetlights create a mosaic of light against the dark skyline."
            };

            // Current artwork in modal
            let currentArtwork = null;

            // View button functionality
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const card = this.closest('.artwork-card');
                    const title = card.querySelector('.artwork-title').textContent;
                    const artist = card.querySelector('.artwork-artist').textContent;
                    const price = card.querySelector('.artwork-price').textContent;
                    const imageSrc = card.querySelector('.artwork-image').src;

                    // Set current artwork
                    currentArtwork = {
                        title: title,
                        artist: artist,
                        price: price,
                        image: imageSrc
                    };

                    // Set modal content
                    modalImage.src = imageSrc;
                    modalTitle.textContent = title;
                    modalArtist.textContent = artist;
                    modalPrice.textContent = price;
                    modalDescription.textContent = artworkDescriptions[title] || "This beautiful artwork captures the essence of nature with vibrant colors and intricate details.";

                    // Show modal
                    artworkModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            });

            // Modal add to cart
            modalAddToCart.addEventListener('click', function () {
                if (currentArtwork) {
                    addToCart(
                        currentArtwork.title,
                        currentArtwork.artist,
                        currentArtwork.price,
                        currentArtwork.image
                    );
                    closeModal();
                }
            });

            // Close modal
            function closeModal() {
                artworkModal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            modalClose.addEventListener('click', closeModal);
            modalCloseBtn.addEventListener('click', closeModal);

            // Close modal when clicking outside content
            artworkModal.addEventListener('click', function (e) {
                if (e.target === artworkModal) {
                    closeModal();
                }
            });

            // Filter buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.textContent;
                    console.log('Filtering by:', filter);

                    // In a real app, you would filter the artwork here
                    // For demo, we'll just log the filter
                    if (filter !== 'All') {
                        alert(`Filtering by: ${filter}. In a real app, this would filter the artwork grid.`);
                    }
                });
            });

            // Profile menu links
            document.querySelectorAll('.profile-menu a').forEach(link => {
                link.addEventListener('click', function () {
                    console.log('Navigating to:', this.textContent);
                    // In a real app, this would navigate to the appropriate page
                });
            });
        });
    </script>
</body>

</html>