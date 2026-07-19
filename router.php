<?php
// 1. حماية: منع الوصول المباشر لأي ملف داخل هذا المجلد
define('ALLOWED_ACCESS', true); 

require_once 'includes/header.php';

// 2. الحصول على الصفحة
$page = $_GET['page'] ?? 'index';

// 3. مسار المجلد المحمي
$page_path = __DIR__ . '/pages/' . $page . '.php';

// 4. التحقق من وجود الملف (مع حماية ضد التلاعب بالمسارات)
if (file_exists($page_path) && strpos($page, '..') === false) {
    include($page_path);
} else {
    // صفحة 404 احترافية
    include(__DIR__ . '/pages/404.php');
}

require_once 'includes/footer.php';
?>
