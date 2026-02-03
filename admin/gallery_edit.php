<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM gallery WHERE id = $id");

if ($result->num_rows == 0) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$item = $result->fetch_assoc();

// Predefined Categories
// Removed hardcoded categories in favor of DB categories

if (isset($_POST['update_gallery'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $image_url = $item['image_path']; // Default to existing image
    
    // Handle Image Upload if provided
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../uploads/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
                // Delete old image if exists
                if (file_exists('../' . $item['image_path'])) {
                    unlink('../' . $item['image_path']);
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
        $sql = "UPDATE gallery SET title='$title', category='$category', image_path='$image_url', description='$description' WHERE id=$id";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Foto berhasil diperbarui!'); window.location.href='gallery.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Edit Foto Galeri</h2>
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
                        <label class="form-label">Judul Foto <span class="text-danger">*</span></label>
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
                        <label class="form-label">Upload Foto Baru (Opsional)</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti foto.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Foto Saat Ini</label>
                    <div class="card">
                        <img src="../<?php echo $item['image_path']; ?>" class="card-img-top" alt="Current Image">
                        <div class="card-body text-center">
                            <small class="text-muted">Preview Gambar</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="update_gallery" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>