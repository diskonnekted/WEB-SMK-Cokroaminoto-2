<?php
require_once 'header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM pages WHERE id = $id");
    echo "<script>window.location.href='pages_index.php';</script>";
}

// Fetch Pages
$pages = [];
$result = $conn->query("SELECT * FROM pages ORDER BY title ASC");
while ($row = $result->fetch_assoc()) {
    $pages[] = $row;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Halaman Statis</h2>
    <a href="pages_create.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Halaman</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Judul</th>
                        <th>URL Slug</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pages)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4">Belum ada halaman statis.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($pages as $index => $page): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo $page['title']; ?></div>
                            </td>
                            <td>
                                <code>page.php?slug=<?php echo $page['slug']; ?></code>
                            </td>
                            <td>
                                <a href="pages_edit.php?id=<?php echo $page['id']; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                <a href="pages_index.php?delete=<?php echo $page['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus halaman ini?')"><i class="fas fa-trash"></i></a>
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
