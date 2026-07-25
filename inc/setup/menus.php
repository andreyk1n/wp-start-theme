<?php
add_action('after_setup_theme', function () {
    register_nav_menus([
        'header_menu' => __('Header Menu', 'your-theme'),
        'footer_menu' => __('Footer Menu', 'your-theme'),
    ]);
});