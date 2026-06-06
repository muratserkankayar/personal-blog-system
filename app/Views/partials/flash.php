<?php foreach (consume_flashes() as $message): ?>
    <?php
    $class = match ($message['type']) {
        'success' => 'success',
        'warning' => 'warning',
        'error' => 'danger',
        default => 'info',
    };
    ?>
    <div class="alert alert-<?= e($class) ?> alert-dismissible fade show shadow-sm" role="alert">
        <?= e($message['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>
