
<?php

defined( 'ABSPATH' ) || exit;

/**
 * Modern dark mode for WordPress admin.
 */
function theme_admin_dark_mode_styles() {
    $css_file = get_theme_file_path( '/css/admin.css' );
    $css_uri  = get_theme_file_uri( '/css/admin.css' );

    if ( ! file_exists( $css_file ) ) {
        return;
    }

    wp_enqueue_style(
        'theme-admin-dark-mode',
        $css_uri,
        array(),
        filemtime( $css_file )
    );
}
add_action( 'admin_enqueue_scripts', 'theme_admin_dark_mode_styles' );

/**
 * Gutenberg editor interface styles.
 */
function theme_admin_dark_mode_editor_styles() {
    $css_file = get_theme_file_path( '/css/admin-editor.css' );
    $css_uri  = get_theme_file_uri( '/css/admin-editor.css' );

    if ( ! file_exists( $css_file ) ) {
        return;
    }

    wp_enqueue_style(
        'theme-admin-dark-mode-editor',
        $css_uri,
        array(),
        filemtime( $css_file )
    );
}
add_action(
    'enqueue_block_editor_assets',
    'theme_admin_dark_mode_editor_styles'
);
