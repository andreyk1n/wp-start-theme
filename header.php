<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/css/main.min.css?v=' . ( file_exists( get_template_directory() . '/css/main.min.css' ) ? filemtime( get_template_directory() . '/css/main.min.css' ) : time() ); ?>">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="header">
        <div class="header__container">
            <a href="/" class="header__logo">
                <img src="<?php echo get_template_directory_uri(); ?>/images/header/logo.svg" alt="Header logo">
            </a>
            <nav class="header__nav">
                <a href="/" class="header__link">Головна</a>
                <a href="<?php echo is_front_page() ? '#services' : home_url('/#services'); ?>"
                    class="header__link">Послуги</a>
                <a href="/blog/" class="header__link">Блог</a>
                <a href="/about/" class="header__link">Про нас</a>
                <a href="/contacts/" class="header__link">Контакти</a>
                <a href="#search" class="header__link"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                        width="24" height="24" viewBox="0 0 50 50">
                        <path
                            d="M 21 4 C 11.082241 4 3 12.082241 3 22 C 3 31.917759 11.082241 40 21 40 C 24.62177 40 27.99231 38.91393 30.820312 37.0625 L 43.378906 49.621094 L 47.621094 45.378906 L 35.224609 32.982422 C 37.581469 29.938384 39 26.13473 39 22 C 39 12.082241 30.917759 4 21 4 z M 21 8 C 28.756241 8 35 14.243759 35 22 C 35 29.756241 28.756241 36 21 36 C 13.243759 36 7 29.756241 7 22 C 7 14.243759 13.243759 8 21 8 z">
                        </path>
                    </svg></a>
            </nav>
            <div class="header__burger">
                <span></span>
            </div>
        </div>
    </header>
    <main>

