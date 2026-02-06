<?php
require_once 'header.php';

// Handle Form Submission
if (isset($_POST['save_job'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $company = $conn->real_escape_string($_POST['company']);
    $description = $conn->real_escape_string($_POST['description']);
    $requirements = $conn->real_escape_string($_POST['requirements']);
    $deadline = !empty($_POST['deadline']) ? "'" . $conn->real_escape_string($_POST['deadline']) . "'" : "NULL";
    $contact = $conn->real_escape_string($_POST['contact']);
    $status = $conn->real_escape_string($_POST['status']);

    // Handle Image Upload
    $image_url = '';
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
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format gambar tidak didukung.";
        }
    }

    if (!isset($error)) {
        $image_val = !empty($image_url) ? "'$image_url'" : "NULL";
        $sql = "INSERT INTO job_vacancies (title, company, description, requirements, deadline, contact, image, status) 
                VALUES ('$title', '$company', '$description', '$requirements', $deadline, '$contact', $image_val, '$status')";
        
        if ($conn->query($sql)) {
            echo "<script>alert('Lowongan berhasil ditambahkan!'); window.location.href='jobs.php';</script>";
        } else {
            $error = "Database Error: " . $conn->error;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Lowongan Kerja</h2>
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
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Staff Administrasi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="company" class="form-control" required placeholder="Contoh: PT. Maju Jaya">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Pekerjaan</label>
                        <textarea name="description" id="summernote" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Persyaratan (Kualifikasi)</label>
                        <textarea name="requirements" id="summernote2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Aktif</option>
                            <option value="closed">Tutup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batas Waktu (Deadline)</label>
                        <input type="date" name="deadline" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak (Email/Telp)</label>
                        <input type="text" name="contact" class="form-control" placeholder="hrd@company.com / 08123...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar / Brosur (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WebP</small>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" name="save_job" class="btn btn-success"><i class="fas fa-save"></i> Simpan Lowongan</button>
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