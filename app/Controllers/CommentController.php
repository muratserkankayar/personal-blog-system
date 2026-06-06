<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comment;
use App\Models\Post;

final class CommentController extends Controller
{
    public function store(): void
    {
        verify_csrf();

        $postId = filter_var($_POST['post_id'] ?? null, FILTER_VALIDATE_INT);
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($postId === false || $postId === null || $postId < 1) {
            abort(404, 'Post not found.');
        }

        $post = (new Post($this->pdo))->find((int) $postId);

        if ($post === null || $post['status'] !== 'published') {
            abort(404, 'Comments can only be added to published posts.');
        }

        if (text_length($content) < 2 || text_length($content) > 2000) {
            flash('error', 'Comment must contain 2-2000 characters.');
            redirect('post.show', ['slug' => $post['slug']]);
        }

        (new Comment($this->pdo))->create(
            (int) $post['id'],
            (int) current_user()['id'],
            $content
        );

        flash('success', 'Comment added.');
        redirect('post.show', ['slug' => $post['slug']]);
    }
}
