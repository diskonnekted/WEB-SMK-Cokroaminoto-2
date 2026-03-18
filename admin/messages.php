<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM contact_messages WHERE id = $id");
    echo "<script>window.location='messages.php';</script>";
}

// Handle Mark as Read
if (isset($_GET['mark_read'])) {
    $id = intval($_GET['mark_read']);
    $conn->query("UPDATE contact_messages SET status = 'read' WHERE id = $id");
    echo "<script>window.location='messages.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Kotak Masuk Pesan</h2>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pesan Masuk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Pengirim</th>
                        <th width="15%">Tujuan</th>
                        <th width="35%">Pesan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
                    $result = $conn->query($query);
                    
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            $status_class = $row['status'] == 'unread' ? 'table-warning' : '';
                            $date_str = date('d M Y H:i', strtotime($row['created_at']));
                            
                            echo "<tr class='{$status_class}'>";
                            echo "<td>{$no}</td>";
                            echo "<td>{$date_str}</td>";
                            echo "<td>
                                    <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                                    <small class='text-muted'><i class='fas fa-phone-alt me-1'></i> " . htmlspecialchars($row['phone']) . "</small>
                                  </td>";
                            echo "<td><span class='badge bg-info'>" . htmlspecialchars($row['purpose']) . "</span></td>";
                            echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                            echo "<td>
                                    <div class='btn-group'>
                                        " . ($row['status'] == 'unread' ? "<a href='messages.php?mark_read={$row['id']}' class='btn btn-sm btn-success' title='Tandai Dibaca'><i class='fas fa-check'></i></a>" : "") . "
                                        <a href='messages.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus pesan ini?\")' title='Hapus'><i class='fas fa-trash'></i></a>
                                    </div>
                                  </td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Belum ada pesan masuk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>