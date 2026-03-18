<?php
require_once 'header.php';

// Ensure backup directory exists
$backup_dir = "../backups/news/";
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

$message = '';
$error = '';

// Handle Create Backup
if (isset($_POST['create_backup'])) {
    try {
        // Fetch News
        $news_data = [];
        $result = $conn->query("SELECT * FROM news");
        while ($row = $result->fetch_assoc()) {
            $news_data[] = $row;
        }

        // Fetch Gallery
        $gallery_data = [];
        $g_result = $conn->query("SELECT * FROM news_gallery");
        while ($row = $g_result->fetch_assoc()) {
            $gallery_data[] = $row;
        }

        // Prepare JSON Data
        $backup_data = [
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s'),
            'news' => $news_data,
            'news_gallery' => $gallery_data
        ];

        $json_content = json_encode($backup_data, JSON_PRETTY_PRINT);
        $filename = 'backup_news_' . date('Y-m-d_H-i-s') . '.json';
        $file_path = $backup_dir . $filename;

        if (file_put_contents($file_path, $json_content)) {
            $message = "Backup berhasil dibuat: " . $filename;
        } else {
            $error = "Gagal menulis file backup.";
        }

    } catch (Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Handle Restore Backup (From Upload)
if (isset($_POST['restore_backup']) && isset($_FILES['backup_file'])) {
    if ($_FILES['backup_file']['error'] == 0) {
        $file_content = file_get_contents($_FILES['backup_file']['tmp_name']);
        $data = json_decode($file_content, true);

        if ($data && isset($data['news'])) {
            $conn->begin_transaction();
            try {
                // Restore News
                $stmt = $conn->prepare("INSERT INTO news (id, title, slug, category, image, content, is_featured, youtube_url, created_at, views, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), slug=VALUES(slug), category=VALUES(category), image=VALUES(image), content=VALUES(content), is_featured=VALUES(is_featured), youtube_url=VALUES(youtube_url), created_at=VALUES(created_at), views=VALUES(views), author_id=VALUES(author_id)");
                
                foreach ($data['news'] as $item) {
                    $author_id = isset($item['author_id']) ? $item['author_id'] : NULL;
                    $stmt->bind_param("isssssissii", 
                        $item['id'], 
                        $item['title'], 
                        $item['slug'], 
                        $item['category'], 
                        $item['image'], 
                        $item['content'], 
                        $item['is_featured'], 
                        $item['youtube_url'], 
                        $item['created_at'], 
                        $item['views'],
                        $author_id
                    );
                    $stmt->execute();
                }

                // Restore Gallery (if exists)
                if (isset($data['news_gallery']) && !empty($data['news_gallery'])) {
                    $stmt_g = $conn->prepare("INSERT INTO news_gallery (id, news_id, image_path, created_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE news_id=VALUES(news_id), image_path=VALUES(image_path), created_at=VALUES(created_at)");
                    
                    foreach ($data['news_gallery'] as $g) {
                        $stmt_g->bind_param("iiss", 
                            $g['id'], 
                            $g['news_id'], 
                            $g['image_path'], 
                            $g['created_at']
                        );
                        $stmt_g->execute();
                    }
                }

                $conn->commit();
                $message = "Artikel berhasil direstore!";
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Gagal merestore: " . $e->getMessage();
            }
        } else {
            $error = "Format file backup tidak valid.";
        }
    } else {
        $error = "Gagal mengupload file.";
    }
}

// Handle Restore Backup (From List)
if (isset($_POST['restore_from_server'])) {
    $filename = $_POST['filename'];
    $file_path = $backup_dir . $filename;

    if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);
        $data = json_decode($file_content, true);

        if ($data && isset($data['news'])) {
            $conn->begin_transaction();
            try {
                // Restore News
                $stmt = $conn->prepare("INSERT INTO news (id, title, slug, category, image, content, is_featured, youtube_url, created_at, views, author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE title=VALUES(title), slug=VALUES(slug), category=VALUES(category), image=VALUES(image), content=VALUES(content), is_featured=VALUES(is_featured), youtube_url=VALUES(youtube_url), created_at=VALUES(created_at), views=VALUES(views), author_id=VALUES(author_id)");
                
                foreach ($data['news'] as $item) {
                    $author_id = isset($item['author_id']) ? $item['author_id'] : NULL;
                    $stmt->bind_param("isssssissii", 
                        $item['id'], 
                        $item['title'], 
                        $item['slug'], 
                        $item['category'], 
                        $item['image'], 
                        $item['content'], 
                        $item['is_featured'], 
                        $item['youtube_url'], 
                        $item['created_at'], 
                        $item['views'],
                        $author_id
                    );
                    $stmt->execute();
                }

                // Restore Gallery
                if (isset($data['news_gallery']) && !empty($data['news_gallery'])) {
                    $stmt_g = $conn->prepare("INSERT INTO news_gallery (id, news_id, image_path, created_at) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE news_id=VALUES(news_id), image_path=VALUES(image_path), created_at=VALUES(created_at)");
                    
                    foreach ($data['news_gallery'] as $g) {
                        $stmt_g->bind_param("iiss", 
                            $g['id'], 
                            $g['news_id'], 
                            $g['image_path'], 
                            $g['created_at']
                        );
                        $stmt_g->execute();
                    }
                }

                $conn->commit();
                $message = "Artikel berhasil direstore dari backup server!";
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Gagal merestore: " . $e->getMessage();
            }
        } else {
            $error = "Format file backup tidak valid.";
        }
    } else {
        $error = "File backup tidak ditemukan.";
    }
}

