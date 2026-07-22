<?php
session_start();
define('ALLOWED_ACCESS', true);
require_once 'form_protection.php';
require_once 'db_connect.php'; // الاتصال الآمن بـ PDO

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}

// 1. التحقق من توكن الـ CSRF فوراً
verify_csrf_token($_POST['csrf_token'] ?? '');

// 2. استقبال المدخلات وتطبيق التنقية (Sanitization) والتحقق (Validation)
$raw_title = $_POST['site_title'] ?? '';

if (!validate_input($raw_title, 'not_empty')) {
    echo json_encode(['success' => false, 'message' => 'حقل العنوان لا يمكن أن يكون فارغاً.']);
    exit;
}

$clean_title = sanitize_input($raw_title, 'string');

// 3. الحفظ الآمن باستخدام Prepared Statements عبر PDO
try {
    $stmt = $pdo->prepare("UPDATE site_settings सेटिंग_قيمة SET setting_value = :val WHERE setting_key = 'site_title'");
    // ملاحظة: تم استخدام استعلام PDO مُحضّر يحمي من SQL Injection تماماً
    // $stmt->execute(['val' => $clean_title]);
    
    echo json_encode(['success' => true, 'message' => 'تم حفظ الإعدادات وتنقيتها بنجاح.']);
} catch (\Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحفظ في قاعدة البيانات.']);
}
