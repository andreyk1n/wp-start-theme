<?php

/*
|--------------------------------------------------------------------------
| Core – базова інформація про тему
|--------------------------------------------------------------------------
*/

// Назва теми (для внутрішнього використання, логів, тощо)
define('THEME_NAME', 'Start Theme');

// Короткий slug теми (можна використовувати в хендлах стилів, класах, id)
define('THEME_SLUG', 'start');

// Версія теми (використовується для кешування стилів/скриптів)
define('THEME_VERSION', '1.0.0');

// Textdomain для перекладів (повинен співпадати з slug теми)
define('THEME_TEXTDOMAIN', 'start');

// Середовище роботи теми
// development – для розробки
// production – для продакшену
define('THEME_ENV', 'development');

// Чи увімкнений debug-режим
// Автоматично true якщо середовище не production
define('THEME_DEBUG', THEME_ENV !== 'production');


/*
|--------------------------------------------------------------------------
| Paths – шляхи до папок теми
|--------------------------------------------------------------------------
*/

// Абсолютний шлях до папки теми на сервері
define('THEME_PATH', get_template_directory());

// URL до папки теми
define('THEME_URI', get_template_directory_uri());

// Абсолютні шляхи до основних директорій
define('THEME_INC_PATH', THEME_PATH . '/inc');
define('THEME_ASSETS_PATH', THEME_PATH . '/assets');
define('THEME_CSS_PATH', THEME_PATH . '/css');
define('THEME_JS_PATH', THEME_PATH . '/js');

// URL-шляхи до ресурсів (використовуються в enqueue)
define('THEME_ASSETS_URI', THEME_URI . '/assets');
define('THEME_CSS_URI', THEME_URI . '/css');
define('THEME_JS_URI', THEME_URI . '/js');


/*
|--------------------------------------------------------------------------
| Assets – керування стилями та скриптами
|--------------------------------------------------------------------------
*/

// Версія ресурсів для кешування
// У production – фіксована версія теми
// У development – time(), щоб відключити кеш браузера
define('THEME_ASSET_VERSION', THEME_ENV === 'production' ? THEME_VERSION : time());

// Чи використовувати мінімізовані файли (.min.css / .min.js)
define('THEME_MINIFY_ASSETS', THEME_ENV === 'production');

// Чи додавати атрибут defer до JS
define('THEME_DEFER_JS', true);

// Чи вмикати lazy-loading для зображень
define('THEME_LAZY_IMAGES', true);

// Вимкнення стандартних emoji WordPress
define('THEME_DISABLE_EMOJIS', true);


/*
|--------------------------------------------------------------------------
| ACF – налаштування Advanced Custom Fields
|--------------------------------------------------------------------------
*/

// Глобальний перемикач ACF
define('THEME_ACF_ENABLED', true);

// Чи використовується Flexible Content
define('THEME_ACF_FLEXIBLE', true);

// Чи увімкнена Option Page
define('THEME_ACF_OPTIONS', true);

// Чи використовується JSON синхронізація ACF
define('THEME_ACF_JSON', true);

// Шлях для збереження ACF JSON файлів
define('THEME_ACF_JSON_PATH', THEME_INC_PATH . '/acf/json');


/*
|--------------------------------------------------------------------------
| Pages – налаштування контенту
|--------------------------------------------------------------------------
*/

// Кількість постів на сторінці (archive, blog)
define('THEME_POSTS_PER_PAGE', 9);

// Довжина excerpt (кількість слів)
define('THEME_EXCERPT_LENGTH', 20);

// Максимальна ширина завантажених зображень
define('THEME_UPLOAD_MAX_WIDTH', 1920);

// Максимальна висота завантажених зображень
define('THEME_UPLOAD_MAX_HEIGHT', 1920);


/*
|--------------------------------------------------------------------------
| Images – оптимізація зображень
|--------------------------------------------------------------------------
*/

// Якість JPEG при збереженні (0–100)
define('THEME_IMAGE_QUALITY', 85);

// Чи генерувати WebP-версії
define('THEME_WEBP_ENABLED', true);


/*
|--------------------------------------------------------------------------
| SEO – пошукова оптимізація
|--------------------------------------------------------------------------
*/

// Чи додавати Schema.org розмітку
define('THEME_SCHEMA_ENABLED', true);

// Чи виводити Open Graph мета-теги
define('THEME_OPEN_GRAPH', true);


/*
|--------------------------------------------------------------------------
| Performance – оптимізація WordPress
|--------------------------------------------------------------------------
*/

// Очищення <head> від зайвих meta / link тегів WP
define('THEME_CLEAN_HEAD', true);

// Вимкнення стандартних стилів Gutenberg
define('THEME_DISABLE_GUTENBERG_STYLES', false);

// Вимкнення XML-RPC
define('THEME_DISABLE_XMLRPC', true);

// Повне вимкнення REST API (обережно)
define('THEME_DISABLE_REST', false);


/*
|--------------------------------------------------------------------------
| Security – базові налаштування безпеки
|--------------------------------------------------------------------------
*/

// Приховування версії WordPress
define('THEME_HIDE_WP_VERSION', true);

// Заборона редагування файлів через адмінку
define('THEME_DISABLE_FILE_EDIT', true);

// Глобальне вимкнення коментарів
define('THEME_DISABLE_COMMENTS', false);
