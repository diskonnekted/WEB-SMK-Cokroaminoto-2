<?php
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$news_item = null;
$gallery = [];

if ($id > 0) {
    $stmt = $conn->prepare("SELECT news.*, users.full_name as author_name FROM news LEFT JOIN users ON news.author_id = users.id WHERE news.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $news_item = $result->fetch_assoc();
        
        // Update views
        $conn->query("UPDATE news SET views = views + 1 WHERE id = $id");
    }
    
    // Fetch Gallery
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $news_item ? $news_item['title'] : 'Detail Berita'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: hsl(150, 80%, 15%);
            --accent: hsl(45, 100%, 50%);
            --bg: #f8f9fa;
        }

        body {
            background-color: var(--bg);
            font-family: 'Segoe UI', sans-serif;
            padding-bottom: 20px;
        }

        .detail-header {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .detail-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.9);
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            z-index: 10;
        }

        .content-container {
            background: white;
            border-radius: 30px 30px 0 0;
            margin-top: -30px;
            position: relative;
            padding: 30px 20px;
            min-height: 500px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.05);
        }

        .category-badge {
            background: var(--accent);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 50px;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: inline-block;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .meta-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }

        .article-body {
            line-height: 1.8;
            color: #444;
            font-size: 1rem;
        }

        .article-body img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 15px;
            margin: 15px 0;
        }

        /* Gallery */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .gallery-item {
            aspect-ratio: 1/1;
            border-radius: 15px;
            overflow: hidden;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <?php if ($news_item): ?>
        <div class="detail-header">
            <a href="mobile.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            <img src="<?php echo $news_item['image']; ?>" alt="Cover">
        </div>

        <div class="content-container">
            <span class="category-badge"><?php echo $news_item['category']; ?></span>
            <h1><?php echo $news_item['title']; ?></h1>
            
            <div class="meta-info">
                <span><i class="fa-regular fa-calendar me-1"></i> <?php echo indo_date($news_item['created_at']); ?></span>
                <span><i class="fa-regular fa-user me-1"></i> <?php echo $news_item['author_name'] ?: 'Admin'; ?></span>
            </div>

            <div class="article-body">
                <?php echo $news_item['content']; ?>
            </div>

            <?php if (!empty($news_item['youtube_url'])): 
                $yt_id = getYoutubeId($news_item['youtube_url']);
                if ($yt_id):
            ?>
            <div class="mt-4">
                <h5 class="fw-bold mb-3">Video Terkait</h5>
                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                    <iframe src="https://www.youtube.com/embed/<?php echo $yt_id; ?>" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <?php endif; endif; ?>

            <?php if (!empty($gallery)): ?>
            <div class="mt-4">
                <h5 class="fw-bold mb-3">Galeri Foto</h5>
                <div class="gallery-grid">
                    <?php foreach ($gallery as $g): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $g['image_path']; ?>" alt="Gallery">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="mt-5 pt-4 text-center border-top">
                <p class="small text-muted">© <?php echo date('Y'); ?> SMK Cokroaminoto 2 Banjarnegara</p>
            </div>
        </div>
    <?php else: ?>
        <div class="container p-5 text-center">
            <div class="alert alert-warning">Berita tidak ditemukan.</div>
            <a href="mobile.php" class="btn btn-success">Kembali ke Beranda</a>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
