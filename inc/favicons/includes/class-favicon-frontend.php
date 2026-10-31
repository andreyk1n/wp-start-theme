<?php

defined('ABSPATH') || exit;

class Theme_Favicon_Frontend {

    private string $directory;
    private string $url;

    public function __construct() {

        $this->directory = get_stylesheet_directory() . '/inf/favicons/generated/';
        $this->url = get_stylesheet_directory_uri() . '/inf/favicons/generated/';

        add_action('wp_head', [$this, 'remove_default_favicon'], 1);
        add_action('wp_head', [$this, 'render_favicons'], 2);
    }

    /**
     * Remove WordPress default Site Icon.
     */
    public function remove_default_favicon() {

        if (file_exists($this->directory . 'favicon.ico')) {
            remove_action('wp_head', 'wp_site_icon', 99);
        }
    }

    /**
     * Render favicon tags.
     */
    public function render_favicons() {

        if (!file_exists($this->directory . 'favicon.ico')) {
            return;
        }

        $version = get_option('theme_favicon_version', time());

        ?>

        <link
            rel="icon"
            href="<?php echo esc_url($this->url . 'favicon.ico?v=' . $version); ?>"
            sizes="any"
        >

        <link
            rel="icon"
            type="image/png"
            sizes="16x16"
            href="<?php echo esc_url($this->url . 'favicon-16x16.png?v=' . $version); ?>"
        >

        <link
            rel="icon"
            type="image/png"
            sizes="32x32"
            href="<?php echo esc_url($this->url . 'favicon-32x32.png?v=' . $version); ?>"
        >

        <link
            rel="apple-touch-icon"
            sizes="180x180"
            href="<?php echo esc_url($this->url . 'apple-touch-icon.png?v=' . $version); ?>"
        >

        <link
            rel="manifest"
            href="<?php echo esc_url($this->url . 'site.webmanifest?v=' . $version); ?>"
        >

        <meta name="theme-color" content="#ffffff">

        <meta
            name="msapplication-config"
            content="<?php echo esc_url($this->url . 'browserconfig.xml?v=' . $version); ?>"
        >

        <?php
    }

}