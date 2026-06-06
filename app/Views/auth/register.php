<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Create an account</h1>
                <p class="text-body-secondary">Join the community and publish your own posts.</p>

                <?php if (!empty($errors['account'])): ?>
                    <div class="alert alert-danger"><?= e($errors['account']) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('register')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>"
                               type="text" id="username" name="username" maxlength="50"
                               value="<?= e($old['username'] ?? '') ?>" autocomplete="username" required>
                        <?php if (isset($errors['username'])): ?>
                            <div class="invalid-feedback"><?= e($errors['username']) ?></div>
                        <?php else: ?>
                            <div class="form-text">3-50 letters, numbers, or underscores.</div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                               type="email" id="email" name="email" maxlength="100"
                               value="<?= e($old['email'] ?? '') ?>" autocomplete="email" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                                   type="password" id="password" name="password" autocomplete="new-password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label" for="password_confirmation">Confirm password</label>
                            <input class="form-control<?= isset($errors['password_confirmation']) ? ' is-invalid' : '' ?>"
                                   type="password" id="password_confirmation" name="password_confirmation"
                                   autocomplete="new-password" required>
                            <?php if (isset($errors['password_confirmation'])): ?>
                                <div class="invalid-feedback"><?= e($errors['password_confirmation']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
