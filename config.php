<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'smkc2_db';

// Load WordPress Compatibility Shim
require_once 'wp_compat.php';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    // Check if error is unknown database, if so, we might need to run setup
    if ($conn->errno == 1049) {
        die("Database belum ada. Silakan jalankan <a href='setup.php'>setup.php</a> terlebih dahulu.");
    }
    die("Koneksi gagal: " . $conn->connect_error);
}

// Helper function for date
if (!function_exists('indo_date')) {
    function indo_date($timestamp) {
        setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252');
        $date = strftime("%d %B %Y", strtotime($timestamp));
        if ($date === false) {
            return date('d F Y', strtotime($timestamp));
        }
        return $date;
    }
}
?>