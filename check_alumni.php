<?php
require 'config.php';
$res = $conn->query("SELECT content FROM pages WHERE slug='alumni'");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo "Content start:\n";
    echo substr($row['content'], 0, 500);
} else {
    echo "Page not found";
}
