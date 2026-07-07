<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        $url = parse_url($url, PHP_URL_PATH);
        session_start(); // تفعيل الجلسة في كل طلب

        // حماية المسارات (Middleware)
        if ($url === '/dashboard' && !isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // توجيه الطلبات
        if ($url === '/login') {
            (new \App\Controllers\AuthController())->login();
        } elseif ($url === '/dashboard') {
            (new \App\Controllers\DashboardController())->index();
        } elseif ($url === '/logout') {
            (new \App\Controllers\AuthController())->logout();
        } else {
            require_once '../views/home.php';
        }
    }
}