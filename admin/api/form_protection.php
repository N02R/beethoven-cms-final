<?php
/**
 * form_protection.php - مكتبة حماية النماذج (CSRF, Validation, Sanitization)
 */

if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

/**
 * 1. توليد وتحقق من توكن الـ CSRF
 */
function set_csrf_token() {
    if (empty($_SESSION['form_csrf_token'])) {
        $_SESSION['form_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['form_csrf_token'];
}

function verify_csrf_token($posted_token) {
    if (!isset($_SESSION['form_csrf_token']) || empty($posted_token) || !hash_equals($_SESSION['form_csrf_token'], $posted_token)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'خطأ أمني: رمز التحقق من الطلب (CSRF Token) غير صالح.']);
        exit;
    }
}

/**
 * 2. تنقية المدخلات (Sanitization)
 * إزالة الفراغات الزائدة وتحويل الأحرف الخاصة لمنع الحقن
 */
function sanitize_input($data, $type = 'string') {
    $data = trim($data);
    
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'int':
            return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        case 'url':
            return filter_var($data, FILTER_SANITIZE_URL);
        case 'string':
        default:
            // تنقية النصوص العامة ومنع تنفيذ أي أكواد HTML أو JavaScript ضارة عند العرض
            return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/**
 * 3. التحقق من صحة البيانات (Validation)
 */
function validate_input($data, $rule) {
    switch ($rule) {
        case 'email':
            return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
        case 'not_empty':
            return !empty(trim($data));
        case 'is_numeric':
            return is_numeric($data);
        default:
            return true;
    }
}
