<?php

add_action('login_head', function () {

    $file = THEME_INC_PATH . '/admin/login.css';

    if (file_exists($file)) {
        echo '<style>';
        include $file;
        echo '</style>';
    }

});


// Заміна логотипу посилання
add_filter('login_headerurl', function () {
    return home_url();
});

// Заміна title
add_filter('login_headertext', function () {
    return THEME_NAME;
});
