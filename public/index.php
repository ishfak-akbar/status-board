<?php
declare(strict_types=1);

// autoload
require __DIR__ . '/../vendor/autoload.php';

// tiny .env loader (reads .env into getenv and $_ENV)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2) + [1=>null]);
        if ($key && $val !== null) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

use App\Core\Router;
use App\Core\Session;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PostController;
use App\Controllers\CommentController;
use App\Controllers\LikeController;

Session::start();

$router = new Router();

// REMOVE THESE LINES - Don't instantiate controllers directly
// $auth = new AuthController();
// $dash = new DashboardController();
// $post = new PostController();

// USE ARRAY CALLBACKS INSTEAD:
$router->get('/', [AuthController::class, 'showLogin']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->post('/register', [AuthController::class, 'register']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Routes for posts
$router->get('/posts', [PostController::class, 'showPosts']);
$router->get('/posts/create', [PostController::class, 'showCreatePost']);
$router->post('/posts/create', [PostController::class, 'createPost']);
$router->post('/posts/delete', [PostController::class, 'delete']);
$router->post('/like/toggle', [LikeController::class, 'toggleLike']);
$router->post('/comment/add', [CommentController::class, 'addComment']);
$router->get('/posts/edit', [PostController::class, 'showEdit']);
$router->post('/posts/update', [PostController::class, 'update']);

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
