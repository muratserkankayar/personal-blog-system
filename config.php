<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Personal Blog System',
        'base_url' => getenv('APP_URL') ?: '/mvc-blog-system/public/index.php',
        'timezone' => 'Europe/Warsaw',
        'debug' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOL),
    ],
    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'mvc_blog_system',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset' => 'utf8mb4',
    ],
    'session' => [
        'name' => 'mvc_blog_session',
    ],
];
