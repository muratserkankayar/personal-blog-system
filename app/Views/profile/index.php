<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h2 mb-1">My Profile</h1>
        <p class="text-body-secondary mb-0">Manage your account and writing.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('post.create')) ?>">Create New Post</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Account details</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Username</dt>
                    <dd class="col-sm-8"><?= e($profile['username']) ?></dd>
                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8"><?= e($profile['email']) ?></dd>
                    <dt class="col-sm-4">Role</dt>
                    <dd class="col-sm-8"><span class="badge text-bg-secondary"><?= e(ucfirst($profile['role'])) ?></span></dd>
                    <dt class="col-sm-4">Joined</dt>
                    <dd class="col-sm-8 mb-0"><?= e(format_date($profile['created_at'])) ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Change password</h2>
                <form method="post" action="<?= e(url('profile.password')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="current_password">Current password</label>
                        <input class="form-control<?= isset($passwordErrors['current_password']) ? ' is-invalid' : '' ?>"
                               type="password" id="current_password" name="current_password" autocomplete="current-password" required>
                        <?php if (isset($passwordErrors['current_password'])): ?>
                            <div class="invalid-feedback"><?= e($passwordErrors['current_password']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="new_password">New password</label>
                            <input class="form-control<?= isset($passwordErrors['new_password']) ? ' is-invalid' : '' ?>"
                                   type="password" id="new_password" name="new_password" autocomplete="new-password" required>
                            <?php if (isset($passwordErrors['new_password'])): ?>
                                <div class="invalid-feedback"><?= e($passwordErrors['new_password']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="new_password_confirmation">Confirm new password</label>
                            <input class="form-control<?= isset($passwordErrors['new_password_confirmation']) ? ' is-invalid' : '' ?>"
                                   type="password" id="new_password_confirmation" name="new_password_confirmation"
                                   autocomplete="new-password" required>
                            <?php if (isset($passwordErrors['new_password_confirmation'])): ?>
                                <div class="invalid-feedback"><?= e($passwordErrors['new_password_confirmation']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h2 class="h4 mb-3">My posts <span class="text-body-secondary">(<?= count($posts) ?>)</span></h2>
        <?php if ($posts === []): ?>
            <p class="text-body-secondary mb-0">You have not written any posts yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                    <tr>
                        <th>Title</th>
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
                            <td><?= e($post['category_name'] ?? 'Uncategorized') ?></td>
                            <td>
                                <span class="badge text-bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                    <?= e(ucfirst($post['status'])) ?>
                                </span>
                            </td>
                            <td class="text-nowrap"><?= e(format_date($post['created_at'])) ?></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="<?= e(url('post.edit', ['id' => $post['id']])) ?>">Edit</a>
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
