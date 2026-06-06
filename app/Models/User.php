<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class User extends BaseModel
{
    public function create(string $username, string $email, string $password, string $role = 'user'): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)'
        );
        $statement->execute([
            'username' => $username,
            'email' => strtolower($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, username, email, password, role, created_at FROM users WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, username, email, password, role, created_at FROM users WHERE email = :email'
        );
        $statement->execute(['email' => strtolower($email)]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function usernameExists(string $username): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $statement->execute(['username' => $username]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function emailExists(string $email): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->execute(['email' => strtolower($email)]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function updatePassword(int $id, string $password): bool
    {
        $statement = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');

        return $statement->execute([
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $id,
        ]);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
}
