<?php

if (!defined('ABSPATH')) {
    exit;
}


// Гнучкий компонент для виведення постів.
// Повністю конфігурується через масив аргументів.
// Підтримує окреме обгортання image, title або всієї картки.


function theme_render_loop($args = [])
{
    // Значення по замовчуванню

    $defaults = [

        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => 1,

        'base_class' => 'loop',

        'show_image' => true,
        'show_title' => true,
        'show_excerpt' => false,
        'excerpt_length' => 20,
        'show_link' => false,

        // Обгортання
        'wrap_card'  => false, // обгортає весь article
        'wrap_image' => false, // обгортає тільки image
        'wrap_title' => false, // обгортає тільки title

        'pagination' => false,

    ];

    $args = wp_parse_args($args, $defaults);


    // Wp Query

    $query = new WP_Query([
        'post_type'      => $args['post_type'],
        'posts_per_page' => $args['posts_per_page'],
        'paged'          => $args['paged'],
    ]);

    if (!$query->have_posts()) {
        return;
    }

    $base = esc_attr($args['base_class']);

    echo "<div class='{$base}'>";

    while ($query->have_posts()) {
        $query->the_post();

        $permalink = esc_url(get_permalink());

        // Якщо картка повністю клікабельна
        if ($args['wrap_card']) {
            echo "<a class='{$base}__item {$base}__item--link' href='{$permalink}'>";
        } else {
            echo "<article class='{$base}__item'>";
        }


        // Featured image

        if ($args['show_image'] && has_post_thumbnail()) {

            if ($args['wrap_image'] && !$args['wrap_card']) {
                echo "<a class='{$base}__image' href='{$permalink}'>";
                the_post_thumbnail('medium');
                echo "</a>";
            } else {
                echo "<div class='{$base}__image'>";
                the_post_thumbnail('medium');
                echo "</div>";
            }
        }


        // Title

        if ($args['show_title']) {

            if ($args['wrap_title'] && !$args['wrap_card']) {
                echo "<h3 class='{$base}__title'>";
                echo "<a href='{$permalink}'>" . esc_html(get_the_title()) . "</a>";
                echo "</h3>";
            } else {
                echo "<h3 class='{$base}__title'>";
                echo esc_html(get_the_title());
                echo "</h3>";
            }
        }


        // Excerpt

        if ($args['show_excerpt']) {
            echo "<div class='{$base}__excerpt'>";
            echo wp_trim_words(get_the_excerpt(), $args['excerpt_length']);
            echo "</div>";
        }


        // Окрема кнопка

        if ($args['show_link'] && !$args['wrap_card']) {
            echo "<a class='{$base}__link' href='{$permalink}'>";
            echo "Детальніше";
            echo "</a>";
        }


        // Закриття картки

        if ($args['wrap_card']) {
            echo "</a>";
        } else {
            echo "</article>";
        }
    }

    echo "</div>";


    // Pagination

    if ($args['pagination']) {

        echo "<div class='{$base}__pagination'>";

        echo paginate_links([
            'total'   => $query->max_num_pages,
            'current' => $args['paged'],
        ]);

        echo "</div>";
    }

    wp_reset_postdata();
}


// Приклад використання

// theme_render_loop([
//     'post_type'      => 'post',
//     'posts_per_page' => 6,
//     'base_class'     => 'blog',

//     'wrap_title'     => true,
//     'show_excerpt'   => true,
//     'pagination'     => true,
// ]);

// theme_render_loop([
//     'post_type'      => 'project',
//     'posts_per_page' => 9,
//     'base_class'     => 'projects',

//     'wrap_card'      => true,
// ]);

