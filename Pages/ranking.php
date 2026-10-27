<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtu Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&family=Roboto:wght@400;500;700&display=swap"
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
        .ranking-container {
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

        /* Ranking Container Styles */
        .ranking-container {
            background-color: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            overflow-y: auto;
            height: 100%;
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }

        .ranking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .ranking-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #202b1d;
        }

        .ranking-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-dropdown {
            position: relative;
            display: inline-block;
        }

        .filter-dropdown-btn {
            padding: 8px 15px;
            background-color: #f5f5f5;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

            .filter-dropdown-btn:hover {
                background-color: #e0e0e0;
            }

            .filter-dropdown-btn i {
                transition: transform 0.3s;
            }

            .filter-dropdown-btn.active i {
                transform: rotate(180deg);
            }

        .filter-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 6px;
            z-index: 1;
            padding: 8px 0;
            border: 1px solid #e0e0e0;
        }

            .filter-dropdown-content a {
                color: #333;
                padding: 8px 16px;
                text-decoration: none;
                display: block;
                font-size: 0.9rem;
                transition: background-color 0.2s;
            }

                .filter-dropdown-content a:hover {
                    background-color: #f5f5f5;
                }

                .filter-dropdown-content a.active {
                    background-color: #202b1d;
                    color: white;
                }

        .ranking-list {
            display: none;
        }

            .ranking-list.active {
                display: block;
            }

        .ranking-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

            .ranking-item:hover {
                background-color: #f9f9f9;
            }

        .ranking-position {
            width: 40px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #202b1d;
            text-align: center;
        }

        .ranking-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 12px;
        }

        .artist-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .ranking-content {
            flex-grow: 1;
        }

        .ranking-title-text {
            font-weight: 600;
            font-size: 0.95rem;
            color: #333;
        }

        .ranking-artist {
            font-size: 0.8rem;
            color: #666;
        }

        .ranking-stats {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            color: #888;
        }

        .follow-btn {
            padding: 6px 15px;
            background-color: #202b1d;
            color: white;
            border: none;
            border-radius: 4px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: auto;
        }

            .follow-btn:hover {
                background-color: #1a2318;
                transform: translateY(-1px);
            }

            .follow-btn.following {
                background-color: #f5f5f5;
                color: #202b1d;
                border: 1px solid #e0e0e0;
            }

        .ranking-container::-webkit-scrollbar {
            width: 8px;
        }

        .ranking-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .ranking-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

            .ranking-container::-webkit-scrollbar-thumb:hover {
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

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

            .toast.show {
                opacity: 1;
            }

        /* Overlay Backdrop */
        .overlay-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

            .overlay-backdrop.active {
                opacity: 1;
                visibility: visible;
            }

        .highlight {
            background-color: rgba(236, 213, 166, 0.2);
            border-left: 3px solid var(--accent-color);
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
                        <a href="UserProfilePage.php">My Collection</a>
                        <a href="#">Logout</a>
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
                                <input type="text" placeholder="Search rankings...">
                                <button class="search-button">Search</button>
                            </div>
                            <div class="filter-dropdown">
                                <button class="filter-dropdown-btn" id="filter-dropdown-btn">
                                    Show All <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="filter-dropdown-content" id="filter-dropdown-content">
                                    <a href="#" class="active" data-filter-type="all">Show All</a>
                                    <a href="#" data-filter-type="artists">Artists</a>
                                    <a href="#" data-filter-type="artworks">Artworks</a>
                                </div>
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
                            <a href="ranking.php" class="Ranking active">
                                <i class="fas fa-trophy"></i>
                                <span>Ranking</span>
                            </a>
                            <a href="settings.php" class="Settings">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                    <div class="ranking-container" id="ranking-container">
                        <div class="ranking-header">
                            <h2 class="ranking-title">Top Rankings</h2>
                        </div>

                        <!-- Artists Ranking List -->
                        <div class="ranking-list" id="artists-ranking">
                            <h3 class="ranking-subtitle">Top Artists</h3>
                            <div class="ranking-item highlight">
                                <div class="ranking-position">1</div>
                                <img src="../images/babaengghibli.png" class="artist-avatar" alt="Artist">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">Studio Ghibli</div>
                                    <div class="ranking-artist">@studio_ghibli</div>
                                    <div class="ranking-stats">
                                        <span>12.5K followers</span>
                                        <span>256 artworks</span>
                                    </div>
                                </div>
                                <button class="follow-btn following">Following</button>
                            </div>
                            <div class="ranking-item">
                                <div class="ranking-position">2</div>
                                <img src="../images/mgamuning.jpg" class="artist-avatar" alt="Artist">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">Van Gogh</div>
                                    <div class="ranking-artist">@vangogh</div>
                                    <div class="ranking-stats">
                                        <span>10.2K followers</span>
                                        <span>189 artworks</span>
                                    </div>
                                </div>
                                <button class="follow-btn">Follow</button>
                            </div>
                            <div class="ranking-item">
                                <div class="ranking-position">3</div>
                                <img src="../images/lalalalal.jpg" class="artist-avatar" alt="Artist">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">Picasso</div>
                                    <div class="ranking-artist">@picasso</div>
                                    <div class="ranking-stats">
                                        <span>9.8K followers</span>
                                        <span>312 artworks</span>
                                    </div>
                                </div>
                                <button class="follow-btn">Follow</button>
                            </div>
                        </div>

                        <!-- Artworks Ranking List -->
                        <div class="ranking-list" id="artworks-ranking">
                            <h3 class="ranking-subtitle">Top Artworks</h3>
                            <div class="ranking-item highlight">
                                <div class="ranking-position">1</div>
                                <img src="../images/easaportrait.png" class="ranking-image" alt="Artwork">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">Starry Night</div>
                                    <div class="ranking-artist">@vangogh</div>
                                    <div class="ranking-stats">
                                        <span>15.7K likes</span>
                                        <span>892 comments</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ranking-item">
                                <div class="ranking-position">2</div>
                                <img src="../images/mgamuning.jpg" class="ranking-image" alt="Artwork">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">The Persistence of Memory</div>
                                    <div class="ranking-artist">@dali</div>
                                    <div class="ranking-stats">
                                        <span>14.3K likes</span>
                                        <span>765 comments</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ranking-item">
                                <div class="ranking-position">3</div>
                                <img src="../images/lalalalal.jpg" class="ranking-image" alt="Artwork">
                                <div class="ranking-content">
                                    <div class="ranking-title-text">Girl with a Pearl Earring</div>
                                    <div class="ranking-artist">@vermeer</div>
                                    <div class="ranking-stats">
                                        <span>12.9K likes</span>
                                        <span>654 comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay-backdrop" id="overlayBackdrop"></div>
        <div class="toast" id="toast"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize with all rankings visible
            showAllRankings();

            // Navigation bar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function () {
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

            // Add artwork functionality
            document.querySelector('.add-image-btn').addEventListener('click', function () {
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
            document.querySelector('.submit-artwork-btn').addEventListener('click', function () {
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

            // Follow button functionality
            document.querySelectorAll('.follow-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (this.textContent === 'Follow') {
                        this.textContent = 'Following';
                        this.classList.add('following');
                        showToast('Artist followed successfully');
                    } else {
                        this.textContent = 'Follow';
                        this.classList.remove('following');
                        showToast('Artist unfollowed');
                    }
                });
            });

            // Filter dropdown functionality
            const filterDropdownBtn = document.getElementById('filter-dropdown-btn');
            const filterDropdownContent = document.getElementById('filter-dropdown-content');

            filterDropdownBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                filterDropdownContent.style.display = filterDropdownContent.style.display === 'block' ? 'none' : 'block';
                this.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function () {
                filterDropdownContent.style.display = 'none';
                filterDropdownBtn.classList.remove('active');
            });

            // Prevent dropdown from closing when clicking inside
            filterDropdownContent.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Filter type selection
            document.querySelectorAll('#filter-dropdown-content a').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active class from all items
                    document.querySelectorAll('#filter-dropdown-content a').forEach(i => {
                        i.classList.remove('active');
                    });

                    // Add active class to clicked item
                    this.classList.add('active');

                    // Update dropdown button text
                    const filterType = this.getAttribute('data-filter-type');
                    let filterText = 'Show All';
                    if (filterType === 'artists') filterText = 'Artists';
                    if (filterType === 'artworks') filterText = 'Artworks';

                    filterDropdownBtn.innerHTML = `${filterText} <i class="fas fa-chevron-down"></i>`;

                    // Close dropdown
                    filterDropdownContent.style.display = 'none';
                    filterDropdownBtn.classList.remove('active');

                    // Apply filter
                    applyRankingFilter(filterType);
                });
            });

            function applyRankingFilter(filterType) {
                const artistsRanking = document.getElementById('artists-ranking');
                const artworksRanking = document.getElementById('artworks-ranking');

                switch (filterType) {
                    case 'all':
                        showAllRankings();
                        break;
                    case 'artists':
                        artistsRanking.style.display = 'block';
                        artworksRanking.style.display = 'none';
                        break;
                    case 'artworks':
                        artistsRanking.style.display = 'none';
                        artworksRanking.style.display = 'block';
                        break;
                }
            }

            function showAllRankings() {
                document.getElementById('artists-ranking').style.display = 'block';
                document.getElementById('artworks-ranking').style.display = 'block';
            }

            function showToast(message) {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.classList.add('show');

                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }

            // Highlight top ranked items
            function highlightTopRankings() {
                const artistsList = document.querySelectorAll('#artists-ranking .ranking-item');
                const artworksList = document.querySelectorAll('#artworks-ranking .ranking-item');

                // Reset all highlights
                artistsList.forEach(item => item.classList.remove('highlight'));
                artworksList.forEach(item => item.classList.remove('highlight'));

                // Highlight top 1 in each category
                if (artistsList.length > 0) artistsList[0].classList.add('highlight');
                if (artworksList.length > 0) artworksList[0].classList.add('highlight');
            }

            // Initial highlight
            highlightTopRankings();
        });
    </script>
</body>

</html>