<?php
require_once 'header_public.php';

// Fetch Active Jobs
$jobs_query = "SELECT * FROM job_vacancies WHERE status = 'active' ORDER BY created_at DESC";
$jobs_result = $conn->query($jobs_query);
?>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Content) -->
        <div class="main-column">
            
            <div class="card shadow-sm mb-4" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Info Bursa Kerja: Untuk Siswa SMK</h1>
                <div class="page-content" style="line-height: 1.8;">
                    <p>Selamat datang di halaman Bursa Kerja Khusus (BKK) SMK Cokroaminoto 2 Banjarnegara. Berikut adalah informasi lowongan kerja terbaru yang tersedia untuk alumni dan siswa SMK.</p>
                </div>
            </div>

            <!-- Jobs List -->
            <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
                <div class="jobs-list">
                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                        <div class="card shadow-sm mb-4 border-0" style="background: white; border-radius: 8px; overflow: hidden;">
                            <div class="card-header border-bottom p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1 text-white" style="font-size: 1.25rem;"><?php echo htmlspecialchars($job['title']); ?></h3>
                                    <h5 class="text-white-50 mb-0" style="font-size: 1rem;"><?php echo htmlspecialchars($job['company']); ?></h5>
                                </div>
                                <?php if ($job['deadline']): ?>
                                    <div class="text-end">
                                        <small class="text-white-50 d-block">Batas Waktu</small>
                                        <span class="badge bg-danger border border-light"><?php echo date('d M Y', strtotime($job['deadline'])); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <?php if ($job['image']): ?>
                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <img src="<?php echo htmlspecialchars($job['image']); ?>" class="img-fluid rounded border" alt="Brosur Lowongan">
                                        </div>
                                        <div class="col-md-8">
                                    <?php else: ?>
                                        <div class="col-12">
                                    <?php endif; ?>
                                        
                                        <div class="mb-3">
                                            <h6 class="fw-bold border-bottom pb-2">Deskripsi Pekerjaan</h6>
                                            <div class="job-description">
                                                <?php echo $job['description']; ?>
                                            </div>
                                        </div>

                                        <?php if ($job['requirements']): ?>
                                        <div class="mb-3">
                                            <h6 class="fw-bold border-bottom pb-2">Kualifikasi</h6>
                                            <div class="job-requirements">
                                                <?php echo $job['requirements']; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if ($job['contact']): ?>
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-paper-plane me-2"></i> <strong>Kirim Lamaran ke:</strong><br>
                                            <?php echo htmlspecialchars($job['contact']); ?>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light p-3 text-muted small">
                                <i class="far fa-clock me-1"></i> Diposting pada: <?php echo indo_date($job['created_at']); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i> Belum ada informasi lowongan kerja yang tersedia saat ini. Silakan cek kembali nanti.
                </div>
            <?php endif; ?>

        </div>

        <!-- Right Column (Sidebar) -->
        <aside class="sidebar">
            
            <!-- Kepala Sekolah Widget -->
            <div class="sidebar-widget">
                <div style="text-align: center;">
                    <img src="<?php echo $settings['kepsek_image'] ?? 'images/placeholder.jpg'; ?>" alt="Kepala Sekolah" style="margin-bottom: 15px; width: 100%; height: auto; object-fit: cover;">
                    <h4 style="color: var(--nu-green);"><?php echo $settings['kepsek_name'] ?? 'Kepala Sekolah'; ?></h4>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 10px;">"<?php echo $settings['kepsek_message'] ?? 'Selamat Datang'; ?>"</p>
                </div>
            </div>

            <!-- Plugin Widget -->
            <?php if(file_exists('plugins/quran-radio/widget.php')) include 'plugins/quran-radio/widget.php'; ?>

            <!-- Popular News (Dynamic) -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Terpopuler</h2>
                </div>
                <ul class="popular-list">
                    <?php
                    // Fetch popular news (ordered by views DESC)
                    $pop_sql = "SELECT id, title, views FROM news ORDER BY views DESC LIMIT 4";
                    $pop_result = $conn->query($pop_sql);
                    
                    if ($pop_result && $pop_result->num_rows > 0) {
                        $rank = 1;
                        while ($pop = $pop_result->fetch_assoc()) {
                            echo '<li>';
                            echo '<span class="popular-number">' . $rank++ . '</span>';
                            echo '<span class="popular-title"><a href="news_detail.php?id=' . $pop['id'] . '">' . htmlspecialchars($pop['title']) . '</a></span>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li><span class="text-muted">Belum ada berita populer.</span></li>';
                    }
                    ?>
                </ul>
            </div>

            <!-- Jurusan Widget -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Kompetensi Keahlian</h2>
                </div>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-ketenagalistrikan">Teknik Instalasi Tenaga Listrik</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-mesin">Teknik Pemesinan</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-pengelasan">Teknik Pengelasan</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-otomotif">Teknik Kendaraan Ringan Otomotif</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=teknik-elektronika">Teknik Audio Video</a>
                    </li>
                    <li style="margin-bottom: 10px; border-left: 3px solid var(--nu-green); padding-left: 10px;">
                        <a href="page.php?slug=multimedia">Multimedia</a>
                    </li>
                </ul>
            </div>

        </aside>

    </div>
</div>

<?php require_once 'footer_public.php'; ?>