<?php
// public/index.php
session_start();

require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Core/CMS.php';

$root = dirname(__DIR__);
$url = $_SERVER['REQUEST_URI'];

if ($url === '/' || $url === '/index.php') {
    require_once $root . '/templates/home.php';
} elseif (strpos($url, '/admin') === 0) {
    // توجيه طلبات الإدارة (login, save-all)
    $path = $root . str_replace('/admin', '', $url) . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        echo "404 - صفحة الإدارة غير موجودة";
    }
} else {
    http_response_code(404);
    echo "404 - الصفحة غير موجودة";
}
