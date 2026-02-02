<?php
require_once 'config.php';

// Create news_gallery table with correct FK type
$sql = "CREATE TABLE IF NOT EXISTS news_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT(6) UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table news_gallery created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>