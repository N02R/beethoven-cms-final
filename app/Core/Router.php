<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        $url = parse_url($url, PHP_URL_PATH);

        if ($url === '/admin-login') {
            (new \App\Controllers\AuthController())->login();
        } 
        elseif ($url === '/dashboard') {
            if (!isset($_SESSION['is_admin'])) { header('Location: /admin-login'); exit; }
            (new \App\Controllers\DashboardController())->index();
        }
        elseif ($url === '/admin/save-all') {
            if (!isset($_SESSION['is_admin'])) die('غير مصرح');
            (new \App\Controllers\DashboardController())->saveAll();
        }
        elseif ($url === '/') {
            // المسار الصحيح لملف home.php داخل مجلد templates
            require_once dirname(__DIR__, 2) . '/templates/home.php';
        } 
        else {
            http_response_code(404);
            echo "404 - الصفحة غير موجودة";
        }
    }
}
