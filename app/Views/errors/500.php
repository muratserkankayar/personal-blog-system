<div class="error-panel text-center py-5">
    <div class="display-1 fw-bold text-danger">500</div>
    <h1 class="h3">Application error</h1>
    <p class="text-body-secondary">
        <?= e($errorMessage ?: 'Something went wrong. Please check the server configuration and error log.') ?>
    </p>
    <a class="btn btn-primary" href="<?= e(url('home')) ?>">Return Home</a>
</div>
