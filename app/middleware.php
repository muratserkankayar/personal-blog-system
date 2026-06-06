<?php

declare(strict_types=1);

function requireLogin(): void
{
    if (!is_logged_in()) {
        flash('warning', 'Please log in to continue.');
        redirect('login');
    }
}

function requireAdmin(): void
{
    requireLogin();

    if (!is_admin()) {
        abort(403, 'Administrator access is required.');
    }
}

function requireGuest(): void
{
    if (is_logged_in()) {
        redirect('home');
    }
}

function can_manage_post(array $post): bool
{
    $user = current_user();

    return $user !== null
        && (is_admin() || (int) $post['user_id'] === (int) $user['id']);
}
