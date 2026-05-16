<?php
/**
 * DB Optimizer for SMK Cokroaminoto 2
 * Goal: Extract Base64 images from 'news' and 'pages' tables to files.
 */
require_once 'config.php';

// Increase PCRE limits for very large content
ini_set('pcre.backtrack_limit', '50000000');
ini_set('pcre.recursion_limit', '50000000');

$tables = ['news', 'pages'];
$extracted_dir = 'uploads/extracted/';

if (!file_exists($extracted_dir)) {
    mkdir($extracted_dir, 0777, true);
}

foreach ($tables as $table) {
    echo "Processing table: $table...\n";
    $result = $conn->query("SELECT id, content FROM $table WHERE content LIKE '%data:image/%'");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $content = $row['content'];
            $found_count = 0;

            // Regex to find base64 images (Optimized for large strings)
            $pattern = '/src="data:image\/([^;]+);base64,([^"]+)"/i';
            
            $content = preg_replace_callback($pattern, function($matches) use ($extracted_dir, &$found_count, $id, $table) {
                $ext = $matches[1];
                $data = base64_decode($matches[2]);
                $filename = "img_{$table}_{$id}_" . uniqid() . ".$ext";
                $filepath = $extracted_dir . $filename;
                
                if (file_put_contents($filepath, $data)) {
                    $found_count++;
                    return 'src="' . $filepath . '"';
                }
                return $matches[0]; // Fallback if failed
            }, $content);

            if ($found_count > 0) {
                $stmt = $conn->prepare("UPDATE $table SET content = ? WHERE id = ?");
                $stmt->bind_param("si", $content, $id);
                $stmt->execute();
                echo "- Row $id: Extracted $found_count images.\n";
            }
        }
    } else {
        echo "- No Base64 images found.\n";
    }
}

echo "\nOptimization complete. Please run 'OPTIMIZE TABLE news, pages' in your database manager to reclaim space.\n";
?>
