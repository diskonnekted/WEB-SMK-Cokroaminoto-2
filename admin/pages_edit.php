<?php
require_once 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $conn->query("SELECT * FROM pages WHERE id = $id");

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Halaman tidak ditemukan!</div>";
    require_once 'footer.php';
    exit;
}

$page = $result->fetch_assoc();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    // Optional: Update slug or keep static
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $conn->real_escape_string($_POST['content']);

    // Check slug uniqueness excluding current id
    $check = $conn->query("SELECT id FROM pages WHERE slug = '$slug' AND id != $id");
    if ($check->num_rows > 0) {
        $message = '<div class="alert alert-danger">Judul/Slug sudah ada! Silakan gunakan judul lain.</div>';
    } else {
        $sql = "UPDATE pages SET title='$title', slug='$slug', content='$content' WHERE id=$id";
        
        if ($conn->query($sql) === TRUE) {
            $message = '<div class="alert alert-success">Halaman berhasil diperbarui!</div>';
            $page['title'] = $title; // Update display
            $page['content'] = stripslashes($_POST['content']);
        } else {
            $message = '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Halaman</h2>
    <a href="pages_index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php echo $message; ?>

<form method="POST" action="" class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Judul Halaman</label>
            <input type="text" class="form-control" name="title" value="<?php echo $page['title']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konten Halaman</label>
            <textarea class="form-control summernote" name="content" rows="15" required><?php echo $page['content']; ?></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
        </div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
