<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-2"><?= e($pageTitle) ?></h1>
                <p class="text-body-secondary">The URL slug is generated automatically and kept unique.</p>
                <form method="post" action="<?= e($formAction) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label" for="name">Category name</label>
                        <input class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>"
                               type="text" id="name" name="name" minlength="2" maxlength="100"
                               value="<?= e($category['name'] ?? '') ?>" required autofocus>
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= e($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="submit"><?= e($submitLabel) ?></button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('admin.categories')) ?>">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
