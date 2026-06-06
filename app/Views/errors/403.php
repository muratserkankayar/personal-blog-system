<div class="error-panel text-center py-5">
    <div class="display-1 fw-bold text-danger">403</div>
    <h1 class="h3">Access forbidden</h1>
    <p class="text-body-secondary"><?= e($errorMessage ?: 'You do not have permission to view this page.') ?></p>
    <a class="btn btn-primary" href="<?= e(url('home')) ?>">Return Home</a>
</div>
