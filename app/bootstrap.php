<?php

declare(strict_types=1);

/**
 * Application bootstrap — defines paths, starts the session lazily,
 * connects the SQLite database and loads helpers.
 */

define('APP_ROOT', dirname(__DIR__));
define('DATA_DIR', APP_ROOT . '/data');
define('DB_PATH', DATA_DIR . '/cms.sqlite');

error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') ? '1' : '0');
ini_set('log_errors', '1');

require APP_ROOT . '/app/Database.php';
require APP_ROOT . '/app/helpers.php';
require APP_ROOT . '/app/schema_org.php';

Database::boot();

/** Start a cookie-locked session (admin auth, CSRF, flash messages). */
function ensure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_name('villa_cms');
    session_start();
}
