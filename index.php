<?php
// Suppress Notices for temporary directory warning
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'header_public.php';

// Fetch Hero/Featured News (limit 3)
$hero_news = [];
$result = $conn->query("SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE is_featured = 1 ORDER BY news.created_at DESC LIMIT 3");
while ($row = $result->fetch_assoc()) {
    $hero_news[] = $row;
}

// If not enough featured news, fill with latest news
if (count($hero_news) < 3) {
    $limit = 3 - count($hero_news);
    $ids = [];
    foreach ($hero_news as $n) $ids[] = $n['id'];
    $ids_str = empty($ids) ? '0' : implode(',', $ids);
    
    $result = $conn->query("SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE news.id NOT IN ($ids_str) ORDER BY news.created_at DESC LIMIT $limit");
    while ($row = $result->fetch_assoc()) {
        $hero_news[] = $row;
    }
}

// Track displayed IDs from Hero
$hero_ids = [];
foreach ($hero_news as $n) $hero_ids[] = $n['id'];
$hero_ids_str = empty($hero_ids) ? '0' : implode(',', $hero_ids);

// --- Berita Terkini (Latest News) Pagination ---
$limit_latest = 6;
$page_latest = isset($_GET['page_latest']) ? (int)$_GET['page_latest'] : 1;
if ($page_latest < 1) $page_latest = 1;
$offset_latest = ($page_latest - 1) * $limit_latest;

// Count total for Latest (exclude Hero)
$total_latest_sql = "SELECT COUNT(*) as total FROM news WHERE id NOT IN ($hero_ids_str)";
$res_total_latest = $conn->query($total_latest_sql);
$total_latest_count = ($res_total_latest) ? $res_total_latest->fetch_assoc()['total'] : 0;
$total_pages_latest = ceil($total_latest_count / $limit_latest);

// Fetch Latest News
$latest_news = [];
$latest_sql = "SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE news.id NOT IN ($hero_ids_str) ORDER BY news.created_at DESC LIMIT $limit_latest OFFSET $offset_latest";
$result = $conn->query($latest_sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $latest_news[] = $row;
    }
}

// --- Berita Lainnya (Other News) Pagination ---
// Logic: Always exclude Hero AND the first page (6 items) of Latest News, regardless of current Latest page
// This ensures "Berita Lainnya" acts as an archive starting from item #10
$exclude_others_ids = $hero_ids;
$res_first_latest = $conn->query("SELECT id FROM news WHERE id NOT IN ($hero_ids_str) ORDER BY news.created_at DESC LIMIT 6");
if ($res_first_latest) {
    while($r = $res_first_latest->fetch_assoc()) {
        $exclude_others_ids[] = $r['id'];
    }
}
$exclude_others_str = empty($exclude_others_ids) ? '0' : implode(',', $exclude_others_ids);

$limit_others = 9;
$page_others = isset($_GET['page_others']) ? (int)$_GET['page_others'] : 1;
if ($page_others < 1) $page_others = 1;
$offset_others = ($page_others - 1) * $limit_others;

// Count total for Others
$total_others_sql = "SELECT COUNT(*) as total FROM news WHERE id NOT IN ($exclude_others_str)";
$res_total_others = $conn->query($total_others_sql);
$total_others_count = ($res_total_others) ? $res_total_others->fetch_assoc()['total'] : 0;
$total_pages_others = ceil($total_others_count / $limit_others);

