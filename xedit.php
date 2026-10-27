<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    $username = $user['username'] ?? $_SESSION['email'];
    $user_id = $_SESSION['user_id'];

    // Fetch posts with like count and user like status
    $stmt = $pdo->prepare('
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
            u.username,
            u.profile_picture,
            (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes,
            EXISTS (SELECT 1 FROM likes l WHERE l.post_id = p.id AND l.user_id = ?) AS is_liked,
            (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comments
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ');
    $stmt->execute([$user_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching data: " . $e->getMessage();
}
?>

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

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="main-content">
        <nav id="navbar">
            <div class="nav-left">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../index.php#features">Features</a></li>
                    <li><a href="../index.php#gallery">Gallery</a></li>
                    <li><a href="Marketplace.php" class="marketplace-link">Marketplace</a></li>
                    <li><a href="">About Us</a></li>
                </ul>
            </div>
            <div class="logo">VirtuGallery</div>
            <div class="nav-right">
                <div class="profile-container">
                    <div class="profile-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-menu">
                        <a href="../Pages/UserProfilePage.php">Profile</a>
                        <a href="../Pages/UserProfilePage.php">My Collection</a>
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
                                <input type="text" id="search-input" placeholder="Search artworks, creators...">
                                <button class="search-button" id="search-button">Search</button>
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
                                <button class="add-image-btn" id="add-artwork-btn">+</button>
                            </div>
                        </div>
                        <div class="sidebarbuttons">
                            <a href="home.php" class="Home active">
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
                            <a href="settings.php" class="Settings">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>
                    <div class="post-container" id="artwork-grid">
                        <?php if (isset($error)): ?>
                            <div class="error"><?php echo htmlspecialchars($error); ?></div>
                        <?php elseif (empty($posts)): ?>
                            <div class="error">No posts available.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Artist Profile Overlay -->
                <div class="artist-profile-overlay" id="artistProfileOverlay">
                    <div class="overlay-content">
                        <div class="artist-header">
                            <div class="artist-avatar-container">
                                <img src="uploads/profile_pictures/<?= $post['profile_picture'] ? htmlspecialchars($post['profile_picture']) : 'default.jpg' ?>"
                                    alt="User Photo">
                            </div>
                            <h2 class="artist-username">chatgpt_artist</h2>
                            <button class="follow-btn">Follow</button>
                        </div>
                        <div class="artist-stats">
                            <div class="stat-item">
                                <span class="stat-number">1,024</span>
                                <span class="stat-label">Posts</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">12.3K</span>
                                <span class="stat-label">Followers</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">543</span>
                                <span class="stat-label">Following</span>
                            </div>
                        </div>
                        <div class="artist-bio">
                            Digital artist inspired by Studio Ghibli. Creating magical worlds one pixel at a time.
                        </div>
                        <div class="artist-tabs">
                            <button class="artist-tab active" data-tab="posts">Posts</button>
                            <button class="artist-tab" data-tab="bookmarks">Bookmarks</button>
                        </div>
                        <div class="artist-content">
                            <div class="tab-content active" id="posts-tab">
                                <div class="artist-posts-grid">
                                    <div class="artist-post">
                                        <img src="../photos/955e466e687fabb75cf25ef036cb9dd3.jpg" alt="Post" />
                                    </div>
                                    <div class="artist-post">
                                        <img src="../photos/797dab886187eb08eca67a0dffc1dbd4.jpg" alt="Post" />
                                    </div>
                                    <div class="artist-post">
                                        <img src="../photos/ee8f62b168540e94bf4307e9090e853d.jpg" alt="Post" />
                                    </div>
                                    <div class="artist-post">
                                        <img src="../photos/5b3807dc2bc563044d3e6f6ffba24c55.jpg" alt="Post">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="bookmarks-tab">
                                <div class="artist-bookmarks-grid">
                                    <div class="artist-bookmark">
                                        <img src="../images/easaportrait.png" alt="Bookmark">
                                    </div>
                                    <div class="artist-bookmark">
                                        <img src="../images/mgamuning.jpg" alt="Bookmark">
                                    </div>
                                    <div class="artist-bookmark">
                                        <img src="../images/lalalalal.jpg" alt="Bookmark">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="close-overlay-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Comment Overlay -->
                <div class="comment-modal" id="commentModal">
                    <div class="comment-modal-content">
                        <div class="comment-image-section">
                            <img src="" class="comment-image" id="comment-image" alt="Post Image">
                        </div>
                        <div class="comment-section">
                            <div class="comments-list" id="comments-list"></div>
                            <div class="comment-input-box">
                                <input type="text" class="comment-input" id="comment-input" placeholder="Add a comment...">
                                <button class="comment-submit" id="comment-submit">Post</button>
                            </div>
                        </div>
                        <button class="comment-modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Likes Overlay -->
                <div class="likes-overlay" id="likesOverlay">
                    <div class="overlay-content">
                        <div class="likes-header">
                            <div>
                                <div class="likes-title">Likes</div>
                                <div class="likes-count">1,024 likes</div>
                            </div>
                            <button class="close-overlay-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="likes-list">
                            <div class="like-item">
                                <img src="../images/babaengghibli.png" class="like-avatar" alt="User">
                                <div class="like-user-info">
                                    <div class="like-username">art_lover42</div>
                                    <div class="like-name">Art Enthusiast</div>
                                </div>
                                <button class="like-follow-btn">Follow</button>
                            </div>
                            <div class="like-item">
                                <img src="../images/mgamuning.jpg" class="like-avatar" alt="User">
                                <div class="like-user-info">
                                    <div class="like-username">digital_creator</div>
                                    <div class="like-name">Digital Artist</div>
                                </div>
                                <button class="like-follow-btn following">Following</button>
                            </div>
                            <div class="like-item">
                                <img src="../images/lalalalal.jpg" class="like-avatar" alt="User">
                                <div class="like-user-info">
                                    <div class="like-username">ghibli_fan</div>
                                    <div class="like-name">Studio Ghibli Fan</div>
                                </div>
                                <button class="like-follow-btn">Follow</button>
                            </div>
                            <div class="like-item">
                                <img src="../images/shanaya.jpg" class="like-avatar" alt="User">
                                <div class="like-user-info">
                                    <div class="like-username">art_collector</div>
                                    <div class="like-name">Contemporary Art Collector</div>
                                </div>
                                <button class="like-follow-btn">Follow</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Artwork Modal -->
                <div class="upload-modal" id="uploadModal">
                    <div class="upload-modal-content">
                        <div class="upload-modal-close" id="closeUploadModal">
                            <i class="fas fa-times"></i>
                        </div>
                        <h2>Upload New Artwork</h2>
                        <form id="uploadForm" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="post-title">Artwork Title</label>
                                <input type="text" id="post-title" name="title" placeholder="Enter your artwork title"
                                    required class="form-input">
                            </div>

                            <div class="form-group">
                                <label for="post-description">Description</label>
                                <textarea id="post-description" name="description"
                                    placeholder="Tell us about your artwork... its inspiration, techniques used, or special meaning"
                                    required class="form-textarea"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category" required class="form-select">
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="paintings">Paintings</option>
                                    <option value="pottery">Pottery</option>
                                    <option value="sculpture">Sculpture</option>
                                    <option value="photography">Photography</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sale-option">Post Type</label>
                                <select id="sale-option" name="post_type" class="form-select">
                                    <option value="content">For Display Only</option>
                                    <option value="product">For Sale</option>
                                </select>
                            </div>

                            <div class="form-group price-field" id="price-field">
                                <label for="price">Price</label>
                                <div style="position: relative;">
                                    <span class="peso-symbol">₱</span>
                                    <input type="number" id="price" name="price" placeholder="Enter price" min="0"
                                        step="1" class="price-input form-input">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input type="text" id="tags" name="tags"
                                    placeholder="modern, abstract, landscape (separate with commas)" class="form-input">
                            </div>

                            <div class="form-group">
                                <label for="post-image">Upload Image</label>
                                <input type="file" id="post-image" name="image" accept="image/*" required
                                    style="display: none;">
                                <div class="preview-area" id="preview-area">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p>Click to upload artwork image</p>
                                    <p class="preview-text">JPEG, PNG (Max 5MB)</p>
                                    <img id="image-preview" src="" alt="Preview">
                                </div>
                            </div>

                            <button type="submit" class="submit-button">
                                <i class="fas fa-upload"></i> Publish Artwork
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Upload Success Modal -->
                <div class="upload-success-modal" id="uploadSuccessModal">
                    <div class="upload-success-content">
                        <i class="fas fa-check-circle"></i>
                        <h3>Artwork Published!</h3>
                        <p>Your artwork has been successfully uploaded to VirtuGallery.</p>
                        <button class="upload-success-button" id="uploadSuccessButton">Return to Home</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay-backdrop" id="overlayBackdrop"></div>
        <div class="toast" id="toast"></div>
    </div>

    <script>
        // Pass PHP posts to JavaScript
        const posts = <?php echo json_encode($posts ?: []); ?>;
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        const currentUserId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
        let redirectAfterLogin = '';

        document.addEventListener('DOMContentLoaded', function() {
            initApp();

            // Navigation bar scroll effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
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
                renderArtworks();
            });

            document.getElementById('following-tab').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('for-you-tab').classList.remove('active');
                renderArtworks();
            });

            // Add Artwork button functionality - now opens modal
            document.getElementById('add-artwork-btn').addEventListener('click', function() {
                if (isLoggedIn) {
                    openUploadModal();
                } else {
                    redirectAfterLogin = window.location.href;
                    showModal('login');
                }
            });

            // Close upload modal button
            document.getElementById('closeUploadModal')?.addEventListener('click', closeUploadModal);

            // Close comment modal
            document.querySelector('.comment-modal-close').addEventListener('click', closeCommentModal);
        });

        function initApp() {
            initEventListeners();
            renderArtworks();
            initUploadModal();
        }

        function initEventListeners() {
            document.addEventListener('click', function(e) {
                // Like button functionality
                if (e.target.closest('.like-btn')) {
                    if (!isLoggedIn) {
                        redirectAfterLogin = window.location.href;
                        showModal('login');
                        return;
                    }

                    const btn = e.target.closest('.like-btn');
                    const feedContainer = btn.closest('.feed-container');
                    const artworkId = parseInt(feedContainer.dataset.artworkId);

                    fetch('../like_post.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                post_id: artworkId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const artwork = posts.find(a => a.id === artworkId);
                                if (artwork) {
                                    artwork.is_liked = data.is_liked;
                                    artwork.likes = data.like_count;

                                    const icon = btn.querySelector('i');
                                    const likesElement = feedContainer.querySelector('.feed-likes');

                                    if (artwork.is_liked) {
                                        icon.classList.remove('far');
                                        icon.classList.add('fas');
                                        btn.classList.add('liked');
                                    } else {
                                        icon.classList.remove('fas');
                                        icon.classList.add('far');
                                        btn.classList.remove('liked');
                                    }

                                    likesElement.textContent = artwork.likes.toLocaleString() + ' likes';
                                    showToast(artwork.is_liked ? 'Post liked' : 'Post unliked');
                                }
                            } else {
                                showToast(data.message || 'Error liking post');
                            }
                        })
                        .catch(() => showToast('Network error'));
                }

                // Bookmark button functionality
                if (e.target.closest('.bookmark-btn')) {
                    if (!isLoggedIn) {
                        redirectAfterLogin = window.location.href;
                        showModal('login');
                        return;
                    }

                    const btn = e.target.closest('.bookmark-btn');
                    const feedContainer = btn.closest('.feed-container');
                    const artworkId = parseInt(feedContainer.dataset.artworkId);
                    const artwork = posts.find(a => a.id === artworkId);

                    if (artwork) {
                        artwork.isBookmarked = !artwork.isBookmarked;

                        const icon = btn.querySelector('i');
                        if (artwork.isBookmarked) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            btn.classList.add('bookmarked');
                            showToast('Added to your favorites');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            btn.classList.remove('bookmarked');
                            showToast('Removed from your favorites');
                        }
                    }
                }

                // Comment button functionality
                if (e.target.closest('.fa-comment') || e.target.closest('.feed-comments')) {
                    const feedContainer = e.target.closest('.feed-container');
                    const artworkId = parseInt(feedContainer.dataset.artworkId);
                    openCommentModal(artworkId);
                }

                // Artist profile functionality
                if (e.target.closest('.feed-username') || e.target.closest('.feed-avatar')) {
                    const username = e.target.closest('.feed-container').querySelector('.feed-username')
                        .textContent;
                    openArtistProfile(username);
                }
            });

            function openCommentModal(postId) {
                if (!isLoggedIn) {
                    redirectAfterLogin = window.location.href;
                    showModal('login');
                    return;
                }

                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
                document.getElementById('overlayBackdrop').classList.add('active');
                const commentModal = document.getElementById('commentModal');
                commentModal.dataset.postId = postId;

                // Set the image source based on the post ID
                const artwork = posts.find(a => a.id === parseInt(postId));
                if (artwork) {
                    const commentImage = document.getElementById('comment-image');
                    commentImage.src = `../${artwork.image_path}`;
                    commentImage.alt = artwork.title;
                }

                setTimeout(() => {
                    commentModal.classList.add('active');
                }, 10);
            }

            // Comment form submission
            document.querySelector('.comment-submit')?.addEventListener('click', function() {
                if (!isLoggedIn) {
                    redirectAfterLogin = window.location.href;
                    showModal('login');
                    return;
                }

                const commentInput = document.querySelector('.comment-input');
                const commentText = commentInput.value.trim();
                const postId = document.querySelector('.comment-modal').dataset.postId;

                if (commentText && postId) {
                    fetch('../add_comment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                post_id: postId,
                                comment_text: commentText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const commentsList = document.querySelector('.comments-list');
                                const newComment = document.createElement('div');
                                newComment.className = 'comment-item';
                                const createdAt = new Date(data.comment.created_at);
                                const now = new Date();
                                const hoursDiff = Math.round((now - createdAt) / (1000 * 60 * 60));
                                const timeText = hoursDiff <= 24 ? `${hoursDiff} hours ago` : createdAt
                                    .toLocaleDateString('en-US', {
                                        month: 'short',
                                        day: 'numeric',
                                        year: 'numeric'
                                    });

                                newComment.innerHTML = `
                        <img src="https://via.placeholder.com/40" class="comment-avatar" alt="User">
                        <div class="comment-content">
                            <div class="comment-user">${data.comment.username}</div>
                            <div class="comment-text">${data.comment.comment_text}</div>
                            <div class="comment-time">${timeText}</div>
                        </div>
                        ${data.comment.username === '<?php echo $username; ?>' ? '<button class="comment-delete"><i class="fas fa-trash"></i></button>' : ''}
                    `;

                                commentsList.prepend(newComment);
                                commentInput.value = '';
                                showToast('Comment added');

                                // Update comment count
                                const artwork = posts.find(a => a.id === parseInt(postId));
                                if (artwork) {
                                    artwork.comments = (artwork.comments || 0) + 1;
                                    const feedContainer = document.querySelector(
                                        `.feed-container[data-artwork-id="${postId}"]`);
                                    if (feedContainer) {
                                        feedContainer.querySelector('.feed-comments').textContent =
                                            `View all ${artwork.comments} comments`;
                                    }
                                }

                                // Re-attach delete event
                                newComment.querySelector('.comment-delete')?.addEventListener('click',
                                    function() {
                                        newComment.remove();
                                        updateCommentCount(postId, -1);
                                    });
                            } else {
                                showToast(data.message || 'Error adding comment');
                            }
                        })
                        .catch(() => showToast('Network error'));
                }
            });

            // Search functionality
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');

            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }

        function initUploadModal() {
            // Image preview functionality
            const fileInput = document.getElementById('post-image');
            const previewArea = document.getElementById('preview-area');
            const imagePreview = document.getElementById('image-preview');

            previewArea?.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput?.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size exceeds 5MB limit');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        previewArea.querySelector('p').style.display = 'none';
                        previewArea.querySelector('.upload-icon').style.display = 'none';
                        previewArea.querySelector('.preview-text').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Show/hide price field
            const saleOption = document.getElementById('sale-option');
            const priceField = document.getElementById('price-field');
            saleOption?.addEventListener('change', function() {
                if (this.value === 'product') {
                    priceField.classList.add('active');
                    document.getElementById('price').setAttribute('required', '');
                } else {
                    priceField.classList.remove('active');
                    document.getElementById('price').removeAttribute('required');
                }
            });

            // Form submission with AJAX
            const form = document.getElementById('uploadForm');
            const successModal = document.getElementById('uploadSuccessModal');
            const successButton = document.getElementById('uploadSuccessButton');

            form?.addEventListener('submit', function(e) {
                e.preventDefault();

                if (saleOption.value === 'product' && !document.getElementById('price').value) {
                    alert('Please enter a price for your artwork');
                    return;
                }
                if (!fileInput.files.length) {
                    alert('Please upload an image of your artwork');
                    return;
                }

                const formData = new FormData(form);
                fetch('../upload.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                throw new Error(
                                    'Upload script not found. Ensure upload.php is in the correct directory.'
                                );
                            }
                            throw new Error('HTTP error ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            closeUploadModal();
                            successModal.classList.add('active');
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);
                        } else {
                            alert('Upload failed: ' + (data.errors || ['Unknown error']).join(', '));
                        }
                    })
                    .catch(error => {
                        alert('An error occurred: ' + error.message);
                    });
            });

            successButton.addEventListener('click', function() {
                successModal.classList.remove('active');
                window.location.reload();
            });
        }

        function openUploadModal() {
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            document.getElementById('overlayBackdrop').classList.add('active');
            setTimeout(() => {
                document.getElementById('uploadModal').classList.add('active');
            }, 10);
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
            setTimeout(() => {
                document.getElementById('overlayBackdrop').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';
            }, 400);
        }

        function renderArtworks() {
            const artworkGrid = document.getElementById('artwork-grid');
            artworkGrid.innerHTML = '';

            if (posts.length === 0) {
                artworkGrid.innerHTML = '<div class="error">No posts available.</div>';
                return;
            }

            posts.forEach(artwork => {
                const createdAt = new Date(artwork.created_at);
                const now = new Date();
                const hoursDiff = Math.round((now - createdAt) / (1000 * 60 * 60));
                const timestamp = hoursDiff <= 24 ? `${hoursDiff} hours ago` : createdAt.toLocaleDateString(
                    'en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });

                const artworkElement = document.createElement('div');
                artworkElement.className = 'feed-container';
                artworkElement.dataset.artworkId = artwork.id;

                artworkElement.innerHTML = `
            <div class="feed-header">
                <img src="${artwork.profile_picture}" class="feed-avatar" alt="User">
                <div class="feed-user-info">
                    <div class="feed-username">${artwork.username || 'Anonymous'}</div>
                    <div class="feed-location">${artwork.category || 'Art'}</div>
                </div>
                <button class="follow-btn">Follow</button>
            </div>
            <div class="feed-image-container">
                <img src="../${artwork.image_path}" class="feed-image" alt="${artwork.title}">
            </div>
            <div class="feed-actions">
                <div class="feed-action-left">
                    <button class="feed-action-btn like-btn ${artwork.is_liked ? 'liked' : ''}">
                        <i class="${artwork.is_liked ? 'fas' : 'far'} fa-heart"></i>
                    </button>
                    <button class="feed-action-btn">
                        <i class="far fa-comment"></i>
                    </button>
                </div>
                <button class="feed-action-btn bookmark-btn ${artwork.isBookmarked ? 'bookmarked' : ''}">
                    <i class="${artwork.isBookmarked ? 'fas' : 'far'} fa-bookmark"></i>
                </button>
            </div>
            <div class="feed-likes">${(artwork.likes || 0).toLocaleString()} likes</div>
            <div class="feed-caption">
                <span class="feed-caption-username">${artwork.username || 'Anonymous'}</span>
                <span>${artwork.description}</span>
            </div>
            
            ${artwork.tags ? `<div class="feed-caption post-tags">${artwork.tags}</div>` : ''}
            <div class="feed-comments">View all ${(artwork.comments || 0)} comments</div>
            <div class="feed-time">${timestamp}</div>
            <div class="feed-comment-box">
                <input type="text" class="feed-comment-input" placeholder="Add a comment...">
                <button class="feed-comment-post">Post</button>
            </div>
        `;

                artworkGrid.appendChild(artworkElement);
            });
        }

        function performSearch() {
            const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
            if (searchTerm) {
                const filteredPosts = posts.filter(post =>
                    post.title.toLowerCase().includes(searchTerm) ||
                    post.username.toLowerCase().includes(searchTerm) ||
                    post.description.toLowerCase().includes(searchTerm) ||
                    (post.tags && post.tags.toLowerCase().includes(searchTerm))
                );

                posts.length = 0;
                posts.push(...filteredPosts);
                renderArtworks();

                showToast(filteredPosts.length > 0 ? `Found ${filteredPosts.length} results for "${searchTerm}"` :
                    'No results found');
                document.getElementById('search-input').value = '';
            }
        }

        function openArtistProfile(username) {
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            document.getElementById('overlayBackdrop').classList.add('active');
            setTimeout(() => {
                document.getElementById('artistProfileOverlay').classList.add('active');
            }, 10);
            document.querySelector('.artist-username').textContent = username;
        }

        function closeArtistProfile() {
            document.getElementById('artistProfileOverlay').classList.remove('active');
            setTimeout(() => {
                document.getElementById('overlayBackdrop').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';
            }, 400);
        }

        function openCommentModal(postId) {
            if (!isLoggedIn) {
                redirectAfterLogin = window.location.href;
                showModal('login');
                return;
            }

            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            document.getElementById('overlayBackdrop').classList.add('active');
            const commentModal = document.getElementById('commentModal');
            commentModal.dataset.postId = postId;

            // Set the image source based on the post ID
            const artwork = posts.find(a => a.id === parseInt(postId));
            if (artwork) {
                const commentImage = document.getElementById('comment-image');
                commentImage.src = 进化
                commentImage.alt = artwork.title;
            }

            setTimeout(() => {
                commentModal.classList.add('active');
            }, 10);
        }

        function closeCommentModal() {
            document.getElementById('commentModal').classList.remove('active');
            setTimeout(() => {
                document.getElementById('overlayBackdrop').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';
            }, 400);
        }

        function openLikesOverlay() {
            if (!isLoggedIn) {
                redirectAfterLogin = window.location.href;
                showModal('login');
                return;
            }

            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            document.getElementById('overlayBackdrop').classList.add('active');
            setTimeout(() => {
                document.getElementById('likesOverlay').classList.add('active');
            }, 10);
        }

        function closeLikesOverlay() {
            document.getElementById('likesOverlay').classList.remove('active');
            setTimeout(() => {
                document.getElementById('overlayBackdrop').classList.remove('active');
                document.body.style.overflow = 'auto';
                document.documentElement.style.overflow = 'auto';
            }, 400);
        }

        function updateCommentCount(postId, delta) {
            const artwork = posts.find(a => a.id === parseInt(postId));
            if (artwork) {
                artwork.comments = (artwork.comments || 0) + delta;
                const feedContainer = document.querySelector(`.feed-container[data-artwork-id="${postId}"]`);
                if (feedContainer) {
                    feedContainer.querySelector('.feed-comments').textContent = `View all ${artwork.comments} comments`;
                }
            }
        }

        function logoutUser() {
            fetch('../logout.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Logged out successfully');
                        setTimeout(() => {
                            window.location.href = '../index.php';
                        }, 1000);
                    } else {
                        showToast(data.message || 'Logout failed');
                    }
                })
                .catch(() => {
                    showToast('Network error during logout');
                });
        }

        document.getElementById('overlayBackdrop').addEventListener('click', function() {
            if (document.getElementById('artistProfileOverlay').classList.contains('active')) {
                closeArtistProfile();
            }
            if (document.getElementById('commentModal').classList.contains('active')) {
                closeCommentModal();
            }
            if (document.getElementById('likesOverlay').classList.contains('active')) {
                closeLikesOverlay();
            }
            if (document.getElementById('uploadModal').classList.contains('active')) {
                closeUploadModal();
            }
        });

        document.querySelectorAll('.close-overlay-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const overlay = this.closest('.artist-profile-overlay, .likes-overlay');
                if (overlay.classList.contains('artist-profile-overlay')) {
                    closeArtistProfile();
                } else if (overlay.classList.contains('likes-overlay')) {
                    closeLikesOverlay();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('artistProfileOverlay').classList.contains('active')) {
                    closeArtistProfile();
                } else if (document.getElementById('commentModal').classList.contains('active')) {
                    closeCommentModal();
                } else if (document.getElementById('likesOverlay').classList.contains('active')) {
                    closeLikesOverlay();
                } else if (document.getElementById('uploadModal').classList.contains('active')) {
                    closeUploadModal();
                }
            }
        });

        document.querySelectorAll('.artist-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.artist-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove(
                    'active'));
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });

        document.querySelector('.artist-header .follow-btn').addEventListener('click', function() {
            this.textContent = this.textContent === 'Follow' ? 'Following' : 'Follow';
            this.classList.toggle('following');
        });

        document.querySelectorAll('.like-follow-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.textContent = this.textContent === 'Follow' ? 'Following' : 'Follow';
                this.classList.toggle('following');
            });
        });

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        function showModal(type) {
            showToast(`Please login to ${type === 'login' ? 'continue' : type}`);
        }
    </script>
</body>

</html>