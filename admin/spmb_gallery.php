<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "SELECT image_path FROM spmb_gallery WHERE id = $id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = '../' . $row['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $conn->query("DELETE FROM spmb_gallery WHERE id = $id");
    }
    echo "<script>window.location.href='spmb_gallery.php';</script>";
}

// Handle Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $target_dir = "../uploads/spmb/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $caption = isset($_POST['caption']) ? $conn->real_escape_string($_POST['caption']) : '';
    
    $file = $_FILES['image'];
    $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $file['name']);
    $target_file = $target_dir . $filename;
    $db_path = "uploads/spmb/" . $filename;
    
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            $sql = "INSERT INTO spmb_gallery (image_path, caption) VALUES ('$db_path', '$caption')";
            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Foto berhasil diupload.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error database: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Gagal mengupload file.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>File bukan gambar valid.</div>";
    }
}

// Fetch Photos
$photos = [];
$result = $conn->query("SELECT * FROM spmb_gallery ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $photos[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Galeri Promosi SPMB</h2>
</div>

<!-- Upload Form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Upload Foto Baru</h6>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Pilih Foto</label>
                <input type="file" class="form-control" name="image" required accept="image/*">
            </div>
            <div class="col-md-5">
                <label class="form-label">Caption (Opsional)</label>
                <input type="text" class="form-control" name="caption" placeholder="Deskripsi singkat foto...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-upload me-2"></i> Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Photos List -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Foto</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Preview</th>
                        <th width="50%">Caption</th>
                        <th width="15%">Tanggal Upload</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($photos)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada foto promosi SPMB.</td>
                    </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($photos as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <a href="../<?php echo $item['image_path']; ?>" target="_blank">
                                    <img src="../<?php echo $item['image_path']; ?>" alt="Preview" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($item['caption']); ?></td>
                            <td><?php echo date('d M Y H:i', strtotime($item['created_at'])); ?></td>
                            <td>
                                <a href="spmb_gallery.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus foto ini?')"><i class="fas fa-trash"></i></a>
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
