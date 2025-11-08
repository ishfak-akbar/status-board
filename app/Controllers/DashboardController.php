<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $totalUsers = User::count();
        
        $this->view('dashboard.php', [
            'user' => $user,
            'totalUsers' => $totalUsers
        ]);
    }
}
