<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Categories</h1>
        <p class="text-body-secondary mb-0">Organize posts into clear topics.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('admin.category.create')) ?>">Create Category</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if ($categories === []): ?>
            <p class="text-body-secondary mb-0">No categories exist yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Posts</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($category['name']) ?></td>
                            <td><code><?= e($category['slug']) ?></code></td>
                            <td><?= (int) $category['post_count'] ?></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('admin.category.edit', ['id' => $category['id']])) ?>">Edit</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-delete-action="<?= e(url('admin.category.delete', ['id' => $category['id']])) ?>"
                                        data-delete-name="<?= e($category['name']) ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-4 mb-0 small">
                Deleting a category does not delete its posts. Those posts become uncategorized.
            </div>
        <?php endif; ?>
    </div>
</div>
