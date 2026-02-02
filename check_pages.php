<?php
require 'config.php';
$output = "";
$output .= "Structure of pages table:\n";
$desc = $conn->query("DESCRIBE pages");
if ($desc) {
    while($row = $desc->fetch_assoc()) {
        $output .= "{$row['Field']} - {$row['Type']}\n";
    }
} else {
    $output .= "Error describing pages: " . $conn->error . "\n";
}
file_put_contents('pages_struct.txt', $output);
echo "Done";
