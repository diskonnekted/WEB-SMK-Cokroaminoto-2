<?php
require_once 'config.php';

// Fetch Settings
$settings = [];
$result = $conn->query("SELECT * FROM settings");
while ($row = $result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch Featured News
$featured_news = [];
$res_f = $conn->query("SELECT * FROM news WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 5");
while($row = $res_f->fetch_assoc()) $featured_news[] = $row;

// Fetch Latest News
$latest_news = [];
$res_l = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 10");
while($row = $res_l->fetch_assoc()) $latest_news[] = $row;

// Fetch Upcoming Events
$events = [];
$res_e = $conn->query("SELECT * FROM calendar_events WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 5");
while($row = $res_e->fetch_assoc()) $events[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mobile App - <?php echo $settings['school_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: hsl(150, 80%, 15%);
            --accent: hsl(45, 100%, 50%);
            --bg: hsl(150, 20%, 98%);
            --glass: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg);
            padding-bottom: 80px;
            overflow-x: hidden;
        }

        /* Glass Header */
        .app-header {
            background: var(--glass);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .app-header .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .app-header img {
            height: 35px;
            width: auto;
        }

        .app-header h1 {
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
            color: var(--primary);
        }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: var(--primary);
            height: 65px;
            border-radius: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 2000;
        }

        .nav-item {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.7rem;
            transition: 0.3s;
        }

        .nav-item i {
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .nav-item.active {
            color: var(--accent);
            transform: translateY(-5px);
        }

        /* Content Sections */
        .app-section {
            display: none;
            padding: 20px;
            animation: slideUp 0.4s ease-out;
        }

        .app-section.active {
            display: block;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Home Components */
        .hero-slider {
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .hero-card {
            position: relative;
            height: 200px;
        }

        .hero-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
        }

        .card-glass {
            background: white;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }

        .section-title {
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title a {
            font-size: 0.8rem;
            color: #666;
            text-decoration: none;
        }

        /* News List */
        .news-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }

        .news-thumb {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            object-fit: cover;
        }

        .news-info h4 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 5px;
            line-height: 1.3;
        }

        .news-info span {
            font-size: 0.75rem;
            color: #999;
        }

        /* Event Cards */
        .event-card {
            border-left: 4px solid var(--accent);
            padding: 15px;
            background: white;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .profile-header {
            text-align: center;
            padding: 30px 20px;
            background: var(--primary);
            color: white;
            border-radius: 0 0 40px 40px;
            margin: -20px -20px 20px -20px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid var(--accent);
            margin-bottom: 15px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <header class="app-header">
        <div class="logo-container">
            <img src="images/logocokro2.png" alt="Logo">
            <h1>CORDUBA MOBILE</h1>
        </div>
        <div class="header-actions">
            <i class="fa-regular fa-bell fs-5"></i>
        </div>
    </header>

    <!-- SECTION: HOME -->
    <section id="home" class="app-section active">
        <div class="hero-slider">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach($featured_news as $index => $news): ?>
                    <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                        <div class="hero-card">
                            <img src="<?php echo $news['image']; ?>" alt="Hero">
                            <div class="hero-overlay">
                                <span class="badge bg-warning text-dark mb-2">Featured</span>
                                <h3 class="fs-6"><?php echo $news['title']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="section-title">
            <h2>Berita Utama</h2>
            <a href="#" onclick="showSection('news')">Lihat Semua</a>
        </div>

        <?php for($i=0; $i<3; $i++): 
            if(!isset($latest_news[$i])) break;
            $n = $latest_news[$i];
        ?>
        <div class="card-glass" onclick="location.href='mobile_detail.php?id=<?php echo $n['id']; ?>'">
            <div class="news-item">
                <img src="<?php echo $n['image']; ?>" class="news-thumb" alt="News">
                <div class="news-info">
                    <h4><?php echo $n['title']; ?></h4>
                    <span><i class="fa-regular fa-calendar me-1"></i> <?php echo indo_date($n['created_at']); ?></span>
                </div>
            </div>
        </div>
        <?php endfor; ?>

        <div class="section-title mt-4">
            <h2>Agenda Sekolah</h2>
            <a href="#" onclick="showSection('events')">Selengkapnya</a>
        </div>
        
        <?php foreach(array_slice($events, 0, 2) as $e): ?>
        <div class="event-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fs-6 fw-bold"><?php echo $e['title']; ?></h5>
                    <span class="text-muted small"><?php echo date('d M Y', strtotime($e['start_date'])); ?></span>
                </div>
                <i class="fa-solid fa-chevron-right text-muted"></i>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <!-- SECTION: NEWS -->
    <section id="news" class="app-section">
        <div class="section-title">
            <h2>Warta Corduba</h2>
        </div>
        <?php foreach($latest_news as $n): ?>
        <div class="card-glass" onclick="location.href='mobile_detail.php?id=<?php echo $n['id']; ?>'">
            <div class="news-item">
                <img src="<?php echo $n['image']; ?>" class="news-thumb" alt="News">
                <div class="news-info">
                    <h4><?php echo $n['title']; ?></h4>
                    <span><?php echo indo_date($n['created_at']); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>

    <!-- SECTION: EVENTS/ANNOUNCEMENTS -->
    <section id="events" class="app-section">
        <div class="section-title">
            <h2>Pengumuman & Agenda</h2>
        </div>
        <?php if(empty($events)): ?>
            <div class="text-center p-5 text-muted">Belum ada agenda terbaru.</div>
        <?php else: ?>
            <?php foreach($events as $e): ?>
            <div class="card-glass">
                <div class="d-flex gap-3">
                    <div class="text-center p-2 rounded bg-light" style="min-width: 60px;">
                        <span class="d-block fw-bold fs-4"><?php echo date('d', strtotime($e['start_date'])); ?></span>
                        <span class="d-block small text-uppercase"><?php echo date('M', strtotime($e['start_date'])); ?></span>
                    </div>
                    <div>
                        <h5 class="fs-6 fw-bold mb-1"><?php echo $e['title']; ?></h5>
                        <p class="small text-muted mb-0"><?php echo $e['description'] ?: 'Tidak ada deskripsi.'; ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <!-- SECTION: PROFILE -->
    <section id="profile" class="app-section">
        <div class="profile-header">
            <img src="<?php echo $settings['kepsek_image']; ?>" class="profile-img" alt="Foto">
            <h4><?php echo $settings['kepsek_name']; ?></h4>
        </div>

        <div class="card-glass">
            <h5 class="fw-bold mb-3">Tentang Sekolah</h5>
            <p class="small text-muted"><?php echo strip_tags($settings['kepsek_message']); ?></p>
            <hr>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-location-dot text-success fs-5"></i>
                    <span class="small"><?php echo $settings['address']; ?></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-phone text-success fs-5"></i>
                    <span class="small"><?php echo $settings['phone']; ?></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-envelope text-success fs-5"></i>
                    <span class="small"><?php echo $settings['email']; ?></span>
                </div>
            </div>
        </div>

        <div class="card-glass text-center">
            <h5 class="fw-bold mb-3">Media Sosial</h5>
            <div class="d-flex justify-content-center gap-4">
                <a href="https://instagram.com/@corduba.official" class="fs-2 text-danger"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://tiktok.com/@corduba.official" class="fs-2 text-dark"><i class="fa-brands fa-tiktok"></i></a>
                <a href="https://youtube.com/@cordubatv5061" class="fs-2 text-danger"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
    </section>

    <nav class="bottom-nav">
        <a href="#" class="nav-item active" onclick="showSection('home', this)">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-item" onclick="showSection('news', this)">
            <i class="fa-solid fa-newspaper"></i>
            <span>Berita</span>
        </a>
        <a href="#" class="nav-item" onclick="showSection('events', this)">
            <i class="fa-solid fa-bullhorn"></i>
            <span>Info</span>
        </a>
        <a href="#" class="nav-item" onclick="showSection('profile', this)">
            <i class="fa-solid fa-user-graduate"></i>
            <span>Profil</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId, el) {
            // Hide all sections
            document.querySelectorAll('.app-section').forEach(s => s.classList.remove('active'));
            // Show target section
            document.getElementById(sectionId).classList.add('active');
            
            // Update nav state
            if(el) {
                document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
                el.classList.add('active');
            }
            
            // Scroll to top
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
