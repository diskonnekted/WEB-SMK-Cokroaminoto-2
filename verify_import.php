<?php
require_once 'config.php';

// Increase memory limit for large XML files
ini_set('memory_limit', '256M');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Import Berita</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .success { color: green; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Verifikasi Import Berita</h1>";

// 1. Get count from Database
$db_count = 0;
$result = $conn->query("SELECT COUNT(*) as count FROM news");
if ($result) {
    $row = $result->fetch_assoc();
    $db_count = $row['count'];
}

// 2. Get count from XML
$xml_file = 'smkc2.xml';
$xml_count = 0;
$xml_published_posts = 0;
$xml_other_posts = 0;

if (file_exists($xml_file)) {
    // Use simple string matching to avoid XML parsing errors with large files or namespaces
    // This is a rough estimation but usually accurate enough for verification
    $content = file_get_contents($xml_file);
    
    // Split by <item>
    $items = explode('<item>', $content);
    
    // Skip the first part (header)
    array_shift($items);
    
    foreach ($items as $item) {
        // Check if it's a post
        if (strpos($item, '<wp:post_type>post</wp:post_type>') !== false) {
            // Check status
            if (strpos($item, '<wp:status>publish</wp:status>') !== false) {
                $xml_published_posts++;
            } else {
                $xml_other_posts++;
            }
        }
    }
} else {
    echo "<p class='error'>File XML ($xml_file) tidak ditemukan!</p>";
}

// 3. Compare and Report
echo "<h2>Ringkasan Data</h2>";
echo "<ul>";
echo "<li>Total Berita (Post) di XML (Status: Publish): <strong>$xml_published_posts</strong></li>";
echo "<li>Total Berita di Database (Tabel 'news'): <strong>$db_count</strong></li>";

if ($db_count >= $xml_published_posts) {
    echo "<li class='success'>Status: Data berita di database SUDAH LENGKAP (Jumlah sama atau lebih banyak).</li>";
} elseif ($db_count > 0) {
    echo "<li class='warning'>Status: Sebagian data masuk ($db_count dari $xml_published_posts). Ada selisih " . ($xml_published_posts - $db_count) . " item.</li>";
} else {
    echo "<li class='error'>Status: Belum ada berita yang masuk ke database.</li>";
}
echo "</ul>";

if ($xml_other_posts > 0) {
    echo "<p><small>Catatan: Ada $xml_other_posts item di XML yang statusnya bukan 'publish' (draft/trash), yang mungkin tidak diimport.</small></p>";
}

// 4. Show sample data
echo "<h2>5 Berita Terakhir di Database</h2>";
$latest = $conn->query("SELECT id, title, created_at, category FROM news ORDER BY created_at DESC LIMIT 5");

if ($latest->num_rows > 0) {
    echo "<table>";
    echo "<thead><tr><th>ID</th><th>Judul</th><th>Tanggal</th><th>Kategori</th></tr></thead>";
    echo "<tbody>";
    while ($row = $latest->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Tidak ada data berita.</p>";
}

echo "</body></html>";
?>