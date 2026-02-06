<?php
require_once 'header_public.php';

// Fetch Hero/Featured News (limit 3)
$hero_news = [];
$result = $conn->query("SELECT * FROM news WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
while ($row = $result->fetch_assoc()) {
    $hero_news[] = $row;
}

// If not enough featured news, fill with latest news
if (count($hero_news) < 3) {
    $limit = 3 - count($hero_news);
    $ids = [];
    foreach ($hero_news as $n) $ids[] = $n['id'];
    $ids_str = empty($ids) ? '0' : implode(',', $ids);
    
    $result = $conn->query("SELECT * FROM news WHERE id NOT IN ($ids_str) ORDER BY created_at DESC LIMIT $limit");
    while ($row = $result->fetch_assoc()) {
        $hero_news[] = $row;
    }
}

// Fetch Latest News (exclude hero news)
$hero_ids = [];
foreach ($hero_news as $n) $hero_ids[] = $n['id'];
$hero_ids_str = implode(',', $hero_ids);

$latest_news = [];
$result = $conn->query("SELECT * FROM news WHERE id NOT IN ($hero_ids_str) ORDER BY created_at DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $latest_news[] = $row;
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
                <div class="section-title">
                    <h2>Berita Terkini</h2>
                </div>

                <div class="news-list">
                    <?php foreach ($latest_news as $news): ?>
                    <article class="news-item">
                        <div class="news-thumb">
                            <a href="news_detail.php?id=<?php echo $news['id']; ?>">
                                <img src="<?php echo $news['image']; ?>" alt="<?php echo $news['title']; ?>">
                            </a>
                        </div>
                        <div class="news-content">
                            <div class="news-meta">
                                <span><?php echo $news['category']; ?></span> • <?php echo indo_date($news['created_at']); ?>
                            </div>
                            <h3 class="news-title"><a href="news_detail.php?id=<?php echo $news['id']; ?>"><?php echo $news['title']; ?></a></h3>
                            <p class="news-excerpt"><?php echo substr(strip_tags($news['content']), 0, 150) . '...'; ?></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

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
