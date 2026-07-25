<?php
/**
 * Beethoven CMS - Secure Session Configuration
 * تطبيق معايير الأمان الأوروبية لحماية جلسات المشرفين
 */

// منع الوصول المباشر للملف
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    http_response_code(403);
    exit("Access Denied");
}

// إعدادات أمان الجلسات قبل البدء بها
if (session_status() === PHP_SESSION_NONE) {
    
    // إعدادات الـ Cookie الخاصة بالجلسة لترقية الحماية
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    
    session_set_cookie_params([
        'lifetime' => 0,                    // تنتهي الجلسة بمجرد إغلاق المتصفح
        'path'     => '/',
        'domain'   => '',                   // النطاق الحالي
        'secure'   => $isSecure,            // تفعيل عبر HTTPS حصرياً في الـ Production
        'httponly' => true,                 // منع الوصول عبر JavaScript (حماية من XSS)
        'samesite' => 'Strict'              // حماية صارمة ضد CSRF
    ]);

    // بدء الجلسة بأمان
    session_start();
    
    // حماية إضافية: إعادة توليد معرف الجلسة كل فترة لمنع تثبيت الجلسة (Session Fixation)
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) { // كل 30 دقيقة يتم تغيير معرف الجلسة
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}
