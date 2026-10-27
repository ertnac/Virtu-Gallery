# VirtuGallery 🎨

A modern, responsive Online Art Gallery & Marketplace web application built with PHP, Vanilla JavaScript, CSS3, and HTML5.

VirtuGallery allows creators and art enthusiasts to discover artworks, purchase masterpieces, explore artist rankings, interact via comments and likes, and manage their portfolio and curated exhibitions through an intuitive curator dashboard.

---

## 🚀 Deployment Options

VirtuGallery is packaged to run on modern cloud container platforms, traditional PHP/MySQL hosting, or serverless environments.

---

### Option 1: Render.com (Recommended — 100% Free & Persistent)

Render offers free web service hosting and natively builds the included `Dockerfile`:

1. Sign up or log into [Render.com](https://render.com).
2. Click **New +** > **Web Service**.
3. Connect your GitHub repository: `https://github.com/ertnac/Virtu-Gallery.git`.
4. Render will auto-detect the **`Dockerfile`**.
5. Set:
   - **Environment:** Docker
   - **Plan:** Free
6. *(Optional)* To attach a free MySQL database, create a database on Aiven, Supabase, or Railway, and add the `DATABASE_URL` environment variable. (Otherwise, it uses the built-in SQLite database automatically!).
7. Click **Create Web Service**. Your app is live in 2–3 minutes with automatic HTTPS.

---

### Option 2: Railway.app (Easiest — 1-Click GitHub + MySQL)

Railway natively supports GitHub repos and offers instant MySQL provisioning:

1. Go to [Railway.app](https://railway.app) and create a new project.
2. Select **Deploy from GitHub repo** and choose `Virtu-Gallery`.
3. In the project canvas, click **+ New** > **Database** > **MySQL**.
4. Railway automatically connects the database and provisions `DATABASE_URL`.
5. Go to your service settings, click **Generate Domain**, and your site is live!

---

### Option 3: Koyeb / Fly.io (Fast Global Containers)

Both platforms deploy containerized apps globally using the included `Dockerfile`:

```bash
# Fly.io deployment
fly launch
fly deploy
```

---

### Option 4: Traditional Shared Hosting (cPanel / Hostinger / InfinityFree)

If you have shared hosting with Apache and cPanel:

1. Upload the project files to your `public_html/` directory (via FTP, File Manager, or Git).
2. Create a MySQL database and user in your hosting control panel.
3. Import `virtugallery.sql` (or let the app auto-create tables on first load).
4. Set your database credentials in `config.local.php` or `db_connect.php`:
   - `MYSQL_HOST`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DATABASE`.
5. The included [`.htaccess`](.htaccess) automatically configures file security, index routing, and upload limits.

---

### Option 5: Local & VPS with Docker Compose

To run the complete stack (PHP 8.2 Apache + MySQL 8.0) on your local machine or VPS:

```bash
# Clone the repository
git clone https://github.com/ertnac/Virtu-Gallery.git
cd Virtu-Gallery

# Start services
docker compose up -d
```

Open your browser at `http://localhost:8000`.

---

### Option 6: Local PHP Built-in Server

```bash
# Windows batch launcher
run_server.bat

# Or manual command
php -S 127.0.0.1:8000 -c php.ini
```

---

## 📁 Project Architecture & Clean Organization

```text
VirtuGallery/
├── admin/                  # Curator Dashboard CMS
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
│   ├── index.php           # Front-controller & serverless router bridge
│   ├── login.php           # User authentication
│   ├── register.php        # Account registration with password hashing
│   ├── logout.php          # Session termination
│   ├── check_auth.php      # Live session & auth status check
│   ├── upload.php          # Media uploader (with serverless fallback)
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
├── db_connect.php          # Hybrid Database Layer (Cloud MySQL + SQLite fallback)
├── Dockerfile              # Production-grade Apache + PHP 8.2 container
├── docker-compose.yml      # Multi-container stack (Web + MySQL 8.0)
├── .htaccess               # Apache directory security & upload limits
└── run_server.bat          # 1-Click local development launcher
```

---

## 🛡️ Security & Best Practices

- **Zero Secret Leaks:** Credentials are managed via environment variables. No `.env` files are tracked in Git.
- **SQL Injection Prevention:** 100% of queries use PDO Prepared Statements.
- **Password Security:** Password hashing using PHP's native `PASSWORD_BCRYPT`.
- **XSS Mitigation:** All user inputs and rendered comments are escaped using `htmlspecialchars()`.

---

## 📄 License
This project is open-source and available under the [MIT License](LICENSE).
