<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">Admin Dashboard</h1>
        <p class="text-body-secondary mb-0">System overview and complete post management.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('admin.categories')) ?>">
        Manage Categories (<?= (int) $categoryCount ?>)
    </a>
</div>

<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['label' => 'Users', 'value' => $stats['users'], 'class' => 'primary'],
        ['label' => 'All Posts', 'value' => $stats['posts'], 'class' => 'dark'],
        ['label' => 'Published', 'value' => $stats['published'], 'class' => 'success'],
        ['label' => 'Comments', 'value' => $stats['comments'], 'class' => 'info'],
    ];
    ?>
    <?php foreach ($cards as $card): ?>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-<?= e($card['class']) ?>">
                <div class="card-body">
                    <div class="text-body-secondary small text-uppercase fw-semibold"><?= e($card['label']) ?></div>
                    <div class="display-6 fw-bold"><?= (int) $card['value'] ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">All posts</h2>
            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('post.create')) ?>">Create Post</a>
        </div>

        <?php if ($posts === []): ?>
            <p class="text-body-secondary mb-0">No posts have been created.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <a class="fw-semibold text-decoration-none"
                                   href="<?= e(url('post.show', ['slug' => $post['slug']])) ?>">
                                    <?= e($post['title']) ?>
                                </a>
                            </td>
                            <td><?= e($post['author_name']) ?></td>
                            <td><?= e($post['category_name'] ?? 'Uncategorized') ?></td>
                            <td>
                                <span class="badge text-bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                    <?= e(ucfirst($post['status'])) ?>
                                </span>
                            </td>
                            <td class="text-nowrap"><?= e(format_date($post['created_at'])) ?></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('post.edit', ['id' => $post['id']])) ?>">Edit</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-delete-action="<?= e(url('post.delete', ['id' => $post['id']])) ?>"
                                        data-delete-name="<?= e($post['title']) ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
