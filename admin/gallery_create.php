<?php
require_once 'header.php';

if (isset($_POST['save_album'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Handle Cover Image Upload (Optional)
    $image_url = '';
    
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $target_dir = "../uploads/gallery/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                $image_url = 'uploads/gallery/' . $new_filename;
            } else {
                $error = "Gagal mengupload cover gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }
    
    if (!isset($error)) {
        $sql = "INSERT INTO gallery_albums (title, slug, category, description, cover_image) VALUES ('$title', '$slug', '$category', '$description', '$image_url')";
        
        if ($conn->query($sql)) {
            $album_id = $conn->insert_id;
            echo "<script>alert('Album berhasil dibuat! Silakan tambahkan foto.'); window.location.href='gallery_photos.php?id=$album_id';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Buat Album Baru</h2>
    <a href="gallery.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul Album <span class="text-danger">*</span></label>
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
                <label class="form-label">Cover Album (Opsional)</label>
                <input type="file" name="cover_image" class="form-control" accept="image/*">
                <div class="form-text">Jika dikosongkan, akan otomatis menggunakan foto pertama yang diupload nanti.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Album</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat tentang album ini..."></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" name="save_album" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan & Lanjut Upload Foto</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>