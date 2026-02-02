<?php
require 'config.php';
// Check ID 2
$res = $conn->query("SELECT * FROM menus WHERE id=2");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo "ID 2 found: " . print_r($row, true) . "\n";
    // Update it
    $conn->query("UPDATE menus SET url='page.php?slug=profil' WHERE id=2");
    echo "Updated ID 2 to page.php?slug=profil\n";
} else {
    echo "ID 2 not found. Inserting Profil menu...\n";
    $conn->query("INSERT INTO menus (label, url, sort_order) VALUES ('PROFIL', 'page.php?slug=profil', 2)");
}
