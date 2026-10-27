# VirtuGallery 🎨

A modern, responsive, and serverless-ready Online Art Gallery & Marketplace web application built with PHP, Vanilla JavaScript, CSS3, and HTML5.

VirtuGallery allows creators and art enthusiasts to discover artworks, purchase masterpieces, explore artist rankings, interact via comments and likes, and manage their portfolio and curated exhibitions through an intuitive curator dashboard.

---

## 🚀 Features

- **Virtual Gallery & Discovery Feed:** Dynamic exploration of artworks with infinite-feel responsive grid layouts and immersive detail modals.
- **Interactive Social Features:** Real-time like counts, instant commenting engine, notifications hub, and artist following system.
- **Artist Rankings & Leaderboard:** Dynamic leaderboard sorting artists by followers, engagement, and trending activity.
- **Curator & Admin CMS (`/admin`):** Full-featured management suite for uploading artwork, managing artist profiles, tracking customer orders, and monitoring platform metrics.
- **E-Commerce & Orders:** Interactive cart and seamless checkout pipeline with customer order records.
- **Serverless & Cloud-Ready Architecture:** Designed to run seamlessly in both traditional server environments (Apache/Nginx/XAMPP) and modern serverless platforms like **Vercel** with auto-healing database fallbacks.

---

## 📁 Project Architecture & Clean Organization

The codebase has been refactored and organized into clean, modular domains:

```text
VirtuGallery/
├── admin/                  # Curator Dashboard CMS (Cleaned & Modular)
│   ├── index.php           # Admin Dashboard overview & statistics
│   ├── artworks.php        # Artwork catalog management
│   ├── artists.php         # Artist directory & profile manager
│   ├── followers.php       # Followers tracking & management
│   ├── orders.php          # Customer orders monitor
│   ├── notifications.php   # System & event notifications
│   ├── fetch_*.php         # Dashboard async data endpoints
│   └── save_*.php          # Dashboard mutation handlers
│
├── api/                    # Core Backend API Endpoints (JSON / REST)
│   ├── login.php           # User authentication
│   ├── register.php        # Account registration with password hashing
│   ├── logout.php          # Session termination
│   ├── check_auth.php      # Live session & auth status check
│   ├── upload.php          # Media uploader (with serverless base64 fallback)
│   ├── like_post.php       # Social like toggle
│   ├── add_comment.php     # Artwork comment submission
│   ├── get_comments.php    # Artwork comments fetcher
│   ├── get_rankings.php    # Artist leaderboard data
│   ├── get_notifications.php # User notifications fetcher
│   ├── toggle_follow.php   # Follow/unfollow artist handler
│   └── create_order.php    # Checkout order creation
│
├── Pages/                  # User-Facing Responsive Pages
│   ├── home.php            # Main gallery feed
│   ├── Marketplace.php     # Art marketplace & catalog
│   ├── UserProfilePage.php # User profile & bio
│   ├── settings.php        # Account & preference settings
│   ├── ranking.php         # Artist leaderboard page
│   ├── notifications.php   # Notifications drawer
│   ├── cart.html           # Interactive shopping cart
│   └── Checkouts.html      # Checkout & payment page
│
├── assets/                 # Static Assets (Images, Icons, Fonts)
│   ├── images/             # UI graphics, logo, banners
│   ├── icons/              # Navigation & interface icons
│   └── css/                # Global styles
│
├── uploads/                # Artworks and user avatar media
│   ├── artworks/           # Artwork image files
│   └── avatars/            # User profile pictures
│
├── shanaya/                # Legacy admin forwarders (100% backward compatible)
├── db_connect.php          # Hybrid Database Layer (Cloud MySQL + SQLite fallback)
├── index.php               # Landing page / authentication portal
├── vercel.json             # Vercel deployment configuration
├── package.json            # Deployment manifest for vercel-php runtime
└── run_server.bat          # 1-Click local development launcher
```

---

## ⚡ Deploying to Vercel

VirtuGallery includes native configuration for zero-config Vercel deployment using the `@vercel/php` runtime.

### Step 1: Import Project to Vercel
1. Log into your [Vercel Dashboard](https://vercel.com).
2. Click **Add New...** > **Project**.
3. Select your GitHub repository: `https://github.com/ertnac/Virtu-Gallery.git`.
4. Keep the Framework Preset as **Other** (Vercel will automatically detect `vercel.json`).

### Step 2: Configure Environment Variables (Optional for Cloud DB)
VirtuGallery features a self-healing database connector. By default, on Vercel without environment variables, it creates and auto-seeds an in-memory SQLite database at `/tmp/virtugallery.db`.

For production persistence, configure any free cloud MySQL database (e.g., PlanetScale, Supabase, Neon, Aiven, or Railway) by adding the following Environment Variables in your Vercel Project Settings:

| Variable | Description | Example |
| :--- | :--- | :--- |
| `DATABASE_URL` | Complete MySQL connection string | `mysql://user:pass@host:3306/virtugallery` |
| *or* `MYSQL_HOST` | Database host | `aws.connect.psdb.cloud` |
| `MYSQL_USER` | Database username | `admin` |
| `MYSQL_PASSWORD` | Database password | `••••••••` |
| `MYSQL_DATABASE` | Database name | `virtugallery` |
| `MYSQL_PORT` | Port (optional, defaults to 3306) | `3306` |

### Step 3: Deploy
Click **Deploy**. Vercel will build and serve the application globally with edge caching and serverless execution.

---

## 💻 Local Development Setup

### Prerequisites
- PHP 8.0 or higher with PDO extensions enabled (`pdo_sqlite` or `pdo_mysql`).

### Running Locally
You can start the built-in development server immediately using the provided batch launcher or command line:

```bash
# Windows
run_server.bat

# Or manual command
php -S 127.0.0.1:8000
```

Once started, open your browser and navigate to:
```
http://127.0.0.1:8000
```

---

## 🛡️ Security & Best Practices

- **Zero Secret Leaks:** No `.env` or credential files are checked into the repository.
- **SQL Injection Prevention:** 100% of queries use PDO Prepared Statements.
- **Password Security:** All passwords are hashed using PHP's native `PASSWORD_BCRYPT` with cost factoring.
- **XSS Mitigation:** Dynamic user inputs and rendered reviews/comments are escaped using `htmlspecialchars()`.
- **Read-Only Serverless Resilience:** Automatic write-directory detection seamlessly routes dynamic writes to `/tmp` on serverless environments to prevent runtime filesystem crashes.

---

## 📄 License
This project is open-source and available under the [MIT License](LICENSE).
