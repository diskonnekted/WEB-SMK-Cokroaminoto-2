<?php
require_once 'header.php';

// Handle Delete Album
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get all photos in this album to delete files
    $sql = "SELECT image_path FROM gallery_photos WHERE album_id = $id";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $file_path = '../' . $row['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete photos record
    $conn->query("DELETE FROM gallery_photos WHERE album_id = $id");
    
    // Delete album record
    $conn->query("DELETE FROM gallery_albums WHERE id = $id");
    
    echo "<script>window.location.href='gallery.php';</script>";
}

// Fetch Albums
$albums = [];
$result = $conn->query("SELECT * FROM gallery_albums ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    // Count photos
    $id = $row['id'];
    $count_res = $conn->query("SELECT COUNT(*) as total FROM gallery_photos WHERE album_id = $id");
    $count_row = $count_res->fetch_assoc();
    $row['photo_count'] = $count_row['total'];
    $albums[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Manajemen Album Galeri</h2>
    <a href="gallery_create.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>Buat Album Baru</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Cover</th>
                        <th width="25%">Judul Album</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Foto</th>
                        <th width="30%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($albums)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada album galeri.</td>
                    </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($albums as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if (!empty($item['cover_image'])): ?>
                                <img src="../<?php echo $item['cover_image']; ?>" alt="Cover" class="img-thumbnail" style="height: 60px; width: 60px; object-fit: cover;">
                                <?php else: ?>
                                <span class="text-muted small">No Cover</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo substr(htmlspecialchars($item['description']), 0, 50); ?></small>
                            </td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($item['category']); ?></span></td>
                            <td class="text-center"><span class="badge bg-secondary"><?php echo $item['photo_count']; ?> Foto</span></td>
                            <td>
                                <a href="gallery_photos.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm mb-1"><i class="fas fa-images me-1"></i> Kelola Foto</a>
                                <a href="gallery_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm mb-1"><i class="fas fa-edit"></i> Edit Info</a>
                                <a href="gallery.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin ingin menghapus album ini beserta seluruh fotonya?')"><i class="fas fa-trash"></i></a>
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