<?php

if (!defined('ABSPATH')) {
    exit;
}


// Зібрати класи в строку

function theme_classes($classes = [])
{
    if (!is_array($classes)) {
        return '';
    }

    $classes = array_filter($classes);

    return esc_attr(implode(' ', $classes));
}


// BEM helper

function theme_bem($block, $element = '', $modifier = '')
{
    $class = $block;

    if ($element) {
        $class .= '__' . $element;
    }

    if ($modifier) {
        $class .= '--' . $modifier;
    }

    return esc_attr($class);
}