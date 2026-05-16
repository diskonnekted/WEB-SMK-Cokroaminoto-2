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

// Dynamic Extracurricular Submenus
// We look for the menu item labeled 'EKSTRAKURIKULER' and inject categories as submenus
foreach ($parent_menus as &$pm) {
    if (strtoupper($pm['label']) === 'EKSTRAKURIKULER') {
        // Fetch categories that are likely extracurriculars (IDs 20-34 in this DB)
        // Or we can just fetch categories that have 'type=news' and are not the main ones
        $ekskul_res = $conn->query("SELECT name, slug FROM categories WHERE type = 'news' AND id >= 20 ORDER BY name ASC");
        
        // Clear existing manual submenus if we want it fully dynamic, 
        // or just append. Let's append unique ones.
        $existing_labels = isset($child_menus[$pm['id']]) ? array_column($child_menus[$pm['id']], 'label') : [];
        
        while ($cat = $ekskul_res->fetch_assoc()) {
            if (!in_array($cat['name'], $existing_labels)) {
                $child_menus[$pm['id']][] = [
                    'id' => 'cat_' . $cat['slug'],
                    'label' => $cat['name'],
                    'url' => 'category.php?slug=' . $cat['slug'],
                    'parent_id' => $pm['id']
                ];
            }
        }
    }
}
unset($pm);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($settings['school_name']) ? $settings['school_name'] : 'SMK Cokroaminoto 2'; ?> <?php echo !empty($settings['school_sub_name']) ? $settings['school_sub_name'] : 'Banjarnegara'; ?></title>
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
                <?php echo indo_date(date('Y-m-d')); ?>
            </div>
            <div class="top-links">
                <a href="contact.php">Kontak Kami</a>
                <a href="calendar.php">Kalender Akademik</a>
                <a href="admin/login.php">Login Admin</a>
            </div>
        </div>
    </div>

    <!-- Mobile Detection & Redirection -->
    <script>
    if (window.innerWidth <= 768 && !window.location.href.includes('mobile.php')) {
        window.location.href = 'mobile.php';
    }
    </script>

    <!-- Logo Section -->
    <div class="logo-section">
        <div class="container">
            <img src="images/logocokro2.png" alt="Logo SMK Cokroaminoto 2" class="school-logo">
            <div class="logo-text">
                <h1><?php echo !empty($settings['school_name']) ? $settings['school_name'] : 'SMK COKROAMINOTO 2'; ?></h1>
                <span><?php echo !empty($settings['school_sub_name']) ? $settings['school_sub_name'] : 'BANJARNEGARA'; ?></span>
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
                            <li class="<?php echo isset($child_menus[$child['id']]) ? 'has-submenu' : ''; ?>">
                                <a href="<?php echo $child['url']; ?>">
                                    <?php echo strtoupper($child['label']); ?>
                                    <?php if (isset($child_menus[$child['id']])): ?>
                                        <i class="fas fa-caret-right" style="font-size: 0.8em;"></i>
                                    <?php endif; ?>
                                </a>
                                
                                <?php if (isset($child_menus[$child['id']])): ?>
                                <ul class="submenu">
                                    <?php foreach ($child_menus[$child['id']] as $grandchild): ?>
                                    <li><a href="<?php echo $grandchild['url']; ?>"><?php echo strtoupper($grandchild['label']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
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
