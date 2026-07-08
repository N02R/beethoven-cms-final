<?php
// public/index.php

// 1. استدعاء ملفات النظام الأساسية
// ملاحظة: يُفضل مستقبلاً وضع هذه الاستدعاءات في ملف واحد (مثل init.php)
require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Core/CMS.php';

// 2. تحديد المسار الجذري للمشروع
$root = dirname(__DIR__);

// 3. الحصول على الرابط المطلوب من المتصفح
$url = $_SERVER['REQUEST_URI'];

// 4. نظام التوجيه (Routing System)
if ($url === '/' || $url === '/index.php') {
    // عرض الصفحة الرئيسية للموقع
    require_once $root . '/templates/home.php';

} elseif (strpos($url, '/admin') === 0) {
    // توجيه أي رابط يبدأ بـ /admin إلى مجلد الإدارة
    // المسار هنا يشير إلى المجلد الموجود بجانب public
    require_once $root . '/admin/index.php';

} else {
    // معالجة الصفحات غير الموجودة
    http_response_code(404);
    echo "404 - الصفحة غير موجودة";
}
?>
