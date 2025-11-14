<?php
$title = 'Edit Post | AuthBoard';
ob_start();
?>
<div class="edit-container">
    <div class="edit-form-wrapper">
        <h2 class="edit-title">Edit Post</h2>
        <form method="POST" action="/posts/update" class="form">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <div class="form-group">
                <label>Edit your post:</label>
                <textarea name="content" rows="4" required maxlength="250" placeholder="What's on your mind?"><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <div class="form-actions">
                <button>Update Post</button>
                <a href="/posts" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';