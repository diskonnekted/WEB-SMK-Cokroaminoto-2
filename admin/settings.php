<?php
// Suppress Notices for temporary directory warning
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle Standard Settings
    $exclude_keys = ['banner_landscape', 'banner_portrait', 'delete_banner_landscape', 'delete_banner_portrait'];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, $exclude_keys)) {
            $value = $conn->real_escape_string($value);
            $sql = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value') ON DUPLICATE KEY UPDATE setting_value='$value'";
            $conn->query($sql);
        }
    }

    // Handle Landscape Banner Upload
    if (isset($_FILES['banner_landscape']) && $_FILES['banner_landscape']['error'] == 0) {
        $target_dir = "../images/banners/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['banner_landscape']['name'], PATHINFO_EXTENSION);
        $filename = "landscape_" . time() . "." . $file_extension;
        $target_file = $target_dir . $filename;
        
        // Get old setting to delete old file
        $old_res = $conn->query("SELECT setting_value FROM settings WHERE setting_key='banner_landscape'");
        $old_row = $old_res->fetch_assoc();
        
        if (move_uploaded_file($_FILES['banner_landscape']['tmp_name'], $target_file)) {
            // Remove old file if exists
            if ($old_row && !empty($old_row['setting_value']) && file_exists("../" . $old_row['setting_value'])) {
                unlink("../" . $old_row['setting_value']);
            }
            $banner_path = "images/banners/" . $filename;
            $conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('banner_landscape', '$banner_path') ON DUPLICATE KEY UPDATE setting_value='$banner_path'");
        }
    }

    // Handle Portrait Banner Upload
    if (isset($_FILES['banner_portrait']) && $_FILES['banner_portrait']['error'] == 0) {
        $target_dir = "../images/banners/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES['banner_portrait']['name'], PATHINFO_EXTENSION);
        $filename = "portrait_" . time() . "." . $file_extension;
        $target_file = $target_dir . $filename;
        
        // Get old setting to delete old file
        $old_res = $conn->query("SELECT setting_value FROM settings WHERE setting_key='banner_portrait'");
        $old_row = $old_res->fetch_assoc();
        
        if (move_uploaded_file($_FILES['banner_portrait']['tmp_name'], $target_file)) {
            // Remove old file if exists
            if ($old_row && !empty($old_row['setting_value']) && file_exists("../" . $old_row['setting_value'])) {
                unlink("../" . $old_row['setting_value']);
            }
            $banner_path = "images/banners/" . $filename;
            $conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('banner_portrait', '$banner_path') ON DUPLICATE KEY UPDATE setting_value='$banner_path'");
        }
    }

    // Handle Deletions
    if (isset($_POST['delete_banner_landscape'])) {
        $old_res = $conn->query("SELECT setting_value FROM settings WHERE setting_key='banner_landscape'");
        $old_row = $old_res->fetch_assoc();
        if ($old_row && !empty($old_row['setting_value']) && file_exists("../" . $old_row['setting_value'])) {
            unlink("../" . $old_row['setting_value']);
        }
        $conn->query("UPDATE settings SET setting_value='' WHERE setting_key='banner_landscape'");
    }

    if (isset($_POST['delete_banner_portrait'])) {
        $old_res = $conn->query("SELECT setting_value FROM settings WHERE setting_key='banner_portrait'");
        $old_row = $old_res->fetch_assoc();
        if ($old_row && !empty($old_row['setting_value']) && file_exists("../" . $old_row['setting_value'])) {
            unlink("../" . $old_row['setting_value']);
        }
        $conn->query("UPDATE settings SET setting_value='' WHERE setting_key='banner_portrait'");
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

<form method="POST" action="" class="card shadow-sm" enctype="multipart/form-data">
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
        <h5 class="card-title mb-4 text-muted">Radio Cakra FM</h5>

        <div class="mb-3">
            <label class="form-label">URL Streaming / API</label>
            <input type="text" class="form-control" name="cakrafm_stream_url" value="<?php echo $settings['cakrafm_stream_url'] ?? ''; ?>" placeholder="https://stream.server.com/radio.mp3">
            <small class="text-muted">Masukkan URL streaming audio (MP3/AAC) atau endpoint API.</small>
        </div>

        <hr>
        <h5 class="card-title mb-4 text-muted">Pengaturan Banner</h5>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title">Banner Landscape (Tengah Halaman)</h6>
                        <p class="small text-muted">Posisi: Di bawah daftar berita terkini. Orientasi: Landscape.</p>
                        
                        <?php if (!empty($settings['banner_landscape'])): ?>
                            <div class="mb-3">
                                <img src="../<?php echo $settings['banner_landscape']; ?>" class="img-fluid rounded border" style="max-height: 150px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="delete_banner_landscape" id="del_land">
                                    <label class="form-check-label text-danger" for="del_land">Hapus Banner Ini</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" class="form-control mb-2" name="banner_landscape" accept="image/*">
                        
                        <label class="form-label small text-muted">Link Tautan (Opsional)</label>
                        <input type="text" class="form-control form-control-sm" name="banner_landscape_link" value="<?php echo $settings['banner_landscape_link'] ?? ''; ?>" placeholder="https://...">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h6 class="card-title">Banner Portrait (Sidebar)</h6>
                        <p class="small text-muted">Posisi: Di bagian bawah sidebar. Orientasi: Portrait/Kotak.</p>
                        
                        <?php if (!empty($settings['banner_portrait'])): ?>
                            <div class="mb-3">
                                <img src="../<?php echo $settings['banner_portrait']; ?>" class="img-fluid rounded border" style="max-height: 200px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="delete_banner_portrait" id="del_port">
                                    <label class="form-check-label text-danger" for="del_port">Hapus Banner Ini</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" class="form-control mb-2" name="banner_portrait" accept="image/*">
                        
                        <label class="form-label small text-muted">Link Tautan (Opsional)</label>
                        <input type="text" class="form-control form-control-sm" name="banner_portrait_link" value="<?php echo $settings['banner_portrait_link'] ?? ''; ?>" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
        </div>
    </div>
</form>

<?php require_once 'footer.php'; ?>