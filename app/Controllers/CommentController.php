<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;

class CommentController extends Controller {
    public function addComment() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        if (!$postId || empty($content)) {
            echo "Post ID and comment content required";
            return;
        }

        // Verify the post exists before adding comment
        $post = Post::getById($postId);
        if (!$post) {
            echo "Post not found";
            return;
        }

        // Add comment
        $success = Post::addComment($postId, $user['id'], $content);
        
        if ($success) {
            header('Location: /posts');
        } else {
            echo "Failed to add comment";
        }
    }
}