<?php

declare(strict_types=1);

$config = require __DIR__ . '/config.php';
$GLOBALS['config'] = $config;

date_default_timezone_set($config['app']['timezone']);

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = __DIR__ . '/app/' . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/middleware.php';
require_once __DIR__ . '/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name($config['session']['name']);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
