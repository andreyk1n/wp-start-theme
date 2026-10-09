
<?php

/**
 * Fix root-relative URLs on local WordPress installations.
 */

if (!defined('THEME_LOCAL_URL_FIX') || !THEME_LOCAL_URL_FIX) {
    return;
}

add_action('template_redirect', function () {
    if (is_admin()) {
        return;
    }

    $host = wp_parse_url(home_url('/'), PHP_URL_HOST);

    if (!in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
        return;
    }

    $prefix = rtrim(wp_parse_url(home_url('/'), PHP_URL_PATH) ?: '', '/');

    if ($prefix === '') {
        return;
    }

    ob_start(function ($html) use ($prefix) {
        return preg_replace_callback(
            '/\b(href|src|action|poster)\s*=\s*(["\'])(\/(?!\/)[^"\']*)\2/i',
            function ($matches) use ($prefix) {
                $url = $matches[3];

                if ($url === $prefix || strpos($url, $prefix . '/') === 0) {
                    return $matches[0];
                }

                return $matches[1] . '=' . $matches[2]
                    . $prefix . $url . $matches[2];
            },
            $html
        );
    });
});