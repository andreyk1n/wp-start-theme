<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- CSS -->
    <link rel="stylesheet"
        href="<?php echo get_template_directory_uri() . '/css/main.min.css?v=' . (file_exists(get_template_directory() . '/css/main.min.css') ? filemtime(get_template_directory() . '/css/main.min.css') : time()); ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="header">
        <div class="header__container">
            <a href="<?php echo home_url('/'); ?>" class="header__logo">
                <img src="<?php echo get_template_directory_uri(); ?>/images/header/logo.svg" alt="Header logo">
            </a>
            <nav class="header__nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'header_menu',
                    'container' => false,
                    'menu_class' => 'header__menu',
                    'fallback_cb' => false,
                    'depth' => 2,
                ]);
                ?>
            </nav>
            <div class="header__burger">
                <span></span>
            </div>
        </div>
    </header>
    <main>