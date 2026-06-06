<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

final class CategoryController extends Controller
{
    public function index(): void
    {
        $this->render('admin/categories/index', [
            'pageTitle' => 'Manage Categories',
            'categories' => (new Category($this->pdo))->allWithPostCounts(),
        ]);
    }

    public function create(): void
    {
        $this->render('admin/categories/form', [
            'pageTitle' => 'Create Category',
            'category' => ['name' => ''],
            'formAction' => url('admin.category.create'),
            'submitLabel' => 'Create Category',
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $name = trim((string) ($_POST['name'] ?? ''));
        $error = $this->validateName($name);

        if ($error !== null) {
            $this->render('admin/categories/form', [
                'pageTitle' => 'Create Category',
                'category' => ['name' => $name],
                'errors' => ['name' => $error],
                'formAction' => url('admin.category.create'),
                'submitLabel' => 'Create Category',
            ], 422);
            return;
        }

        (new Category($this->pdo))->create($name);
        flash('success', 'Category created.');
        redirect('admin.categories');
    }

    public function edit(): void
    {
        $category = $this->findCategory(request_id());

        $this->render('admin/categories/form', [
            'pageTitle' => 'Edit Category',
            'category' => $category,
            'formAction' => url('admin.category.edit', ['id' => $category['id']]),
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(): void
    {
        verify_csrf();
        $category = $this->findCategory(request_id());
        $name = trim((string) ($_POST['name'] ?? ''));
        $error = $this->validateName($name);

        if ($error !== null) {
            $category['name'] = $name;
            $this->render('admin/categories/form', [
                'pageTitle' => 'Edit Category',
                'category' => $category,
                'errors' => ['name' => $error],
                'formAction' => url('admin.category.edit', ['id' => $category['id']]),
                'submitLabel' => 'Save Changes',
            ], 422);
            return;
        }

        (new Category($this->pdo))->update((int) $category['id'], $name);
        flash('success', 'Category updated.');
        redirect('admin.categories');
    }

    public function delete(): void
    {
        verify_csrf();
        $category = $this->findCategory(request_id());
        (new Category($this->pdo))->delete((int) $category['id']);

        flash('success', 'Category deleted. Its posts are now uncategorized.');
        redirect('admin.categories');
    }

    private function findCategory(int $id): array
    {
        $category = (new Category($this->pdo))->find($id);

        if ($category === null) {
            abort(404, 'Category not found.');
        }

        return $category;
    }

    private function validateName(string $name): ?string
    {
        if (text_length($name) < 2 || text_length($name) > 100) {
            return 'Category name must contain 2-100 characters.';
        }

        return null;
    }
}
