<?php
require_once 'config.php';

header('Content-Type: application/json');

$start = $_GET['start'] ?? date('Y-m-d');
$end = $_GET['end'] ?? date('Y-m-d', strtotime('+1 month'));

$sql = "SELECT * FROM calendar_events WHERE (start_date BETWEEN '$start' AND '$end') OR (end_date BETWEEN '$start' AND '$end')";
$result = $conn->query($sql);

$events = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $color = '#0d6efd'; // Default academic
        $badge_class = 'bg-primary';
        $cat_label = 'Akademik';
        
        if ($row['category'] == 'holiday') {
            $color = '#dc3545';
            $badge_class = 'bg-danger';
            $cat_label = 'Hari Libur';
        } elseif ($row['category'] == 'event') {
            $color = '#198754';
            $badge_class = 'bg-success';
            $cat_label = 'Acara Sekolah';
        } elseif ($row['category'] == 'exam') {
            $color = '#ffc107';
            $badge_class = 'bg-warning text-dark';
            $cat_label = 'Ujian';
        }
        
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'start' => $row['start_date'],
            'end' => $row['end_date'] ? date('Y-m-d', strtotime($row['end_date'] . ' +1 day')) : null, // FullCalendar end date is exclusive
            'backgroundColor' => $color,
            'borderColor' => $color,
            'description' => $row['description'],
            'extendedProps' => [
                'description' => $row['description'],
                'category' => $row['category'],
                'category_label' => $cat_label,
                'badge_class' => $badge_class
            ]
        ];
    }
}

echo json_encode($events);
?>