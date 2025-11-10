<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;

class LikeController extends Controller {
    public function toggleLike() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        if (!$postId) {
            echo "Post ID required";
            return;
        }

        // Check if user already liked the post
        if (Post::isLikedByUser($postId, $user['id'])) {
            // Remove like
            Post::removeLike($postId, $user['id']);
        } else {
            // Add like
            Post::addLike($postId, $user['id']);
        }

        // Return updated like count
        $likeCount = Post::getLikesCount($postId);
        echo $likeCount;
    }
}