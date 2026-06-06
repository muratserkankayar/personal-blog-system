<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Post extends BaseModel
{
    public const PER_PAGE = 10;

    public function paginatePublished(string $search, int $page): array
    {
        $search = trim($search);
        $offset = ($page - 1) * self::PER_PAGE;
        $where = "p.status = 'published'";
        $parameters = [];

        if ($search !== '') {
            $where .= ' AND (
                p.title LIKE :title_search
                OR p.content LIKE :content_search
                OR u.username LIKE :author_search
            )';
            $pattern = '%' . $search . '%';
            $parameters = [
                'title_search' => $pattern,
                'content_search' => $pattern,
                'author_search' => $pattern,
            ];
        }

        $countStatement = $this->pdo->prepare(
            "SELECT COUNT(*)
             FROM posts p
             INNER JOIN users u ON u.id = p.user_id
             WHERE {$where}"
        );
        $countStatement->execute($parameters);
        $total = (int) $countStatement->fetchColumn();

        $sql = "SELECT p.id, p.title, p.slug, p.excerpt, p.content, p.status,
                       p.created_at, p.updated_at, p.user_id, p.category_id,
                       u.username AS author_name, c.name AS category_name
                FROM posts p
                INNER JOIN users u ON u.id = p.user_id
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE {$where}
                ORDER BY p.created_at DESC
                LIMIT :limit OFFSET :offset";

        $statement = $this->pdo->prepare($sql);

        foreach ($parameters as $key => $value) {
            $statement->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $statement->bindValue(':limit', self::PER_PAGE, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return [
            'items' => $statement->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'current_page' => $page,
            'last_page' => max(1, (int) ceil($total / self::PER_PAGE)),
        ];
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, u.username AS author_name, c.name AS category_name
             FROM posts p
             INNER JOIN users u ON u.id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.slug = :slug'
        );
        $statement->execute(['slug' => $slug]);
        $post = $statement->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, u.username AS author_name, c.name AS category_name
             FROM posts p
             INNER JOIN users u ON u.id = p.user_id
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id'
        );
        $statement->execute(['id' => $id]);
        $post = $statement->fetch(PDO::FETCH_ASSOC);

        return $post ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO posts (title, slug, content, excerpt, category_id, user_id, status)
             VALUES (:title, :slug, :content, :excerpt, :category_id, :user_id, :status)'
        );
        $statement->execute([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] !== '' ? $data['excerpt'] : null,
            'category_id' => $data['category_id'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE posts
             SET title = :title, slug = :slug, content = :content, excerpt = :excerpt,
                 category_id = :category_id, status = :status
             WHERE id = :id'
        );

        return $statement->execute([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title'], $id),
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] !== '' ? $data['excerpt'] : null,
            'category_id' => $data['category_id'],
            'status' => $data['status'],
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() === 1;
    }

    public function byUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.id, p.title, p.slug, p.status, p.created_at, p.updated_at,
                    p.user_id, p.category_id, c.name AS category_name
             FROM posts p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.user_id = :user_id
             ORDER BY p.created_at DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allForAdmin(): array
    {
        $sql = 'SELECT p.id, p.title, p.slug, p.status, p.created_at, p.updated_at,
                       p.user_id, u.username AS author_name, c.name AS category_name
                FROM posts p
                INNER JOIN users u ON u.id = p.user_id
                LEFT JOIN categories c ON c.id = p.category_id
                ORDER BY p.created_at DESC';

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    }

    public function countPublished(): int
    {
        return (int) $this->pdo
            ->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")
            ->fetchColumn();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = slugify($title);
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
        $sql = 'SELECT COUNT(*) FROM posts WHERE slug = :slug';
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
