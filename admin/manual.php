<?php
require_once 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Panduan Administrator</h2>
</div>

<div class="row">
    <div class="col-lg-12">

        <!-- Panduan Mengelola Berita & Artikel -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-newspaper me-2"></i>Panduan Mengelola Berita & Artikel (Termasuk Gambar & Video)</h6>
            </div>
            <div class="card-body">
                <p>Fitur ini digunakan untuk memposting berita sekolah, pengumuman, prestasi siswa, atau artikel lainnya.</p>

                <h5 class="mt-4 mb-3 text-info">Langkah 1: Membuat Berita Baru</h5>
                <ol>
                    <li>Klik menu <strong>Berita & Artikel</strong> di sidebar sebelah kiri.</li>
                    <li>Klik tombol <strong><i class="fas fa-plus"></i> Tambah Berita</strong> di bagian atas halaman.</li>
                </ol>

                <h5 class="mt-4 mb-3 text-info">Langkah 2: Mengisi Konten</h5>
                <ul>
                    <li><strong>Judul Berita:</strong> Isi dengan judul yang menarik dan jelas.</li>
                    <li><strong>Kategori:</strong> Pilih kategori yang sesuai (Misal: Berita, Pengumuman, Prestasi).</li>
                    <li><strong>Konten Berita:</strong> Tulis isi berita di kotak editor besar yang tersedia.
                        <br>
                        <div class="alert alert-secondary mt-2 mb-2">
                            <strong>Tips Editor (Summernote):</strong>
                            <ul class="mb-0 mt-1">
                                <li><strong>Menambahkan Gambar di Dalam Teks:</strong> 
                                    Klik ikon <i class="fas fa-image"></i> (Picture) pada toolbar editor &rarr; Pilih file gambar dari komputer &rarr; Gambar akan muncul di antara teks.
                                </li>
                                <li><strong>Menambahkan Video (YouTube):</strong> 
                                    Klik ikon <i class="fas fa-video"></i> (Video) pada toolbar editor &rarr; Tempelkan (Paste) Link YouTube &rarr; Klik Insert. Video akan muncul di dalam artikel.
                                </li>
                                <li><strong>Format Teks:</strong> Gunakan ikon <b>B</b> (Bold), <i>I</i> (Italic), atau Heading (H1, H2) untuk merapikan tulisan.</li>
                            </ul>
                        </div>
                    </li>
                </ul>

                <h5 class="mt-4 mb-3 text-info">Langkah 3: Gambar Utama & Penerbitan</h5>
                <ul>
                    <li><strong>Gambar Utama (Thumbnail):</strong> Klik "Choose File" untuk mengupload gambar sampul berita.
                        <br><small class="text-muted">Gambar ini yang akan muncul di halaman depan (Home) dan daftar berita. Gunakan gambar orientasi Landscape (mendatar) untuk hasil terbaik.</small>
                    </li>
                    <li><strong>Status:</strong>
                        <ul>
                            <li><code>Published</code>: Berita langsung tampil di website publik.</li>
                            <li><code>Draft</code>: Berita hanya disimpan di admin, belum tampil di publik.</li>
                        </ul>
                    </li>
                    <li>Klik tombol <strong>Simpan Berita</strong> untuk menyelesaikan.</li>
                </ul>
            </div>
        </div>
        
        <!-- Panduan Membuat Sub Menu -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-sitemap me-2"></i>Panduan Membuat Sub Menu Navigasi</h6>
            </div>
            <div class="card-body">
                <p>Sub menu (Drop Down) adalah menu yang muncul ketika pengunjung mengarahkan kursor ke menu utama. Berikut langkah-langkah membuatnya:</p>

                <div class="alert alert-info">
                    <strong>Konsep Dasar:</strong> Sub menu terdiri dari <strong>Menu Induk (Parent)</strong> dan <strong>Menu Anak (Child/Submenu)</strong>.
                </div>

                <h5 class="mt-4 mb-3 text-success">Langkah 1: Membuat Menu Induk (Parent)</h5>
                <ol>
                    <li>Buka halaman <strong>Menu Navigasi</strong>.</li>
                    <li>Pada formulir "Tambah Menu Baru":
                        <ul>
                            <li><strong>Label Menu:</strong> Isi nama menu induk (Contoh: <code>Kompetensi Keahlian</code>).</li>
                            <li><strong>URL / Link:</strong> 
                                <ul>
                                    <li>Jika menu induk ini <strong>bisa diklik</strong> ke halaman tertentu, isi dengan link halaman tersebut (Contoh: <code>page.php?slug=kompetensi</code>).</li>
                                    <li>Jika menu induk ini <strong>hanya sebagai pembungkus</strong> dan tidak bisa diklik, isi dengan tanda pagar <code>#</code>.</li>
                                </ul>
                            </li>
                            <li><strong>Induk Menu (Parent):</strong> Pilih <code>-- Menu Utama (Tidak ada induk) --</code>.</li>
                            <li><strong>Urutan:</strong> Tentukan urutan menu.</li>
                        </ul>
                    </li>
                    <li>Klik tombol <strong>Tambah Menu</strong>.</li>
                </ol>

                <h5 class="mt-4 mb-3 text-success">Langkah 2: Membuat Sub Menu (Child)</h5>
                <ol>
                    <li>Tetap di halaman <strong>Menu Navigasi</strong>.</li>
                    <li>Pada formulir "Tambah Menu Baru":
                        <ul>
                            <li><strong>Label Menu:</strong> Isi nama sub menu (Contoh: <code>Teknik Otomotif</code>).</li>
                            <li><strong>URL / Link:</strong> Isi dengan link halaman tujuan (Contoh: <code>page.php?slug=teknik-otomotif</code>).
                                <br><small class="text-muted">Tip: Anda bisa menyalin link dari daftar "Pilih Halaman Statis" di sebelah kanan form.</small>
                            </li>
                            <li><strong>Induk Menu (Parent):</strong> <span class="badge bg-warning text-dark">PENTING</span> Pilih menu induk yang baru saja Anda buat di Langkah 1 (Contoh: pilih <code>Kompetensi Keahlian</code>).</li>
                            <li><strong>Urutan:</strong> Isi urutan tampilan sub menu (Misal: 1 untuk yang paling atas, 2 untuk bawahnya, dst).</li>
                        </ul>
                    </li>
                    <li>Klik tombol <strong>Tambah Menu</strong>.</li>
                    <li>Ulangi langkah ini untuk menambahkan sub menu lainnya di bawah induk yang sama.</li>
                </ol>

                <h5 class="mt-4 mb-3 text-success">Contoh Struktur yang Benar</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Label</th>
                                <th>URL</th>
                                <th>Parent (Induk)</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kompetensi Keahlian</td>
                                <td>#</td>
                                <td>- (Menu Utama)</td>
                                <td>Menu Utama</td>
                            </tr>
                            <tr>
                                <td>Teknik Otomotif</td>
                                <td>page.php?slug=otomotif</td>
                                <td>Kompetensi Keahlian</td>
                                <td>Sub Menu</td>
                            </tr>
                            <tr>
                                <td>Teknik Mesin</td>
                                <td>page.php?slug=mesin</td>
                                <td>Kompetensi Keahlian</td>
                                <td>Sub Menu</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-lightbulb me-2"></i><strong>Tips:</strong>
                    Jika menu drop down tidak muncul di website, pastikan Anda sudah memilih Parent yang benar untuk setiap sub menu.
                </div>
            </div>
        </div>

        <!-- Panduan Membuat Halaman Statis -->
        <div class="card shadow mb-4 mt-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-file-alt me-2"></i>Panduan Membuat Halaman Statis</h6>
            </div>
            <div class="card-body">
                <p>Halaman statis digunakan untuk konten yang jarang berubah, seperti Profil Sekolah, Visi Misi, Sejarah, atau penjelasan Jurusan/Kompetensi Keahlian.</p>

                <h5 class="mt-4 mb-3 text-primary">Langkah-langkah Membuat Halaman Baru:</h5>
                <ol>
                    <li>Buka menu <strong>Halaman Statis</strong> di sidebar.</li>
                    <li>Klik tombol <strong><i class="fas fa-plus"></i> Tambah Halaman Baru</strong>.</li>
                    <li>Isi formulir yang tersedia:
                        <ul>
                            <li><strong>Judul Halaman:</strong> Masukkan judul (Contoh: <code>Visi dan Misi</code>).</li>
                            <li><strong>Konten:</strong> Tulis isi halaman menggunakan editor yang tersedia. Anda bisa menebalkan huruf, membuat daftar, menyisipkan tabel, atau gambar.</li>
                        </ul>
                    </li>
                    <li>Klik tombol <strong>Simpan Halaman</strong>.</li>
                </ol>

                <h5 class="mt-4 mb-3 text-primary">Cara Menghubungkan Halaman ke Menu Navigasi:</h5>
                <p>Setelah halaman dibuat, Anda perlu memasukkannya ke menu agar bisa diakses pengunjung.</p>
                <ol>
                    <li>Setelah menyimpan, lihat daftar halaman di <strong>Halaman Statis</strong>.</li>
                    <li>Perhatikan kolom <strong>Slug/Link</strong> (Contoh: <code>page.php?slug=visi-misi</code>).</li>
                    <li>Buka menu <strong>Menu Navigasi</strong>.</li>
                    <li>Buat menu baru atau edit menu yang ada.</li>
                    <li>Pada kolom <strong>URL / Link</strong>, tempelkan link yang tadi Anda lihat (atau pilih langsung dari daftar halaman di sebelah kanan form).</li>
                    <li>Simpan menu.</li>
                </ol>

                <div class="alert alert-info">
                    <strong>Catatan:</strong> Setiap kali Anda mengubah Judul Halaman, link (slug) mungkin akan berubah jika Anda membuatnya ulang. Namun, mengedit konten tidak akan mengubah link.
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'footer.php'; ?>