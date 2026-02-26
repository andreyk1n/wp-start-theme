<?php

if (!defined('ABSPATH')) {
    exit;
}


// Обрізати текст красиво

function theme_trim($text, $length = 20)
{
    return wp_trim_words($text, $length);
}


// Зробити slug

function theme_slugify($string)
{
    return sanitize_title($string);
}