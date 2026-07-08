<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        $url = parse_url($url, PHP_URL_PATH);

        // مسارات المصادقة
        if ($url === '/admin-login') {
            (new \App\Controllers\AuthController())->login();
        } 
        elseif ($url === '/admin-process') {
            (new \App\Controllers\AuthController())->processLogin();
        } 
        elseif ($url === '/logout') {
            (new \App\Controllers\AuthController())->logout();
        }
        
        // مسار التحقق من صلاحية المدير (للـ JS)
        elseif ($url === '/admin/check-auth') {
            header('Content-Type: application/json');
            echo json_encode(['is_admin' => isset($_SESSION['is_admin'])]);
            exit;
        }

        // مسارات لوحة التحكم
        elseif ($url === '/dashboard') {
            if (!isset($_SESSION['is_admin'])) {
                header('Location: /admin-login');
                exit;
            }
            (new \App\Controllers\DashboardController())->index();
        }
        elseif ($url === '/admin/save-all') {
            if (!isset($_SESSION['is_admin'])) die('غير مصرح لك');
            (new \App\Controllers\DashboardController())->saveAll();
        }
        
        // الصفحة الرئيسية
        elseif ($url === '/') {
            require_once '../templates/home.php';
        } 
        else {
            http_response_code(404);
            echo "404 - الصفحة غير موجودة";
        }
    }
}
