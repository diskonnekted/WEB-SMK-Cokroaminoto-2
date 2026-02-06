<?php
require_once 'config.php';

$slug = isset($_GET['slug']) ? $conn->real_escape_string($_GET['slug']) : '';

// Redirect old alumni page to new alumni.php
if ($slug == 'alumni') {
    header("Location: alumni.php");
    exit();
}

require_once 'header_public.php';

$page = null;

if ($slug) {
    $result = $conn->query("SELECT * FROM pages WHERE slug = '$slug'");
    if ($result->num_rows > 0) {
        $page = $result->fetch_assoc();
    }
}
?>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Page Content) -->
        <div class="main-column">
            <div class="card shadow-sm" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <?php if ($page): ?>
                    <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;"><?php echo $page['title']; ?></h1>
                    <div class="page-content" style="line-height: 1.8;">
                        <?php echo $page['content']; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <h3>Halaman Tidak Ditemukan</h3>
                        <p>Maaf, halaman yang Anda cari tidak tersedia atau telah dihapus.</p>
                        <a href="index.php" class="btn btn-success" style="display: inline-block; padding: 10px 20px; background: var(--nu-green); color: white; text-decoration: none; margin-top: 10px; border-radius: 4px;">Kembali ke Beranda</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php
            $category = null;
            $map = [
                'multimedia' => 'Multimedia',
                'teknik-mesin' => 'Teknik Mesin',
                'teknik-pengelasan' => 'Teknik Pengelasan',
                'teknik-elektronika' => 'Teknik Elektronika',
                'teknik-otomotif' => 'Teknik Otomotif',
                'teknik-ketenagalistrikan' => 'Teknik Ketenagalistrikan',
            ];
            if (isset($map[$slug])) {
                $category = $conn->real_escape_string($map[$slug]);
                $news_q = $conn->query("SELECT * FROM news WHERE category = '$category' ORDER BY created_at DESC");
                echo '<div class="section-title" style="margin-top: 20px;"><h2>Berita ' . strtoupper($category) . '</h2></div>';
                if ($news_q && $news_q->num_rows > 0) {
                    echo '<div class="news-list">';
                    while ($news = $news_q->fetch_assoc()) {
                        $img = $news['image'];
                        if (!filter_var($img, FILTER_VALIDATE_URL) && !empty($img)) {
                            $img = $img;
                        }
                        echo '<article class="news-item">';
                        echo '  <div class="news-thumb"><img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($news['title']) . '"></div>';
                        echo '  <div class="news-content">';
                        echo '      <div class="news-meta"><span>' . htmlspecialchars($news['category']) . '</span> • ' . indo_date($news['created_at']) . '</div>';
                        echo '      <h3 class="news-title"><a href="news_detail.php?id=' . intval($news['id']) . '">' . htmlspecialchars($news['title']) . '</a></h3>';
                        echo '      <p class="news-excerpt">' . substr(strip_tags($news['content']), 0, 150) . '...</p>';
                        echo '  </div>';
                        echo '</article>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info">Belum ada berita untuk kategori ini.</div>';
                }
            }

            // Form Alumni
            ?>
        </div>

        <!-- Right Column (Sidebar) -->
        <aside class="sidebar">
            
            <!-- Kepala Sekolah Widget -->
            <div class="sidebar-widget">
                <div style="text-align: center;">
                    <img src="<?php echo $settings['kepsek_image'] ?? 'images/placeholder.jpg'; ?>" alt="Kepala Sekolah" style="margin-bottom: 15px; width: 100%; height: auto; object-fit: cover;">
                    <h4 style="color: var(--nu-green);"><?php echo $settings['kepsek_name'] ?? 'Kepala Sekolah'; ?></h4>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 10px;">"<?php echo $settings['kepsek_message'] ?? 'Selamat Datang'; ?>"</p>
                </div>
            </div>

            <!-- Plugin Widget -->
            <?php if(file_exists('plugins/quran-radio/widget.php')) include 'plugins/quran-radio/widget.php'; ?>

            <!-- Popular News (Dynamic) -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Terpopuler</h2>
                </div>
                <ul class="popular-list">
                    <?php
                    // Fetch popular news (ordered by views DESC)
                    $pop_sql = "SELECT id, title, views FROM news ORDER BY views DESC LIMIT 4";
                    $pop_result = $conn->query($pop_sql);
                    
                    if ($pop_result->num_rows > 0) {
                        $rank = 1;
                        while ($pop = $pop_result->fetch_assoc()) {
                            echo '<li>';
                            echo '<span class="popular-number">' . $rank++ . '</span>';
                            echo '<span class="popular-title"><a href="news_detail.php?id=' . $pop['id'] . '">' . htmlspecialchars($pop['title']) . '</a></span>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li><span class="text-muted">Belum ada berita populer.</span></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Jurusan Widget -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Kompetensi Keahlian</h2>
                </div>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-ketenagalistrikan">Teknik Instalasi Tenaga Listrik</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-mesin">Teknik Pemesinan</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-pengelasan">Teknik Pengelasan</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-otomotif">Teknik Kendaraan Ringan Otomotif</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-elektronika">Teknik Audio Video</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=multimedia">Multimedia</a>
                    </li>
                </ul>
            </div>

        </aside>

    </div>
</div>

<?php require_once 'footer_public.php'; ?>
