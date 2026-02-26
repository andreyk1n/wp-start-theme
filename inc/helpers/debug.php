<?php

if (!defined('ABSPATH')) {
    exit;
}


// Гарний var_dump

function theme_dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
}