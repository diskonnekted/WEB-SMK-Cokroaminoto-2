<?php
require 'config.php';

// 1. Define Dummy Pages
$dummy_pages = [
    [
        'title' => 'Profil Sekolah',
        'slug' => 'profil',
        'content' => '<h2>Sejarah Singkat</h2><p>SMK Cokroaminoto 2 Banjarnegara didirikan pada tahun ... dengan tujuan mencetak lulusan yang siap kerja dan berakhlak mulia.</p><h2>Visi</h2><p>Menjadi SMK unggulan yang berlandaskan IMTAQ dan IPTEK.</p><h2>Misi</h2><ul><li>Melaksanakan pembelajaran yang efektif.</li><li>Mengembangkan potensi siswa.</li></ul>'
    ],
    [
        'title' => 'Kompetensi Keahlian',
        'slug' => 'kompetensi-keahlian',
        'content' => '<h2>Program Keahlian</h2><p>SMK Cokroaminoto 2 Banjarnegara memiliki beberapa kompetensi keahlian unggulan:</p><ul><li><strong>Teknik Komputer dan Jaringan (TKJ)</strong>: Mempelajari jaringan komputer, administrasi server, dan troubleshooting.</li><li><strong>Akuntansi dan Keuangan Lembaga (AKL)</strong>: Mempelajari pengelolaan keuangan dan akuntansi perusahaan.</li><li><strong>Bisnis Daring dan Pemasaran (BDP)</strong>: Mempelajari strategi pemasaran digital dan bisnis retail.</li></ul>'
    ],
    [
        'title' => 'Prestasi Siswa',
        'slug' => 'prestasi',
        'content' => '<h2>Daftar Prestasi</h2><p>Siswa-siswi SMK Cokroaminoto 2 Banjarnegara telah mengukir banyak prestasi, di antaranya:</p><ul><li>Juara 1 LKS Tingkat Kabupaten (2024)</li><li>Juara 2 Lomba Debat Bahasa Inggris (2023)</li><li>Juara Harapan 1 Pencak Silat Popda (2023)</li></ul>'
    ],
    [
        'title' => 'Ekstrakurikuler',
        'slug' => 'ekstrakurikuler',
        'content' => '<h2>Kegiatan Ekstrakurikuler</h2><p>Untuk mengembangkan bakat dan minat siswa, sekolah menyediakan berbagai kegiatan ekstrakurikuler:</p><ul><li>Pramuka</li><li>PMR (Palang Merah Remaja)</li><li>Paskibra</li><li>Olahraga (Futsal, Voli, Basket)</li><li>Rohis</li><li>Seni Tari dan Musik</li></ul>'
    ],
    [
        'title' => 'Alumni',
        'slug' => 'alumni',
        'content' => '<h2>Jejak Alumni</h2><p>Alumni SMK Cokroaminoto 2 Banjarnegara telah tersebar di berbagai instansi pemerintah, perusahaan swasta, maupun berwirausaha.</p><h3>Testimoni</h3><blockquote>"Sekolah di SMK Cokroaminoto 2 memberikan saya bekal keterampilan yang sangat berguna di dunia kerja." - Budi, Alumni 2020</blockquote>'
    ]
];

// 2. Insert Pages
echo "Creating dummy pages...<br>";
foreach ($dummy_pages as $page) {
    $slug = $page['slug'];
    $title = $conn->real_escape_string($page['title']);
    $content = $conn->real_escape_string($page['content']);
    
    // Check if exists
    $check = $conn->query("SELECT id FROM pages WHERE slug = '$slug'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO pages (title, slug, content) VALUES ('$title', '$slug', '$content')";
        if ($conn->query($sql)) {
            echo "Page created: $title ($slug)<br>";
        } else {
            echo "Error creating page $slug: " . $conn->error . "<br>";
        }
    } else {
        echo "Page already exists: $title ($slug)<br>";
    }
}

// 3. Update Menus
echo "<br>Updating menus...<br>";

$menu_mapping = [
    'PROFIL' => 'page.php?slug=profil',
    'KOMPETENSI KEAHLIAN' => 'page.php?slug=kompetensi-keahlian',
    'BERITA SEKOLAH' => 'index.php', // Or create a dedicated news archive page
    'PRESTASI' => 'page.php?slug=prestasi',
    'EKSTRAKURIKULER' => 'page.php?slug=ekstrakurikuler',
    'GALERI' => 'gallery.php',
    'ALUMNI' => 'page.php?slug=alumni'
];

foreach ($menu_mapping as $label => $url) {
    $label_esc = $conn->real_escape_string($label);
    $url_esc = $conn->real_escape_string($url);
    
    // Update menu URL based on label
    $sql = "UPDATE menus SET url = '$url_esc' WHERE label = '$label_esc'";
    if ($conn->query($sql)) {
        if ($conn->affected_rows > 0) {
            echo "Menu updated: $label -> $url<br>";
        } else {
            echo "Menu unchanged (already correct or not found): $label<br>";
        }
    } else {
        echo "Error updating menu $label: " . $conn->error . "<br>";
    }
}

echo "<br>Setup Pages Completed!";
?>
