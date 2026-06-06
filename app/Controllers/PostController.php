<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;

final class PostController extends Controller
{
    public function index(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));

        if (text_length($search) > 100) {
            $search = substr($search, 0, 100);
        }

        $page = request_page();
        $pagination = (new Post($this->pdo))->paginatePublished($search, $page);

        if ($page > $pagination['last_page']) {
            abort(404, 'That results page does not exist.');
        }

        $this->render('posts/index', [
            'pageTitle' => $search === '' ? 'Latest Posts' : 'Search Results',
            'posts' => $pagination['items'],
            'pagination' => $pagination,
            'search' => $search,
        ]);
    }

    public function show(): void
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));

        if ($slug === '') {
            abort(404, 'Post not found.');
        }

        $post = (new Post($this->pdo))->findBySlug($slug);

        if ($post === null) {
            abort(404, 'Post not found.');
        }

        if ($post['status'] !== 'published' && !can_manage_post($post)) {
            abort(404, 'Post not found.');
        }

        $this->render('posts/show', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'comments' => (new Comment($this->pdo))->forPost((int) $post['id']),
        ]);
    }

    public function create(): void
    {
        $this->render('posts/form', [
            'pageTitle' => 'Create Post',
            'post' => [
                'title' => '',
                'content' => '',
                'excerpt' => '',
                'category_id' => '',
                'status' => 'draft',
            ],
            'categories' => (new Category($this->pdo))->all(),
            'formAction' => url('post.create'),
            'submitLabel' => 'Create Post',
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        [$data, $errors] = $this->validatedPostData();

        if ($errors !== []) {
            $this->render('posts/form', [
                'pageTitle' => 'Create Post',
                'post' => $data,
                'categories' => (new Category($this->pdo))->all(),
                'errors' => $errors,
                'formAction' => url('post.create'),
                'submitLabel' => 'Create Post',
            ], 422);
            return;
        }

        $data['user_id'] = (int) current_user()['id'];
        $id = (new Post($this->pdo))->create($data);
        $post = (new Post($this->pdo))->find($id);

        flash('success', 'Post created successfully.');
        redirect('post.show', ['slug' => $post['slug']]);
    }

    public function edit(): void
    {
        $post = $this->manageablePost(request_id());

        $this->render('posts/form', [
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'categories' => (new Category($this->pdo))->all(),
            'formAction' => url('post.edit', ['id' => $post['id']]),
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(): void
    {
        verify_csrf();
        $post = $this->manageablePost(request_id());
        [$data, $errors] = $this->validatedPostData();
        $data['id'] = $post['id'];

        if ($errors !== []) {
            $this->render('posts/form', [
                'pageTitle' => 'Edit Post',
                'post' => $data,
                'categories' => (new Category($this->pdo))->all(),
                'errors' => $errors,
                'formAction' => url('post.edit', ['id' => $post['id']]),
                'submitLabel' => 'Save Changes',
            ], 422);
            return;
        }

        $posts = new Post($this->pdo);
        $posts->update((int) $post['id'], $data);
        $updated = $posts->find((int) $post['id']);

        flash('success', 'Post updated successfully.');
        redirect('post.show', ['slug' => $updated['slug']]);
    }

    public function delete(): void
    {
        verify_csrf();
        $post = $this->manageablePost(request_id());
        (new Post($this->pdo))->delete((int) $post['id']);

        flash('success', 'Post deleted successfully.');
        redirect(is_admin() ? 'admin' : 'profile');
    }

    private function manageablePost(int $id): array
    {
        $post = (new Post($this->pdo))->find($id);

        if ($post === null) {
            abort(404, 'Post not found.');
        }

        if (!can_manage_post($post)) {
            abort(403, 'You may only manage your own posts.');
        }

        return $post;
    }

    private function validatedPostData(): array
    {
        $categoryValue = trim((string) ($_POST['category_id'] ?? ''));
        $categoryId = $categoryValue === '' ? null : filter_var($categoryValue, FILTER_VALIDATE_INT);
        $data = [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'content' => trim((string) ($_POST['content'] ?? '')),
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
            'category_id' => $categoryId,
            'status' => (string) ($_POST['status'] ?? 'draft'),
        ];
        $errors = [];

        if (text_length($data['title']) < 3 || text_length($data['title']) > 255) {
            $errors['title'] = 'Title must contain 3-255 characters.';
        }

        if (text_length($data['content']) < 20) {
            $errors['content'] = 'Content must contain at least 20 characters.';
        }

        if (text_length($data['excerpt']) > 500) {
            $errors['excerpt'] = 'Excerpt cannot exceed 500 characters.';
        }

        if (!in_array($data['status'], ['draft', 'published'], true)) {
            $errors['status'] = 'Choose a valid post status.';
        }

        if ($categoryValue !== '') {
            if ($categoryId === false || $categoryId < 1 || !(new Category($this->pdo))->exists((int) $categoryId)) {
                $errors['category_id'] = 'Choose a valid category.';
                $data['category_id'] = $categoryValue;
            } else {
                $data['category_id'] = (int) $categoryId;
            }
        }

        return [$data, $errors];
    }
}
