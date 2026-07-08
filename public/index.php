<?php
// public/index.php

// 1. تعريف المسارات الأساسية
define('ROOT', dirname(__DIR__));

// 2. استدعاء ملفات الـ Core (سنقوم بتطويرها لاحقاً للتحكم الديناميكي)
require_once ROOT . '/app/Core/Database.php';

// 3. تحليل المسار (Routing)
$url = $_SERVER['REQUEST_URI'];

// 4. عرض الصفحة بناءً على المسار
// مستقبلاً، سنستبدل هذا المنطق بـ Router متطور يقرأ من قاعدة البيانات
if ($url === '/' || $url === '/index.php') {
    include ROOT . '/templates/home.php';
} else {
    // يمكنك إضافة المزيد من الصفحات هنا
    echo "404 - الصفحة غير موجودة";
}
?>
