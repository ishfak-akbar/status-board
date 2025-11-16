<?php
$title = 'Create Post | StatusBoard';
ob_start();
?>
<div class="create_container">
    <div class="create-form-wrapper">
        <h2 class="new">Create New Post</h2>
        <form method="POST" action="/posts/create" class="form" enctype="multipart/form-data">
            <label>What's on your mind?</label>
            <textarea name="content" rows="4" required maxlength="255" placeholder="Share your thoughts..."></textarea>
            
            <label>Image (optional)</label>
            <input type="file" name="image" accept="image/*">
            
            <div class="post-cancel">
                <button>Post</button>
                <a href="/posts" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';