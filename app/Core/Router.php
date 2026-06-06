<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Router
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly array $routes
    ) {
    }

    public function dispatch(string $method, string $route): void
    {
        $definition = $this->routes[$method][$route] ?? null;

        if ($definition === null) {
            throw new HttpException(404, 'The requested page does not exist.');
        }

        [$controllerClass, $action, $middleware] = $definition;

        foreach ($middleware as $function) {
            $function();
        }

        $controller = new $controllerClass($this->pdo);
        $controller->{$action}();
    }
}
