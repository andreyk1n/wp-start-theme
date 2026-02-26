<?php

if (!defined('ABSPATH')) {
    exit;
}


// Безпечний include шаблону з передачею змінних

function theme_get_template($path, $vars = [])
{
    extract($vars);

    $file = get_template_directory() . '/' . $path . '.php';

    if (file_exists($file)) {
        include $file;
    }
}