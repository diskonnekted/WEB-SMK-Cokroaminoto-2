<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    header("Location: jobs.php");
    exit;
}

$id = intval($_GET['id']);
$job = $conn->query("SELECT * FROM job_vacancies WHERE id = $id")->fetch_assoc();

if (!$job) {
    header("Location: jobs.php");
    exit;
}

// Handle Form Submission
if (isset($_POST['update_job'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $description = $conn->real_escape_string($_POST['description']);
    $requirements = $conn->real_escape_string($_POST['requirements']);
    $deadline = !empty($_POST['deadline']) ? "'" . $conn->real_escape_string($_POST['deadline']) . "'" : "NULL";
    $contact = $conn->real_escape_string($_POST['contact']);
    $status = $conn->real_escape_string($_POST['status']);

    // Handle Image Upload
    $image_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/jobs/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = 'uploads/jobs/' . $new_filename;
                $image_sql = ", image = '$image_url'";
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }

    if (!isset($error)) {
        $sql = "UPDATE job_vacancies SET 
                title = '$title', 
                company = '$company', 
                description = '$description', 
                requirements = '$requirements', 
                deadline = $deadline, 
                contact = '$contact', 
                status = '$status' 
                $image_sql 
                WHERE id = $id";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Lowongan berhasil diperbarui!'); window.location.href='jobs.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Lowongan Kerja</h2>
    <a href="jobs.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Judul Posisi</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($job['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="company" class="form-control" required value="<?php echo htmlspecialchars($job['company']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Pekerjaan</label>
                        <textarea name="description" id="summernote" class="form-control"><?php echo $job['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persyaratan (Kualifikasi)</label>
                        <textarea name="requirements" id="summernote2" class="form-control"><?php echo $job['requirements']; ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" <?php echo $job['status'] == 'active' ? 'selected' : ''; ?>>Aktif</option>
                            <option value="closed" <?php echo $job['status'] == 'closed' ? 'selected' : ''; ?>>Tutup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batas Waktu (Deadline)</label>
                        <input type="date" name="deadline" class="form-control" value="<?php echo $job['deadline']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak (Email/Telp)</label>
                        <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($job['contact']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar / Brosur (Biarkan kosong jika tidak diubah)</label>
                        <?php if ($job['image']): ?>
                            <div class="mb-2">
                                <img src="../<?php echo $job['image']; ?>" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" name="update_job" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis deskripsi pekerjaan...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
        $('#summernote2').summernote({
            placeholder: 'Tulis persyaratan kualifikasi...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>

<?php require_once 'footer.php'; ?>