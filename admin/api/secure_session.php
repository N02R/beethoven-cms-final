<?php
/**
 * secure_session.php - ملف تكوين وتأمين الجلسات والكوكيز وفقاً لأعلى معايير الأمان (OWASP)
 */

if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

// التأكد من عدم بدء جلسة مسبقاً قبل تعديل إعدادات الكوكيز
if (session_status() === PHP_SESSION_NONE) {
    
    // 1. إعدادات كوكيز الجلسة الآمنة قبل استدعاء session_start()
    $is_secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    // ضبط المعاملات الأمنية لـ session cookie
    session_set_cookie_params([
        'lifetime' => 0,                      // تنتهي الجلسة بإغلاق المتصفح (Session Cookie)
        'path'     => '/',                      // متاح في كامل نطاق الموقع
        'domain'   => $_SERVER['HTTP_HOST'] ?? '', // النطاق الحالي حصرياً
        'secure'   => true,                     // [Secure Cookie]: يمنع إرسال الكوكيز إلا عبر اتصال مشفر HTTPS حصرياً
        'httponly' => true,                     // [HttpOnly Cookie]: يمنع تماماً وصول لغة JavaScript إلى الـ Session Cookie لحمايتها من ثغرات XSS
        'samesite' => 'Strict'                  // [SameSite Cookie]: حماية صارمة ضد هجمات تزوير الطلبات عبر المواقع (CSRF)
    ]);

    // تغيير اسم جلسة PHP الافتراضي (PHPSESSID) لإخفاء بصمة النظام وتقليل خطر الهجمات الموجهة
    session_name('BCS_SECURE_ADMIN_SESSION');

    // بدء الجلسة بأمان
    session_start();

    // 2. [Regenerate Session ID]: تجديد معرف الجلسة دورياً لمنع هجمات Session Fixation
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > 1800) {
        // تجديد المعرف كلياً كل 30 دقيقة مع نقل البيانات القديمة
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

// 3. التحقق الإضافي من بصمة المتصفح (Browser Fingerprinting) لمزيد من الأمان ضد اختطاف الجلسات
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    $current_user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    if (!isset($_SESSION['USER_AGENT'])) {
        $_SESSION['USER_AGENT'] = $current_user_agent;
    } elseif ($_SESSION['USER_AGENT'] !== $current_user_agent) {
        // في حال تغيرت بصمة المتصفح، يتم تدمير الجلسة فوراً للاشتباه باختطافها
        session_unset();
        session_destroy();
        header("Location: login.php?error=" . urlencode('تم إنهاء الجلسة لأسباب أمنية. يرجى إعادة تسجيل الدخول.'));
        exit;
    }
}
