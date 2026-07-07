<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        // تنظيف المسار (إزالة أي إضافات)
        $url = parse_url($url, PHP_URL_PATH);

        // داخل دالة dispatch في ملف Router.php
if ($url === '/login') {
    (new \App\Controllers\AuthController())->login();
} elseif ($url === '/dashboard') {
    (new \App\Controllers\DashboardController())->index();
} else {
    require_once '../views/home.php';
}
    }
}