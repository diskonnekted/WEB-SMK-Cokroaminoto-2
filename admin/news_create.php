<?php
require_once 'header.php';

// Handle Form Submission
if (isset($_POST['save_news'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $content = $conn->real_escape_string($_POST['content']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $youtube_url = $conn->real_escape_string($_POST['youtube_url']);
    
    // Create Slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    // Check Slug Uniqueness
    $check = $conn->query("SELECT id FROM news WHERE slug = '$slug'");
    if ($check->num_rows > 0) {
        $slug .= '-' . time();
    }

    // Handle Main Image Upload
    $image_url = '';
    
    // Option A: Upload File
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
            } else {
                $error = "Gagal mengupload gambar utama.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    } 
    // Option B: URL Input (Fallback if no file uploaded)
    elseif (!empty($_POST['image_url'])) {
        $image_url = $conn->real_escape_string($_POST['image_url']);
    }
    
    // Default fallback if no image provided
    if (empty($image_url)) {
        $image_url = 'uploads/news/default_news.jpg';
    }

    if (!isset($error)) {
        // Insert News
        $sql = "INSERT INTO news (title, slug, category, image, content, is_featured, youtube_url) VALUES ('$title', '$slug', '$category', '$image_url', '$content', $is_featured, '$youtube_url')";
        
        if ($conn->query($sql)) {
            $news_id = $conn->insert_id;
            
            // Handle Gallery Images
            if (isset($_FILES['gallery_files'])) {
                $gallery_dir = "../uploads/news/gallery/";
                if (!file_exists($gallery_dir)) mkdir($gallery_dir, 0777, true);
                
                $total = count($_FILES['gallery_files']['name']);
                
                for ($i = 0; $i < $total; $i++) {
                    if ($_FILES['gallery_files']['error'][$i] == 0) {
                        $g_ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                        $g_filename = time() . '_' . uniqid() . '_' . $i . '.' . $g_ext;
                        $g_target = $gallery_dir . $g_filename;
                        
                        if (in_array($g_ext, $allowed_types)) {
                            if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$i], $g_target)) {
                                $g_path = 'uploads/news/gallery/' . $g_filename;
                                $conn->query("INSERT INTO news_gallery (news_id, image_path) VALUES ($news_id, '$g_path')");
                            }
                        }
                    }
                }
            }
            
            echo "<script>alert('Berita berhasil ditambahkan!'); window.location.href='index.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Berita Baru</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="save_news" value="1">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul Berita</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Konten Berita</label>
                        <textarea class="form-control summernote" name="content" rows="10" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL Video YouTube (Opsional)</label>
                        <input type="text" class="form-control" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
                        <small class="text-muted">Masukkan link lengkap video YouTube untuk ditampilkan di dalam berita.</small>
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
                                echo '<option value="'.$c['name'].'">'.$c['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Utama</label>
                        <div class="card p-2 bg-light">
                            <!-- Tab for Upload vs URL -->
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
                                    <small class="text-muted d-block mt-1">Format: jpg, png, webp. Max: 2MB</small>
                                </div>
                                <div class="tab-pane fade" id="url" role="tabpanel">
                                    <input type="text" class="form-control" name="image_url" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Galeri Foto Tambahan</label>
                        <input type="file" class="form-control" name="gallery_files[]" multiple accept="image/*">
                        <small class="text-muted">Bisa pilih banyak foto sekaligus.</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured">
                            <label class="form-check-label" for="is_featured">Jadikan Berita Utama (Featured)</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-save me-2"></i> Simpan Berita</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>
