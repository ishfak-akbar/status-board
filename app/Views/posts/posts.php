<?php
$title = 'Posts | AuthBoard';
ob_start();
?>
<div class="page-header">
    <h2 class="all-posts">All Posts</h2>
    <a href="/posts/create" class="btn">Create New Post</a>
</div>

<div class="posts-container">
    <?php if (empty($posts)): ?>
        <div class="no-posts">
            <p>No posts yet. Be the first to share something!</p>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                    <div class="user-info">
                        <div class="avatar-circle small">
                            <?= strtoupper(substr($post['user_name'], 0, 1)) ?>
                        </div>
                        <div class="user-details">
                            <div class="post-user"><?= htmlspecialchars($post['user_name']) ?></div>
                            <div class="post-date"><?= date('M j, Y g:i A', strtotime($post['created_at'])) ?></div>
                        </div>
                    </div>
                    <?php if ($post['user_id'] == ($_SESSION['user']['id'] ?? null)): ?>
                    <div class="post-actions">
                        <form action="/posts/delete" method="POST" class="delete-form">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                Delete
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="post-content">
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <?php if ($post['image']): ?>
                        <div class="post-image">
                            <img src="/<?= htmlspecialchars($post['image']) ?>" alt="Post image">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';