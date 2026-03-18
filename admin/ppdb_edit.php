<?php
require_once 'header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='ppdb.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM ppdb_registrations WHERE id = $id");

if ($result->num_rows == 0) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    require_once 'footer.php';
    exit;
}

$data = $result->fetch_assoc();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $district = $conn->real_escape_string($_POST['district']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Optional: Update other fields if needed, but focusing on requested ones
    $origin_school = $conn->real_escape_string($_POST['origin_school']);
    $major = $conn->real_escape_string($_POST['major']);

    $sql = "UPDATE ppdb_registrations SET 
            full_name = '$full_name',
            district = '$district',
            status = '$status',
            origin_school = '$origin_school',
            major = '$major'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil diperbarui!";
        // Refresh data
        $result = $conn->query("SELECT * FROM ppdb_registrations WHERE id = $id");
        $data = $result->fetch_assoc();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Pendaftar #<?php echo $data['id']; ?></h2>
    <a href="ppdb.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo strpos($message, 'Error') !== false ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($data['full_name']); ?>" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-control" name="district" value="<?php echo htmlspecialchars($data['district'] ?? ''); ?>" placeholder="Contoh: Banjarnegara">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Asal Sekolah</label>
                    <input type="text" class="form-control" name="origin_school" value="<?php echo htmlspecialchars($data['origin_school']); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Jurusan Pilihan</label>
                    <select class="form-select" name="major">
                        <?php
                        $majors = ['Teknik Kendaraan Ringan', 'Teknik Sepeda Motor', 'Teknik Komputer Jaringan', 'Desain Komunikasi Visual', 'Akuntansi', 'Farmasi']; // Example majors, adjust as needed or make dynamic
                        foreach ($majors as $m) {
                            $selected = ($data['major'] == $m) ? 'selected' : '';
                            echo "<option value=\"$m\" $selected>$m</option>";
                        }
                        ?>
                        <!-- Fallback if current major is not in list -->
                        <?php if (!in_array($data['major'], $majors) && !empty($data['major'])): ?>
                            <option value="<?php echo htmlspecialchars($data['major']); ?>" selected><?php echo htmlspecialchars($data['major']); ?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Status Pendaftaran</label>
                    <select class="form-select" name="status">
                        <?php
                        $statuses = ['Pendaftar Baru', 'Proses Verifikasi', 'Diterima', 'Tidak Diterima'];
                        foreach ($statuses as $s) {
                            $selected = ($data['status'] == $s) ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>