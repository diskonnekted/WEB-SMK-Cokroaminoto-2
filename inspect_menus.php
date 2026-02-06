<?php
require_once 'config.php';
$res = $conn->query("SELECT * FROM menus ORDER BY sort_order ASC");
$menus = [];
if ($res) {
    while($row = $res->fetch_assoc()) {
        $menus[] = $row;
    }
}
echo json_encode($menus, JSON_PRETTY_PRINT);
?>