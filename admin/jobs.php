<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM job_vacancies WHERE id = $id");
    echo "<script>window.location.href='jobs.php';</script>";
}

// Fetch Jobs
$jobs = [];
$result = $conn->query("SELECT * FROM job_vacancies ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Bursa Kerja Khusus (BKK)</h2>
    <a href="jobs_create.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Lowongan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Perusahaan</th>
                        <th>Posisi</th>
                        <th>Batas Waktu</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jobs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada info lowongan kerja.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($jobs as $index => $job): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo $job['company']; ?></div>
                            </td>
                            <td><?php echo $job['title']; ?></td>
                            <td><?php echo ($job['deadline']) ? date('d M Y', strtotime($job['deadline'])) : '-'; ?></td>
                            <td>
                                <?php if ($job['status'] == 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tutup</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="jobs_edit.php?id=<?php echo $job['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="jobs.php?delete=<?php echo $job['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus lowongan ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>