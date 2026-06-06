<div class="error-panel text-center py-5">
    <div class="display-1 fw-bold text-warning">419</div>
    <h1 class="h3">Session expired</h1>
    <p class="text-body-secondary"><?= e($errorMessage ?: 'Refresh the page and submit the form again.') ?></p>
    <a class="btn btn-primary" href="<?= e(url('home')) ?>">Return Home</a>
</div>
