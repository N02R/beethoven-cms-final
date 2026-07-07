<?php
namespace App\Controllers;

class DashboardController {
    public function index() {
        // لا نحتاج لـ session_start() هنا لأن الـ Router قام بها بالفعل
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require_once '../views/dashboard.php';
    }
}