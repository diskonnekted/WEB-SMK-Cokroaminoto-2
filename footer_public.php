<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo mb-3">
                    <img src="images/logocokro2.png" alt="Logo SMK Cokroaminoto 2" style="max-width: 120px; height: auto; display: block;">
                </div>
                <h3>SMK Cokroaminoto 2</h3>
                <p>Terwujudnya Generasi Muslim Unggulan yang Mandiri dan Berdaya Saing</p>
                <br>
                <p><strong>Alamat:</strong><br><?php echo isset($settings['address']) ? $settings['address'] : 'Alamat Sekolah'; ?></p>
            </div>
            <div class="footer-col">
                <h3>Tautan Cepat</h3>
                <ul class="footer-links">
                    <li><a href="page.php?slug=profil">Profil</a></li>
                    <li><a href="gallery.php">Galeri</a></li>
                    <li><a href="bkk.php">BKK Alumni</a></li>
                    <li><a href="ppdb.php">SPMB Online</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Hubungi Kami</h3>
                <ul class="footer-links">
                    <li>Email: <?php echo isset($settings['email']) ? $settings['email'] : 'email@sekolah.sch.id'; ?></li>
                    <li>Telp: <?php echo isset($settings['phone']) ? $settings['phone'] : '08123456789'; ?></li>
                </ul>
                <div style="margin-top: 20px;">
                    <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
                    <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="color: white; margin-right: 15px; font-size: 1.2rem;"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> SMK Cokroaminoto 2 Banjarnegara. All Rights Reserved.</p>
            <p class="mb-0 small" style="margin-top: 5px;">SmartSchool Lite By <a href="https://www.clasnet.co.id" target="_blank" style="color: #fff; text-decoration: none;">Clasnet</a></p>
        </div>
    </div>
</footer>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
