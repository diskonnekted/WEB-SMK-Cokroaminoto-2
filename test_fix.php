<?php
require_once 'config.php';

$id = 40;
$res = $conn->query("SELECT content FROM news WHERE id=$id");
$row = $res->fetch_assoc();
$val = $row['content'];

echo "Original: " . substr($val, 0, 100) . "...\n";

// Try Fix 1: UTF-8 to ISO-8859-1
$fix1 = mb_convert_encoding($val, 'ISO-8859-1', 'UTF-8');
echo "Fix 1 Sample: " . substr($fix1, 0, 100) . "...\n";

// Try Fix 2: If Fix 1 is still garbled or broke things, try double conversion
$fix2 = iconv('UTF-8', 'windows-1252//IGNORE', $val);
echo "Fix 2 Sample: " . substr($fix2, 0, 100) . "...\n";

// Look for the Arabic part in Fix 2
if (strpos($fix2, 'يا') !== false || strpos($fix2, 'يَا') !== false) {
    echo "SUCCESS: Arabic detected in Fix 2!\n";
} else {
    echo "FAILED: No Arabic detected.\n";
}
?>