// Fetch More News
$more_news = [];
$more_sql = "SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE news.id NOT IN ($exclude_others_str) ORDER BY news.created_at DESC LIMIT $limit_others OFFSET $offset_others";
$result = $conn->query($more_sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $more_news[] = $row;
    }
}
?>

    <!-- Main Content -->
    <div class="container main-content">
        <div class="content-grid">
            
            <!-- Left Column (News) -->
            <div class="main-column">
                
                <!-- Hero Section -->
                <?php if (!empty($hero_news)): ?>
                <div class="hero-grid">
                    <?php 
                    // Main Hero (First item)
                    $main_hero = $hero_news[0]; 
                    ?>
                    <div class="hero-main">
                        <a href="news_detail.php?id=<?php echo $main_hero['id']; ?>" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                            <img src="<?php echo $main_hero['image']; ?>" alt="<?php echo $main_hero['title']; ?>">
                            <div class="hero-overlay">
                                <span style="background: #f39c12; padding: 2px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; margin-bottom: 5px; display: inline-block;">UTAMA</span>
                                <h2><?php echo $main_hero['title']; ?></h2>
                            </div>
                        </a>
                    </div>

                    <?php 
                    // Sub Hero 1
                    if (isset($hero_news[1])): 
                        $sub1 = $hero_news[1];
                    ?>
                    <div class="hero-sub">
                        <a href="news_detail.php?id=<?php echo $sub1['id']; ?>" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                            <img src="<?php echo $sub1['image']; ?>" alt="<?php echo $sub1['title']; ?>">
                            <div class="hero-sub-content">
                                <h4><?php echo $sub1['title']; ?></h4>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php 
                    // Sub Hero 2
                    if (isset($hero_news[2])): 
                        $sub2 = $hero_news[2];
                    ?>
                    <div class="hero-sub">
                        <a href="news_detail.php?id=<?php echo $sub2['id']; ?>" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                            <img src="<?php echo $sub2['image']; ?>" alt="<?php echo $sub2['title']; ?>">
                            <div class="hero-sub-content">
                                <h4><?php echo $sub2['title']; ?></h4>
                            </div>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Latest News -->
                <div class="section-title" id="latest-news">
                    <h2>Berita Terkini</h2>
                </div>

                <div class="row g-4">
                    <?php foreach ($latest_news as $news): ?>
                    <div class="col-md-6">
                        <article class="news-card-grid d-flex align-items-start h-100 mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1 pe-3">
                                <span class="badge bg-success mb-2" style="border-radius: 0; font-weight: 500; font-size: 0.75rem;"><?php echo $news['category']; ?></span>
                                <h3 class="news-title mb-2" style="font-size: 1.1rem; line-height: 1.4; font-weight: 700;">
                                    <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="text-dark text-decoration-none hover-primary">
                                        <?php echo $news['title']; ?>
                                    </a>
                                </h3>
                                <div class="text-muted small">
                                    <?php echo indo_date($news['created_at']); ?>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="news_detail.php?id=<?php echo $news['id']; ?>">
                                    <img src="<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>" class="rounded" style="width: 100px; height: 75px; object-fit: cover;">
                                </a>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination for Latest News -->
                <?php if ($total_pages_latest > 1): ?>
                <nav aria-label="Latest News Pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php 
                        $prev_latest = $page_latest - 1;
                        $next_latest = $page_latest + 1;
                        $other_params = "&page_others=" . $page_others;
                        ?>
                        
                        <!-- Previous -->
                        <li class="page-item <?php if($page_latest <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page_latest=<?php echo $prev_latest . $other_params; ?>#latest-news" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <!-- Numbers -->
                        <?php for($i = 1; $i <= $total_pages_latest; $i++): ?>
                        <li class="page-item <?php if($page_latest == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page_latest=<?php echo $i . $other_params; ?>#latest-news"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <li class="page-item <?php if($page_latest >= $total_pages_latest) echo 'disabled'; ?>">
                            <a class="page-link" href="?page_latest=<?php echo $next_latest . $other_params; ?>#latest-news" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>

                <!-- Landscape Banner (Middle) -->
                <?php if (!empty($settings['banner_landscape']) && file_exists($settings['banner_landscape'])): ?>
                <div class="row mt-4 mb-4">
                    <div class="col-12 text-center">
                        <?php if (!empty($settings['banner_landscape_link'])): ?>
                            <a href="<?php echo $settings['banner_landscape_link']; ?>" target="_blank">
                                <img src="<?php echo $settings['banner_landscape']; ?>" class="img-fluid w-100 shadow-sm rounded" style="max-height: 250px; object-fit: cover;" alt="Info Banner">
                            </a>
                        <?php else: ?>
                            <img src="<?php echo $settings['banner_landscape']; ?>" class="img-fluid w-100 shadow-sm rounded" style="max-height: 250px; object-fit: cover;" alt="Info Banner">
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- More News Grid (9 items, 2 columns) -->
                <?php if (!empty($more_news)): ?>
                <div class="section-title" id="more-news">
                    <h2>Berita Lainnya</h2>
                </div>
                <div class="row g-4">
                    <?php foreach ($more_news as $news): ?>
                    <div class="col-md-6">
                        <article class="news-card-grid d-flex align-items-start h-100 mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1 pe-3">
                                <span class="badge bg-secondary mb-2" style="border-radius: 0; font-weight: 500; font-size: 0.75rem;"><?php echo $news['category']; ?></span>
                                <h3 class="news-title mb-2" style="font-size: 1.1rem; line-height: 1.4; font-weight: 700;">
                                    <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="text-dark text-decoration-none hover-primary">
                                        <?php echo $news['title']; ?>
                                    </a>
                                </h3>
                                <div class="text-muted small">
                                    <?php echo indo_date($news['created_at']); ?>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="news_detail.php?id=<?php echo $news['id']; ?>">
                                    <img src="<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>" class="rounded" style="width: 100px; height: 75px; object-fit: cover;">
                                </a>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination for More News -->
                <?php if ($total_pages_others > 1): ?>
                <nav aria-label="Other News Pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php 
                        $prev_others = $page_others - 1;
                        $next_others = $page_others + 1;
                        $latest_params = "&page_latest=" . $page_latest;
                        ?>
                        
                        <!-- Previous -->
                        <li class="page-item <?php if($page_others <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="?page_others=<?php echo $prev_others . $latest_params; ?>#more-news" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        
                        <!-- Numbers -->
                        <?php for($i = 1; $i <= $total_pages_others; $i++): ?>
                        <li class="page-item <?php if($page_others == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page_others=<?php echo $i . $latest_params; ?>#more-news"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <!-- Next -->
                        <li class="page-item <?php if($page_others >= $total_pages_others) echo 'disabled'; ?>">
                            <a class="page-link" href="?page_others=<?php echo $next_others . $latest_params; ?>#more-news" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php endif; ?>

            </div>

            <!-- Right Column (Sidebar) -->
            <aside class="sidebar">
                
                <!-- Kepala Sekolah Widget -->
                <div class="sidebar-widget">

                    <div style="text-align: center;">
                        <img src="<?php echo !empty($settings['kepsek_image']) ? $settings['kepsek_image'] : 'images/placeholder.jpg'; ?>" alt="Kepala Sekolah" style="margin-bottom: 15px; width: 100%; height: auto; object-fit: cover;">
                        <h4 style="color: var(--nu-green);"><?php echo !empty($settings['kepsek_name']) ? $settings['kepsek_name'] : 'Kepala Sekolah'; ?></h4>
                        <p style="font-size: 0.9rem; color: #666; margin-top: 10px;">"<?php echo !empty($settings['kepsek_message']) ? $settings['kepsek_message'] : 'Selamat Datang'; ?>"</p>
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
                            <a href="page.php?slug=multimedia">Multimedia</a>
                        </li>
                    </ul>
                </div>

                <!-- Portrait Banner (Sidebar Bottom) -->
                <?php if (!empty($settings['banner_portrait']) && file_exists($settings['banner_portrait'])): ?>
                <div class="sidebar-widget mt-4 text-center">
                    <?php if (!empty($settings['banner_portrait_link'])): ?>
                        <a href="<?php echo $settings['banner_portrait_link']; ?>" target="_blank">
                            <img src="<?php echo $settings['banner_portrait']; ?>" class="img-fluid rounded shadow-sm" style="width: 100%; height: auto;" alt="Banner Sidebar">
                        </a>
                    <?php else: ?>
                        <img src="<?php echo $settings['banner_portrait']; ?>" class="img-fluid rounded shadow-sm" style="width: 100%; height: auto;" alt="Banner Sidebar">
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </aside>

        </div>
    </div>

<?php require_once 'footer_public.php'; ?>
