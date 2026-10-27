CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    suffix VARCHAR(10),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(50) UNIQUE,
    phone VARCHAR(30),
    municipality VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    bio TEXT,
    website VARCHAR(255),
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('paintings', 'pottery', 'sculpture', 'photography') NOT NULL,
    post_type ENUM('content', 'product') NOT NULL,
    price DECIMAL(10, 2) DEFAULT NULL,
    tags VARCHAR(255),
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (post_id, user_id)
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL,
    followed_id INT NOT NULL,
    follow_date DATE NOT NULL,
    follow_timestamp DATETIME NOT NULL,
    flagged BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (follower_id) REFERENCES users(id),
    FOREIGN KEY (followed_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS artists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    stage_name VARCHAR(100),
    art_style VARCHAR(100),
    portfolio_link VARCHAR(255),
    verified BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    post_id INT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Completed',
    payment_method VARCHAR(50) DEFAULT 'Credit Card',
    address TEXT,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(id)
);
