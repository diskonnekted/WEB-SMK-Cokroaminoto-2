<?php
require_once 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $conn->real_escape_string($_POST['content']);

    $check = $conn->query("SELECT id FROM pages WHERE slug = '$slug'");
    if ($check->num_rows > 0) {
        $message = '<div class="alert alert-danger">Judul/Slug sudah ada! Silakan gunakan judul lain.</div>';
    } else {
        $sql = "INSERT INTO pages (title, slug, content) VALUES ('$title', '$slug', '$content')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>window.location.href='pages_index.php';</script>";
            exit;
        } else {
            $message = '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Halaman Baru</h2>
    <a href="pages_index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php echo $message; ?>

<form method="POST" action="" class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" class="form-control" name="title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konten Halaman</label>
            <textarea class="form-control summernote" name="content" rows="15" required></textarea>
            <small class="text-muted">Bisa menggunakan tag HTML dasar.</small>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Simpan Halaman</button>
        </div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
