<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\User;

final class ProfileController extends Controller
{
    public function index(): void
    {
        $user = (new User($this->pdo))->findById((int) current_user()['id']);

        if ($user === null) {
            unset($_SESSION['user']);
            flash('error', 'Your account could not be found.');
            redirect('login');
        }

        $this->render('profile/index', [
            'pageTitle' => 'My Profile',
            'profile' => $user,
            'posts' => (new Post($this->pdo))->byUser((int) $user['id']),
        ]);
    }

    public function changePassword(): void
    {
        verify_csrf();

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmation = (string) ($_POST['new_password_confirmation'] ?? '');
        $users = new User($this->pdo);
        $user = $users->findById((int) current_user()['id']);
        $errors = [];

        if ($user === null || !password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Current password is incorrect.';
        }

        if (strlen($newPassword) < 8 || strlen($newPassword) > 72) {
            $errors['new_password'] = 'New password must contain 8-72 bytes.';
        }

        if ($newPassword !== $confirmation) {
            $errors['new_password_confirmation'] = 'Password confirmation does not match.';
        }

        if ($currentPassword !== '' && $currentPassword === $newPassword) {
            $errors['new_password'] = 'New password must be different from the current password.';
        }

        if ($errors !== []) {
            $this->render('profile/index', [
                'pageTitle' => 'My Profile',
                'profile' => $user,
                'posts' => (new Post($this->pdo))->byUser((int) current_user()['id']),
                'passwordErrors' => $errors,
            ], 422);
            return;
        }

        $users->updatePassword((int) $user['id'], $newPassword);
        session_regenerate_id(true);
        flash('success', 'Password changed successfully.');
        redirect('profile');
    }
}
