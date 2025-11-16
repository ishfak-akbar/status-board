<?php
$title = 'Dashboard | StatusBoard';
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
                <div class="stat-number"><?= count($posts) ?></div>
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
            <?php foreach ($posts as $post): 
                $likeCount = \App\Models\Post::getLikesCount($post['id']);
                $isLiked = \App\Models\Post::isLikedByUser($post['id'], $_SESSION['user']['id'] ?? 0);
                $comments = \App\Models\Post::getComments($post['id']);?>
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
                            <div>
                                <a href="/posts/edit?id=<?= $post['id'] ?>" class="edit-btn">&#9998;</a>
                            </div>
                            <div>
                                <form action="/posts/delete" method="POST" class="delete-form">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                        🗑️
                                    </button>
                                </form>
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
                    <div class="post-interactions">
                        <div>
                            <form method="POST" action="/like/toggle" class="like-form">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="interaction" class="interaction-btn like-btn <?= $isLiked ? 'liked' : '' ?>">
                                    <span class="interaction-icon"><?= $isLiked ? '❤️' : '💚' ?></span>
                                    <span class="interaction-count"><?= $likeCount ?></span>
                                </button>
                            </form>
                        </div>
                        <div>
                            <button type="interaction" class="interaction-btn comment-btn" onclick="toggleComments(<?= $post['id'] ?>)">
                                <span class="interaction-icon">💬</span>
                                <span class="interaction-count"><?= count($comments) ?></span>
                            </button>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section" id="comments-<?= $post['id'] ?>" style="display: none;">
                        <div class="comments-list">
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment">
                                    <div class="comment-user"><?= htmlspecialchars($comment['user_name']) ?></div>
                                    <div class="comment-content"><?= htmlspecialchars($comment['content']) ?></div>
                                    <div class="comment-date"><?= date('M j, g:i A', strtotime($comment['created_at'])) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="commentInput-Button">
                            <div>
                                <form method="POST" action="/comment/add" class="comment-form">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <div class="inputt">
                                        <input type="text" name="content" placeholder="Write a comment..." required>
                                    </div>
                                    <button type="submitComment">➤</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<script>
function toggleComments(postId) {
    const commentsSection = document.getElementById('comments-' + postId);
    if (commentsSection.style.display === 'none') {
        commentsSection.style.display = 'block';
    } else {
        commentsSection.style.display = 'none';
    }
}

//AJAX for likes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const likeBtn = this.querySelector('.like-btn');
            const likeCount = this.querySelector('.interaction-count');
            const likeIcon = this.querySelector('.interaction-icon');
            
            fetch('/like/toggle', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(count => {
                likeCount.textContent = count;
                if (likeBtn.classList.contains('liked')) {
                    likeBtn.classList.remove('liked');
                    likeIcon.textContent = '💚';
                } else {
                    likeBtn.classList.add('liked');
                    likeIcon.textContent = '❤️';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error liking post. Please try again.');
            });
        });
    });
});
</script>


<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>