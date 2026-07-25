<?php
/**
 * Beethoven CMS - Secure Database Connection
 * Production-Ready PDO Configuration
 */

// بيانات الاتصال (في الشركات الكبرى، توضع غالباً في متغيرات بيئية Environment Variables / .env)
$host = '127.0.0.1';
$db   = 'beethoven_cms';
$user = 'root';      // في السيرفر الحقيقي، لا تستخدم root أبداً، بل مستخدم مخصص بصلاحيات محدودة
$pass = '';          // ضع كلمة المرور الخاصة بك إن وجدت
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// خيارات أمان وعمليات PDO المتقدمة
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // رمي استثناءات عند حدوث خطأ في الاستعلام
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // جلب البيانات على شكل مصفوفة ترابطية (Associative Array)
    PDO::ATTR_EMULATE_PREPARES   => false,                  // تعطيل محاكاة السترنج والاستفادة القصوى من Prepared Statements الحقيقية (حماية من SQL Injection)
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // في بيئة الإنتاج Production: لا تعرض أبداً رسالة الخطأ التقنية للمستخدم نهائياً لتفادي كشف بنية قاعدة البيانات
    // قم بتسجيل الخطأ في ملف سجلات السيرفر (Error Log) واعرض رسالة عامة لطيفة
    error_log("Database Connection Error: " . $e->getMessage());
    
    http_response_code(500);
    exit("عذراً، حدث خطأ في الاتصال بالنظام. يرجى المحاولة لاحقاً.");
}
