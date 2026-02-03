<?php
require_once 'header.php';

// Handle Add
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    
    $conn->query("INSERT INTO categories (name, type, slug) VALUES ('$name', '$type', '$slug')");
    echo "<script>window.location.href='categories.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $id");
    echo "<script>window.location.href='categories.php';</script>";
}

// Handle Update
if (isset($_POST['update_category'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    
    $conn->query("UPDATE categories SET name='$name', type='$type', slug='$slug' WHERE id=$id");
    echo "<script>window.location.href='categories.php';</script>";
}

// Fetch Categories
$categories = [];
$result = $conn->query("SELECT * FROM categories ORDER BY type ASC, name ASC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Kategori</h2>
</div>

<div class="row">
    <!-- Form Tambah -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fs-6">Tambah Kategori Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="add_category" value="1">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis (Tipe)</label>
                        <select class="form-select" name="type">
                            <option value="news">Berita (News)</option>
                            <option value="gallery">Galeri (Gallery)</option>
                        </select>
                        <small class="text-muted">Pilih dimana kategori ini akan muncul.</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-success">Daftar Kategori</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Tipe</th>
                                <th>Slug</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($categories as $cat): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td>
                                    <?php if($cat['type'] == 'news'): ?>
                                        <span class="badge bg-primary">Berita</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Galeri</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $cat['slug']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $cat['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="categories.php?delete=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?');">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $cat['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Kategori</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="update_category" value="1">
                                                        <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Kategori</label>
                                                            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis</label>
                                                            <select class="form-select" name="type">
                                                                <option value="news" <?php echo ($cat['type'] == 'news') ? 'selected' : ''; ?>>Berita</option>
                                                                <option value="gallery" <?php echo ($cat['type'] == 'gallery') ? 'selected' : ''; ?>>Galeri</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>