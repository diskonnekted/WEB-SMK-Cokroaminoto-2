<?php
require 'config.php';
$output = "";
$res = $conn->query("SHOW TABLES");
if ($res) {
    while($row = $res->fetch_array()) {
        $output .= $row[0] . "\n";
    }
} else {
    $output = "Error showing tables: " . $conn->error;
}
file_put_contents('tables_log.txt', $output);
echo "Done";
