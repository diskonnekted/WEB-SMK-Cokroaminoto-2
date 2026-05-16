<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit('Unauthorized');
}

if ($_FILES['image']['name']) {
    if (!$_FILES['image']['error']) {
        $name = md5(rand(100, 200));
        $ext = explode('.', $_FILES['image']['name']);
        $filename = $name . '.' . end($ext);
        $destination = '../uploads/news/content/' . $filename;
        
        // Ensure directory exists
        if (!file_exists('../uploads/news/content/')) {
            mkdir('../uploads/news/content/', 0777, true);
        }

        $location = $_FILES['image']['tmp_name'];
        if (move_uploaded_file($location, $destination)) {
            // Return the URL for Summernote to use
            echo 'uploads/news/content/' . $filename;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo 'Gagal memindahkan file.';
        }
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        echo 'Ooops!  Your upload triggered the following error:  ' . $_FILES['image']['error'];
    }
}
?>
