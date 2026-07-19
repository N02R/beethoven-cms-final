<?php
// router.php

// 1. تشغيل الجلسة (مرة واحدة فقط)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. تعريف الثابت مع التحقق لعدم تكرار التعريف (لحماية الصفحات)
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

// 3. استدعاء الهيدر
require_once 'includes/header.php';

// 4. الحصول على الصفحة المطلوبة من المتغير page، وإذا لم يوجد نعتبرها 'home'
$page = $_GET['page'] ?? 'home'; 

// 5. مصفوفة الصفحات المسموح بها (تأكدي من إضافة أي صفحة جديدة هنا)
$allowed = ['home', 'about', 'contact', 'education', 'job', 'guide'];

// 6. تحديد المسار الكامل للملف داخل مجلد pages
$page_path = __DIR__ . '/pages/' . $page . '.php';

// 7. التحقق من وجود الملف ومن كونه ضمن المصفوفة المسموح بها (حماية إضافية)
if (in_array($page, $allowed) && file_exists($page_path)) {
    include($page_path);
} else {
    // في حال الصفحة غير موجودة أو غير مسموح بها، نظهر صفحة 404
    include(__DIR__ . '/pages/404.php');
}

// 8. استدعاء الفوتر
require_once 'includes/footer.php';
?>
