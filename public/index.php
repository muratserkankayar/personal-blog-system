<?php

declare(strict_types=1);

use App\Core\Controller;
use App\Core\HttpException;
use App\Core\Router;

require dirname(__DIR__) . '/bootstrap.php';

try {
    $pdo = Database::connection($GLOBALS['config']['database']);
    $routes = require dirname(__DIR__) . '/routes.php';
    $router = new Router($pdo, $routes);
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_GET['route'] ?? 'home');
} catch (HttpException $exception) {
    if (!isset($pdo)) {
        http_response_code($exception->status);
        echo e($exception->getMessage());
        exit;
    }

    $view = in_array($exception->status, [403, 404, 419], true)
        ? 'errors/' . $exception->status
        : 'errors/500';

    (new Controller($pdo))->render($view, [
        'pageTitle' => $exception->status . ' Error',
        'errorMessage' => $exception->getMessage(),
    ], $exception->status);
} catch (Throwable $exception) {
    error_log($exception->__toString());

    if (!isset($pdo)) {
        http_response_code(500);
        echo app_config('app.debug') ? e($exception->getMessage()) : 'Application error.';
        exit;
    }

    (new Controller($pdo))->render('errors/500', [
        'pageTitle' => 'Application Error',
        'errorMessage' => app_config('app.debug') ? $exception->getMessage() : '',
    ], 500);
}
