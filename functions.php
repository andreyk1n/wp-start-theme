<?php

// Підключаємо конфіг першим
require_once get_template_directory() . '/inc/config.php';

// Theme setup

add_action('after_setup_theme', function () {

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption'
    ]);

});


// Assets

add_action('wp_enqueue_scripts', function () {

    wp_enqueue_style(
        THEME_SLUG . '-style',
        get_stylesheet_uri(),
        [],
        THEME_ASSET_VERSION
    );

});


// Modules 

// ACF Option Page
if (defined('THEME_ACF_OPTIONS') && THEME_ACF_OPTIONS) {
    require_once THEME_INC_PATH . '/acf/option-page.php';
}
