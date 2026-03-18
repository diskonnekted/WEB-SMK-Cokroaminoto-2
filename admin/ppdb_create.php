<?php
require_once 'header.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect Input
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $birth_place = $conn->real_escape_string($_POST['birth_place']);
    $birth_date = $conn->real_escape_string($_POST['birth_date']);
    $district = $conn->real_escape_string($_POST['district']);
    $parent_phone = $conn->real_escape_string($_POST['parent_phone']);
    
    $origin_school = $conn->real_escape_string($_POST['origin_school']);
    $graduation_year = $conn->real_escape_string($_POST['graduation_year']);
    $major = $conn->real_escape_string($_POST['major']);
    
    $grade_math = floatval($_POST['grade_math']);
    $grade_indo = floatval($_POST['grade_indo']);
    $grade_english = floatval($_POST['grade_english']);
    $grade_science = floatval($_POST['grade_science']);
    $grade_average = ($grade_math + $grade_indo + $grade_english + $grade_science) / 4;
    
    $future_goal = $conn->real_escape_string($_POST['future_goal']);
    $shoe_size = intval($_POST['shoe_size']);
    $status = $conn->real_escape_string($_POST['status']);

    // Insert Query
    $sql = "INSERT INTO ppdb_registrations (
        full_name, gender, birth_place, birth_date, district, parent_phone,
        origin_school, graduation_year, major,
        grade_math, grade_indo, grade_english, grade_science, grade_average,
        future_goal, shoe_size, status
    ) VALUES (
        '$full_name', '$gender', '$birth_place', '$birth_date', '$district', '$parent_phone',
        '$origin_school', '$graduation_year', '$major',
        $grade_math, $grade_indo, $grade_english, $grade_science, $grade_average,
        '$future_goal', $shoe_size, '$status'
    )";

    if ($conn->query($sql) === TRUE) {
        $message = "Data siswa berhasil ditambahkan!";
        $message_type = "success";
        // Reset form or redirect? Let's show success message.
    } else {
        $message = "Error: " . $conn->error;
        $message_type = "danger";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Data Pendaftar Manual</h2>
    <a href="ppdb.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <h5 class="text-primary mb-3 border-bottom pb-2">Data Pribadi</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select" name="gender">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" name="birth_place">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="birth_date">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" class="form-control" name="district" placeholder="Kecamatan Domisili">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. HP Orang Tua</label>
                    <input type="text" class="form-control" name="parent_phone">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ukuran Sepatu</label>
                    <input type="number" class="form-control" name="shoe_size" placeholder="Contoh: 40">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cita-cita</label>
                    <input type="text" class="form-control" name="future_goal">
                </div>
            </div>

            <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">Data Sekolah & Akademik</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Asal Sekolah</label>
                    <input type="text" class="form-control" name="origin_school">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Lulus</label>
                    <input type="number" class="form-control" name="graduation_year" value="<?php echo date('Y'); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jurusan Pilihan</label>
                    <select class="form-select" name="major">
                        <option value="Teknik Instalasi Tenaga Listrik">Teknik Instalasi Tenaga Listrik</option>
                        <option value="Teknik Pemesinan">Teknik Pemesinan</option>
                        <option value="Teknik Pengelasan">Teknik Pengelasan</option>
                        <option value="Teknik Kendaraan Ringan Otomotif">Teknik Kendaraan Ringan Otomotif</option>
                        <option value="Teknik Audio Video">Teknik Audio Video</option>
                        <option value="Desain Komunikasi Visual">Desain Komunikasi Visual (Multimedia)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Pendaftaran</label>
                    <select class="form-select" name="status">
                        <option value="Pendaftar Baru">Pendaftar Baru</option>
                        <option value="Proses Verifikasi">Proses Verifikasi</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Tidak Diterima">Tidak Diterima</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nilai MTK</label>
                    <input type="number" step="0.01" class="form-control" name="grade_math" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nilai B. Indo</label>
                    <input type="number" step="0.01" class="form-control" name="grade_indo" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nilai B. Inggris</label>
                    <input type="number" step="0.01" class="form-control" name="grade_english" value="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nilai IPA</label>
                    <input type="number" step="0.01" class="form-control" name="grade_science" value="0">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>