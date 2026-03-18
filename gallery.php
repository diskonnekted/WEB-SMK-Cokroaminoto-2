<?php
require_once 'header_public.php';

// Get Categories
$categories = [];
$cat_result = $conn->query("SELECT DISTINCT category FROM gallery_albums ORDER BY category ASC");
while ($row = $cat_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Get Albums with Photo Count
$albums = [];
$result = $conn->query("SELECT a.*, (SELECT COUNT(*) FROM gallery_photos WHERE album_id = a.id) as photo_count FROM gallery_albums a ORDER BY a.created_at DESC");
while ($row = $result->fetch_assoc()) {
    $albums[] = $row;
}
?>

<!-- Add custom CSS for gallery -->
<style>
    .gallery-filter-btn {
        margin: 0 5px 10px 0;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .gallery-filter-btn.active {
        background-color: var(--nu-green);
        color: white;
        border-color: var(--nu-green);
    }
    .gallery-filter-btn:hover {
        background-color: var(--nu-green);
        color: white;
    }
    .gallery-item {
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }
    .gallery-item:hover {
        transform: translateY(-5px);
    }
    .gallery-img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        aspect-ratio: 4/3;
    }
    .gallery-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-item:hover .gallery-img-wrap img {
        transform: scale(1.1);
    }
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 143, 76, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 20px;
        text-align: center;
        color: white;
    }
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    .album-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
</style>

<div class="container main-content mt-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: var(--nu-green); border-bottom: 3px solid var(--nu-green); display: inline-block; padding-bottom: 10px;">GALERI SEKOLAH</h1>
        <p class="text-muted mt-3">Dokumentasi kegiatan dan fasilitas SMK Cokroaminoto 2 Banjarnegara</p>
        
        <?php if (!empty($categories)): ?>
        <div class="mt-4">
            <button class="btn btn-outline-success gallery-filter-btn active" data-filter="all">Semua</button>
            <?php foreach ($categories as $cat): ?>
            <button class="btn btn-outline-success gallery-filter-btn" data-filter="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="row gallery-container">
        <?php if (empty($albums)): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Belum ada album galeri.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($albums as $album): ?>
            <div class="col-md-4 col-sm-6 gallery-item" data-category="<?php echo htmlspecialchars($album['category']); ?>">
                <div class="card h-100 border-0 shadow-sm">
                    <a href="gallery_detail.php?id=<?php echo $album['id']; ?>" class="gallery-img-wrap d-block">
                        <?php if (!empty($album['cover_image']) && file_exists($album['cover_image'])): ?>
                            <img src="<?php echo $album['cover_image']; ?>" alt="<?php echo htmlspecialchars($album['title']); ?>">
                        <?php else: ?>
                            <img src="images/placeholder.jpg" alt="No Cover">
                        <?php endif; ?>
                        
                        <div class="gallery-overlay">
                            <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($album['title']); ?></h5>
                            <span class="btn btn-sm btn-light rounded-pill px-3">Lihat Album</span>
                        </div>
                        
                        <div class="album-badge">
                            <i class="fas fa-camera me-1"></i> <?php echo $album['photo_count']; ?> Foto
                        </div>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-1">
                            <a href="gallery_detail.php?id=<?php echo $album['id']; ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo htmlspecialchars($album['title']); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-2"><?php echo substr(htmlspecialchars($album['description']), 0, 80) . (strlen($album['description']) > 80 ? '...' : ''); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary rounded-pill"><?php echo htmlspecialchars($album['category']); ?></span>
                            <small class="text-muted"><?php echo date('d M Y', strtotime($album['created_at'])); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const items = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');

            items.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>

<?php require_once 'footer_public.php'; ?>