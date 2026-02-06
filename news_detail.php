<?php
require_once 'header_public.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$news_item = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $news_item = $result->fetch_assoc();
    }
    
    // Fetch Gallery
    $gallery = [];
    $g_result = $conn->query("SELECT * FROM news_gallery WHERE news_id = $id");
    while ($row = $g_result->fetch_assoc()) {
        $gallery[] = $row;
    }
}

// Function to get YouTube ID
function getYoutubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return isset($match[1]) ? $match[1] : false;
}
?>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Page Content) -->
        <div class="main-column">
            <div class="card shadow-sm" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <?php if ($news_item): ?>
                    <div class="news-detail-header mb-4">
                        <span class="badge bg-success mb-2"><?php echo $news_item['category']; ?></span>
                        <h1 class="mb-2" style="color: var(--nu-green);"><?php echo $news_item['title']; ?></h1>
                        <div class="text-muted small">
                            <i class="far fa-calendar-alt me-1"></i> <?php echo indo_date($news_item['created_at']); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($news_item['image'])): ?>
                    <div class="news-detail-image mb-4">
                        <img src="<?php echo $news_item['image']; ?>" alt="<?php echo $news_item['title']; ?>" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 4px;">
                    </div>
                    <?php endif; ?>

                    <div class="page-content" style="line-height: 1.8; font-size: 1.1rem;">
                        <?php echo $news_item['content']; ?>
                    </div>
                    
                    <!-- YouTube Embed -->
                    <?php if (!empty($news_item['youtube_url'])): 
                        $yt_id = getYoutubeId($news_item['youtube_url']);
                        if ($yt_id):
                    ?>
                    <div class="mt-4 mb-4">
                        <h4 class="mb-3 border-bottom pb-2">Video Terkait</h4>
                        <div class="ratio ratio-16x9" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000;">
                            <iframe src="https://www.youtube.com/embed/<?php echo $yt_id; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                        </div>
                    </div>
                    <?php endif; endif; ?>

                    <!-- Gallery Section -->
                    <?php if (!empty($gallery)): ?>
                    <div class="mt-5">
                        <h4 class="mb-3 border-bottom pb-2">Galeri Foto</h4>
                        <div class="row g-2">
                            <?php foreach ($gallery as $g): ?>
                            <div class="col-md-4 col-6">
                                <a href="<?php echo $g['image_path']; ?>" target="_blank">
                                    <img src="<?php echo $g['image_path']; ?>" class="img-fluid rounded border" style="width: 100%; height: 150px; object-fit: cover;">
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-5 pt-4 border-top">
                        <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda</a>
                    </div>

                <?php else: ?>
                    <div class="alert alert-warning">
                        <h3>Berita Tidak Ditemukan</h3>
                        <p>Maaf, berita yang Anda cari tidak tersedia atau telah dihapus.</p>
                        <a href="index.php" class="btn btn-success" style="display: inline-block; padding: 10px 20px; background: var(--nu-green); color: white; text-decoration: none; margin-top: 10px; border-radius: 4px;">Kembali ke Beranda</a>
                    </div>
                <?php endif; ?>
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

            <!-- Popular News -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Berita Terbaru</h2>
                </div>
                <ul class="popular-list">
                    <?php
                    $sidebar_news = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 5");
                    $i = 1;
                    while($s_news = $sidebar_news->fetch_assoc()):
                    ?>
                    <li>
                        <span class="popular-number"><?php echo $i++; ?></span>
                        <span class="popular-title"><a href="news_detail.php?id=<?php echo $s_news['id']; ?>"><?php echo $s_news['title']; ?></a></span>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>

        </aside>

    </div>
</div>

<?php require_once 'footer_public.php'; ?>
