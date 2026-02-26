<?php

if (!defined('ABSPATH')) {
    exit;
}


// Отримати версію файлу по filemtime

function theme_asset_version($path)
{
    $file = get_template_directory() . $path;

    if (file_exists($file)) {
        return filemtime($file);
    }

    return null;
}