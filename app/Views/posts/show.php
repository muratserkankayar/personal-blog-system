<div class="row justify-content-center">
    <div class="col-xl-9">
        <article class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge text-bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                        <?= e(ucfirst($post['status'])) ?>
                    </span>
                    <span class="badge text-bg-primary"><?= e($post['category_name'] ?? 'Uncategorized') ?></span>
                </div>
                <h1 class="display-5 fw-bold"><?= e($post['title']) ?></h1>
                <p class="text-body-secondary border-bottom pb-4">
                    By <strong><?= e($post['author_name']) ?></strong>
                    &middot; <?= e(format_date($post['created_at'])) ?>
                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                        &middot; Updated <?= e(format_date($post['updated_at'])) ?>
                    <?php endif; ?>
                </p>

                <?php if (!empty($post['excerpt'])): ?>
                    <p class="lead"><?= e($post['excerpt']) ?></p>
                <?php endif; ?>

                <div class="post-content"><?= nl2br(e($post['content'])) ?></div>

                <?php if (can_manage_post($post)): ?>
                    <div class="d-flex gap-2 border-top pt-4 mt-4 position-relative">
                        <a class="btn btn-outline-primary" href="<?= e(url('post.edit', ['id' => $post['id']])) ?>">Edit</a>
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-delete-action="<?= e(url('post.delete', ['id' => $post['id']])) ?>"
                                data-delete-name="<?= e($post['title']) ?>">
                            Delete
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </article>

        <section class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h4 mb-4">Comments <span class="text-body-secondary">(<?= count($comments) ?>)</span></h2>

                <?php if ($comments === []): ?>
                    <p class="text-body-secondary">No comments yet. Start the conversation.</p>
                <?php else: ?>
                    <div class="vstack gap-3 mb-4">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment rounded-3 p-3">
                                <div class="d-flex justify-content-between gap-3 mb-2">
                                    <strong><?= e($comment['username']) ?></strong>
                                    <span class="small text-body-secondary"><?= e(format_date($comment['created_at'])) ?></span>
                                </div>
                                <div><?= nl2br(e($comment['content'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($post['status'] === 'published' && is_logged_in()): ?>
                    <form method="post" action="<?= e(url('comment.create')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                        <label class="form-label fw-semibold" for="content">Leave a comment</label>
                        <textarea class="form-control mb-3" id="content" name="content" rows="4"
                                  minlength="2" maxlength="2000" required></textarea>
                        <button class="btn btn-primary" type="submit">Post Comment</button>
                    </form>
                <?php elseif ($post['status'] === 'published'): ?>
                    <div class="alert alert-info mb-0">
                        <a href="<?= e(url('login')) ?>">Log in</a> to leave a comment.
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">Publish this draft to enable comments.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
