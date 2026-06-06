<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A secure personal blog built with pure PHP and MVC.">
    <title><?= e($pageTitle ?? app_config('app.name')) ?> | <?= e(app_config('app.name')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(dirname((string) app_config('app.base_url')) . '/assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-body-tertiary d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e(url('home')) ?>">MVC Blog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= e(url('home')) ?>">Home</a></li>
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('post.create')) ?>">Write Post</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('profile')) ?>">My Profile</a></li>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin')) ?>">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin.categories')) ?>">Categories</a></li>
                <?php endif; ?>
            </ul>

            <form class="d-flex me-lg-3 mb-3 mb-lg-0" method="get" action="<?= e(app_config('app.base_url')) ?>" role="search">
                <input type="hidden" name="route" value="home">
                <input class="form-control form-control-sm me-2" type="search" name="q"
                       value="<?= e($_GET['q'] ?? '') ?>" placeholder="Search posts" aria-label="Search posts">
                <button class="btn btn-outline-light btn-sm" type="submit">Search</button>
            </form>

            <div class="d-flex align-items-center gap-2">
                <?php if (is_logged_in()): ?>
                    <span class="navbar-text small me-1">Hello, <?= e(current_user()['username']) ?></span>
                    <form method="post" action="<?= e(url('logout')) ?>">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline-light btn-sm" type="submit">Log Out</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-outline-light btn-sm" href="<?= e(url('login')) ?>">Log In</a>
                    <a class="btn btn-primary btn-sm" href="<?= e(url('register')) ?>">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    <?php require dirname(__DIR__) . '/partials/flash.php'; ?>
    <?= $content ?>
</main>

<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container d-flex flex-column flex-sm-row justify-content-between gap-2 small">
        <span>&copy; <?= date('Y') ?> Personal Blog System</span>
        <span>Pure PHP MVC, PDO, MySQL, Bootstrap 5</span>
    </div>
</footer>

<?php require dirname(__DIR__) . '/partials/delete-modal.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('deleteModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const form = document.getElementById('deleteForm');
    const label = document.getElementById('deleteItemName');

    form.action = button.getAttribute('data-delete-action');
    label.textContent = button.getAttribute('data-delete-name');
});
</script>
</body>
</html>
