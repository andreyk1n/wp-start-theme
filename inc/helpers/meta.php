<?php

if (!defined('ABSPATH')) {
    exit;
}


// Отримати meta без помилки

function theme_meta($key, $post_id = null, $default = '')
{
    $post_id = $post_id ?: get_the_ID();

    $value = get_post_meta($post_id, $key, true);

    return $value ? $value : $default;
}