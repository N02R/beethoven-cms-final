<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        $url = parse_url($url, PHP_URL_PATH);

        // المسارات الخاصة بالإدارة
        if ($url === '/admin-login') {
            (new \App\Controllers\AuthController())->login();
        } 
        elseif ($url === '/admin-process') {
            (new \App\Controllers\AuthController())->processLogin();
        } 
        elseif ($url === '/dashboard') {
            // حماية صفحة الداشبورد
            if (!isset($_SESSION['is_admin'])) {
                header('Location: /admin-login');
                exit;
            }
            (new \App\Controllers\DashboardController())->index();
        }
        elseif ($url === '/logout') {
            (new \App\Controllers\AuthController())->logout();
        }
        elseif ($url === '/admin/save-all') {
            if (!isset($_SESSION['is_admin'])) die('غير مصرح لك');
            (new \App\Controllers\DashboardController())->saveAll();
        }
        // الصفحة الرئيسية للموقع
        elseif ($url === '/') {
            require_once '../templates/home.php';
        } 
        else {
            http_response_code(404);
            echo "404 - الصفحة غير موجودة";
        }
    }
}
