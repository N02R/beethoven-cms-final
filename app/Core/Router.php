<?php
namespace App\Core;

class Router {
    public function dispatch($url) {
        // تنظيف المسار (إزالة أي إضافات)
        $url = parse_url($url, PHP_URL_PATH);

        if ($url === '/login') {
            (new \App\Controllers\AuthController())->login();
        } else {
            // صفحة افتراضية أو الرئيسية
            require_once '../views/home.php';
        }
    }
}