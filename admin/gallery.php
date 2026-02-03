<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image path to delete file
    $sql = "SELECT image_path FROM gallery WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = '../' . $row['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    $conn->query("DELETE FROM gallery WHERE id = $id");
    echo "<script>window.location.href='gallery.php';</script>";
}

// Fetch Gallery Items
$gallery_items = [];
$result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $gallery_items[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Manajemen Galeri</h2>
    <a href="gallery_create.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>Tambah Foto</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Foto</th>
                        <th width="20%">Judul</th>
                        <th width="15%">Kategori</th>
                        <th width="30%">Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gallery_items)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data galeri.</td>
                    </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($gallery_items as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="../<?php echo $item['image_path']; ?>" alt="Thumb" class="img-thumbnail" style="height: 60px; width: 60px; object-fit: cover;">
                            </td>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($item['category']); ?></span></td>
                            <td><?php echo substr(htmlspecialchars($item['description']), 0, 50) . '...'; ?></td>
                            <td>
                                <a href="gallery_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="gallery.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus foto ini?')"><i class="fas fa-trash"></i></a>
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