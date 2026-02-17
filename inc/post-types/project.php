<?php


// Тут реєструється кастомний тип запису "Проєкти".
// Використовується для портфоліо, кейсів, реалізованих робіт тощо.
// Файл підключається вручну в functions.php або через автолоад.

// Перевірка — якщо WordPress ще не ініціалізований, не виконувати код
if (!defined('ABSPATH')) {
    exit;
}


add_action('init', function () {

    // Це всі тексти, які будуть відображатися в адмін-панелі.
    // Рекомендується прописувати повністю, щоб не було англійських залишків.


    $labels = [

        'name' => 'Проєкти',
        'singular_name' => 'Проєкт',
        'menu_name' => 'Проєкти',
        'name_admin_bar' => 'Проєкт',

        'add_new' => 'Додати проєкт',
        'add_new_item' => 'Додати новий проєкт',
        'new_item' => 'Новий проєкт',
        'edit_item' => 'Редагувати проєкт',
        'view_item' => 'Переглянути проєкт',

        'all_items' => 'Всі проєкти',
        'search_items' => 'Пошук проєктів',

        'not_found' => 'Проєкти не знайдено',
        'not_found_in_trash' => 'В кошику проєктів немає',

    ];


    // Налаштування поведінки

    $args = [

        // Підписи
        'labels' => $labels,

        // Публічність
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,

        // REST API (для Gutenberg та headless)
        'show_in_rest' => true,

        // Позиція в меню
        'menu_position' => 5,

        // Іконка в адмінці
        'menu_icon' => 'dashicons-portfolio',

        // Підтримувані можливості
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'revisions'
        ],

        // Архів
        'has_archive' => true,

        // Permalink структура
        'rewrite' => [
            'slug' => 'projects',
            'with_front' => false,
        ],

        // Чи дозволено ієрархію (як сторінки)
        'hierarchical' => false,

        // Чи можна експортувати
        'can_export' => true,

    ];

    // Реєстрація пост-тайпу

    register_post_type('project', $args);

});
