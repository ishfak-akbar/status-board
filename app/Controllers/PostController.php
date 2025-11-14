<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;

class PostController extends Controller {
    public function showPosts() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $posts = Post::getAllWithUsers();
        $this->view('posts/posts.php', ['user' => $user, 'posts' => $posts]);
    }

    public function showCreatePost() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $this->view('posts/create.php', ['user' => $user]);
    }

    public function createPost() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        $image = $_FILES['image'] ?? null;

        if (empty($content)) {
            echo "Content cannot be empty.";
            return;
        }

        $imagePath = null;
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $imagePath = 'uploads/' . $fileName;

            if (!move_uploaded_file($image['tmp_name'], $uploadDir . $fileName)) {
                echo "Failed to upload image.";
                return;
            }
        }

        Post::create($user['id'], $content, $imagePath);
        header('Location: /posts');
    }

    public function delete()
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        
        if (!$postId) {
            Session::set('error', 'Post ID is required');
            header('Location: /posts');
            exit;
        }

        $post = Post::getById($postId);

        if (!$post || $post['user_id'] != $user['id']) {
            Session::set('error', 'You can only delete your own posts');
            header('Location: /posts');
            exit;
        }

        Post::delete($postId);

        Session::set('success', 'Post deleted successfully');
        header('Location: /posts');
        exit;
    }
    public function showEdit() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_GET['id'] ?? null;
        $post = Post::getById($postId);

        if (!$post || $post['user_id'] != $user['id']) {
            echo "You can only edit your own posts";
            return;
        }

        $this->view('posts/edit.php', ['post' => $post]);
    }

    public function update() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        $post = Post::getById($postId);
        
        if (!$post || $post['user_id'] != $user['id']) {
            echo "Unauthorized";
            return;
        }
        Post::updatePost($postId, $content);
        header('Location: /posts');
    }
}