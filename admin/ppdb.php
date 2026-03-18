<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // First get file path to delete image
    $file_query = $conn->query("SELECT report_card_file FROM ppdb_registrations WHERE id = $id");
    if ($file_query->num_rows > 0) {
        $file_row = $file_query->fetch_assoc();
        if (!empty($file_row['report_card_file']) && file_exists('../' . $file_row['report_card_file'])) {
            unlink('../' . $file_row['report_card_file']);
        }
    }
    
    $conn->query("DELETE FROM ppdb_registrations WHERE id = $id");
    echo "<script>window.location.href='ppdb.php';</script>";
}

// Fetch Registrations
$registrations = [];
$result = $conn->query("SELECT * FROM ppdb_registrations ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $registrations[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Pendaftaran PPDB</h2>
    <div>
        <a href="ppdb_create.php" class="btn btn-primary me-2"><i class="fas fa-plus me-2"></i>Tambah Data</a>
        <a href="ppdb_export.php" class="btn btn-success"><i class="fas fa-file-excel me-2"></i>Export Excel</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Kecamatan</th>
                        <th>Asal Sekolah</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registrations)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data pendaftaran.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registrations as $reg): ?>
                        <tr>
                            <td>#<?php echo $reg['id']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($reg['full_name']); ?></td>
                            <td><?php echo !empty($reg['district']) ? htmlspecialchars($reg['district']) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($reg['origin_school']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($reg['major']); ?></span></td>
                            <td>
                                <?php
                                $status_class = 'secondary';
                                $status_text = htmlspecialchars($reg['status'] ?? 'Pendaftar Baru');
                                
                                if (stripos($status_text, 'Diterima') !== false && stripos($status_text, 'Tidak') === false) {
                                    $status_class = 'success';
                                } elseif (stripos($status_text, 'Tidak Diterima') !== false) {
                                    $status_class = 'danger';
                                } elseif (stripos($status_text, 'Proses') !== false) {
                                    $status_class = 'warning text-dark';
                                } else {
                                    $status_class = 'info text-dark';
                                }
                                ?>
                                <span class="badge bg-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="ppdb_detail.php?id=<?php echo $reg['id']; ?>" class="btn btn-sm btn-info text-white" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="ppdb_edit.php?id=<?php echo $reg['id']; ?>" class="btn btn-sm btn-warning text-dark" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="ppdb.php?delete=<?php echo $reg['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
                                </div>
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