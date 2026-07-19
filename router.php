<?php
// router.php

// 1. تشغيل الجلسة (مرة واحدة فقط)
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 2. تعريف الثابت مع التحقق لعدم تكرار التعريف
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

require_once 'includes/header.php';

// الحصول على الصفحة المطلوبة
$page = $_GET['page'] ?? 'index'; 

// مصفوفة الصفحات المسموح بها
$allowed = ['home', 'about', 'contact', 'education', 'job', 'guide'];

// التحقق من وجود الملف
$page_path = __DIR__ . '/pages/' . $page . '.php';

if (in_array($page, $allowed) && file_exists($page_path)) {
    include($page_path);
} else {
    include(__DIR__ . '/pages/404.php');
}

require_once 'includes/footer.php';
?>