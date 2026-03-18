<?php
require_once 'config.php';

// Script untuk update konten Profil Sekolah di Live Server
// Upload file ini ke hosting Anda, lalu buka di browser (misal: smkc2.com/update_db_profil_live.php)
// Setelah berhasil, HAPUS file ini demi keamanan.

$content = <<<'EOD'
<style>
    /* Custom Green Tabs */
    .nav-pills-green .nav-link {
        color: #008f4c;
        background-color: transparent;
        margin: 0 5px;
        transition: all 0.3s;
        border: 1px solid transparent;
        font-weight: bold;
    }
    .nav-pills-green .nav-link:hover {
        background-color: rgba(0, 143, 76, 0.1);
        color: #00703c;
    }
    .nav-pills-green .nav-link.active {
        background-color: #008f4c !important;
        color: white !important;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>

<!-- Hero Section -->
<div class="text-white p-5 rounded-3 mb-5 shadow position-relative overflow-hidden" 
     style="background: linear-gradient(rgba(0, 143, 76, 0.85), rgba(0, 112, 60, 0.9)), url('uploads/news/default_news.jpg'); background-size: cover; background-position: center;">
    
    <div class="container-fluid py-3 position-relative">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold"><i class="fas fa-university me-3"></i>Profil Sekolah</h1>
                <p class="fs-4 mt-2">Mengenal lebih dekat SMK Cokroaminoto 2 Banjarnegara, tempat lahirnya generasi unggul.</p>
            </div>
            <!-- Logo Column -->
            <div class="col-md-4 text-center d-none d-md-block">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg" style="width: 250px; height: 250px;">
                    <img src="images/logo.png" alt="Logo Sekolah" class="img-fluid p-2" style="max-height: 230px;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sejarah Section -->
<div class="row mb-5">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm h-100 bg-light">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                    <h2 class="fw-bold text-dark m-0">Sejarah Singkat</h2>
                </div>
                <div class="p-4 bg-white rounded shadow-sm border-start border-5 border-success">
                    <p class="lead text-secondary fst-italic mb-0" style="line-height: 1.8;">
                        SMK Cokroaminoto 2 Banjarnegara atau STM Cokroaminoto Banjarnegara adalah sekolah di bawah naungan Yayasan Pendidikan Islam Cokroaminoto Kabupaten Banjarnegara. Berdiri pada tanggal 9 Mei 1994 berdasarkan Surat Persetujuan Pendirian Penyelenggaraan Sekolah Swasta, nomor : 528/103/I/94 oleh Kepala kantor Wilayah Departemen Pendidikan dan Kebudayaan Propinsi Jawa Tengah. Sekolah ini didirikan dan didanai oleh alm. Bapak H Muctharom Suja'i. Beliau adalah ketua Umum Yayasan Pendidikan Islam Cokroaminoto Kabupaten Banjarnegara pada saat berdirinya sekolah ini sampai wafatnya.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visi Misi Section -->
<div class="row mb-5 g-4">
    <div class="col-12 text-center mb-2">
        <h2 class="fw-bold text-uppercase letter-spacing-2" style="color: #00703c;">Visi & Misi</h2>
        <div class="bg-warning mx-auto rounded-pill" style="width: 80px; height: 4px;"></div>
    </div>
    
    <!-- Visi -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow hover-top transition-300" style="background: linear-gradient(to bottom right, #ffffff, #f0f9f4);">
            <div class="card-body p-5 text-center">
                <div class="mb-4 d-inline-block p-3 rounded-circle bg-success bg-opacity-10 text-success">
                    <i class="fas fa-bullseye fa-3x"></i>
                </div>
                <h3 class="h4 fw-bold mb-3 text-success">VISI SEKOLAH</h3>
                <p class="fs-5 fst-italic text-muted px-lg-3">"Menjadi sekolah yang unggul dalam prestasi dilandasi Iman dan Taqwa serta menghasilkan tamatan yang mampu bersaing dalam profesinya, mempunyai sikap produktif, adaptif, dan kreatif dalam berkarya."</p>
            </div>
        </div>
    </div>
    
    <!-- Misi -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow hover-top transition-300">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="d-inline-block p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-tasks fa-3x"></i>
                    </div>
                    <h3 class="h4 fw-bold mt-3 text-warning">MISI SEKOLAH</h3>
                </div>
                <ul class="list-group list-group-flush"><li class="list-group-item border-0 bg-transparent d-flex align-items-start mb-2"><i class="fas fa-check-circle text-success mt-1 me-3"></i> <span>Menumbuhkan penghayatan terhadap agama islam dan budaya bangsa Indonesia sebagai sumber kearifan dalam bertindak.</span></li><li class="list-group-item border-0 bg-transparent d-flex align-items-start mb-2"><i class="fas fa-check-circle text-success mt-1 me-3"></i> <span>Menumbuhkan semangat keunggulan kompetitif, produktif, kreatif,dan adaptif secara intensif kepada seluruh warga sekolah.</span></li><li class="list-group-item border-0 bg-transparent d-flex align-items-start mb-2"><i class="fas fa-check-circle text-success mt-1 me-3"></i> <span>Melaksanakan kegiatan belajar mengajar secara optimal yang berorientasi kepada pencapaian kompetensi dengan mengembangkan sistem pendidikan yang adaptif, fleksibel dan berwawasan global.</span></li><li class="list-group-item border-0 bg-transparent d-flex align-items-start mb-2"><i class="fas fa-check-circle text-success mt-1 me-3"></i> <span>Mewujudkan pelayanan prima dalam upaya pemberdayaan sekolah dan masyarakat.</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Data -->
<div class="card shadow border-0 mb-5 overflow-hidden">
    <div class="card-header bg-white border-bottom-0 p-0">
        <ul class="nav nav-pills nav-pills-green nav-fill p-2 bg-light rounded-top" id="profilTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active py-3" id="identitas-tab" data-bs-toggle="pill" data-bs-target="#identitas" type="button" role="tab"><i class="fas fa-school me-2"></i>Identitas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3" id="pelengkap-tab" data-bs-toggle="pill" data-bs-target="#pelengkap" type="button" role="tab"><i class="fas fa-file-alt me-2"></i>Data Pelengkap</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link py-3" id="ptk-tab" data-bs-toggle="pill" data-bs-target="#ptk" type="button" role="tab"><i class="fas fa-users me-2"></i>PTK & PD</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-4">
        <div class="tab-content" id="profilTabsContent">
            <!-- Identitas Tab -->
            <div class="tab-pane fade show active" id="identitas" role="tabpanel">
                <h4 class="mb-4 text-success fw-bold"><i class="fas fa-school me-2"></i>A. Identitas Sekolah</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr><th width="30%">Nama Sekolah</th><td>SMK Cokroaminoto 2 Banjarnegara</td></tr>
                                <tr><th>NSS</th><td>324 03 04 06 004</td></tr>
                                <tr><th>NPSN</th><td>20303949</td></tr>
                                <tr><th>Alamat Sekolah</th><td>Jl. Letjend Soeprapto 221 Banjarnegara</td></tr>
                                <tr><th>Telepon/fax</th><td>0286 592592</td></tr>
                                <tr><th>Website</th><td><a href="http://www.smkcokro2bna.sch.id" target="_blank">http://www.smkcokro2bna.sch.id</a></td></tr>
                                <tr><th>Email Sekolah</th><td>smkcokro2bna@gmail.com</td></tr>
                                <tr>
                                    <th>SK Ijin Pendirian Sekolah</th>
                                    <td>
                                        <strong>Nomor:</strong> 528 / I03/I / 1994<br>
                                        <strong>Tanggal:</strong> 9 Mei 1994<br>
                                        <strong>Yang mengeluarkan:</strong> an. Menteri pendidikan dan Kebudayaan, Kepala kantor Wilayah Departemen Pendidikan dan Kebudayaan Propinsi Jawa Tengah.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h4 class="mb-4 text-success fw-bold"><i class="fas fa-building me-2"></i>B. Yayasan</h4>
                <div class="row g-3">
                    <div class="col-md-12">
                         <table class="table table-bordered table-striped">
                            <tbody>
                                <tr><th width="30%">Nama Yayasan</th><td>YPI Cokroaminoto Banjarnegara</td></tr>
                                <tr><th>Akta Pendirian Yayasan</th><td>Akta Notaris No. 21 Tanggal 19 September 2011</td></tr>
                                <tr>
                                    <th>Pengesahan Yayasan</th>
                                    <td>
                                        dari Kementerian Hukum dan HAM RI<br>
                                        Keputusan Menteri Hukum dan HAM RI<br>
                                        Nomor: AHU-8260.AH.01.04. Tahun 2011
                                    </td>
                                </tr>
                                <tr><th>Alamat Yayasan</th><td>Jln. Pemuda Nomor 63 Banjarnegara</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pelengkap Tab -->
            <div class="tab-pane fade" id="pelengkap" role="tabpanel">
                <h4 class="mb-4 text-success fw-bold">Data Pelengkap</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kebutuhan Khusus</th>
                                <th>Menerima</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Kebutuhan Khusus Dilayani</td><td>Tidak Ada</td></tr>
                            <tr><td>Nama Bank</td><td>BANK JATENG</td></tr>
                            <tr><td>Cabang KCP/Unit</td><td>BANJARNEGARA</td></tr>
                            <tr><td>Rekening Atas Nama</td><td>SMK COKROAMINOTO 2 BNA</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <i class="fas fa-check-circle text-success me-3 fa-2x"></i>
                            <div>
                                <small class="text-muted d-block">MBS</small>
                                <strong>Ya</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <i class="fas fa-check-circle text-success me-3 fa-2x"></i>
                            <div>
                                <small class="text-muted d-block">Sertifikasi ISO</small>
                                <strong>Belum Bersertifikat</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <i class="fas fa-wifi text-success me-3 fa-2x"></i>
                            <div>
                                <small class="text-muted d-block">Akses Internet</small>
                                <strong>Telkomsel Flash</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PTK Tab -->
            <div class="tab-pane fade" id="ptk" role="tabpanel">
                <h4 class="mb-4 text-success fw-bold">Data PTK dan PD <small class="text-muted fs-6 fw-normal ms-2">(Per 7 Februari 2026)</small></h4>
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="table-responsive shadow-sm rounded">
                            <table class="table table-bordered text-center align-middle mb-0">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="py-3">Uraian</th>
                                        <th class="py-3">Guru</th>
                                        <th class="py-3">Tendik</th>
                                        <th class="py-3">PTK</th>
                                        <th class="py-3">PD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-start ps-4">Laki-laki</td>
                                        <td class="bg-light">30</td>
                                        <td class="bg-light">13</td>
                                        <td class="bg-light fw-bold text-success">43</td>
                                        <td class="bg-light fw-bold text-success">864</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-start ps-4">Perempuan</td>
                                        <td>22</td>
                                        <td>10</td>
                                        <td class="fw-bold text-success">32</td>
                                        <td class="fw-bold text-success">83</td>
                                    </tr>
                                    <tr class="bg-success text-white fw-bold"> 
                                        <td class="text-start ps-4">Total</td>
                                        <td>52</td>
                                        <td>23</td>
                                        <td>75</td>
                                        <td>947</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info border-0 mt-3 d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3 text-success"></i>
                            <div>
                                <strong>Keterangan:</strong><br>
                                PTK = Guru ditambah Tendik, PD = Peserta Didik
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
EOD;

$slug = 'profil';
$stmt = $conn->prepare("UPDATE pages SET content = ? WHERE slug = ?");
$stmt->bind_param("ss", $content, $slug);

if ($stmt->execute()) {
    echo '<div style="font-family: sans-serif; padding: 20px; border: 1px solid #ccc; background: #e8f5e9; color: #1b5e20; border-radius: 5px; max-width: 600px; margin: 50px auto; text-align: center;">';
    echo '<h2 style="margin-top:0;">✅ Update Berhasil!</h2>';
    echo '<p>Konten halaman Profil Sekolah telah berhasil diperbarui di database.</p>';
    echo '<hr style="border: 0; border-top: 1px solid #c8e6c9; margin: 15px 0;">';
    echo '<p><strong>PENTING:</strong><br>Demi keamanan, silakan hapus file <code>update_db_profil_live.php</code> dari server/hosting Anda sekarang.</p>';
    echo '<p><a href="page.php?slug=profil" style="display: inline-block; padding: 10px 20px; background: #2e7d32; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">Lihat Halaman Profil</a></p>';
    echo '</div>';
} else {
    echo '<div style="font-family: sans-serif; padding: 20px; border: 1px solid #ccc; background: #ffebee; color: #c62828; border-radius: 5px; max-width: 600px; margin: 50px auto; text-align: center;">';
    echo '<h2 style="margin-top:0;">❌ Update Gagal</h2>';
    echo '<p>Terjadi kesalahan saat mengupdate database:</p>';
    echo '<pre>' . htmlspecialchars($conn->error) . '</pre>';
    echo '</div>';
}
?>