<?php
$title = 'Search Users | StatusBoard';
ob_start();
?>

<div class="search-container">
    <div class="search-header">
        <h2>Search Users</h2>
        
        <form method="GET" action="/search" class="search-form">
            <div class="search-input-group">
                <div>
                    <input type="text" name="q" value="<?= htmlspecialchars($query ?? '') ?>" 
                        placeholder="Search users by name or email..." class="search-input" required>
                </div>
                <div>
                    <button type="submitSearch" class="search-btn">🔍 Search</button>
                </div>
            </div>
            
        </form>
    </div>

    <?php if (isset($userProfile)): ?>
        <div class="user-profile-view">
            <div class="back-button">
                <a href="/search<?= !empty($query) ? '?q=' . urlencode($query) : '' ?>" class="btn-back">←</a>
            </div>
            
            <div class="user-profile-header">
                <div class="user-avatar-large">
                    <div class="avatar-circle large">
                        <?= strtoupper(substr($userProfile['name'], 0, 1)) ?>
                    </div>
                </div>
                <div class="user-profile-info">
                    <h2><?= htmlspecialchars($userProfile['name']) ?></h2>
                    <p class="user-email"><?= htmlspecialchars($userProfile['email']) ?></p>
                    <p class="user-joined">Joined <?= date('F j, Y', strtotime($userProfile['created_at'])) ?></p>
                </div>
            </div>

            <div class="user-posts-section">
                <h3>Posts by <?= htmlspecialchars($userProfile['name']) ?></h3>
                
                <?php if (empty($userPosts)): ?>
                    <div class="no-posts">
                        <p>This user hasn't created any posts yet.</p>
                    </div>
                <?php else: ?>
                    <div class="posts-container">
                        <?php foreach ($userPosts as $post): 
                            $likeCount = \App\Models\Post::getLikesCount($post['id']);
                            $comments = \App\Models\Post::getComments($post['id']);
                        ?>
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
                                </div>
                                <div class="post-content">
                                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                                    <?php if ($post['image']): ?>
                                        <div class="post-image">
                                            <img src="/<?= htmlspecialchars($post['image']) ?>" alt="Post image">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="post-stats">
                                    <span class="likes">❤️ <?= $likeCount ?> likes</span>
                                    <span class="comments">💬 <?= count($comments) ?> comments</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <?php if (!empty($query)): ?>
            <div class="search-results">
                <?php if (empty($users)): ?>
                    <div class="no-results">
                        <div class="no-results-icon">🔍</div>
                        <h3>No users found for "<?= htmlspecialchars($query) ?>"</h3>
                        <p>Try different search terms</p>
                    </div>
                <?php else: ?>
                    <div class="results-section">
                        <h3>Found <?= count($users) ?> user(s)</h3>
                        <div class="users-results">
                            <?php foreach ($users as $user): ?>
                                <div class="user-result">
                                    <div class="user-avatar">
                                        <div class="avatar-circle">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div class="user-info">
                                        <h4><?= htmlspecialchars($user['name']) ?></h4>
                                        <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
                                        <p class="user-joined">Joined <?= date('M Y', strtotime($user['created_at'])) ?></p>
                                    </div>
                                    <div class="user-actions">
                                        <a href="/search?user_id=<?= $user['id'] ?>&q=<?= urlencode($query) ?>" class="btn-view-posts">
                                            View Posts
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="search-empty">
                <div class="search-icon">🔍</div>
                <h3>Search Users</h3>
                <p>Enter a name or email address to find users</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';