<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Log in</h1>
                <p class="text-body-secondary">Access your posts, profile, and comments.</p>

                <?php if (!empty($errors['credentials'])): ?>
                    <div class="alert alert-danger"><?= e($errors['credentials']) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('login')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                               type="email" id="email" name="email" maxlength="100"
                               value="<?= e($old['email'] ?? '') ?>" autocomplete="email" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                               type="password" id="password" name="password" autocomplete="current-password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Log In</button>
                </form>
                <p class="text-center small mt-4 mb-0">
                    No account? <a href="<?= e(url('register')) ?>">Register here</a>.
                </p>
            </div>
        </div>
    </div>
</div>
