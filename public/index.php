<?php
// public/index.php
require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Core/CMS.php'; // تأكدي من إضافة هذا السطر

// تصحيح المسار ليعود للمجلد الرئيسي
$root = dirname(__DIR__);

$url = $_SERVER['REQUEST_URI'];

if ($url === '/' || $url === '/index.php') {
    require_once $root . '/templates/home.php';
} else {
    echo "404 - الصفحة غير موجودة";
}
?>
