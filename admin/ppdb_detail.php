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
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Detail Pendaftaran #<?php echo $data['id']; ?></h2>
    <a href="ppdb.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row">
    <!-- Main Info -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fs-6"><i class="fas fa-user me-2"></i>Data Pribadi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama Lengkap</th>
                        <td>: <?php echo htmlspecialchars($data['full_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>: <?php echo $data['gender'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>: <?php echo htmlspecialchars($data['birth_place']) . ', ' . date('d F Y', strtotime($data['birth_date'])); ?></td>
                    </tr>
                    <tr>
                        <th>Ukuran Sepatu</th>
                        <td>: <?php echo $data['shoe_size']; ?></td>
                    </tr>
                    <tr>
                        <th>Harapan Setelah Lulus</th>
                        <td>: <?php echo htmlspecialchars($data['future_goal']); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fs-6"><i class="fas fa-school me-2"></i>Data Sekolah & Akademik</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Asal Sekolah</th>
                        <td>: <?php echo htmlspecialchars($data['origin_school']); ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Lulus</th>
                        <td>: <?php echo htmlspecialchars($data['graduation_year']); ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan Dipilih</th>
                        <td>: <span class="badge bg-success fs-6"><?php echo htmlspecialchars($data['major']); ?></span></td>
                    </tr>
                </table>
                
                <h6 class="mt-3 mb-2 fw-bold text-muted border-bottom pb-2">Nilai Rapor (Semester 4)</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center bg-light">
                            <small class="d-block text-muted mb-1">Matematika</small>
                            <span class="fw-bold fs-5"><?php echo $data['grade_math']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center bg-light">
                            <small class="d-block text-muted mb-1">B. Indonesia</small>
                            <span class="fw-bold fs-5"><?php echo $data['grade_indo']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center bg-light">
                            <small class="d-block text-muted mb-1">B. Inggris</small>
                            <span class="fw-bold fs-5"><?php echo $data['grade_english']; ?></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded text-center bg-light">
                            <small class="d-block text-muted mb-1">IPA</small>
                            <span class="fw-bold fs-5"><?php echo $data['grade_science']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <span class="fw-bold">Rata-rata: </span>
                    <span class="badge bg-dark fs-6"><?php echo number_format($data['grade_average'], 2); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 fs-6"><i class="fas fa-address-book me-2"></i>Kontak</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">No. WA Orang Tua/Wali</label>
                    <div class="d-flex align-items-center mt-1">
                        <i class="fab fa-whatsapp text-success fa-2x me-2"></i>
                        <span class="fs-5 fw-bold"><?php echo htmlspecialchars($data['parent_phone']); ?></span>
                    </div>
                    <a href="https://wa.me/<?php echo preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $data['parent_phone'])); ?>" target="_blank" class="btn btn-success btn-sm w-100 mt-2">Hubungi via WhatsApp</a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0 fs-6"><i class="fas fa-image me-2"></i>Bukti Rapor</h5>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($data['report_card_file']) && file_exists('../' . $data['report_card_file'])): ?>
                    <img src="../<?php echo $data['report_card_file']; ?>" alt="Foto Rapor" class="img-fluid rounded border mb-2" style="max-height: 300px;">
                    <a href="../<?php echo $data['report_card_file']; ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2"><i class="fas fa-expand me-1"></i>Lihat Ukuran Penuh</a>
                <?php else: ?>
                    <div class="text-muted py-4">
                        <i class="fas fa-image-slash fa-3x mb-2"></i>
                        <p>Tidak ada foto rapor.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="ppdb.php?delete=<?php echo $data['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data pendaftaran ini?')"><i class="fas fa-trash me-2"></i>Hapus Pendaftaran</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
