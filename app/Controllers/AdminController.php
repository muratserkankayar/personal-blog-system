<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        $posts = new Post($this->pdo);

        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'stats' => [
                'users' => (new User($this->pdo))->countAll(),
                'posts' => $posts->countAll(),
                'published' => $posts->countPublished(),
                'comments' => (new Comment($this->pdo))->countAll(),
            ],
            'posts' => $posts->allForAdmin(),
            'categoryCount' => count((new Category($this->pdo))->all()),
        ]);
    }
}
