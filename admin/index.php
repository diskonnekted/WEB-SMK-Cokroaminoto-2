<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news WHERE id = $id");
    echo "<script>window.location.href='index.php';</script>";
}

// Fetch News
$news_list = [];
$result = $conn->query("SELECT * FROM news ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $news_list[] = $row;
}

// Stats Counts
$count_news = $conn->query("SELECT COUNT(*) as total FROM news")->fetch_assoc()['total'];
$count_pages = $conn->query("SELECT COUNT(*) as total FROM pages")->fetch_assoc()['total'];
$count_menus = $conn->query("SELECT COUNT(*) as total FROM menus")->fetch_assoc()['total'];
// Check if users table exists before querying to avoid error if not fully set up, though it should be.
// Assuming users table exists based on previous context.
$count_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
?>

<div class="row mb-4 g-3">
    <!-- News Stats -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Berita</div>
                        <div class="fs-2 fw-bold text-dark"><?php echo $count_news; ?></div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(0, 143, 76, 0.1);">
                        <i class="fas fa-newspaper text-success fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <a href="index.php" class="text-decoration-none text-success fw-bold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pages Stats -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Halaman Statis</div>
                        <div class="fs-2 fw-bold text-dark"><?php echo $count_pages; ?></div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(13, 202, 240, 0.1);">
                        <i class="fas fa-file-alt text-info fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <a href="pages_index.php" class="text-decoration-none text-info fw-bold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menus Stats -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Menu Navigasi</div>
                        <div class="fs-2 fw-bold text-dark"><?php echo $count_menus; ?></div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(255, 193, 7, 0.1);">
                        <i class="fas fa-bars text-warning fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <a href="menus.php" class="text-decoration-none text-warning fw-bold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Stats -->
    <div class="col-md-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold mb-1">Admin Users</div>
                        <div class="fs-2 fw-bold text-dark"><?php echo $count_users; ?></div>
                    </div>
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(220, 53, 69, 0.1);">
                        <i class="fas fa-users text-danger fs-4"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="text-muted">Kelola akun admin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Berita</h2>
    <a href="news_create.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Berita</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="100">Gambar</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Featured</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada berita.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($news_list as $index => $news): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php 
                                    $img_src = $news['image'];
                                    if (!filter_var($img_src, FILTER_VALIDATE_URL)) {
                                        $img_src = "../" . $img_src;
                                    }
                                ?>
                                <img src="<?php echo $img_src; ?>" alt="Thumb" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo $news['title']; ?></div>
                                <small class="text-muted"><?php echo $news['slug']; ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?php echo $news['category']; ?></span></td>
                            <td><?php echo date('d M Y', strtotime($news['created_at'])); ?></td>
                            <td>
                                <?php if ($news['is_featured']): ?>
                                    <span class="badge bg-warning text-dark">Utama</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border">Biasa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="news_edit.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="index.php?delete=<?php echo $news['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
