<?php
if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

/**
 * توليد وتحقق من توكن حماية CSRF على مستوى الجلسة
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Security token validation failed (CSRF Attack Detected)']);
        exit;
    }
}

/**
 * دالة آمنة لمعالجة رفع الملفات للمنتجات والاتفاقيات مع فحص الـ MIME Type الحقيقي
 */
function secure_handle_upload($file_input_name, $target_directory) {
    if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$file_input_name];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('فشل في عملية رفع الملف.');
    }

    // التحقق من الحجم (مثلاً أقصى حد 10 ميجابايت)
    if ($file['size'] > 10 * 1024 * 1024) {
        throw new Exception('حجم الملف يتجاوز الحد المسموح به (10 ميجابايت).');
    }

    // فحص نوع الملف الحقيقي عبر الفاينل آي دي (MIME Type) وليس الامتداد فقط
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($mime_type, $allowed_mimes)) {
        throw new Exception('نوع الملف غير مسموح به لأسباب أمنية.');
    }

    // توليد اسم عشوائي آمن لمنع تخريب أسماء الملفات أو تجاوز الـ Directory Traversal
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safe_filename = bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    
    if (!is_dir($target_directory)) {
        mkdir($target_directory, 0755, true);
    }

    $destination = $target_directory . $safe_filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('فشل نقل الملف المرفوع إلى المجلد المخصص.');
    }

    return $safe_filename;
}
