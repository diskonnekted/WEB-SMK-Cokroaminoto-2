<?php
require_once 'header_public.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$album_res = $conn->query("SELECT * FROM gallery_albums WHERE id = $id");

if ($album_res->num_rows == 0) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$album = $album_res->fetch_assoc();

// Get Photos
$photos = [];
$photo_res = $conn->query("SELECT * FROM gallery_photos WHERE album_id = $id ORDER BY created_at ASC");
while ($row = $photo_res->fetch_assoc()) {
    $photos[] = $row;
}
?>

<style>
    .photo-item {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .photo-item:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .photo-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
    }
    /* Simple Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        overflow: hidden;
        justify-content: center;
        align-items: center;
    }
    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        border-radius: 5px;
        box-shadow: 0 0 20px rgba(255,255,255,0.2);
    }
    .close-lightbox {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
    .close-lightbox:hover,
    .close-lightbox:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    .lightbox-caption {
        position: absolute;
        bottom: 20px;
        left: 0;
        width: 100%;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
    }
</style>

<div class="container main-content mt-5 mb-5">
    <div class="mb-4">
        <a href="gallery.php" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Galeri
        </a>
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <span class="badge bg-success mb-2"><?php echo htmlspecialchars($album['category']); ?></span>
                <h1 class="fw-bold mb-2"><?php echo htmlspecialchars($album['title']); ?></h1>
                <p class="text-muted mb-0"><i class="far fa-calendar-alt me-2"></i><?php echo date('d F Y', strtotime($album['created_at'])); ?></p>
            </div>
        </div>
        <?php if (!empty($album['description'])): ?>
        <div class="mt-4 p-4 bg-light rounded border-start border-4 border-success">
            <p class="mb-0 text-dark" style="font-size: 1.05rem; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($album['description'])); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if (empty($photos)): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> Belum ada foto dalam album ini.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($photos as $photo): ?>
            <div class="col-md-4 col-sm-6">
                <div class="photo-item" onclick="openLightbox('<?php echo $photo['image_path']; ?>', '<?php echo htmlspecialchars(addslashes($album['title'])); ?>')">
                    <img src="<?php echo $photo['image_path']; ?>" class="photo-img shadow-sm" alt="Foto Galeri">
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox">
    <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
    <div id="caption" class="lightbox-caption"></div>
</div>

<script>
    function openLightbox(src, caption) {
        document.getElementById('lightbox').style.display = 'flex';
        document.getElementById('lightbox-img').src = src;
        document.getElementById('caption').innerText = caption;
        document.body.style.overflow = 'hidden'; // Disable scroll
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = 'auto'; // Enable scroll
    }

    // Close on click outside image
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>

<?php require_once 'footer_public.php'; ?>