<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>

<div class="dashboard">
    <div class="welcome-section">
        <div class="avatar-circle large">
            <?= strtoupper(substr($_SESSION['user']['name'], 0, 1)) ?>
        </div>
            <div class="welcome-text">
                <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?> <span class="you-text">(You)</span></h2>
                <p class="email"><?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?></p>
            </div>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <h3>Total Posts</h3>
                <div class="stat-number">0</div>
                <p class="stat-description">Posts you've created</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <h3>Friends</h3>
                <div class="stat-number">0</div>
                <p class="stat-description">Friends you've made</p>
            </div>
        </div>

        <div class="empty-state">
        <div class="empty-icon">📝</div>
            <form method="POST" action="/posts/create" class="direct-post-form">
            <textarea name="content" placeholder="What's on your mind?" rows="3" maxlength="255" required></textarea>
            <button type="submit" class="btn">Create Post</button>
        </form>
    </div>
</div>
<div class="dashboard-posts-section">
    <h3 class="section-title">Your Posts</h3>
    
    <div class="posts-container">
        <?php if (empty($posts)): ?>
            <div class="no-posts">
                <div class="no-posts-icon">📝</div>
                <p class="noposts">You haven't created any posts yet.</p>
                <a href="/posts/create" class="btn">Create Your First Post</a>
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
                        <div class="post-actions">
                            <form action="/posts/delete" method="POST" class="delete-form">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                    Delete
                                </button>
                            </form>
                        </div>
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
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>