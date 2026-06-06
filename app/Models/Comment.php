<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Comment extends BaseModel
{
    public function forPost(int $postId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT c.id, c.content, c.created_at, c.user_id, u.username
             FROM comments c
             INNER JOIN users u ON u.id = c.user_id
             WHERE c.post_id = :post_id
             ORDER BY c.created_at ASC'
        );
        $statement->execute(['post_id' => $postId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $postId, int $userId, string $content): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO comments (post_id, user_id, content)
             VALUES (:post_id, :user_id, :content)'
        );
        $statement->execute([
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $content,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM comments')->fetchColumn();
    }
}
