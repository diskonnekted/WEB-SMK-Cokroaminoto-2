<?php
require_once 'config.php';
require_once 'header_public.php';

// Fetch SPMB Gallery Photos
$photos = [];
$result = $conn->query("SELECT * FROM spmb_gallery ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $photos[] = $row;
}
?>

<style>
    .gallery-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        height: 100%;
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .gallery-img {
        height: 250px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    .spmb-cta {
        text-align: center;
        padding: 60px 20px;
        background-color: #f8f9fa;
    }
</style>

<!-- Hero Section -->
<div class="bg-light py-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold text-success">Info SPMB SMK Cokroaminoto 2</h1>
        <p class="lead text-muted mb-4">Galeri Informasi Penerimaan Peserta Didik Baru</p>
        <div>
            <a href="https://ppdb.smkc2.com/" class="btn btn-success btn-lg px-5 shadow-sm rounded-pill mb-2"><i class="fas fa-edit me-2"></i> Daftar Sekarang</a>
            <a href="cara_pendaftaran.php" class="btn btn-outline-success btn-lg px-4 ms-2 rounded-pill mb-2"><i class="fas fa-info-circle me-2"></i> Cara Daftar</a>
        </div>
    </div>
</div>

<!-- Gallery Grid -->
<div class="container py-5">
    <?php if (empty($photos)): ?>
        <div class="text-center py-5">
            <div class="alert alert-info d-inline-block px-5">
                <h4>Belum ada informasi foto SPMB yang diupload.</h4>
                <p class="mb-0">Silakan cek kembali nanti untuk update terbaru.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($photos as $photo): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card gallery-card" data-bs-toggle="modal" data-bs-target="#imageModal" 
                         onclick="showImage('<?php echo htmlspecialchars($photo['image_path']); ?>', '<?php echo htmlspecialchars(addslashes($photo['caption'])); ?>')">
                        <img src="<?php echo htmlspecialchars($photo['image_path']); ?>" class="gallery-img" alt="Info SPMB">
                        <?php if (!empty($photo['caption'])): ?>
                        <div class="card-body">
                            <p class="card-text text-center text-muted small mb-0"><?php echo htmlspecialchars($photo['caption']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CTA Section -->
<div class="spmb-cta">
    <div class="container">
        <h3 class="mb-3">Tertarik Bergabung Bersama Kami?</h3>
        <p class="mb-4 text-muted">Segera daftarkan diri Anda dan jadilah bagian dari SMK Cokroaminoto 2 Banjarnegara.</p>
        <a href="https://ppdb.smkc2.com/" class="btn btn-primary btn-lg px-5 rounded-pill shadow">Daftar Online Sekarang</a>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="modalImage" class="img-fluid rounded shadow-lg" style="max-height: 85vh;">
                <div id="modalCaption" class="text-white mt-2 fw-bold text-shadow"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function showImage(src, caption) {
        document.getElementById('modalImage').src = src;
        document.getElementById('modalCaption').innerText = caption;
    }
</script>

<?php require_once 'footer_public.php'; ?>
