<?php
require_once 'config.php';

// Create Pages Table
$sql = "CREATE TABLE IF NOT EXISTS pages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table pages created successfully<br>";
} else {
    echo "Error creating table pages: " . $conn->error . "<br>";
}

// Create Menus Table
$sql = "CREATE TABLE IF NOT EXISTS menus (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    sort_order INT(3) DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    echo "Table menus created successfully<br>";
} else {
    echo "Error creating table menus: " . $conn->error . "<br>";
}

// Insert Default Menus if empty
$check = $conn->query("SELECT id FROM menus");
if ($check->num_rows == 0) {
    $defaults = [
        ['BERANDA', 'index.php', 1],
        ['PROFIL', '#', 2],
        ['KOMPETENSI KEAHLIAN', '#', 3],
        ['BERITA SEKOLAH', '#', 4],
        ['PRESTASI', '#', 5],
        ['EKSTRAKURIKULER', '#', 6],
        ['GALERI', '#', 7],
        ['ALUMNI', '#', 8]
    ];
    
    foreach ($defaults as $menu) {
        $label = $menu[0];
        $url = $menu[1];
        $order = $menu[2];
        $conn->query("INSERT INTO menus (label, url, sort_order) VALUES ('$label', '$url', $order)");
    }
    echo "Default menus inserted<br>";
}

echo "Migration Complete! <a href='index.php'>Back to Home</a>";
?>