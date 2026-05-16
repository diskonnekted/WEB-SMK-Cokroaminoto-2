<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'smkcokro2bnasch_data';

// Load WordPress Compatibility Shim
require_once 'wp_compat.php';

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset("utf8mb4");

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
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $time = strtotime($timestamp);
        $date = date('d', $time);
        $month = $months[(int)date('m', $time)];
        $year = date('Y', $time);
        return "$date $month $year";
    }
}
?>