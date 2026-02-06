<?php
require_once 'config.php';

// Fetch Settings
$settings = [];
$result = $conn->query("SELECT * FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch Menus (Parent-Child)
$parent_menus = [];
$child_menus = [];
$m_result = $conn->query("SELECT * FROM menus ORDER BY sort_order ASC");
while ($row = $m_result->fetch_assoc()) {
    if (empty($row['parent_id'])) {
        $parent_menus[] = $row;
    } else {
        $child_menus[$row['parent_id']][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['school_name'] ?? 'SMK Cokroaminoto 2'; ?> <?php echo $settings['school_sub_name'] ?? 'Banjarnegara'; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="date-display">
                <?php
                setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252');
                $date = strftime("%A, %d %B %Y");
                echo ($date === false) ? date('l, d F Y') : $date;
                ?>
            </div>
            <div class="top-links">
                <a href="contact.php">Kontak Kami</a>
                <a href="calendar.php">Kalender Akademik</a>
                <a href="admin/login.php">Login Admin</a>
            </div>
        </div>
    </div>

    <!-- Logo Section -->
    <div class="logo-section">
        <div class="container">
            <img src="images/logocokro2.png" alt="Logo SMK Cokroaminoto 2" class="school-logo">
            <div class="logo-text">
                <h1><?php echo $settings['school_name'] ?? 'SMK COKROAMINOTO 2'; ?></h1>
                <span><?php echo $settings['school_sub_name'] ?? 'BANJARNEGARA'; ?></span>
            </div>
            
            <!-- Social Media Icons -->
            <div class="header-social ms-auto">
                <a href="https://instagram.com/@corduba.official" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="https://youtube.com/@cordubatv5061" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
                <a href="https://tiktok.com/@corduba.official" target="_blank" class="social-icon"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul>
                <?php if (empty($parent_menus)): ?>
                    <li><a href="index.php">BERANDA</a></li>
                <?php else: ?>
                    <?php foreach ($parent_menus as $menu): ?>
                    <li class="<?php echo isset($child_menus[$menu['id']]) ? 'has-submenu' : ''; ?>">
                        <a href="<?php echo $menu['url']; ?>">
                            <?php echo strtoupper($menu['label']); ?>
                            <?php if (isset($child_menus[$menu['id']])): ?>
                                <i class="fas fa-caret-down" style="font-size: 0.8em; margin-left: 5px;"></i>
                            <?php endif; ?>
                        </a>
                        
                        <?php if (isset($child_menus[$menu['id']])): ?>
                        <ul class="submenu">
                            <?php foreach ($child_menus[$menu['id']] as $child): ?>
                            <li><a href="<?php echo $child['url']; ?>"><?php echo strtoupper($child['label']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                    <li><a href="quran.php">AL-QURAN</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
