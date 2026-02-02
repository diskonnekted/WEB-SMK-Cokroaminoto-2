<?php
// WordPress Compatibility Shim
// This file allows basic WordPress plugin files to be included without throwing fatal errors immediately.
// It does NOT provide full WordPress functionality.

if (!function_exists('add_action')) {
    function add_action($hook, $function_to_add, $priority = 10, $accepted_args = 1) { return true; }
}
if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) { return true; }
}
if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') { return true; }
}
if (!function_exists('shortcode_exists')) {
    function shortcode_exists($tag) { return false; }
}
if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) { return true; }
}
if (!function_exists('do_shortcode')) {
    function do_shortcode($content) { return $content; }
}
if (!function_exists('esc_url')) {
    function esc_url($url) { return filter_var($url, FILTER_SANITIZE_URL); }
}
if (!function_exists('esc_attr')) {
    function esc_attr($text) { return htmlspecialchars($text, ENT_QUOTES); }
}
if (!function_exists('__')) {
    function __($text, $domain = 'default') { return $text; }
}
if (!function_exists('_e')) {
    function _e($text, $domain = 'default') { echo $text; }
}
?>