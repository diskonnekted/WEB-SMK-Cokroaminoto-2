<?php
require_once 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        $value = $conn->real_escape_string($value);
        $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value='$value'";
        $conn->query($sql);
    }
    $message = '<div class="alert alert-success">Pengaturan berhasil disimpan!</div>';
}

// Fetch Settings
$settings = [];
$result = $conn->query("SELECT * FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pengaturan Sekolah</h2>
</div>

<?php echo $message; ?>

<form method="POST" action="" class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-4 text-muted">Informasi Dasar</h5>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nama Sekolah</label>
                <input type="text" class="form-control" name="school_name" value="<?php echo $settings['school_name'] ?? ''; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sub Nama (Wilayah)</label>
                <input type="text" class="form-control" name="school_sub_name" value="<?php echo $settings['school_sub_name'] ?? ''; ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" name="address" rows="2"><?php echo $settings['address'] ?? ''; ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" name="phone" value="<?php echo $settings['phone'] ?? ''; ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo $settings['email'] ?? ''; ?>">
            </div>
        </div>

        <hr>
        <h5 class="card-title mb-4 text-muted">Kepala Sekolah</h5>

        <div class="mb-3">
            <label class="form-label">Nama Kepala Sekolah</label>
            <input type="text" class="form-control" name="kepsek_name" value="<?php echo $settings['kepsek_name'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">URL Foto Kepala Sekolah</label>
            <input type="text" class="form-control" name="kepsek_image" value="<?php echo $settings['kepsek_image'] ?? ''; ?>" placeholder="images/foto.jpg atau https://...">
            <small class="text-muted">Masukkan path gambar (contoh: images/bfb.jpg) atau URL lengkap.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Pesan Sambutan</label>
            <textarea class="form-control summernote" name="kepsek_message" rows="3"><?php echo $settings['kepsek_message'] ?? ''; ?></textarea>
        </div>

        <hr>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
        </div>
    </div>
</form>

<?php require_once 'footer.php'; ?>
