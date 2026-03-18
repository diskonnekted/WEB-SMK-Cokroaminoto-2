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
    <div class="content-grid" <?php if($slug == 'profil') echo 'style="display: block;"'; ?>>
        
        <!-- Left Column (Page Content) -->
        <div class="main-column" <?php if ($slug == 'profil') echo 'style="width: 100%;"'; ?>>
            <div class="card shadow-sm" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <?php if ($page): ?>
                    <?php if ($slug != 'profil'): ?>
                    <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;"><?php echo $page['title']; ?></h1>
                    <?php endif; ?>
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
            $display_title = null;
            $map = [
                'multimedia' => ['category' => 'Multimedia', 'title' => 'MULTIMEDIA'],
                'desain-komunikasi-visual' => ['category' => 'Multimedia', 'title' => 'DESAIN KOMUNIKASI VISUAL'],
                'teknik-mesin' => ['category' => 'Teknik Mesin', 'title' => 'TEKNIK MESIN'],
                'teknik-pengelasan' => ['category' => 'Teknik Pengelasan', 'title' => 'TEKNIK PENGELASAN'],
                'teknik-elektronika' => ['category' => 'Teknik Elektronika', 'title' => 'TEKNIK ELEKTRONIKA'],
                'teknik-otomotif' => ['category' => 'Teknik Otomotif', 'title' => 'TEKNIK OTOMOTIF'],
                'teknik-ketenagalistrikan' => ['category' => 'Teknik Ketenagalistrikan', 'title' => 'TEKNIK KETENAGALISTRIKAN'],
            ];

            if (isset($map[$slug])) {
                if (is_array($map[$slug])) {
                    $category = $conn->real_escape_string($map[$slug]['category']);
                    $display_title = $map[$slug]['title'];
                } else {
                    $category = $conn->real_escape_string($map[$slug]);
                    $display_title = strtoupper($category);
                }

                $news_q = $conn->query("SELECT * FROM news WHERE category = '$category' ORDER BY created_at DESC");
                echo '<div class="section-title" style="margin-top: 20px;"><h2>Berita ' . htmlspecialchars($display_title) . '</h2></div>';
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

        <?php if ($slug != 'profil'): ?>
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

            <!-- Upcoming Events Widget -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Agenda Terdekat</h2>
                </div>
                <div class="agenda-list">
                    <?php
                    $today = date('Y-m-d');
                    $upcoming_sql = "SELECT * FROM calendar_events WHERE start_date >= '$today' ORDER BY start_date ASC LIMIT 5";
                    $upcoming = $conn->query($upcoming_sql);
                    
                    if ($upcoming && $upcoming->num_rows > 0) {
                        while ($evt = $upcoming->fetch_assoc()) {
                            $date_time = strtotime($evt['start_date']);
                            $day = date('d', $date_time);
                            $month = date('M', $date_time);
                            
                            $badge_class = 'bg-primary';
                            $border_class = 'border-primary';
                            $text_class = 'text-primary';
                            $cat_label = 'Akademik';
                            
                            if ($evt['category'] == 'holiday') {
                                $badge_class = 'bg-danger';
                                $border_class = 'border-danger';
                                $text_class = 'text-danger';
                                $cat_label = 'Libur';
                            }
                            if ($evt['category'] == 'event') {
                                $badge_class = 'bg-success';
                                $border_class = 'border-success';
                                $text_class = 'text-success';
                                $cat_label = 'Acara';
                            }
                            if ($evt['category'] == 'exam') {
                                $badge_class = 'bg-warning text-dark';
                                $border_class = 'border-warning';
                                $text_class = 'text-warning';
                                $cat_label = 'Ujian';
                            }
                            
                            echo '<div class="agenda-item d-flex align-items-center mb-3 p-2 rounded shadow-sm bg-white border-start border-4 ' . $border_class . '" style="transition: transform 0.2s;">';
                            echo '  <div class="date-box text-center me-3 p-2 rounded bg-light" style="min-width: 55px; line-height: 1;">';
                            echo '      <span class="d-block fw-bold fs-4 text-dark">' . $day . '</span>';
                            echo '      <span class="d-block small text-uppercase text-muted" style="font-size: 0.75rem;">' . $month . '</span>';
                            echo '  </div>';
                            echo '  <div class="event-info">';
                            echo '      <h6 class="mb-1 text-dark fw-bold" style="font-size: 0.95rem; line-height: 1.3;">' . htmlspecialchars($evt['title']) . '</h6>';
                            echo '      <span class="badge ' . $badge_class . '" style="font-size: 0.65rem; padding: 3px 6px;">' . $cat_label . '</span>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="text-center p-3 text-muted small bg-light rounded">Belum ada agenda terdekat.</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Plugin Widget -->
            <?php if(file_exists('plugins/cakra-fm/widget.php')) include 'plugins/cakra-fm/widget.php'; ?>

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
                        <a href="page.php?slug=desain-komunikasi-visual">Desain Komunikasi Visual</a>
                    </li>
                </ul>
            </div>

        </aside>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'footer_public.php'; ?>
