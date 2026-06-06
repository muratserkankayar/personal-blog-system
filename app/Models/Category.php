<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

final class Category extends BaseModel
{
    public function all(): array
    {
        return $this->pdo
            ->query('SELECT id, name, slug FROM categories ORDER BY name ASC')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allWithPostCounts(): array
    {
        $sql = 'SELECT c.id, c.name, c.slug, COUNT(p.id) AS post_count
                FROM categories c
                LEFT JOIN posts p ON p.category_id = c.id
                GROUP BY c.id, c.name, c.slug
                ORDER BY c.name ASC';

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, name, slug FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);
        $category = $statement->fetch(PDO::FETCH_ASSOC);

        return $category ?: null;
    }

    public function exists(int $id): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function create(string $name): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO categories (name, slug) VALUES (:name, :slug)'
        );
        $statement->execute([
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, string $name): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE categories SET name = :name, slug = :slug WHERE id = :id'
        );

        return $statement->execute([
            'name' => $name,
            'slug' => $this->uniqueSlug($name, $id),
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $this->pdo->beginTransaction();

        try {
            $clearPosts = $this->pdo->prepare(
                'UPDATE posts SET category_id = NULL WHERE category_id = :category_id'
            );
            $clearPosts->execute(['category_id' => $id]);

            $delete = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');
            $delete->execute(['id' => $id]);

            $this->pdo->commit();

            return $delete->rowCount() === 1;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = slugify($name);
        $slug = $base;
        $suffix = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        $sql = 'SELECT COUNT(*) FROM categories WHERE slug = :slug';
        $parameters = ['slug' => $slug];

        if ($ignoreId !== null) {
            $sql .= ' AND id != :id';
            $parameters['id'] = $ignoreId;
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return (int) $statement->fetchColumn() > 0;
    }
}
