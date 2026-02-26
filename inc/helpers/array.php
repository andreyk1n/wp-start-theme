<?php

if (!defined('ABSPATH')) {
    exit;
}


// Отримати значення з масиву без помилки

function theme_array_get($array, $key, $default = null)
{
    return isset($array[$key]) ? $array[$key] : $default;
}


// Видалити пусті значення

function theme_array_filter_empty($array)
{
    return array_filter($array, function ($value) {
        return !empty($value);
    });
}