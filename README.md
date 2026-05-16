# Website Resmi SMK Cokroaminoto 2 Banjarnegara

Sistem Informasi Manajemen Sekolah dan Portal Berita Resmi SMK Cokroaminoto 2 Banjarnegara berbasis PHP Native.

## 🚀 Fitur Utama

- **Portal Berita**: Manajemen berita sekolah, kategori, dan detail berita yang SEO-friendly.
- **Sistem PPDB**: Pendaftaran Peserta Didik Baru secara online dengan formulir yang lengkap.
- **Tracer Study (Alumni)**: Pendataan alumni untuk memantau keterserapan di dunia kerja dan pendidikan lanjutan.
- **Panel Admin Kustom**: Dashboard manajemen konten (berita, galeri, alumni, PPDB, dan pengaturan sekolah).
- **Galeri Foto**: Manajemen album foto kegiatan sekolah.
- **Integrasi Multimedia**: Fitur Radio Cakra FM dan pembacaan Quran (Text & Audio).
- **Optimasi Database**: Skrip otomatis untuk optimasi dan pembersihan database.
- **Responsif**: Desain yang dioptimalkan untuk perangkat mobile dan desktop.

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 7.4+ (Native)
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, Vanilla CSS, FontAwesome
- **Library**: jQuery (untuk beberapa komponen interaktif)

## 📂 Struktur Proyek Utama

- `/admin`: Panel kontrol untuk administrator.
- `/data`: Penyimpanan file konfigurasi JSON dan data statis.
- `/images`: Aset gambar, banner, dan logo.
- `/plugins`: Plugin tambahan seperti Cakra FM dan fitur religi.
- `config.php`: Konfigurasi koneksi database.
- `index.php`: Halaman utama website.

## 🔧 Instalasi

1. Clone repositori ini:
   ```bash
   git clone https://github.com/diskonnekted/WEB-SMK-Cokroaminoto-2.git
   ```
2. Impor database SQL yang disediakan (cek file `.sql` di root jika ada atau gunakan skrip migrasi).
3. Sesuaikan konfigurasi database di `config.php`:
   ```php
   $host = "localhost";
   $user = "username_anda";
   $pass = "password_anda";
   $db   = "nama_database";
   ```
4. Pastikan folder `uploads/` memiliki izin tulis (write permission).

## 📄 Lisensi

© 2026 SMK Cokroaminoto 2 Banjarnegara. All Rights Reserved.