// Handle Delete Backup
if (isset($_POST['delete_backup'])) {
    $filename = $_POST['filename'];
    $file_path = $backup_dir . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
        $message = "File backup berhasil dihapus.";
    }
}

// Get List of Backups
$backups = [];
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) == 'json') {
            $backups[] = [
                'filename' => $file,
                'path' => $backup_dir . $file,
                'time' => filemtime($backup_dir . $file),
                'size' => filesize($backup_dir . $file)
            ];
        }
    }
    // Sort by time desc
    usort($backups, function($a, $b) {
        return $b['time'] - $a['time'];
    });
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Backup & Restore Artikel</h2>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Backup Section -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-save me-2"></i> Buat Backup Baru</h5>
            </div>
            <div class="card-body text-center">
                <p class="text-muted">Klik tombol di bawah ini untuk membackup seluruh data artikel berita dan galeri ke dalam file JSON.</p>
                <form method="POST">
                    <button type="submit" name="create_backup" class="btn btn-primary btn-lg">
                        <i class="fas fa-download me-2"></i> Buat Backup Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Restore Section -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i> Restore dari File</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Upload file backup JSON dari komputer Anda untuk mengembalikan data artikel.</p>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="file" class="form-control" name="backup_file" accept=".json" required>
                    </div>
                    <button type="submit" name="restore_backup" class="btn btn-warning w-100">
                        <i class="fas fa-history me-2"></i> Restore Data
                    </button>
                    <small class="text-danger d-block mt-2">* Peringatan: Data dengan ID yang sama akan ditimpa.</small>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Backup List -->
<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="mb-0">Riwayat Backup di Server</h5>
    </div>
    <div class="card-body">
        <?php if (empty($backups)): ?>
            <p class="text-center text-muted my-3">Belum ada file backup tersimpan.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Tanggal Dibuat</th>
                            <th>Ukuran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td>
                                <i class="fas fa-file-code text-primary me-2"></i>
                                <?php echo $backup['filename']; ?>
                            </td>
                            <td><?php echo date('d M Y H:i', $backup['time']); ?></td>
                            <td><?php echo round($backup['size'] / 1024, 2); ?> KB</td>
                            <td class="text-end">
                                <a href="<?php echo $backup['path']; ?>" class="btn btn-sm btn-info text-white me-1" download>
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin merestore data dari file ini? Data yang ada mungkin tertimpa.');">
                                    <input type="hidden" name="filename" value="<?php echo $backup['filename']; ?>">
                                    <button type="submit" name="restore_from_server" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-sync-alt"></i> Restore
                                    </button>
                                </form>
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus file backup ini?');">
                                    <input type="hidden" name="filename" value="<?php echo $backup['filename']; ?>">
                                    <button type="submit" name="delete_backup" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>