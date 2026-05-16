<?php
require_once 'config.php';

// Get Slug
$slug = $_GET['slug'] ?? '';

// Get Category Info
$stmt = $conn->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    // If not found, show simple error or redirect
    require_once 'header_public.php';
    echo '<div class="container my-5"><div class="alert alert-danger">Kategori tidak ditemukan.</div></div>';
    require_once 'footer_public.php';
    exit;
}

require_once 'header_public.php';

// Fetch News for this Category
if ($category['slug'] === 'ekstrakurikuler') {
    // If parent Ekstrakurikuler, fetch news from all sub-categories (IDs >= 20) plus the parent itself
    $stmt = $conn->prepare("SELECT news.*, users.full_name as author_name FROM news 
                            LEFT JOIN users ON news.author_id = users.id 
                            WHERE category = 'Ekstrakurikuler' 
                            OR category IN (SELECT name FROM categories WHERE id >= 20)
                            ORDER BY news.created_at DESC");
} else {
    $stmt = $conn->prepare("SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE category = ? ORDER BY news.created_at DESC");
    $stmt->bind_param("s", $category['name']);
}
$stmt->execute();
$news_result = $stmt->get_result();
?>

<div class="container main-content my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="section-title mb-4">
                <h3>Kategori: <?php echo htmlspecialchars($category['name']); ?></h3>
                <div class="line"></div>
            </div>

            <?php if ($news_result->num_rows > 0): ?>
                <div class="row">
                    <?php while ($news = $news_result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0 news-card">
                                <div class="card-img-wrapper" style="height: 200px; overflow: hidden;">
                                    <a href="news_detail.php?id=<?php echo $news['id']; ?>">
                                        <img src="<?php echo !empty($news['image']) ? $news['image'] : 'images/placeholder.jpg'; ?>" 
                                             class="card-img-top w-100 h-100 object-fit-cover transition-scale" 
                                             alt="<?php echo htmlspecialchars($news['title']); ?>">
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="small text-muted mb-2">
                                        <i class="far fa-calendar-alt me-1"></i> <?php echo indo_date($news['created_at']); ?>
                                        <span class="mx-1">|</span>
                                        <i class="far fa-user me-1"></i> <?php echo htmlspecialchars($news['author_name']); ?>
                                    </div>
                                    <h5 class="card-title">
                                        <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($news['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-secondary">
                                        <?php echo substr(strip_tags($news['content']), 0, 100) . '...'; ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                    <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Belum ada berita atau postingan untuk kategori <strong><?php echo htmlspecialchars($category['name']); ?></strong>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Custom styles for category page to match likely index style */
.section-title {
    position: relative;
    padding-bottom: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #eee;
}
.section-title h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    display: inline-block;
    padding-bottom: 10px;
    border-bottom: 3px solid #0d6efd; /* Bootstrap primary color */
    margin-bottom: -2px;
}
.transition-scale {
    transition: transform 0.3s ease;
}
.news-card:hover .transition-scale {
    transform: scale(1.05);
}
</style>

<?php require_once 'footer_public.php'; ?>
