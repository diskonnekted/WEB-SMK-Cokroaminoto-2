<?php
require 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $birth_place = $conn->real_escape_string($_POST['birth_place']);
    $birth_date = $conn->real_escape_string($_POST['birth_date']);
    $origin_school = $conn->real_escape_string($_POST['origin_school']);
    $graduation_year = $conn->real_escape_string($_POST['graduation_year']);
    $major = $conn->real_escape_string($_POST['major']);
    $parent_phone = $conn->real_escape_string($_POST['parent_phone']);
    $grade_math = floatval($_POST['grade_math']);
    $grade_indo = floatval($_POST['grade_indo']);
    $grade_english = floatval($_POST['grade_english']);
    $grade_science = floatval($_POST['grade_science']);
    $grade_average = ($grade_math + $grade_indo + $grade_english + $grade_science) / 4;
    $future_goal = $conn->real_escape_string($_POST['future_goal']);
    $shoe_size = intval($_POST['shoe_size']);

    // Handle File Upload
    $report_card_file = '';
    if (isset($_FILES['report_card']) && $_FILES['report_card']['error'] == 0) {
        $target_dir = "uploads/ppdb/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["report_card"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["report_card"]["tmp_name"], $target_file)) {
            $report_card_file = $target_file;
        } else {
            $message = "Gagal mengupload file rapor.";
            $message_type = "danger";
        }
    }

    if (empty($message)) {
        $sql = "INSERT INTO ppdb_registrations (
            full_name, gender, birth_place, birth_date, origin_school, 
            graduation_year, major, parent_phone, grade_math, grade_indo, 
            grade_english, grade_science, grade_average, report_card_file, 
            future_goal, shoe_size
        ) VALUES (
            '$full_name', '$gender', '$birth_place', '$birth_date', '$origin_school',
            '$graduation_year', '$major', '$parent_phone', $grade_math, $grade_indo,
            $grade_english, $grade_science, $grade_average, '$report_card_file',
            '$future_goal', $shoe_size
        )";

        if ($conn->query($sql) === TRUE) {
            $message = "Pendaftaran berhasil! Data Anda telah tersimpan.";
            $message_type = "success";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
            $message_type = "danger";
        }
    }
}

require 'header_public.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white p-4">
                    <h2 class="mb-0 text-center"><i class="fas fa-user-graduate me-2"></i>Formulir PPDB Online</h2>
                    <p class="text-center mb-0 mt-2 text-white-50">SMK Cokroaminoto 2 Banjarnegara</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($message_type != 'success'): ?>
                    <form method="POST" action="" enctype="multipart/form-data">
                        
                        <h5 class="text-success mb-3 border-bottom pb-2">Data Pribadi</h5>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Sesuai Ijazah/Akta">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" required>
                                        <label class="form-check-label" for="genderL">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderP" value="P">
                                        <label class="form-check-label" for="genderP">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="shoe_size" class="form-label">Ukuran Sepatu</label>
                                <select class="form-select" id="shoe_size" name="shoe_size" required>
                                    <option value="">Pilih Ukuran</option>
                                    <?php for($i=36; $i<=45; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="birth_place" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place" required>
                            </div>
                            <div class="col-md-6">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                            </div>
                        </div>

                        <h5 class="text-success mb-3 border-bottom pb-2 mt-4">Data Sekolah Asal & Pilihan</h5>

                        <div class="mb-3">
                            <label for="origin_school" class="form-label">Asal Sekolah (SMP/MTS)</label>
                            <input type="text" class="form-control" id="origin_school" name="origin_school" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="graduation_year" class="form-label">Tahun Lulus</label>
                                <select class="form-select" id="graduation_year" name="graduation_year" required>
                                    <?php 
                                    $current_year = date('Y');
                                    for($i=$current_year; $i>=$current_year-2; $i--): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="major" class="form-label">Pilih Jurusan</label>
                                <select class="form-select" id="major" name="major" required>
                                    <option value="">Pilih Kompetensi Keahlian</option>
                                    <option value="TKJ">Teknik Komputer dan Jaringan (TKJ)</option>
                                    <option value="AKL">Akuntansi dan Keuangan Lembaga (AKL)</option>
                                    <option value="BDP">Bisnis Daring dan Pemasaran (BDP)</option>
                                    <!-- Adding placeholders from reference just in case user wants them -->
                                    <option value="DPIB">DPIB (Desain Pemodelan dan Info Bangunan)</option>
                                    <option value="TEI">TEI (Teknik Elektronika Industri)</option>
                                    <option value="TKR">TKR (Teknik Kendaraan Ringan Otomotif)</option>
                                    <option value="TSM">TSM (Teknik Bisnis dan Sepeda Motor)</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="text-success mb-3 border-bottom pb-2 mt-4">Nilai Rapor (Semester 4/Kelas 8 Genap)</h5>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-3 col-6">
                                <label for="grade_math" class="form-label">Matematika</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="grade_math" name="grade_math" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="grade_indo" class="form-label">B. Indonesia</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="grade_indo" name="grade_indo" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="grade_english" class="form-label">B. Inggris</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="grade_english" name="grade_english" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="grade_science" class="form-label">IPA</label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" id="grade_science" name="grade_science" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="report_card" class="form-label">Upload Foto Nilai Rapor</label>
                            <input type="file" class="form-control" id="report_card" name="report_card" accept="image/*" required>
                            <div class="form-text">Format: JPG, JPEG, PNG. Maksimal 2MB.</div>
                        </div>

                        <h5 class="text-success mb-3 border-bottom pb-2 mt-4">Lainnya</h5>

                        <div class="mb-3">
                            <label for="parent_phone" class="form-label">No. WA Orang Tua/Wali</label>
                            <input type="text" class="form-control" id="parent_phone" name="parent_phone" required placeholder="08xxxxxxxxxx">
                            <div class="form-text">Untuk informasi daftar ulang.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Harapan Setelah Lulus</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="future_goal" id="goalWork" value="Bekerja" required>
                                    <label class="form-check-label" for="goalWork">Bekerja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="future_goal" id="goalBusiness" value="Wirausaha">
                                    <label class="form-check-label" for="goalBusiness">Wirausaha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="future_goal" id="goalCollege" value="Kuliah">
                                    <label class="form-check-label" for="goalCollege">Kuliah</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Daftar Sekarang</button>
                        </div>
                    </form>
                    <?php else: ?>
                        <div class="text-center">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            <h3 class="mt-3">Terima Kasih!</h3>
                            <p class="lead">Data pendaftaran Anda telah kami terima.</p>
                            <a href="ppdb.php" class="btn btn-outline-success">Daftar Lagi</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer_public.php'; ?>
