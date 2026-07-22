<?php
/**
 * logout.php - تسجيل الخروج الآمن وتدمير الجلسة والكوكيز
 */

// 1. السماح بالوصول وتضمين التهيئة المركزية (لتشغيل الجلسة الآمنة)
define('ALLOWED_ACCESS', true);
require_once __DIR__ . '/api/init.php';

// 2. تفريغ كافة متغيرات الجلسة
$_SESSION = [];

// 3. تدمير كوكيز الجلسة من متصفح المستخدم إن وجدت
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. تدمير الجلسة نهائياً من على الخادم
session_destroy();

// 5. التوجيه الفوري لصفحة تسجيل الدخول مع رسالة نجاح خفيفة
header("Location: login.php?message=" . urlencode('تم تسجيل الخروج بنجاح.'));
exit;
