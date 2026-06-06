<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Controller
{
    public function __construct(protected PDO $pdo)
    {
    }

    public function render(string $view, array $data = [], int $status = 200): void
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!is_file($viewPath)) {
            throw new HttpException(500, 'View not found: ' . $view);
        }

        if ($status === 419) {
            header('HTTP/1.1 419 Authentication Timeout');
        } else {
            http_response_code($status);
        }
        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = (string) ob_get_clean();

        require dirname(__DIR__) . '/Views/layouts/layout.php';
    }
}
