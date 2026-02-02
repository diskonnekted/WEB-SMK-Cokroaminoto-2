<?php
require 'config.php';

// Upgrade pages table content to LONGTEXT
$sql1 = "ALTER TABLE pages MODIFY content LONGTEXT";
if ($conn->query($sql1) === TRUE) {
    echo "Table pages content column modified to LONGTEXT successfully.<br>";
} else {
    echo "Error modifying pages table: " . $conn->error . "<br>";
}

// Upgrade news table content to LONGTEXT
$sql2 = "ALTER TABLE news MODIFY content LONGTEXT";
if ($conn->query($sql2) === TRUE) {
    echo "Table news content column modified to LONGTEXT successfully.<br>";
} else {
    echo "Error modifying news table: " . $conn->error . "<br>";
}

echo "Database schema upgrade completed.";
