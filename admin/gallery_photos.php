<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$album_id = intval($_GET['id']);
$album_res = $conn->query("SELECT * FROM gallery_albums WHERE id = $album_id");

if ($album_res->num_rows == 0) {
    echo "<script>window.location.href='gallery.php';</script>";
    exit;
}

$album = $album_res->fetch_assoc();

// Handle Delete Photo
if (isset($_GET['delete_photo'])) {
    $photo_id = intval($_GET['delete_photo']);
    
    // Get image path
    $sql = "SELECT image_path FROM gallery_photos WHERE id = $photo_id AND album_id = $album_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = '../' . $row['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        $conn->query("DELETE FROM gallery_photos WHERE id = $photo_id");
        echo "<script>window.location.href='gallery_photos.php?id=$album_id';</script>";
    }
}

// Handle Multiple Upload
if (isset($_POST['upload_photos'])) {
    $target_dir = "../uploads/gallery/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    
    $success_count = 0;
    $error_msg = "";
    
    // Check if files were uploaded
    if (isset($_FILES['photos']) && count($_FILES['photos']['name']) > 0) {
        $total_files = count($_FILES['photos']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['photos']['error'][$i] == 0) {
                $file_ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
                $new_filename = time() . '_' . uniqid() . '_' . $i . '.' . $file_ext;
                $target_file = $target_dir . $new_filename;
                
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($file_ext, $allowed_types)) {
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $target_file)) {
                        $image_url = 'uploads/gallery/' . $new_filename;
                        $sql = "INSERT INTO gallery_photos (album_id, image_path) VALUES ($album_id, '$image_url')";
                        if ($conn->query($sql)) {
                            $success_count++;
                            
                            // If album has no cover, set this as cover
                            if (empty($album['cover_image'])) {
                                $conn->query("UPDATE gallery_albums SET cover_image = '$image_url' WHERE id = $album_id");
                                $album['cover_image'] = $image_url; // Update local var
                            }
                        }
                    }
                }
            }
        }
        
        if ($success_count > 0) {
            echo "<script>alert('$success_count foto berhasil ditambahkan!'); window.location.href='gallery_photos.php?id=$album_id';</script>";
        } else {
            $error = "Gagal mengupload foto. Pastikan format file benar.";
        }
    } else {
        $error = "Pilih minimal satu foto.";
    }
}

// Fetch Photos
$photos = [];
$res = $conn->query("SELECT * FROM gallery_photos WHERE album_id = $album_id ORDER BY created_at DESC");
while ($row = $res->fetch_assoc()) {
    $photos[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">Kelola Foto: <?php echo htmlspecialchars($album['title']); ?></h2>
        <p class="mb-0 text-muted">Total <?php echo count($photos); ?> foto dalam album ini.</p>
    </div>
    <a href="gallery.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Album</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Upload Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Foto Baru</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row align-items-end">
                <div class="col-md-9">
                    <label class="form-label">Pilih Foto (Bisa lebih dari satu)</label>
                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*" required>
                    <div class="form-text">Format: JPG, PNG, GIF, WEBP.</div>
                </div>
                <div class="col-md-3">
                    <button type="submit" name="upload_photos" class="btn btn-primary w-100">
                        <i class="fas fa-cloud-upload-alt me-2"></i>Upload Semua
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Photos Grid -->
<div class="row">
    <?php if (empty($photos)): ?>
    <div class="col-12 text-center py-5">
        <div class="text-muted">
            <i class="fas fa-images fa-3x mb-3"></i>
            <p>Belum ada foto dalam album ini.</p>
        </div>
    </div>
    <?php else: ?>
        <?php foreach ($photos as $photo): ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm h-100">
                <div class="position-relative" style="height: 200px; overflow: hidden;">
                    <a href="../<?php echo $photo['image_path']; ?>" target="_blank">
                        <img src="../<?php echo $photo['image_path']; ?>" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="Foto Galeri">
                    </a>
                </div>
                <div class="card-body p-2 text-center">
                    <small class="text-muted d-block mb-2"><?php echo date('d M Y H:i', strtotime($photo['created_at'])); ?></small>
                    <div class="btn-group btn-group-sm">
                        <a href="gallery_photos.php?id=<?php echo $album_id; ?>&delete_photo=<?php echo $photo['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus foto ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>