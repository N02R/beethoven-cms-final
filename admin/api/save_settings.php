<?php
/**
 * save_settings.php - معالج حفظ الإعدادات مع الحماية الأمنية الكاملة (CSRF + Session Security + PDO)
 */

session_start();

// 1. السماح بالوصول وتضمين أدوات الحماية والاتصال
define('ALLOWED_ACCESS', true);
require_once 'form_protection.php';
require_once 'db_connect.php'; // الاتصال الآمن بـ PDO

// تعيين رأس الاستجابة ليكون JSON
header('Content-Type: application/json; charset=UTF-8');

// 2. التحقق من أن الطلب تم عبر طريقة POST حصراً
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموح بها (Method Not Allowed).']);
    exit;
}

// 3. التحقق من تسجيل الدخول وصلاحيات المسؤول (Admin Session Security)
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized Access: يرجى تسجيل الدخول بصلاحيات المسؤول لتعديل الإعدادات.']);
    exit;
}

// 4. التحقق من توكن حماية النماذج (CSRF Token) لمنع الهجمات العابرة للمواقع
$client_csrf_token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
verify_csrf_token($client_csrf_token);

// 5. استقبال المدخلات وتطبيق التنقية (Sanitization) والتحقق (Validation)
$raw_title = $_POST['site_title'] ?? '';

if (!validate_input($raw_title, 'not_empty')) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'حقل العنوان لا يمكن أن يكون فارغاً.']);
    exit;
}

$clean_title = sanitize_input($raw_title, 'string');

// 6. الحفظ الآمن باستخدام Prepared Statements عبر PDO مع حماية كاملة من SQL Injection
try {
    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = :val WHERE setting_key = 'site_title'");
    $stmt->execute(['val' => $clean_title]);
    
    echo json_encode(['success' => true, 'message' => 'تم حفظ الإعدادات وتنقيتها بنجاح.']);
} catch (\Exception $e) {
    error_log('Database Error in save_settings.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحفظ في قاعدة البيانات.']);
}
