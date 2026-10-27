<?php
$host = '127.0.0.1';
$dbname = 'virtugallery';
$username = 'root';
$password = '';

$pdo = null;

// 1. Try MySQL first if available
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2
    ]);
} catch (Exception $e) {
    // 2. Fall back to local SQLite database seamlessly
    $dbPath = __DIR__ . DIRECTORY_SEPARATOR . 'virtugallery.db';
    $needInit = !file_exists($dbPath) || filesize($dbPath) === 0;

    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize schema if needed
    initSqliteDatabase($pdo, $needInit);
}

function initSqliteDatabase($pdo, $needInit = false) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            middle_name TEXT,
            last_name TEXT NOT NULL,
            suffix TEXT,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            username TEXT UNIQUE,
            phone TEXT,
            municipality TEXT NOT NULL,
            province TEXT NOT NULL,
            bio TEXT,
            website TEXT,
            profile_picture TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            description TEXT NOT NULL,
            category TEXT NOT NULL,
            post_type TEXT NOT NULL,
            price REAL DEFAULT NULL,
            tags TEXT,
            image_path TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS likes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(post_id, user_id)
        );

        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            comment_text TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS follows (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            follower_id INTEGER NOT NULL,
            followed_id INTEGER NOT NULL,
            follow_date DATE DEFAULT CURRENT_DATE,
            follow_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            flagged INTEGER DEFAULT 0,
            FOREIGN KEY (follower_id) REFERENCES users(id),
            FOREIGN KEY (followed_id) REFERENCES users(id)
        );

        CREATE TABLE IF NOT EXISTS artists (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL UNIQUE,
            stage_name TEXT,
            art_style TEXT,
            portfolio_link TEXT,
            verified INTEGER DEFAULT 0,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_id INTEGER NOT NULL,
            post_id INTEGER NOT NULL,
            date DATETIME DEFAULT CURRENT_TIMESTAMP,
            total REAL NOT NULL,
            status TEXT DEFAULT 'Completed',
            payment_method TEXT DEFAULT 'Credit Card',
            address TEXT,
            FOREIGN KEY (customer_id) REFERENCES users(id),
            FOREIGN KEY (post_id) REFERENCES posts(id)
        );
    ");

    // Check if users exist; if not, seed data
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $defaultPassword = password_hash('Password123!', PASSWORD_BCRYPT);
        
        $pdo->exec("
            INSERT INTO users (id, first_name, middle_name, last_name, suffix, email, password, username, phone, municipality, province, bio, website, profile_picture) VALUES
            (1, 'Cedrick', '', 'Albuero', '', 'jamesclarencecantre@gmail.com', '$defaultPassword', 'ertnac', '+63 912 345 6789', 'Alaminos', 'Laguna', 'Contemporary visual artist and painter based in Laguna.', 'https://virtugallery.art', 'Pages/profile_image/profile_cantre_68438a5c28574.jpg'),
            (2, 'Jane Xyrel', 'F', 'Ramos', '', 'ramosjanexyrel@gmail.com', '$defaultPassword', 'leryx', '+63 998 765 4321', 'Alaminos', 'Laguna', 'Digital artist and illustrator passionate about fantasy landscapes.', 'https://virtugallery.art', 'Pages/profile_image/profile_jeniper_684659615942f.jpg'),
            (3, 'Diana', '', 'Gucela', '', 'dianagucela@gmail.com', '$defaultPassword', 'diana', '+63 911 223 3445', 'San Pablo', 'Laguna', 'Sculptor and fine art creator specializing in organic materials.', 'https://virtugallery.art', 'Pages/profile_image/profile_diana123_683fa9d729ff6.jpg'),
            (4, 'Shanaya', '', 'Concibido', '', 'shanaya@virtugallery.com', '$defaultPassword', 'shaneyney', '+63 955 667 7889', 'San Pablo', 'Laguna', 'Gallery administrator and fine arts curator.', 'https://virtugallery.art', 'Pages/profile_image/profile_shaneyney_68464cc8b8d9f.jpg');

            INSERT INTO artists (id, user_id, stage_name, art_style, portfolio_link, verified) VALUES
            (1, 1, 'Cedrick Art', 'Oil & Acrylic on Canvas', 'https://virtugallery.art/ertnac', 1),
            (2, 2, 'Leryx Studio', 'Digital Illustration', 'https://virtugallery.art/leryx', 1),
            (3, 3, 'Diana Studio', 'Contemporary Sculptures', 'https://virtugallery.art/diana', 1);

            INSERT INTO posts (id, user_id, title, description, category, post_type, price, tags, image_path) VALUES
            (1, 1, 'Lawrence Expression', 'Expressionist acrylic painting on primed canvas.', 'paintings', 'content', NULL, 'expressionism, canvas', 'uploads/artwork_1_683afa7a5eeff.png'),
            (2, 1, 'Aderik Serenity', 'Contemporary abstract exploration of calmness and light.', 'paintings', 'content', NULL, 'modern, abstract', 'uploads/artwork_1_683f055d0b607.png'),
            (3, 1, 'Monalisa Reimagined', 'Renaissance aesthetic reimagined with bold palette knives.', 'paintings', 'product', 25000.00, 'classic, portrait', 'uploads/artwork_1_683f1187c6f7e.jpg'),
            (4, 3, 'Starry Twilight', 'Night sky captured in vibrant swirling brush strokes.', 'paintings', 'product', 18500.00, 'starry, night, sky', 'uploads/artwork_3_683f1afa3b1ae.jpg'),
            (5, 2, 'Medusa Gaze', 'Mythological study featuring vibrant earthen textures.', 'sculpture', 'product', 17500.00, 'mythology, sculpture', 'uploads/artwork_3_68438343461c4.jpg'),
            (6, 2, 'Mountain Peak Solitude', 'Highland scenic landscape photography captured at dawn.', 'photography', 'product', 14000.00, 'mountain, landscape, nature', 'uploads/artwork_3_6843823bdb475.jpg'),
            (7, 3, 'Forest Trail Awakening', 'Deep forest path wrapped in fresh morning mist.', 'paintings', 'product', 22000.00, 'forest, nature, path', 'uploads/artwork_3_684383c848806.jpg'),
            (8, 2, 'Abstract Visions', 'Geometric symmetry in warm earthy tones.', 'paintings', 'product', 19500.00, 'abstract, geometric', 'uploads/artwork_3_684381ca59dde.jpg'),
            (9, 3, 'City Lights Nocturne', 'Reflections of metropolitan neon on rainy pavement.', 'photography', 'product', 28000.00, 'urban, neon, city', 'uploads/artwork_3_68439f96c07df.jpg'),
            (10, 1, 'Sunset Silhouette', 'Golden hour sunset over calm ocean waves.', 'photography', 'product', 12000.00, 'sunset, golden hour', 'uploads/artwork_4_683f635f9d55e.jpg');

            INSERT INTO likes (post_id, user_id) VALUES
            (1, 1), (2, 1), (3, 1), (3, 2), (4, 1), (5, 2), (6, 3), (7, 1);

            INSERT INTO comments (post_id, user_id, comment_text) VALUES
            (3, 1, 'Breathtaking brushwork and lighting!'),
            (3, 2, 'Love the vibrant textures and colors.'),
            (4, 1, 'Reminds me of Van Gogh with a modern twist.'),
            (5, 3, 'The sculpture detail is remarkable.');

            INSERT INTO follows (follower_id, followed_id, follow_date, follow_timestamp, flagged) VALUES
            (1, 2, '2026-10-20', '2026-10-20 10:00:00', 0),
            (2, 1, '2026-10-21', '2026-10-21 11:30:00', 0),
            (3, 1, '2026-10-22', '2026-10-22 14:15:00', 0),
            (1, 3, '2026-10-23', '2026-10-23 09:45:00', 0);

            INSERT INTO orders (customer_id, post_id, date, total, status, payment_method, address) VALUES
            (2, 3, '2026-10-25 14:32:00', 25000.00, 'Completed', 'Credit Card', 'Alaminos, Laguna'),
            (3, 5, '2026-10-26 09:15:00', 17500.00, 'Processing', 'GCash', 'San Pablo, Laguna'),
            (1, 4, '2026-10-26 16:40:00', 18500.00, 'Delivered', 'Bank Transfer', 'Alaminos, Laguna');
        ");
    }
}
?>
