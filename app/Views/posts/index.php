<section class="hero-panel rounded-4 p-4 p-lg-5 mb-4 text-white shadow-sm">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="badge text-bg-light text-primary mb-3">Personal Blog System</span>
            <h1 class="display-6 fw-bold mb-3">
                <?= $search === '' ? 'Ideas worth sharing' : 'Search results' ?>
            </h1>
            <p class="lead mb-0">
                <?= $search === ''
                    ? 'Read the latest published stories from our community.'
                    : 'Published posts matching "' . e($search) . '".' ?>
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <?php if (is_logged_in()): ?>
                <a class="btn btn-light btn-lg" href="<?= e(url('post.create')) ?>">Write a Post</a>
            <?php else: ?>
                <a class="btn btn-light btn-lg" href="<?= e(url('register')) ?>">Join the Blog</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0"><?= $search === '' ? 'Latest posts' : 'Results' ?></h2>
    <span class="text-body-secondary small"><?= (int) $pagination['total'] ?> post(s)</span>
</div>

<?php if ($posts === []): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <h2 class="h4">No posts found</h2>
            <p class="text-body-secondary mb-3">Try another search or check back after a post is published.</p>
            <a class="btn btn-outline-primary" href="<?= e(url('home')) ?>">View all posts</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4 mb-4">
        <?php foreach ($posts as $post): ?>
            <?php
            $summary = trim((string) ($post['excerpt'] ?? ''));
            if ($summary === '') {
                $summary = text_length($post['content']) > 180
                    ? substr($post['content'], 0, 180) . '...'
                    : $post['content'];
            }
            ?>
            <div class="col-md-6">
                <article class="card h-100 border-0 shadow-sm post-card">
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 mb-3">
                            <?php if ($post['category_name']): ?>
                                <span class="badge text-bg-primary"><?= e($post['category_name']) ?></span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Uncategorized</span>
                            <?php endif; ?>
                            <span class="small text-body-secondary"><?= e(format_date($post['created_at'])) ?></span>
                        </div>
                        <h3 class="h4">
                            <a class="stretched-link text-decoration-none text-body"
                               href="<?= e(url('post.show', ['slug' => $post['slug']])) ?>">
                                <?= e($post['title']) ?>
                            </a>
                        </h3>
                        <p class="text-body-secondary mb-3"><?= e($summary) ?></p>
                        <div class="small fw-semibold">By <?= e($post['author_name']) ?></div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>
    </div>

    <?php require dirname(__DIR__) . '/partials/pagination.php'; ?>
<?php endif; ?>
