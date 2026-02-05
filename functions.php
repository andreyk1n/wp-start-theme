<?php
function basic_wp_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
}
add_action('after_setup_theme', 'basic_wp_theme_setup');

function basic_wp_theme_assets() {
    wp_enqueue_style('basic-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'basic_wp_theme_assets');

// ACF Options Page (contacts)
// require_once get_template_directory() . '/inc/acf/option-page.php';
