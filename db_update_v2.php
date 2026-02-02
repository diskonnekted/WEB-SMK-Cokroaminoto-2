<?php
require_once 'config.php';

// 1. Check/Add 'image' column to news table
$check_image = $conn->query("SHOW COLUMNS FROM news LIKE 'image'");
if ($check_image->num_rows == 0) {
    $conn->query("ALTER TABLE news ADD COLUMN image VARCHAR(255) AFTER category");
    echo "Column 'image' added to news table.<br>";
} else {
    echo "Column 'image' already exists in news table.<br>";
}

// 2. Add 'youtube_url' column to news table
$check_yt = $conn->query("SHOW COLUMNS FROM news LIKE 'youtube_url'");
if ($check_yt->num_rows == 0) {
    $conn->query("ALTER TABLE news ADD COLUMN youtube_url VARCHAR(255) AFTER content");
    echo "Column 'youtube_url' added to news table.<br>";
} else {
    echo "Column 'youtube_url' already exists in news table.<br>";
}

// 3. Create news_gallery table
// Explicitly setting Engine and Charset to match typical defaults
$sql = "CREATE TABLE IF NOT EXISTS news_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT(6) UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_news_gallery FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($sql) === TRUE) {
    echo "Table news_gallery created successfully.<br>";
} else {
    echo "Error creating table news_gallery: " . $conn->error . "<br>";
}

echo "Database update completed.";
?>