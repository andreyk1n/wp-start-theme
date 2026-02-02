<?php
// Theme bootstrap
foreach ([
    'config/constants.php',
    'config/theme.php',
    'config/features.php',
    'setup/theme-support.php',
    'setup/images.php',
    'setup/menus.php',
    'assets/enqueue.php',
    'acf/init.php',
    'hooks/actions.php',
    'hooks/filters.php',
] as $file) {
    require_once __DIR__ . '/' . $file;
}
