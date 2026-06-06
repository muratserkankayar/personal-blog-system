<div class="error-panel text-center py-5">
    <div class="display-1 fw-bold text-primary">404</div>
    <h1 class="h3">Page not found</h1>
    <p class="text-body-secondary"><?= e($errorMessage ?: 'The requested page could not be found.') ?></p>
    <a class="btn btn-primary" href="<?= e(url('home')) ?>">Return Home</a>
</div>
