<?php
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS smkc2_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("smkc2_db");

// Create Users Table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully<br>";
} else {
    echo "Error creating table users: " . $conn->error . "<br>";
}

// Insert Default Admin (password: admin123)
$pass = password_hash("admin123", PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password) SELECT * FROM (SELECT 'admin', '$pass') AS tmp WHERE NOT EXISTS (
    SELECT username FROM users WHERE username = 'admin'
) LIMIT 1";

if ($conn->query($sql) === TRUE) {
    echo "Default admin user created (user: admin, pass: admin123)<br>";
} else {
    echo "Error creating default admin: " . $conn->error . "<br>";
}

// Create News Table
$sql = "CREATE TABLE IF NOT EXISTS news (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    image VARCHAR(255),
    content TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table news created successfully<br>";
} else {
    echo "Error creating table news: " . $conn->error . "<br>";
}

// Create Settings Table
$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table settings created successfully<br>";
} else {
    echo "Error creating table settings: " . $conn->error . "<br>";
}

// Insert Default Settings
$settings = [
    'school_name' => 'SMK COKROAMINOTO 2',
    'school_sub_name' => 'BANJARNEGARA',
    'address' => 'Jl. Letjend Suprapto No. 123, Banjarnegara, Jawa Tengah',
    'phone' => '(0286) 123456',
    'email' => 'info@smkcokroaminoto2.sch.id',
    'kepsek_name' => 'Drs. H. Nama Kepsek',
    'kepsek_message' => 'Selamat datang di website resmi SMK Cokroaminoto 2 Banjarnegara. Kami berkomitmen mencetak generasi unggul.',
    'hero_title' => 'SMK Cokroaminoto 2 Banjarnegara Siap Cetak Lulusan Kompeten di Era Digital',
    'hero_image' => 'images/placeholder.jpg'
];

foreach ($settings as $key => $value) {
    $value = $conn->real_escape_string($value);
    $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value='$value'";
    $conn->query($sql);
}
echo "Default settings inserted<br>";

// Insert Default News
$news_data = [
    [
        "Rapat Kerja Tahun Ajaran Baru 2025/2026 Fokus pada Peningkatan Mutu",
        "rapat-kerja-2025",
        "Berita Sekolah",
        "images/placeholder.jpg",
        "Dalam rangka mempersiapkan tahun ajaran baru, segenap dewan guru dan staf mengadakan rapat kerja untuk membahas strategi peningkatan mutu pendidikan. Rapat ini dihadiri oleh seluruh elemen sekolah.",
        0
    ],
    [
        "Pelantikan Bantara Gugus Depan SMK Cokroaminoto 2",
        "pelantikan-bantara",
        "Ekstrakurikuler",
        "images/placeholder.jpg",
        "Kegiatan perkemahan sabtu minggu (Persami) dalam rangka pelantikan Bantara berjalan dengan khidmat. Siswa-siswi menunjukkan dedikasi dan semangat kepramukaan yang tinggi.",
        0
    ],
    [
        "Peremajaan Laboratorium Komputer untuk Menunjang Pembelajaran TKJ",
        "peremajaan-lab-komputer",
        "Fasilitas",
        "images/placeholder.jpg",
        "Sekolah terus berkomitmen meningkatkan fasilitas. Kali ini laboratorium komputer mendapat upgrade spesifikasi PC terbaru untuk mendukung praktik siswa jurusan TKJ.",
        0
    ],
    [
        "Kunjungan Industri ke PT. Telkom Indonesia",  
        "kunjungan-industri-telkom",
        "Kunjungan Industri",
        "images/placeholder.jpg",
        "Siswa kelas XI melakukan kunjungan industri untuk melihat langsung dunia kerja di bidang telekomunikasi.",
        1
    ],
    [
        "Siswa Meraih Juara 1 LKS Tingkat Kabupaten",  
        "juara-lks-kabupaten",
        "Prestasi",
        "images/placeholder.jpg",
        "Prestasi membanggakan kembali diraih oleh siswa SMK Cokroaminoto 2 dalam ajang Lomba Kompetensi Siswa (LKS) tingkat kabupaten.",
        1
    ]
];

foreach ($news_data as $news) {
    $title = $conn->real_escape_string($news[0]);
    $slug = $conn->real_escape_string($news[1]);
    $category = $conn->real_escape_string($news[2]);
    $image = $conn->real_escape_string($news[3]);
    $content = $conn->real_escape_string($news[4]);
    $is_featured = $news[5];
    
    // Check if exists
    $check = $conn->query("SELECT id FROM news WHERE slug = '$slug'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO news (title, slug, category, image, content, is_featured) VALUES ('$title', '$slug', '$category', '$image', '$content', $is_featured)";
        $conn->query($sql);
    }
}
echo "Default news inserted<br>";

echo "<br><b>Setup Complete!</b> <a href='index.php'>Go to Home</a>";
$conn->close();
?>