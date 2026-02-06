<?php
require_once 'config.php';
require_once 'header_public.php';
?>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Content) -->
        <div class="main-column">
            
            <div class="card shadow-sm mb-4" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Kontak Kami</h1>
                
                <div class="page-content" style="line-height: 1.8;">
                    <p class="mb-4">Terima kasih atas kunjungan Anda ke website SMK Cokroaminoto 2 Banjarnegara. Jika Anda memiliki pertanyaan, saran, atau ingin mendapatkan informasi lebih lanjut mengenai sekolah kami, silakan hubungi kami melalui kontak di bawah ini atau datang langsung ke lokasi kami.</p>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h4 style="color: var(--nu-green); margin-bottom: 15px;"><i class="fas fa-map-marker-alt me-2"></i> Alamat</h4>
                            <p>
                                <strong>SMK Cokroaminoto 2 Banjarnegara</strong><br>
                                Jl. Letjend Soeprapto No. 221, Wangon<br>
                                Kec. Banjarnegara, Kab. Banjarnegara<br>
                                Jawa Tengah 53417
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h4 style="color: var(--nu-green); margin-bottom: 15px;"><i class="fas fa-phone-alt me-2"></i> Kontak</h4>
                            <p>
                                <i class="fas fa-envelope me-2 text-muted"></i> Email: <a href="mailto:humascorduba@gmail.com" style="color: inherit;">humascorduba@gmail.com</a><br>
                                <i class="fab fa-instagram me-2 text-muted"></i> Instagram: <a href="https://instagram.com/corduba.official" target="_blank" style="color: inherit;">@corduba.official</a><br>
                                <i class="fab fa-facebook me-2 text-muted"></i> Facebook: <a href="https://www.facebook.com/p/SMK-Cokroaminoto-2-Banjarnegara-100077484438556/" target="_blank" style="color: inherit;">SMK Cokroaminoto 2 Banjarnegara</a>
                            </p>
                        </div>
                    </div>

                    <h4 style="color: var(--nu-green); margin-bottom: 15px;"><i class="fas fa-map me-2"></i> Peta Lokasi</h4>
                    <div class="map-container" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.887465228087!2d109.6845347758364!3d-7.398863272491178!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6ff326c5478479%3A0x6a05372333e66974!2sSMK%20Cokroaminoto%202%20Banjarnegara!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0; position: absolute; top: 0; left: 0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                </div>
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
