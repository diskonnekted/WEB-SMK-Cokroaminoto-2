<?php
require_once 'config.php';

// Add parent_id column to menus table if it doesn't exist
$check = $conn->query("SHOW COLUMNS FROM menus LIKE 'parent_id'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE menus ADD COLUMN parent_id INT NULL DEFAULT NULL AFTER id");
    echo "Column parent_id added successfully.<br>";
} else {
    echo "Column parent_id already exists.<br>";
}

echo "Database update completed.";
?>