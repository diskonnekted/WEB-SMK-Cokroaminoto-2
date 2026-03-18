<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM gallery_albums WHERE id = $id");

if ($result->num_rows == 0) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$item = $result->fetch_assoc();

if (isset($_POST['update_album'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $image_url = $item['cover_image']; // Default to existing image
    
    // Handle Image Upload if provided
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $target_dir = "../uploads/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                // Delete old image if exists
                if (!empty($item['cover_image']) && file_exists('../' . $item['cover_image'])) {
                    unlink('../' . $item['cover_image']);
                }
                $image_url = 'uploads/gallery/' . $new_filename;
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }

    if (!isset($error)) {
        $sql = "UPDATE gallery_albums SET title='$title', slug='$slug', category='$category', cover_image='$image_url', description='$description' WHERE id=$id";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Album berhasil diperbarui!'); window.location.href='gallery.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Edit Album Galeri</h2>
    <a href="gallery.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul Album <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($item['title']); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $cat_q = $conn->query("SELECT name FROM categories WHERE type='gallery' ORDER BY name ASC");
                            while($c = $cat_q->fetch_assoc()){
                                $selected = ($item['category'] == $c['name']) ? 'selected' : '';
                                echo '<option value="'.$c['name'].'" '.$selected.'>'.$c['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Cover Baru (Opsional)</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah cover.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Album</label>
                        <textarea name="description" class="form-control" rows="5"><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight-bold text-primary">Cover Saat Ini</h6>
                        </div>
                        <div class="card-body text-center">
                            <?php if (!empty($item['cover_image'])): ?>
                                <img src="../<?php echo $item['cover_image']; ?>" class="img-fluid rounded mb-2" alt="Cover">
                            <?php else: ?>
                                <p class="text-muted">Belum ada cover.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-grid">
                        <a href="gallery_photos.php?id=<?php echo $item['id']; ?>" class="btn btn-info">
                            <i class="fas fa-images me-2"></i>Kelola Foto Album
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="update_album" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>