<?php
// public/index.php

// تصحيح المسار ليعود للمجلد الرئيسي
$root = dirname(__DIR__);

$url = $_SERVER['REQUEST_URI'];

if ($url === '/' || $url === '/index.php') {
    require_once $root . '/templates/home.php';
} else {
    echo "404 - الصفحة غير موجودة";
}
?>
