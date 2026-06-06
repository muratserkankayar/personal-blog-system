<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1"><?= e($pageTitle) ?></h1>
                        <p class="text-body-secondary mb-0">Draft privately or publish for everyone.</p>
                    </div>
                    <a class="btn btn-outline-secondary" href="<?= e(url('profile')) ?>">Cancel</a>
                </div>

                <form method="post" action="<?= e($formAction) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="title">Title</label>
                        <input class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>"
                               type="text" id="title" name="title" maxlength="255"
                               value="<?= e($post['title'] ?? '') ?>" required>
                        <?php if (isset($errors['title'])): ?>
                            <div class="invalid-feedback"><?= e($errors['title']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="excerpt">Excerpt <span class="text-body-secondary">(optional)</span></label>
                        <textarea class="form-control<?= isset($errors['excerpt']) ? ' is-invalid' : '' ?>"
                                  id="excerpt" name="excerpt" rows="2" maxlength="500"><?= e($post['excerpt'] ?? '') ?></textarea>
                        <?php if (isset($errors['excerpt'])): ?>
                            <div class="invalid-feedback"><?= e($errors['excerpt']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="content">Content</label>
                        <textarea class="form-control<?= isset($errors['content']) ? ' is-invalid' : '' ?>"
                                  id="content" name="content" rows="13" minlength="20" required><?= e($post['content'] ?? '') ?></textarea>
                        <?php if (isset($errors['content'])): ?>
                            <div class="invalid-feedback"><?= e($errors['content']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-4">
                            <label class="form-label" for="category_id">Category</label>
                            <select class="form-select<?= isset($errors['category_id']) ? ' is-invalid' : '' ?>"
                                    id="category_id" name="category_id">
                                <option value="">Uncategorized</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>"<?= selected($post['category_id'] ?? '', $category['id']) ?>>
                                        <?= e($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?>
                                <div class="invalid-feedback"><?= e($errors['category_id']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-5 mb-4">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select<?= isset($errors['status']) ? ' is-invalid' : '' ?>"
                                    id="status" name="status" required>
                                <option value="draft"<?= selected($post['status'] ?? '', 'draft') ?>>Draft</option>
                                <option value="published"<?= selected($post['status'] ?? '', 'published') ?>>Published</option>
                            </select>
                            <?php if (isset($errors['status'])): ?>
                                <div class="invalid-feedback"><?= e($errors['status']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit"><?= e($submitLabel) ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
