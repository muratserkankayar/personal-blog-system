<?php

declare(strict_types=1);

use App\Core\HttpException;

function app_config(string $key, mixed $default = null): mixed
{
    $value = $GLOBALS['config'] ?? [];

    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $route = 'home', array $parameters = []): string
{
    $query = http_build_query(array_merge(['route' => $route], $parameters));

    return rtrim((string) app_config('app.base_url'), '?') . '?' . $query;
}

function redirect(string $route, array $parameters = []): never
{
    header('Location: ' . url($route, $parameters));
    exit;
}

function abort(int $status, string $message = ''): never
{
    throw new HttpException($status, $message);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $messages;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $submitted = $_POST['_token'] ?? '';
    $stored = $_SESSION['csrf_token'] ?? '';

    if (!is_string($submitted) || !is_string($stored) || $stored === '' || !hash_equals($stored, $submitted)) {
        abort(419, 'Your session token is invalid or has expired. Please try again.');
    }
}

function current_user(): ?array
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    return (current_user()['role'] ?? null) === 'admin';
}

function request_id(string $key = 'id'): int
{
    $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);

    if ($value === false || $value === null || $value < 1) {
        abort(404, 'The requested resource was not found.');
    }

    return $value;
}

function request_page(): int
{
    if (!isset($_GET['page']) || $_GET['page'] === '') {
        return 1;
    }

    $page = filter_var($_GET['page'], FILTER_VALIDATE_INT);

    if ($page === false || $page < 1) {
        abort(404, 'That results page does not exist.');
    }

    return $page;
}

function slugify(string $value): string
{
    $value = trim($value);

    if (function_exists('iconv')) {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        if ($converted !== false) {
            $value = $converted;
        }
    }

    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item';
}

function format_date(string $date): string
{
    return date('F j, Y, H:i', strtotime($date));
}

function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

function selected(mixed $left, mixed $right): string
{
    return (string) $left === (string) $right ? ' selected' : '';
}

function pagination_url(int $page, string $search = ''): string
{
    $parameters = ['page' => $page];

    if ($search !== '') {
        $parameters['q'] = $search;
    }

    return url('home', $parameters);
}
