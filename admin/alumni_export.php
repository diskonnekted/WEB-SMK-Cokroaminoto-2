<?php
require_once '../config.php';

// Check login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Filter logic
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = "";
if ($status_filter) {
    $where = "WHERE status = '" . $conn->real_escape_string($status_filter) . "'";
}

// Filename
$filename = "data_alumni_" . date('Ymd') . ".xls";

// Headers for Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch Data
$alumni_list = [];
$result = $conn->query("SELECT * FROM alumni $where ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>
    <h3>Data Alumni SMK Cokroaminoto 2 Banjarnegara</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Daftar</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Tahun Lulus</th>
                <th>Jurusan</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Pekerjaan Saat Ini</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()): 
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                <td>'<?php echo htmlspecialchars($row['nik']); ?></td> <!-- Add ' to force string in Excel -->
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['graduation_year']); ?></td>
                <td><?php echo htmlspecialchars($row['major']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td>'<?php echo htmlspecialchars($row['phone']); ?></td> <!-- Add ' to force string in Excel -->
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['current_job']); ?></td>
                <td>
                    <?php 
                    if($row['status'] == 'approved') echo 'Disetujui';
                    else echo 'Pending';
                    ?>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
