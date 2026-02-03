<?php
require_once 'header_public.php';

// Get Categories
$categories = [];
$cat_result = $conn->query("SELECT DISTINCT category FROM gallery ORDER BY category ASC");
while ($row = $cat_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Get All Images
$gallery_items = [];
$result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $gallery_items[] = $row;
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
        <?php if (empty($gallery_items)): ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Belum ada foto di galeri. Silakan tambahkan foto melalui halaman Admin.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($gallery_items as $item): ?>
            <div class="col-md-4 col-sm-6 gallery-item animate__animated animate__fadeIn" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                <div class="gallery-img-wrap">
                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <a href="<?php echo htmlspecialchars($item['image_path']); ?>" class="gallery-overlay text-decoration-none" target="_blank">
                        <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <small class="d-block mb-3 text-white-50"><?php echo htmlspecialchars($item['category']); ?></small>
                        <span class="btn btn-light btn-sm rounded-pill px-3">Lihat Full</span>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.gallery-filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                    // Re-trigger animation
                    item.classList.remove('animate__fadeIn');
                    void item.offsetWidth; // trigger reflow
                    item.classList.add('animate__fadeIn');
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php require_once 'footer_public.php'; ?>