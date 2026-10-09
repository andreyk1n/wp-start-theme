
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $css_path = get_template_directory() . '/css/main.min.css';
    $css_uri  = get_template_directory_uri() . '/css/main.min.css';

    if (file_exists($css_path)) {
        wp_enqueue_style(
            'theme-main',
            $css_uri,
            [],
            filemtime($css_path)
        );
    }

    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
    <div class="header__container">

        <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo">
            <img
                src="<?php echo esc_url(get_theme_file_uri('/images/header/logo.svg')); ?>"
                alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
            >
        </a>

        <nav class="header__nav" aria-label="Головна навігація">
            <?php
            wp_nav_menu([
                'theme_location' => 'header_menu',
                'container'      => false,
                'menu_class'     => 'header__menu',
                'fallback_cb'    => false,
                'depth'          => 2,
            ]);
            ?>
        </nav>

        <button
            class="header__burger"
            type="button"
            aria-label="Відкрити меню"
            aria-expanded="false"
        >
            <span></span>
        </button>

    </div>
</header>

<main>