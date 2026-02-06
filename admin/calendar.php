<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM calendar_events WHERE id = $id");
    echo "<script>window.location='calendar.php';</script>";
}

// Handle Add/Edit
$edit_mode = false;
$event = [
    'title' => '',
    'start_date' => date('Y-m-d'),
    'end_date' => '',
    'description' => '',
    'category' => 'academic'
];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM calendar_events WHERE id = $id");
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $start_date = $conn->real_escape_string($_POST['start_date']);
    $end_date = !empty($_POST['end_date']) ? "'" . $conn->real_escape_string($_POST['end_date']) . "'" : "NULL";
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category']);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = intval($_POST['id']);
        $sql = "UPDATE calendar_events SET title='$title', start_date='$start_date', end_date=$end_date, description='$description', category='$category' WHERE id=$id";
    } else {
        // Insert
        $sql = "INSERT INTO calendar_events (title, start_date, end_date, description, category) VALUES ('$title', '$start_date', $end_date, '$description', '$category')";
    }

    if ($conn->query($sql)) {
        echo "<div class='alert alert-success'>Data berhasil disimpan!</div>";
        echo "<script>setTimeout(function(){ window.location='calendar.php'; }, 1000);</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Kelola Kalender Akademik</h2>
</div>

<div class="row">
    <!-- Form Section -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $edit_mode ? 'Edit Agenda' : 'Tambah Agenda Baru'; ?></h6>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" class="form-control" name="title" required value="<?php echo htmlspecialchars($event['title']); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" required value="<?php echo $event['start_date']; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai (Opsional)</label>
                        <input type="date" class="form-control" name="end_date" value="<?php echo $event['end_date']; ?>">
                        <small class="text-muted">Isi jika kegiatan lebih dari 1 hari.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="category">
                            <option value="academic" <?php echo $event['category'] == 'academic' ? 'selected' : ''; ?>>Akademik</option>
                            <option value="holiday" <?php echo $event['category'] == 'holiday' ? 'selected' : ''; ?>>Hari Libur</option>
                            <option value="event" <?php echo $event['category'] == 'event' ? 'selected' : ''; ?>>Acara Sekolah</option>
                            <option value="exam" <?php echo $event['category'] == 'exam' ? 'selected' : ''; ?>>Ujian</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Simpan Agenda</button>
                    <?php if ($edit_mode): ?>
                        <a href="calendar.php" class="btn btn-secondary w-100 mt-2">Batal Edit</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- List Section -->
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Agenda</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kegiatan</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM calendar_events ORDER BY start_date DESC";
                            $result = $conn->query($query);
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $date_str = date('d M Y', strtotime($row['start_date']));
                                    if ($row['end_date']) {
                                        $date_str .= ' - ' . date('d M Y', strtotime($row['end_date']));
                                    }
                                    
                                    $badge_class = 'bg-primary';
                                    if ($row['category'] == 'holiday') $badge_class = 'bg-danger';
                                    if ($row['category'] == 'event') $badge_class = 'bg-success';
                                    if ($row['category'] == 'exam') $badge_class = 'bg-warning text-dark';
                                    
                                    echo "<tr>";
                                    echo "<td>{$date_str}</td>";
                                    echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong><br><small class='text-muted'>" . htmlspecialchars($row['description']) . "</small></td>";
                                    echo "<td><span class='badge {$badge_class}'>" . ucfirst($row['category']) . "</span></td>";
                                    echo "<td>
                                            <a href='calendar.php?edit={$row['id']}' class='btn btn-sm btn-info'><i class='fas fa-edit'></i></a>
                                            <a href='calendar.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'><i class='fas fa-trash'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Belum ada data agenda.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>