<?php
require_once 'header.php';

// Handle Approve
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE alumni SET status = 'approved' WHERE id = $id");
    echo "<script>window.location.href='alumni.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM alumni WHERE id = $id");
    echo "<script>window.location.href='alumni.php';</script>";
}

// Fetch Alumni
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = "";
if ($status_filter) {
    $where = "WHERE status = '" . $conn->real_escape_string($status_filter) . "'";
}

$alumni_list = [];
$result = $conn->query("SELECT * FROM alumni $where ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $alumni_list[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Data Alumni</h2>
    <div>
        <a href="alumni.php" class="btn btn-outline-secondary <?php echo $status_filter == '' ? 'active' : ''; ?>">Semua</a>
        <a href="alumni.php?status=pending" class="btn btn-outline-warning <?php echo $status_filter == 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="alumni.php?status=approved" class="btn btn-outline-success <?php echo $status_filter == 'approved' ? 'active' : ''; ?>">Disetujui</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal Daftar</th>
                        <th>Nama</th>
                        <th>Tahun Lulus</th>
                        <th>Kontak</th>
                        <th>Pekerjaan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (count($alumni_list) > 0):
                        foreach ($alumni_list as $row): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($row['address']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['graduation_year']); ?></td>
                        <td>
                            <i class="fas fa-phone small text-muted"></i> <?php echo htmlspecialchars($row['phone']); ?><br>
                            <i class="fas fa-envelope small text-muted"></i> <?php echo htmlspecialchars($row['email']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['current_job']); ?></td>
                        <td>
                            <?php if($row['status'] == 'approved'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="alumni.php?approve=<?php echo $row['id']; ?>" class="btn btn-sm btn-success mb-1" onclick="return confirm('Setujui data alumni ini?')"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <a href="alumni.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">Belum ada data alumni.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
