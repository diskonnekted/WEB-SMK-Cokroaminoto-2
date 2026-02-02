<?php
require_once 'config.php';
$result = $conn->query("SHOW TABLE STATUS LIKE 'news'");
$row = $result->fetch_assoc();
echo "Engine: " . $row['Engine'] . "\n";
echo "Collation: " . $row['Collation'] . "\n";

$result = $conn->query("SHOW CREATE TABLE news");
$row = $result->fetch_assoc();
echo "Create Table:\n" . $row['Create Table'] . "\n";
?>