<?php
/**
 * Beethoven CMS - Unified Database Connection
 * ملف اتصال موحد لقاعدة البيانات لجميع واجهات النظام
 */

// منع الوصول المباشر للملف للأمان
if (!defined('ALLOWED_ACCESS')) {
    define('ALLOWED_ACCESS', true);
}

$host = '127.0.0.1'; // استخدام IP لتجنب أخطاء Socket في Termux
$port = '3306';
$db   = 'beethoven_cms';
$user = 'root';
$pass = '';          // ضعي كلمة المرور هنا إن وجدت
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // تسجيل الخطأ داخلياً وعدم كشف تفاصيل القاعدة للمستخدم النهائي
    error_log("Database Connection Error: " . $e->getMessage());
    
    http_response_code(500);
    exit("عذراً، حدث خطأ في الاتصال بقاعدة البيانات. يرجى المحاولة لاحقاً.");
}
