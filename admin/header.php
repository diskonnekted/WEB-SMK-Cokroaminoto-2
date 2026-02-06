<?php
require_once '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK Cokroaminoto 2</title>
    <!-- jQuery (required for Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #008f4c;
            --primary-dark: #00703c;
            --sidebar-bg: #1e1e2d;
            --sidebar-hover: #2b2b40;
            --text-color: #3f4254;
            --bg-light: #f5f8fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-color);
        }
        .sidebar {
            background-color: var(--sidebar-bg);
            color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            z-index: 100;
        }
        .sidebar .brand-logo {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: white;
            padding: 10px 0;
        }
        .sidebar hr {
            background-color: rgba(255,255,255,0.1);
            opacity: 0.1;
        }
        .sidebar a {
            color: #a2a3b7;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .sidebar a:hover {
            color: white;
            background-color: var(--sidebar-hover);
        }
        .sidebar a.active {
            color: white;
            background-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0, 143, 76, 0.3);
        }
        .sidebar a i {
            width: 25px;
            font-size: 1.1rem;
            text-align: center;
            margin-right: 10px;
        }
        .main-content {
            padding: 30px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(76, 87, 125, 0.02);
            background: white;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #ebedf3;
            padding: 20px 25px;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            font-weight: 600;
        }
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .form-control {
            border-radius: 6px;
            padding: 10px 15px;
            border-color: #e4e6ef;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 76, 0.15);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 280px;">
        <a href="../index.php" target="_blank" class="d-flex align-items-center mb-4 text-decoration-none brand-logo">
            <img src="../images/logocokro2.png" alt="Logo" class="me-2" style="width: 40px; height: auto;">
            <span class="fs-4">Admin</span>
        </a>
        
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper"></i> <span>Berita & Artikel</span>
                </a>
            </li>
            <li>
                <a href="pages_index.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'pages') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i> <span>Halaman Statis</span>
                </a>
            </li>
            <li>
                <a href="menus.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'menus.php' ? 'active' : ''; ?>">
                    <i class="fas fa-bars"></i> <span>Menu Navigasi</span>
                </a>
            </li>
            <li>
                <a href="gallery.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'gallery') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> <span>Galeri Foto</span>
                </a>
            </li>
            <li>
                <a href="ppdb.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'ppdb') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-user-graduate"></i> <span>Data PPDB</span>
                </a>
            </li>
            <li>
                <a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> <span>Pengaturan</span>
                </a>
            </li>
            <li>
                <a href="manual.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manual.php' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> <span>Manual & Panduan</span>
                </a>
            </li>
            <li>
                <a href="categories.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="alumni.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'alumni.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-graduate"></i> <span>Data Alumni</span>
                </a>
            </li>
            <li>
                <a href="jobs.php" class="<?php echo strpos(basename($_SERVER['PHP_SELF']), 'jobs') !== false ? 'active' : ''; ?>">
                    <i class="fas fa-briefcase"></i> <span>BKK / Lowongan</span>
                </a>
            </li>
            <li>
                <a href="calendar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'calendar.php' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i> <span>Kalender Akademik</span>
                </a>
            </li>
        </ul>
        
        <div class="mt-auto pt-3 border-top border-secondary">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-light" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="fas fa-user text-white small"></i>
                    </div>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i> Edit Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 main-content">
