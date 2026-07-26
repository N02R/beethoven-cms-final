<?php
// منع الوصول المباشر للملف إذا لم يتم تعريف الثابت مسبقاً
if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Direct access not permitted.');
}

// منع إعادة تعريف الثابت إذا تم تعريفه في مكان آخر
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

/**
 * التحقق من توكن الحماية ضد الهجمات عبر الموقع (CSRF Token)
 * 
 * @param string|null $token
 * @return bool
 * @throws Exception إذا كان التوكن غير صالح أو غير موجود
 */
if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token(?string $token): bool {
        // التحقق مما إذا كان الجلسة تحتوي على التوكن ومطابقته للـ token المرسل
        if (empty($token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            throw new InvalidArgumentException('Invalid security token (CSRF verification failed).');
        }
        return true;
    }
}

/**
 * دالة توليد توكن حماية جديد إذا لزم الأمر
 */
if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
