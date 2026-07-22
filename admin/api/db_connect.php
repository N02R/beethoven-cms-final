<?php
/**
 * db_connect.php - طبقة الاتصال الآمنة بقاعدة البيانات باستخدام PDO
 * تضمن حماية تامة ضد SQL Injection عبر استخدام Prepared Statements حصرياً.
 */

if (!defined('ALLOWED_ACCESS')) {
    header("HTTP/1.1 403 Forbidden");
    exit('Access Denied');
}

// إعدادات الاتصال (يتم جلبها عادة من متغيرات البيئة Environment Variables على السيرفر الأوروبي)
$host     = '127.0.0.1';
$db_name  = 'bcs_commercial_cms';
$username = 'bcs_db_user';
$password = 'Your_Extremely_Secure_Database_Password_Here';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

// خيارات الأمان المتقدمة لـ PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // رمي استثناءات عند الأخطاء بدلاً من كشف مسارات النظام
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // جلب البيانات كـ Associative Array نظيفة
    PDO::ATTR_EMULATE_PREPARES   => false,                  // [مهم جداً للحماية]: إيقاف محاكاة الـ Prepared Statements وإجبار السيرفر على استخدام النظام الحقيقي لنوع البيانات
    PDO::ATTR_PERSISTENT         => false                   // إيقاف الاتصالات المستمرة لمنع استنزاف موارد السيرفر
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // في البيئة الحقيقية (Production)، لا نُظهر تفاصيل خطأ قاعدة البيانات للمستخدم أبداً
    error_log("Database Connection Error: " . $e->getMessage());
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'عذراً، حدث خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.']);
    exit;
}
