<?php

defined('ABSPATH') || exit;

require_once __DIR__ . '/includes/class-favicon-generator.php';
require_once __DIR__ . '/includes/class-favicon-admin.php';
require_once __DIR__ . '/includes/class-favicon-frontend.php';

class Theme_Favicons {

    public function __construct() {
        new Theme_Favicon_Admin();
        new Theme_Favicon_Frontend();
    }

}

new Theme_Favicons();