<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use PDOException;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        $this->render('auth/login', ['pageTitle' => 'Log In']);
    }

    public function login(): void
    {
        verify_csrf();

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        }

        $user = $errors === [] ? (new User($this->pdo))->findByEmail($email) : null;

        if ($errors === [] && ($user === null || !password_verify($password, $user['password']))) {
            $errors['credentials'] = 'The email or password is incorrect.';
        }

        if ($errors !== []) {
            $this->render('auth/login', [
                'pageTitle' => 'Log In',
                'errors' => $errors,
                'old' => ['email' => $email],
            ], 422);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        flash('success', 'Welcome back, ' . $user['username'] . '!');
        redirect(is_admin() ? 'admin' : 'home');
    }

    public function registerForm(): void
    {
        $this->render('auth/register', ['pageTitle' => 'Create Account']);
    }

    public function register(): void
    {
        verify_csrf();

        $data = [
            'username' => trim((string) ($_POST['username'] ?? '')),
            'email' => strtolower(trim((string) ($_POST['email'] ?? ''))),
            'password' => (string) ($_POST['password'] ?? ''),
            'password_confirmation' => (string) ($_POST['password_confirmation'] ?? ''),
        ];
        $errors = $this->validateRegistration($data);
        $users = new User($this->pdo);

        if ($errors === [] && $users->usernameExists($data['username'])) {
            $errors['username'] = 'That username is already in use.';
        }

        if ($errors === [] && $users->emailExists($data['email'])) {
            $errors['email'] = 'That email address is already registered.';
        }

        if ($errors !== []) {
            $this->render('auth/register', [
                'pageTitle' => 'Create Account',
                'errors' => $errors,
                'old' => [
                    'username' => $data['username'],
                    'email' => $data['email'],
                ],
            ], 422);
            return;
        }

        try {
            $userId = $users->create($data['username'], $data['email'], $data['password']);
            $user = $users->findById($userId);
        } catch (PDOException) {
            $this->render('auth/register', [
                'pageTitle' => 'Create Account',
                'errors' => ['account' => 'The username or email was registered by another request.'],
                'old' => ['username' => $data['username'], 'email' => $data['email']],
            ], 422);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        flash('success', 'Your account has been created.');
        redirect('home');
    }

    public function logout(): void
    {
        verify_csrf();
        unset($_SESSION['user']);
        session_regenerate_id(true);
        flash('success', 'You have been logged out.');
        redirect('home');
    }

    private function validateRegistration(array $data): array
    {
        $errors = [];

        if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $data['username'])) {
            $errors['username'] = 'Use 3-50 letters, numbers, or underscores.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || text_length($data['email']) > 100) {
            $errors['email'] = 'Enter a valid email address up to 100 characters.';
        }

        if (strlen($data['password']) < 8 || strlen($data['password']) > 72) {
            $errors['password'] = 'Password must contain 8-72 bytes.';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Password confirmation does not match.';
        }

        return $errors;
    }
}
