<?php
require_once 'header_public.php';

// Fetch all gallery images
$sql = "SELECT ng.*, n.title as news_title, n.slug as news_slug 
        FROM news_gallery ng 
        JOIN news n ON ng.news_id = n.id 
        ORDER BY ng.id DESC";
$result = $conn->query($sql);
?>

<div class="container main-content">
    <div class="card shadow-sm" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
        <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Galeri Foto</h1>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="gallery-item" style="border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                        <a href="news_detail.php?id=<?php echo $row['news_id']; ?>">
                            <?php 
                                $img_src = $row['image_path'];
                                if (!filter_var($img_src, FILTER_VALIDATE_URL)) {
                                    // Handle relative paths if necessary, assuming stored as 'uploads/...'
                                    // If stored as full path or relative to root, adjust accordingly.
                                    // Based on previous code, it seems paths are relative.
                                }
                            ?>
                            <img src="<?php echo $row['image_path']; ?>" alt="Galeri" style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="p-2" style="background: #f9f9f9;">
                            <small class="text-muted"><i class="fas fa-camera"></i> <?php echo htmlspecialchars($row['news_title']); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Belum ada foto di galeri.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer_public.php'; ?>
