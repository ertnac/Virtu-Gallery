<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtu Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --primary-color: #202b1d;
        --secondary-color: #1a2318;
        --text-color: #333;
        --accent-color: #ecd5a6;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        overflow: hidden;
        font-family: 'Montserrat', sans-serif;
        color: #333;
        line-height: 1.6;
        background-color: #f9f9f9;
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

    /* Navigation Bar Styles */
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
        background-color: var(--primary-color);
        padding: 10px 5%;
    }

    nav.scrolled .logo,
    nav.scrolled .nav-links a,
    nav.scrolled .profile-icon {
        color: white;
    }

    nav.scrolled .nav-links a:hover {
        color: #ecd5a6;
    }

    nav.scrolled .profile-icon:hover {
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
        background-color: #000000;
        transition: width 0.3s;
    }

    .nav-links a:hover::after {
        width: 100%;
    }

    nav.scrolled .nav-links a::after {
        background-color: #ecd5a6;
    }

    .profile-container {
        position: relative;
        margin-left: 10px;
    }

    .profile-icon {
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
    }

    .profile-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
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

    @media (max-width: 768px) {
        nav {
            padding: 10px 5%;
        }

        .logo {
            font-size: 1.5rem;
        }

        .nav-links {
            position: fixed;
            top: 60px;
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
    }

    /* Main Content Styles */
    .main-content {
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .content-wrapper {
        flex: 1;
        padding: 90px 5% 0;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
        transition: background-color 0.5s ease;
        overflow: hidden;
        height: calc(100vh - 90px);
    }

    .parent {
        flex: 1;
        gap: 30px;
        height: 100%;
        overflow: hidden;
    }

    .content-container {
        display: grid;
        grid-template-columns: 20% 80%;
        grid-template-rows: auto 1fr;
        gap: 15px;
        height: 100%;
        width: 100%;
        box-sizing: border-box;
        padding: 20px;
        overflow: hidden;
    }

    .searchtab,
    .sidebartab,
    .settings-container {
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s ease;
        background-color: white;
    }

    .searchtab {
        grid-column: 1 / -1;
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 15px 25px;
        height: auto;
        min-height: 80px;
        display: flex;
        align-items: center;
    }

    .sidebartab {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sidebarbuttons {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 10px;
        height: 100%;
        overflow-y: auto;
    }

    .sidebarbuttons a {
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 15px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        text-decoration: none;
    }

    .sidebarbuttons a:hover {
        background-color: #f5f5f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        border-color: #202b1d;
    }

    .sidebarbuttons a i {
        font-size: 1.1rem;
        color: #202b1d;
        width: 24px;
        text-align: center;
    }

    .sidebarbuttons a.active {
        background-color: var(--primary-color);
        color: white;
    }

    .sidebarbuttons a.active i {
        color: white;
    }

    .AddWork {
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }

    .AddWork:hover {
        background-color: #f5f5f5;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        border-color: #202b1d;
    }

    .add-work-container {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.7);
    }

    .add-work-container:hover {
        border-color: #202b1d;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .add-work-placeholder {
        color: #666;
        font-size: 1rem;
        margin-bottom: 15px;
        text-align: center;
    }

    .add-image-btn {
        background-color: #202b1d;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .add-image-btn:hover {
        background-color: #1a2318;
        transform: scale(1.1);
    }

    /* Settings Container Styles */
    .settings-container {
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        overflow-y: auto;
        height: 100%;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    .settings-section {
        margin-bottom: 20px;
    }

    .settings-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #202b1d;
        margin-bottom: 15px;
    }

    .settings-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .settings-option label {
        font-size: 0.95rem;
        color: #333;
    }

    .settings-option select,
    .settings-option input[type="checkbox"] {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        color: #333;
    }

    .settings-option select {
        padding: 5px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    .theme-options {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .theme-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .theme-btn.active {
        border-color: #202b1d;
        transform: scale(1.1);
    }

    .theme-btn#light {
        background-color: #ffffff;
    }

    .theme-btn#dark {
        background-color: #333333;
    }

    .theme-btn#sepia {
        background-color: #f4ecd8;
    }

    .language-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .language-btn {
        padding: 8px 15px;
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        color: #333;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .language-btn.active {
        background-color: #202b1d;
        color: white;
        border-color: #202b1d;
    }

    .language-btn:hover:not(.active) {
        background-color: #e0e0e0;
        transform: translateY(-1px);
    }

    .settings-container::-webkit-scrollbar {
        width: 8px;
    }

    .settings-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .settings-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .settings-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .search-tabs-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 30px;
    }

    .search-bar {
        display: flex;
        flex: 1;
        min-width: 200px;
        max-width: 500px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .search-bar input {
        flex: 1;
        padding: 10px 20px;
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        background-color: white;
        color: #333;
    }

    .search-bar input::placeholder {
        color: #aaa;
    }

    .search-button {
        padding: 0 15px;
        border: none;
        background-color: #202b1d;
        color: white;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: background-color 0.3s;
    }

    .search-button:hover {
        background-color: #1a2318;
    }

    .nav-tabs {
        display: flex;
        background-color: rgba(32, 43, 29, 0.1);
        border-radius: 8px;
        padding: 2px;
    }

    .tab-button {
        padding: 8px 25px;
        background: none;
        border: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        color: #333;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background-color: #202b1d;
        color: white;
        font-weight: 500;
    }

    .tab-button:hover:not(.active) {
        background-color: rgba(32, 43, 29, 0.2);
    }
    </style>
</head>

<body>
    <div class="main-content">
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
                <div class="profile-container">
                    <div class="profile-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-menu">
                        <a href="UserProfilePage.php">Profile</a>
                        <a href="#">My Collection</a>
                        <a href="#" id="logout-btn">Logout</a>
                    </div>
                </div>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>

        <div class="content-wrapper">
            <div class="parent">
                <div class="content-container">
                    <div class="searchtab">
                        <div class="search-tabs-container">
                            <div class="search-bar">
                                <input type="text" placeholder="Search artworks, creators...">
                                <button class="search-button">Search</button>
                            </div>
                            <div class="nav-tabs">
                                <button class="tab-button active" id="for-you-tab">For You</button>
                                <button class="tab-button" id="following-tab">Following</button>
                            </div>
                        </div>
                    </div>
                    <div class="sidebartab">
                        <div class="AddWork">
                            <div class="add-work-container">
                                <div class="add-work-placeholder">Add Your Artwork</div>
                                <button class="add-image-btn">+</button>
                                <div class="artwork-details" style="display: none;">
                                    <input type="text" class="artwork-title" placeholder="Artwork Title" required>
                                    <input type="text" class="artwork-artist" placeholder="Artwork Artist" required>
                                    <button class="submit-artwork-btn">Add Work</button>
                                </div>
                            </div>
                        </div>
                        <div class="sidebarbuttons">
                            <a href="home.php" class="Home">
                                <i class="fas fa-home"></i>
                                <span>Home</span>
                            </a>
                            <a href="notifications.php" class="Notifications">
                                <i class="fas fa-bell"></i>
                                <span>Notifications</span>
                            </a>
                            <a href="ranking.php" class="Ranking">
                                <i class="fas fa-trophy"></i>
                                <span>Ranking</span>
                            </a>
                            <a href="settings.php" class="Settings active">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                    <div class="settings-container" id="settings-container">
                        <div class="settings-section">
                            <h2>Preferences</h2>
                            <div class="settings-option">
                                <label for="language">Language</label>
                                <select id="language">
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                </select>
                            </div>
                            <!-- <div class="settings-option">
                                <label for="theme">Theme</label>
                                <div class="theme-options">
                                    <div class="theme-btn active" id="light" data-theme="light"></div>
                                    <div class="theme-btn" id="dark" data-theme="dark"></div>
                                    <div class="theme-btn" id="sepia" data-theme="sepia"></div>
                                </div>
                            </div> -->
                        </div>
                        <div class="settings-section">
                            <h2>Notifications</h2>
                            <div class="settings-option">
                                <label for="push-notifications">Push Notifications</label>
                                <input type="checkbox" id="push-notifications" checked>
                            </div>
                            <div class="settings-option">
                                <label for="email-notifications">Email Notifications</label>
                                <input type="checkbox" id="email-notifications">
                            </div>
                        </div>
                        <div class="settings-section">
                            <h2>Account</h2>
                            <div class="settings-option">
                                <label>Logout</label>
                                <button id="logout-settings-btn">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay-backdrop" id="overlayBackdrop"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Store the current view state
        let currentView = 'settings';

        // Initialize with settings view
        showSettingsView();

        // Navigation bar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Profile menu toggle
        const profileIcon = document.querySelector('.profile-icon');
        const profileMenu = document.querySelector('.profile-menu');
        const profileContainer = document.querySelector('.profile-container');

        profileIcon.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                profileMenu.classList.toggle('active');
            }
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileContainer.contains(e.target) && window.innerWidth <= 768) {
                profileMenu.classList.remove('active');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        mobileMenuBtn.addEventListener('click', function() {
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
            link.addEventListener('click', function() {
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    const icon = mobileMenuBtn.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });
        });

        // Tab switching functionality
        document.getElementById('for-you-tab').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('following-tab').classList.remove('active');
        });

        document.getElementById('following-tab').addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('for-you-tab').classList.remove('active');
        });

        // Sidebar navigation functionality
        document.querySelectorAll('.sidebarbuttons > div').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.sidebarbuttons > div').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                showSettingsView();
            });
        });

        // View management function
        function showSettingsView() {
            document.getElementById('settings-container').style.display = 'block';
            currentView = 'settings';
        }

        // Add artwork functionality
        document.querySelector('.add-image-btn').addEventListener('click', function() {
            const placeholder = document.querySelector('.add-work-placeholder');
            const details = document.querySelector('.artwork-details');

            if (details.style.display === 'none') {
                placeholder.style.display = 'none';
                details.style.display = 'block';
            } else {
                placeholder.style.display = 'block';
                details.style.display = 'none';
            }
        });

        // Submit artwork
        document.querySelector('.submit-artwork-btn').addEventListener('click', function() {
            const title = document.querySelector('.artwork-title').value;
            const artist = document.querySelector('.artwork-artist').value;

            if (title && artist) {
                alert(`Artwork "${title}" by ${artist} submitted!`);
                document.querySelector('.add-work-placeholder').style.display = 'block';
                document.querySelector('.artwork-details').style.display = 'none';
                document.querySelector('.artwork-title').value = '';
                document.querySelector('.artwork-artist').value = '';
            } else {
                alert('Please fill in both title and artist name');
            }
        });

        // Settings navigation functionality
        document.getElementById('language').addEventListener('change', function() {
            console.log(`Language changed to: ${this.value}`);
            // Placeholder for language change logic
        });

        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.theme-btn').forEach(b => b.classList.remove(
                    'active'));
                this.classList.add('active');
                const theme = this.getAttribute('data-theme');
                changeTheme(theme);
            });
        });

        function changeTheme(theme) {
            console.log(`Theme changed to: ${theme}`);
            // Placeholder for theme change logic
            if (theme === 'dark') {
                document.body.style.backgroundColor = '#333';
                document.querySelector('.content-wrapper').style.backgroundColor = '#333';
            } else if (theme === 'sepia') {
                document.body.style.backgroundColor = '#f4ecd8';
                document.querySelector('.content-wrapper').style.backgroundColor = '#f4ecd8';
            } else {
                document.body.style.backgroundColor = '#f9f9f9';
                document.querySelector('.content-wrapper').style.backgroundColor = '#f9f9f9';
            }
        }

        // Logout functionality
        function logout() {
            console.log('User logged out');
            // Placeholder for actual logout logic
            alert('You have been logged out.');
        }

        document.getElementById('logout-btn').addEventListener('click', logout);
        document.getElementById('logout-settings-btn').addEventListener('click', logout);
    });
    </script>
</body>

</html>