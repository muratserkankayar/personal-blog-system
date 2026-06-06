<?php if (($pagination['last_page'] ?? 1) > 1): ?>
    <nav aria-label="Post list pages">
        <ul class="pagination justify-content-center">
            <li class="page-item<?= $pagination['current_page'] <= 1 ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= e(pagination_url($pagination['current_page'] - 1, $search ?? '')) ?>">Previous</a>
            </li>
            <?php for ($pageNumber = 1; $pageNumber <= $pagination['last_page']; $pageNumber++): ?>
                <li class="page-item<?= $pageNumber === $pagination['current_page'] ? ' active' : '' ?>">
                    <a class="page-link" href="<?= e(pagination_url($pageNumber, $search ?? '')) ?>">
                        <?= $pageNumber ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item<?= $pagination['current_page'] >= $pagination['last_page'] ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= e(pagination_url($pagination['current_page'] + 1, $search ?? '')) ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
