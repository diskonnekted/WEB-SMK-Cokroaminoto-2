<?php
require_once 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header('Location: index.php');
    exit;
}

// Fetch Existing Data
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();

if (!$news) {
    die("Berita tidak ditemukan.");
}

// Handle Update Submission
if (isset($_POST['update_news'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $content = $conn->real_escape_string($_POST['content']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $youtube_url = $conn->real_escape_string($_POST['youtube_url']);
    
    // Update basic info
    $sql = "UPDATE news SET title='$title', category='$category', content='$content', is_featured=$is_featured, youtube_url='$youtube_url' WHERE id=$id";
    $conn->query($sql);

    // Handle Main Image Update
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $target_dir = "../uploads/news/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
                $image_url = 'uploads/news/' . $new_filename;
                $conn->query("UPDATE news SET image='$image_url' WHERE id=$id");
            }
        }
    } elseif (!empty($_POST['image_url'])) {
        $image_url = $conn->real_escape_string($_POST['image_url']);
        $conn->query("UPDATE news SET image='$image_url' WHERE id=$id");
    }

    // Handle Gallery Additions
    if (isset($_FILES['gallery_files'])) {
        $gallery_dir = "../uploads/news/gallery/";
        if (!file_exists($gallery_dir)) mkdir($gallery_dir, 0777, true);
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $total = count($_FILES['gallery_files']['name']);
        
        for ($i = 0; $i < $total; $i++) {
            if ($_FILES['gallery_files']['error'][$i] == 0) {
                $g_ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                $g_filename = time() . '_' . uniqid() . '_' . $i . '.' . $g_ext;
                $g_target = $gallery_dir . $g_filename;
                
                if (in_array($g_ext, $allowed_types)) {
                    if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$i], $g_target)) {
                        $g_path = 'uploads/news/gallery/' . $g_filename;
                        $conn->query("INSERT INTO news_gallery (news_id, image_path) VALUES ($id, '$g_path')");
                    }
                }
            }
        }
    }

    echo "<script>alert('Berita berhasil diperbarui!'); window.location.href='index.php';</script>";
}

// Handle Gallery Deletion
if (isset($_GET['delete_gallery'])) {
    $g_id = intval($_GET['delete_gallery']);
    // Get path to delete file
    $g_res = $conn->query("SELECT image_path FROM news_gallery WHERE id=$g_id AND news_id=$id");
    if ($g_res->num_rows > 0) {
        $g_row = $g_res->fetch_assoc();
        $file_path = "../" . $g_row['image_path'];
        if (file_exists($file_path)) unlink($file_path);
        
        $conn->query("DELETE FROM news_gallery WHERE id=$g_id");
    }
    header("Location: news_edit.php?id=$id");
    exit;
}

// Fetch Gallery Images
$gallery = [];
$g_result = $conn->query("SELECT * FROM news_gallery WHERE news_id = $id");
while ($row = $g_result->fetch_assoc()) {
    $gallery[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Berita</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="update_news" value="1">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul Berita</label>
                        <input type="text" class="form-control" name="title" value="<?php echo $news['title']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konten Berita</label>
                        <textarea class="form-control summernote" name="content" rows="10" required><?php echo $news['content']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL Video YouTube (Opsional)</label>
                        <input type="text" class="form-control" name="youtube_url" value="<?php echo $news['youtube_url'] ?? ''; ?>" placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <!-- Gallery Management -->
                    <div class="mb-3">
                        <label class="form-label">Galeri Foto Saat Ini</label>
                        <div class="row g-2">
                            <?php if (empty($gallery)): ?>
                                <p class="text-muted small">Belum ada foto galeri.</p>
                            <?php else: ?>
                                <?php foreach ($gallery as $g): ?>
                                <div class="col-md-3 col-6 position-relative">
                                    <img src="../<?php echo $g['image_path']; ?>" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                                    <a href="news_edit.php?id=<?php echo $id; ?>&delete_gallery=<?php echo $g['id']; ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="return confirm('Hapus foto ini?')"><i class="fas fa-times"></i></a>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                            $cat_q = $conn->query("SELECT name FROM categories WHERE type='news' ORDER BY name ASC");
                            while($c = $cat_q->fetch_assoc()){
                                $selected = ($c['name'] == $news['category']) ? 'selected' : '';
                                echo '<option value="'.$c['name'].'" '.$selected.'>'.$c['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Utama</label>
                        <?php if (!empty($news['image'])): 
                            $img_src = $news['image'];
                            if (!filter_var($img_src, FILTER_VALIDATE_URL)) {
                                $img_src = "../" . $img_src;
                            }
                        ?>
                            <div class="mb-2">
                                <img src="<?php echo $img_src; ?>" class="img-fluid rounded border" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                        
                        <div class="card p-2 bg-light">
                            <p class="small fw-bold mb-2">Ganti Gambar:</p>
                            <ul class="nav nav-tabs mb-2" id="imageTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active p-1 px-2 small" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">Upload File</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-1 px-2 small" id="url-tab" data-bs-toggle="tab" data-bs-target="#url" type="button" role="tab">URL Gambar</button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="imageTabContent">
                                <div class="tab-pane fade show active" id="upload" role="tabpanel">
                                    <input type="file" class="form-control" name="image_file" accept="image/*">
                                </div>
                                <div class="tab-pane fade" id="url" role="tabpanel">
                                    <input type="text" class="form-control" name="image_url" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tambah Foto Galeri</label>
                        <input type="file" class="form-control" name="gallery_files[]" multiple accept="image/*">
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" <?php echo $news['is_featured'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_featured">Jadikan Berita Utama (Featured)</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i> Update Berita</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>
