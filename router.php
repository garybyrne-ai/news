<?php

// Dev router for `php -S localhost:8080 router.php`
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Never serve internals, even in dev.
if (preg_match('#^/(data|app|templates)/#', $path)) {
    http_response_code(403);
    exit('Forbidden');
}
if ($path !== '/' && is_file($file)) {
    return false; // static asset or admin script
}
if (is_dir($file) && is_file(rtrim($file, '/') . '/index.php')) {
    require rtrim($file, '/') . '/index.php'; // e.g. /admin/
    return true;
}
require __DIR__ . '/index.php';
