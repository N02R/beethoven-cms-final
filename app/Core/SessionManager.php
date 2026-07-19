<?php
namespace App\Core;

class SessionManager {
    public static function startSecureSession() {
        if (session_status() === PHP_SESSION_NONE) {
            // إعدادات أمنية صارمة للجلسة
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // أو اسم النطاق الخاص بك
                'secure' => true, // يتطلب HTTPS في الإنتاج
                'httponly' => true, // منع الوصول للجلسة عبر JavaScript
                'samesite' => 'Strict' // منع هجمات CSRF تلقائياً
            ]);
            session_start();
        }

        // منع Session Fixation: تجديد الـ ID في كل عملية دخول
        if (!isset($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = true;
        }
    }
}
