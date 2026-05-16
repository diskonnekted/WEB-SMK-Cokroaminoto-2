<?php
/**
 * Arabic Charset Repair Script
 * Fixes double-encoded UTF-8 text (mojibake) in the database.
 */
require_once 'config.php';

// Set charset to binary-safe for extraction if needed, 
// but usually mysqli handles the conversion.
$conn->set_charset("utf8mb4");

$tables = ['news', 'pages', 'settings'];
$fields = [
    'news' => ['title', 'content'],
    'pages' => ['title', 'content'],
    'settings' => ['setting_value']
];

foreach ($tables as $table) {
    echo "Repairing table: $table...\n";
    $field_list = implode(', ', array_merge(['id'], $fields[$table]));
    $result = $conn->query("SELECT $field_list FROM $table");
    
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $updates = [];
        $params = [];
        $types = "";

        foreach ($fields[$table] as $field) {
            $val = $row[$field];
            
            // Check if string contains typical UTF-8 mojibake patterns
            // Â (NBSP corruption), â (apostrophe/quote corruption), Ù/Ø (Arabic corruption)
            if (strpos($val, 'Â') !== false || strpos($val, 'â') !== false || strpos($val, 'Ù') !== false || strpos($val, 'Ø') !== false) {
                
                // The fix: Convert from UTF-8 to Windows-1252 to recover raw bytes
                $fixed = iconv('UTF-8', 'windows-1252//IGNORE', $val);
                
                if ($fixed !== $val && !empty($fixed)) {
                    $updates[] = "$field = ?";
                    $params[] = $fixed;
                    $types .= "s";
                }
            }
        }

        if (!empty($updates)) {
            $sql = "UPDATE $table SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $params[] = $id;
            $types .= "i";
            
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            echo "- Fixed Row ID: $id\n";
        }
    }
}

echo "\nRepair complete. Please check the website.\n";
?>
