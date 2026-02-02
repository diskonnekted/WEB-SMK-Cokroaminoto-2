<?php
require_once 'header.php';

// Handle Add Menu
if (isset($_POST['add_menu'])) {
    $label = $conn->real_escape_string($_POST['label']);
    $url = $conn->real_escape_string($_POST['url']);
    $sort_order = intval($_POST['sort_order']);
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : 'NULL';
    
    $conn->query("INSERT INTO menus (label, url, sort_order, parent_id) VALUES ('$label', '$url', $sort_order, $parent_id)");
    echo "<script>window.location.href='menus.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Check if it has children
    $check = $conn->query("SELECT id FROM menus WHERE parent_id = $id");
    if ($check->num_rows > 0) {
        echo "<script>alert('Menu ini memiliki submenu. Hapus submenu terlebih dahulu.'); window.location.href='menus.php';</script>";
    } else {
        $conn->query("DELETE FROM menus WHERE id = $id");
        echo "<script>window.location.href='menus.php';</script>";
    }
}

// Handle Update Order/Parent
if (isset($_POST['update_menu'])) {
    $id = intval($_POST['id']);
    $label = $conn->real_escape_string($_POST['label']);
    $url = $conn->real_escape_string($_POST['url']);
    $sort_order = intval($_POST['sort_order']);
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : 'NULL';
    
    // Prevent setting parent to itself
    if ($parent_id == $id) {
        $parent_id = 'NULL';
    }

    $conn->query("UPDATE menus SET label='$label', url='$url', sort_order=$sort_order, parent_id=$parent_id WHERE id=$id");
    echo "<script>window.location.href='menus.php';</script>";
}

// Fetch All Menus
$menus = [];
$result = $conn->query("SELECT * FROM menus ORDER BY sort_order ASC");
while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

// Organize into hierarchy for display and selection
$parent_menus = [];
$child_menus = [];
foreach ($menus as $m) {
    if (empty($m['parent_id'])) {
        $parent_menus[] = $m;
    } else {
        $child_menus[$m['parent_id']][] = $m;
    }
}

// Fetch Available Pages for easy linking
$pages = [];
$p_result = $conn->query("SELECT title, slug FROM pages ORDER BY title ASC");
while ($row = $p_result->fetch_assoc()) {
    $pages[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Menu Navigasi</h2>
</div>

<div class="row">
    <!-- Add Menu Form -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fs-6">Tambah Menu Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="add_menu" value="1">
                    <div class="mb-3">
                        <label class="form-label">Label Menu</label>
                        <input type="text" class="form-control" name="label" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL / Link</label>
                        <input type="text" class="form-control" name="url" id="url_input" required>
                        <small class="text-muted">Contoh: <code>page.php?slug=profil</code></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Induk Menu (Parent)</label>
                        <select class="form-select" name="parent_id">
                            <option value="">-- Menu Utama (Tidak ada induk) --</option>
                            <?php foreach ($parent_menus as $pm): ?>
                                <option value="<?php echo $pm['id']; ?>"><?php echo $pm['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="sort_order" value="0">
                    </div>
                    
                    <hr>
                    <h6>Pilih Halaman Statis:</h6>
                    <select class="form-select mb-3" onchange="document.getElementById('url_input').value = this.value">
                        <option value="">-- Pilih Halaman --</option>
                        <option value="index.php">Beranda (index.php)</option>
                        <?php foreach ($pages as $p): ?>
                        <option value="page.php?slug=<?php echo $p['slug']; ?>"><?php echo $p['title']; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-success w-100">Tambah Menu</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Menu List -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="50">Urutan</th>
                                <th>Label</th>
                                <th>URL</th>
                                <th>Induk</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($parent_menus)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada menu.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($parent_menus as $menu): ?>
                                    <!-- Parent Row -->
                                    <tr class="table-light">
                                        <form method="POST" action="">
                                            <input type="hidden" name="update_menu" value="1">
                                            <input type="hidden" name="id" value="<?php echo $menu['id']; ?>">
                                            <td>
                                                <input type="number" name="sort_order" value="<?php echo $menu['sort_order']; ?>" class="form-control form-control-sm" style="width: 60px;">
                                            </td>
                                            <td>
                                                <input type="text" name="label" value="<?php echo $menu['label']; ?>" class="form-control form-control-sm fw-bold">
                                            </td>
                                            <td>
                                                <input type="text" name="url" value="<?php echo $menu['url']; ?>" class="form-control form-control-sm text-muted">
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Menu Utama</span>
                                                <input type="hidden" name="parent_id" value="">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary" title="Update"><i class="fas fa-save"></i></button>
                                                <a href="menus.php?delete=<?php echo $menu['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')" title="Delete"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </form>
                                    </tr>

                                    <!-- Children Rows -->
                                    <?php if (isset($child_menus[$menu['id']])): ?>
                                        <?php foreach ($child_menus[$menu['id']] as $child): ?>
                                        <tr>
                                            <form method="POST" action="">
                                                <input type="hidden" name="update_menu" value="1">
                                                <input type="hidden" name="id" value="<?php echo $child['id']; ?>">
                                                <td>
                                                    <input type="number" name="sort_order" value="<?php echo $child['sort_order']; ?>" class="form-control form-control-sm" style="width: 60px;">
                                                </td>
                                                <td style="padding-left: 40px;">
                                                    <i class="fas fa-level-up-alt fa-rotate-90 me-2 text-muted"></i>
                                                    <input type="text" name="label" value="<?php echo $child['label']; ?>" class="form-control form-control-sm d-inline-block" style="width: auto;">
                                                </td>
                                                <td>
                                                    <input type="text" name="url" value="<?php echo $child['url']; ?>" class="form-control form-control-sm text-muted">
                                                </td>
                                                <td>
                                                    <select name="parent_id" class="form-select form-select-sm">
                                                        <?php foreach ($parent_menus as $pm): ?>
                                                            <option value="<?php echo $pm['id']; ?>" <?php echo $pm['id'] == $child['parent_id'] ? 'selected' : ''; ?>><?php echo $pm['label']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Update"><i class="fas fa-save"></i></button>
                                                    <a href="menus.php?delete=<?php echo $child['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')" title="Delete"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </form>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
