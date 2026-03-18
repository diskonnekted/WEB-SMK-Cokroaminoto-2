<?php
/**
 * Wrapper for Cara Pendaftaran Page
 * Redirects logic to page.php with specific slug
 */

// Set the slug for the dynamic page
$_GET['slug'] = 'cara-pendaftaran';

// Include the standard page handler
require 'page.php';
?>
