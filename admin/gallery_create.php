<?php
require_once 'header.php';

// Predefined Categories
// Removed hardcoded categories in favor of DB categories

if (isset($_POST['save_gallery'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Handle Image Upload
    $image_url = '';
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../uploads/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
                $image_url = 'uploads/gallery/' . $new_filename;
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    } else {
        $error = "Harap pilih gambar untuk diupload.";
    }

    if (!isset($error)) {
        $sql = "INSERT INTO gallery (title, category, image_path, description) VALUES ('$title', '$category', '$image_url', '$description')";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Foto berhasil ditambahkan!'); window.location.href='gallery.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Tambah Foto Galeri</h2>
    <a href="gallery.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul Foto <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="Contoh: Kegiatan Upacara Bendera">
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $cat_q = $conn->query("SELECT name FROM categories WHERE type='gallery' ORDER BY name ASC");
                    while($c = $cat_q->fetch_assoc()){
                        echo '<option value="'.$c['name'].'">'.$c['name'].'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Foto <span class="text-danger">*</span></label>
                <input type="file" name="image_file" class="form-control" required accept="image/*">
                <div class="form-text">Format yang didukung: JPG, JPEG, PNG, GIF, WEBP. Maksimal 2MB disarankan.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat tentang foto ini..."></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" name="save_gallery" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Galeri</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>