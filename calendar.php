<?php
require_once 'header_public.php';
?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-daygrid-event {
        white-space: normal;
    }
    .calendar-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    /* Legend Styles */
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    .legend-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }
    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 3px;
        margin-right: 8px;
    }
</style>

<!-- Main Content -->
<div class="container main-content">
    <div class="content-grid">
        
        <!-- Left Column (Content) -->
        <div class="main-column">
            
            <div class="card shadow-sm mb-4" style="background: white; padding: 30px; border-radius: 4px; border: 1px solid #ddd;">
                <h1 class="mb-4" style="color: var(--nu-green); border-bottom: 2px solid #eee; padding-bottom: 15px;">Kalender Akademik</h1>
                
                <div class="calendar-container">
                    <div id="calendar"></div>
                    
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #0d6efd;"></div>
                            <span>Akademik</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #dc3545;"></div>
                            <span>Hari Libur</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #198754;"></div>
                            <span>Acara Sekolah</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #ffc107;"></div>
                            <span>Ujian</span>
                        </div>
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
            
            <!-- Upcoming Events Widget -->
            <div class="sidebar-widget">
                <div class="section-title">
                    <h2>Agenda Terdekat</h2>
                </div>
                <ul class="list-unstyled">
                    <?php
                    $today = date('Y-m-d');
                    $upcoming_sql = "SELECT * FROM calendar_events WHERE start_date >= '$today' ORDER BY start_date ASC LIMIT 5";
                    $upcoming = $conn->query($upcoming_sql);
                    
                    if ($upcoming && $upcoming->num_rows > 0) {
                        while ($evt = $upcoming->fetch_assoc()) {
                            $date_display = date('d M', strtotime($evt['start_date']));
                            $color_class = 'text-primary';
                            if ($evt['category'] == 'holiday') $color_class = 'text-danger';
                            if ($evt['category'] == 'event') $color_class = 'text-success';
                            if ($evt['category'] == 'exam') $color_class = 'text-warning';
                            
                            echo '<li class="mb-3 border-bottom pb-2">';
                            echo '<div class="fw-bold ' . $color_class . '">' . $date_display . '</div>';
                            echo '<div class="small fw-bold">' . htmlspecialchars($evt['title']) . '</div>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li class="text-muted small">Belum ada agenda terdekat.</li>';
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

<!-- Event Detail Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventTitle">Detail Kegiatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="eventDate" class="text-muted fw-bold"></p>
        <p id="eventDescription"></p>
        <span id="eventCategory" class="badge"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        events: 'get_events.php',
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            
            document.getElementById('eventTitle').innerText = event.title;
            
            var dateStr = event.start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            if (event.end) {
                dateStr += ' - ' + event.end.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            }
            document.getElementById('eventDate').innerText = dateStr;
            document.getElementById('eventDescription').innerText = props.description || 'Tidak ada keterangan tambahan.';
            
            var badge = document.getElementById('eventCategory');
            badge.innerText = props.category_label;
            badge.className = 'badge ' + props.badge_class;
            
            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        }
    });
    calendar.render();
});
</script>

<?php require_once 'footer_public.php'; ?>