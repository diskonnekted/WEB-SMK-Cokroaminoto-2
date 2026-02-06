<?php
require_once 'config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Update</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .success { color: green; border-left: 4px solid green; padding-left: 10px; margin-bottom: 10px; }
        .error { color: red; border-left: 4px solid red; padding-left: 10px; margin-bottom: 10px; }
        .info { color: blue; border-left: 4px solid blue; padding-left: 10px; margin-bottom: 10px; }
        code { background: #f4f4f4; padding: 2px 5px; }
    </style>
</head>
<body>
    <h1>Update Database Otomatis (Revisi)</h1>
    <p>Skrip ini akan memperbarui struktur database agar sesuai dengan aplikasi terbaru.</p>
    <hr>";

function run_query($conn, $sql, $description) {
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'><strong>BERHASIL:</strong> $description</div>";
    } else {
        // Ignore "Duplicate column name" error
        if (strpos($conn->error, "Duplicate column") !== false) {
             echo "<div class='info'><strong>INFO:</strong> $description (Kolom sudah ada)</div>";
        } elseif (strpos($conn->error, "already exists") !== false) {
             echo "<div class='info'><strong>INFO:</strong> $description (Tabel sudah ada)</div>";
        } else {
             echo "<div class='error'><strong>ERROR:</strong> $description <br><small>" . $conn->error . "</small></div>";
        }
    }
}

function check_column_exists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return ($result && $result->num_rows > 0);
}

// 1. Tabel Categories (Kategori Berita)
$sql = "CREATE TABLE IF NOT EXISTS categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
run_query($conn, $sql, "Membuat tabel 'categories'");

// 2. Tabel Alumni
$sql = "CREATE TABLE IF NOT EXISTS alumni (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    graduation_year YEAR NOT NULL,
    major VARCHAR(100) NOT NULL,
    current_activity VARCHAR(255) NOT NULL,
    testimony TEXT,
    image VARCHAR(255),
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
run_query($conn, $sql, "Membuat tabel 'alumni'");

// 3. Tabel Gallery
$sql = "CREATE TABLE IF NOT EXISTS gallery (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
run_query($conn, $sql, "Membuat tabel 'gallery'");

// 4. Tabel Menus (Perbaikan Schema)
echo "<div class='info'>Memeriksa Tabel Menus...</div>";

// Cek apakah tabel menus ada
$table_check = $conn->query("SHOW TABLES LIKE 'menus'");
if ($table_check->num_rows == 0) {
    // Buat tabel menus jika belum ada
    $sql = "CREATE TABLE menus (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        label VARCHAR(50) NOT NULL,
        url VARCHAR(255) NOT NULL,
        parent_id INT(11) DEFAULT NULL,
        sort_order INT(3) DEFAULT 0
    )";
    run_query($conn, $sql, "Membuat tabel 'menus'");
} else {
    echo "<div class='success'>Tabel 'menus' ditemukan. Memeriksa kolom...</div>";
    
    // Pastikan kolom yang benar ada (sesuai header_public.php: label, url, parent_id, sort_order)
    if (!check_column_exists($conn, 'menus', 'label')) {
        // Jika pakai 'title' (legacy), ubah ke 'label'
        if (check_column_exists($conn, 'menus', 'title')) {
            run_query($conn, "ALTER TABLE menus CHANGE title label VARCHAR(50) NOT NULL", "Mengubah kolom 'title' menjadi 'label'");
        } else {
            run_query($conn, "ALTER TABLE menus ADD COLUMN label VARCHAR(50) NOT NULL", "Menambahkan kolom 'label'");
        }
    }

    if (!check_column_exists($conn, 'menus', 'url')) {
        // Jika pakai 'link' (legacy), ubah ke 'url'
        if (check_column_exists($conn, 'menus', 'link')) {
            run_query($conn, "ALTER TABLE menus CHANGE link url VARCHAR(255) NOT NULL", "Mengubah kolom 'link' menjadi 'url'");
        } else {
            run_query($conn, "ALTER TABLE menus ADD COLUMN url VARCHAR(255) NOT NULL", "Menambahkan kolom 'url'");
        }
    }

    if (!check_column_exists($conn, 'menus', 'parent_id')) {
        run_query($conn, "ALTER TABLE menus ADD COLUMN parent_id INT(11) DEFAULT NULL", "Menambahkan kolom 'parent_id'");
    }
    
    if (!check_column_exists($conn, 'menus', 'sort_order')) {
        run_query($conn, "ALTER TABLE menus ADD COLUMN sort_order INT(3) DEFAULT 0", "Menambahkan kolom 'sort_order'");
    }
}

// Update Isi Menu
echo "<div class='info'>Memperbarui Item Menu...</div>";

// Menu Alumni
$check = $conn->query("SELECT id FROM menus WHERE url = 'alumni.php' OR url LIKE '%slug=alumni%'");
if ($check && $check->num_rows == 0) {
    $sql = "INSERT INTO menus (label, url, sort_order) VALUES ('ALUMNI', 'alumni.php', 8)";
    run_query($conn, $sql, "Menambahkan menu 'ALUMNI'");
} else {
    // Update link lama jika perlu
    $conn->query("UPDATE menus SET url = 'alumni.php', label = 'ALUMNI' WHERE url LIKE '%slug=alumni%'");
    echo "<div class='success'>Menu 'ALUMNI' sudah ada.</div>";
}

// Menu Kontak
$check = $conn->query("SELECT id FROM menus WHERE url = 'contact.php'");
if ($check && $check->num_rows == 0) {
    $sql = "INSERT INTO menus (label, url, sort_order) VALUES ('KONTAK KAMI', 'contact.php', 9)";
    run_query($conn, $sql, "Menambahkan menu 'KONTAK KAMI'");
} else {
    echo "<div class='success'>Menu 'KONTAK KAMI' sudah ada.</div>";
}

// 5. Update Tabel Users (Kolom username)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'username'");
if ($check->num_rows == 0) {
    $sql = "ALTER TABLE users ADD COLUMN username VARCHAR(50) NOT NULL AFTER id";
    run_query($conn, $sql, "Menambahkan kolom 'username' ke tabel users");
}

echo "<hr>
    <h3>Selesai!</h3>
    <p>Silakan hapus file <code>db_update.php</code> ini setelah database berhasil diupdate demi keamanan.</p>
    <a href='index.php'>Ke Halaman Utama</a> | <a href='admin/login.php'>Login Admin</a>
</body>
</html>";
?>