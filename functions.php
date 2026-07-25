<?php

// Підключаємо конфіг першим
require_once get_template_directory() . '/inc/config.php';

// Стилізація логіну
require_once THEME_INC_PATH . '/admin/login.php';


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

// Пост-тайпи
if (defined('THEME_CPT_PROJECT') && THEME_CPT_PROJECT) {
    require_once THEME_INC_PATH . '/post-types/project.php';
}

// Підключення універсального компоненту циклу
// require_once THEME_INC_PATH . '/components/loop.php';

// Підключення helpers
// foreach (glob(THEME_INC_PATH . '/helpers/*.php') as $file) {
//     require_once $file;
// }


// Register header and footer menu

add_action('after_setup_theme', function () {
    register_nav_menus([
        'header_menu' => __('Header Menu', 'your-theme'),
        'footer_menu' => __('Footer Menu', 'your-theme'),
    ]);
});

// Register header and footer menu

/**
 * Allow SVG uploads
 */
add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/**
 * Fix SVG preview in Media Library
 */
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    if (strtolower($ext) === 'svg') {
        $data['ext']  = 'svg';
        $data['type'] = 'image/svg+xml';
    }

    return $data;

}, 10, 4);