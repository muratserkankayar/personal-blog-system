<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->pdo->exec(
            'CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(10) DEFAULT "user",
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $this->pdo->exec(
            'CREATE TABLE categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(100) NOT NULL,
                slug VARCHAR(100) UNIQUE NOT NULL
            )'
        );

        $this->pdo->exec(
            'CREATE TABLE posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                content TEXT NOT NULL,
                excerpt TEXT,
                category_id INTEGER,
                user_id INTEGER NOT NULL,
                status VARCHAR(20) DEFAULT "draft",
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }
}
