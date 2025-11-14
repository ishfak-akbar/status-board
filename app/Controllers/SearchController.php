<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller {
    public function search() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $query = trim($_GET['q'] ?? '');
        $user_id = $_GET['user_id'] ?? null;

        if ($user_id) {
            $userProfile = User::findById($user_id);
            $userPosts = Post::getUserPostsWithDetails($user_id);
            
            $this->view('search.php', [
                'query' => $query,
                'userProfile' => $userProfile,
                'userPosts' => $userPosts
            ]);
            return;
        }

        $users = [];
        if (!empty($query)) {
            $users = User::search($query);
        }

        $this->view('search.php', [
            'users' => $users,
            'query' => $query
        ]);
    }
}