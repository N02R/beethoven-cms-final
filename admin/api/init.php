<?php
/**
 * init.php - ملف التهيئة المركزي للوحة التحكم والصفحات المحمية
 */

// 1. منع الوصول المباشر للملف
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

// 2. تحديد المسار الأساسي للمشروع (Base Path) لضمان دقة مسارات الملفات
$root_path = dirname(__DIR__) . '/'; // تعديل حسب مسار المجلدات الفعلية لديك

// 3. استدعاء ملف تأمين الجلسات والكوكيز
require_once __DIR__ . '/secure_session.php';

// 4. استدعاء ملف الاتصال بقاعدة البيانات عبر PDO
require_once __DIR__ . '/db_connect.php';

// 5. استدعاء مكتبة حماية النماذج وتنقية المدخلات (اختياري حسب الحاجة)
if (file_exists(__DIR__ . '/form_protection.php')) {
    require_once __DIR__ . '/form_protection.php';
}
