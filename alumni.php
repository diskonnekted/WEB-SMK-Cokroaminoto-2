<?php
require_once 'header_public.php';

// Handle Alumni Registration
$alumni_msg = '';
if (isset($_POST['register_alumni'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $grad_year = $conn->real_escape_string($_POST['grad_year']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $job = $conn->real_escape_string($_POST['job']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $sql_alumni = "INSERT INTO alumni (name, graduation_year, phone, email, current_job, address, status) VALUES ('$name', '$grad_year', '$phone', '$email', '$job', '$address', 'pending')";
    
    if ($conn->query($sql_alumni) === TRUE) {
        $alumni_msg = '<div class="alert alert-success">Terima kasih! Data Anda telah berhasil dikirim dan menunggu verifikasi admin.</div>';
    } else {
        $alumni_msg = '<div class="alert alert-danger">Maaf, terjadi kesalahan: ' . $conn->error . '</div>';
    }
}
?>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Content) -->
        <div class="main-column">
            
            <div class="card shadow-sm mb-4" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Halaman Alumni</h1>
                <div class="page-content" style="line-height: 1.8;">
                    <p>Selamat datang di halaman khusus Alumni SMK Cokroaminoto 2 Banjarnegara. Halaman ini didedikasikan untuk mempererat tali silaturahmi antar alumni serta memberikan informasi terkini mengenai kegiatan dan berita seputar alumni.</p>
                </div>
            </div>

            <!-- Job Vacancies Section -->
            <?php
            $jobs_q = $conn->query("SELECT * FROM job_vacancies WHERE status = 'active' ORDER BY created_at DESC LIMIT 3");
            
            if ($jobs_q && $jobs_q->num_rows > 0) {
                echo '<div class="section-title" style="margin-top: 20px;"><h2>Info Lowongan Kerja Terbaru</h2></div>';
                echo '<div class="jobs-list mb-4">';
                while ($job = $jobs_q->fetch_assoc()) {
                    echo '<div class="card shadow-sm mb-3 border-0" style="background: white; border-radius: 8px; overflow: hidden; border: 1px solid #eee;">';
                    echo '  <div class="card-body p-3">';
                    echo '      <div class="d-flex justify-content-between align-items-center mb-2">';
                    echo '          <h5 class="mb-0" style="color: var(--nu-green); font-size: 1.1rem;">' . htmlspecialchars($job['title']) . '</h5>';
                    if ($job['deadline']) {
                        echo '          <span class="badge bg-danger" style="font-size: 0.8rem;">Deadline: ' . date('d M Y', strtotime($job['deadline'])) . '</span>';
                    }
                    echo '      </div>';
                    echo '      <h6 class="text-muted mb-2">' . htmlspecialchars($job['company']) . '</h6>';
                    echo '      <p class="mb-2 text-muted small">' . substr(strip_tags($job['description']), 0, 150) . '...</p>';
                    echo '      <a href="bkk.php" class="btn btn-sm btn-outline-success">Lihat Detail</a>';
                    echo '  </div>';
                    echo '</div>';
                }
                echo '<div class="text-center mt-3"><a href="bkk.php" class="btn btn-success">Lihat Semua Lowongan</a></div>';
                echo '</div>';
            }
            ?>

            <!-- Alumni News Section -->
            <?php
            $category = 'Alumni';
            $news_q = $conn->query("SELECT * FROM news WHERE category = '$category' ORDER BY created_at DESC");
            
            echo '<div class="section-title" style="margin-top: 20px;"><h2>Berita ' . strtoupper($category) . '</h2></div>';
            
            if ($news_q && $news_q->num_rows > 0) {
                echo '<div class="news-list">';
                while ($news = $news_q->fetch_assoc()) {
                    $img = $news['image'];
                    if (!filter_var($img, FILTER_VALIDATE_URL) && !empty($img)) {
                        $img = $img;
                    }
                    echo '<article class="news-item">';
                    echo '  <div class="news-thumb"><img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($news['title']) . '"></div>';
                    echo '  <div class="news-content">';
                    echo '      <div class="news-meta"><span>' . htmlspecialchars($news['category']) . '</span> • ' . indo_date($news['created_at']) . '</div>';
                    echo '      <h3 class="news-title"><a href="news_detail.php?id=' . intval($news['id']) . '">' . htmlspecialchars($news['title']) . '</a></h3>';
                    echo '      <p class="news-excerpt">' . substr(strip_tags($news['content']), 0, 150) . '...</p>';
                    echo '  </div>';
                    echo '</article>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info">Belum ada berita untuk kategori ini.</div>';
            }
            ?>

            <!-- Form Alumni -->
            <div class="card shadow-sm mt-4" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <h3 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Form Pendataan Alumni</h3>
                
                <?php echo $alumni_msg; ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="register_alumni" value="1">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" required placeholder="Nama lengkap sesuai ijazah">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Lulus</label>
                            <input type="number" class="form-control" name="grad_year" required min="1900" max="2099" placeholder="Contoh: 2023">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor HP / WhatsApp</label>
                            <input type="text" class="form-control" name="phone" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email@contoh.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pekerjaan / Aktivitas Saat Ini</label>
                        <input type="text" class="form-control" name="job" placeholder="Mahasiswa / Karyawan PT... / Wirausaha...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea class="form-control" name="address" rows="3" placeholder="Alamat lengkap saat ini"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="background-color: var(--nu-green); border: none;">Kirim Data Alumni</button>
                </form>
            </div>

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
                    
                    if ($pop_result->num_rows > 0) {
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
