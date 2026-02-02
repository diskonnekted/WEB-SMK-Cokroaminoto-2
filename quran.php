<?php
// Main Quran Page
include 'config.php';
include 'header_public.php';
?>

<div class="container-fluid py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-success">Al-Quran Digital</h1>
            <p class="lead text-muted">Baca dan Dengarkan Al-Quran (Text & Audio)</p>
        </div>
        
        <!-- Include Plugin View -->
        <?php include 'plugins/quran-text-audio/view.php'; ?>
        
    </div>
</div>

<?php include 'footer_public.php'; ?>