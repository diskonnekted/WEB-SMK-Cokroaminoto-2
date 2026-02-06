<?php
require_once 'config.php';

echo "<h2>Sinkronisasi Menu Navigasi</h2>";

// 1. Kosongkan tabel menu saat ini agar bersih
$truncate = "TRUNCATE TABLE menus";
if ($conn->query($truncate) === TRUE) {
    echo "<div style='color:green'>Tabel menu berhasil dikosongkan.</div>";
} else {
    echo "<div style='color:red'>Gagal mengosongkan tabel: " . $conn->error . "</div>";
}

// 2. Masukkan data menu yang sama persis dengan Localhost
// ID 15 saya beri label 'PPDB ONLINE' karena di data sumber kosong
$sql = "INSERT INTO menus (id, label, url, sort_order, parent_id) VALUES 
(1, 'BERANDA', 'index.php', 1, NULL),
(2, 'PROFIL', 'page.php?slug=profil', 2, NULL),
(6, 'EKSTRAKURIKULER', 'page.php?slug=ekstrakurikuler', 6, NULL),
(7, 'GALERI', 'gallery.php', 7, NULL),
(8, 'BKK & ALUMNI', 'alumni.php', 8, NULL),
(10, 'Test Submenu', 'page.php?slug=ppdb', 0, 2),
(12, 'Teknik Mesin', 'page.php?slug=teknik-mesin', 2, 16),
(13, 'Sepak Bola', 'page.php?slug=sepak-bola', 0, 6),
(15, 'PPDB ONLINE', 'https://ppdb.smkc2.com/', 9, NULL),
(16, 'Kompetensi Keahlian', 'page.php?slug=kompetensi-keahlian', 50, NULL),
(17, 'Multimedia (Desain Grafis, Animasi, Video Editing)', 'page.php?slug=multimedia', 1, 16),
(19, 'Teknik Pengelasan', 'page.php?slug=teknik-pengelasan', 3, 16),
(20, 'Teknik Elektronika', 'page.php?slug=teknik-elektronika', 4, 16),
(21, 'Teknik Otomotif', 'page.php?slug=teknik-otomotif', 5, 16),
(22, 'Teknik Ketenagalistrikan', 'page.php?slug=teknik-ketenagalistrikan', 6, 16)";

if ($conn->query($sql) === TRUE) {
    echo "<div style='color:green; font-weight:bold; margin-top:20px;'>SUKSES: Menu navigasi berhasil disinkronisasi!</div>";
    echo "<p>Sekarang menu di website Anda sudah sama persis dengan versi localhost.</p>";
    echo "<p>Silakan hapus file <code>db_sync_menu.php</code> ini.</p>";
    echo "<a href='index.php'>Lihat Hasil</a>";
} else {
    echo "<div style='color:red; margin-top:20px;'>ERROR: Gagal memasukkan data menu. <br>" . $conn->error . "</div>";
}
?>