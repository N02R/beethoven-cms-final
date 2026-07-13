<?php
// 1. بدء الجلسة للوصول إلى البيانات الحالية
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. إفراغ مصفوفة الجلسة بالكامل
$_SESSION = array();

// 3. تدمير ملف تعريف الارتباط الخاص بالجلسة (Session Cookie) في متصفح المستخدم لأمان أعلى
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. تدمير الجلسة نهائياً من السيرفر
session_destroy();

// 5. التوجيه الفوري إلى صفحة تسجيل الدخول الرئيسية للموقع
header("Location: ../login.php");
exit();
