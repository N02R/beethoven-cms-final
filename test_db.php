<?php
/**
 * Beethoven CMS - Database Connection Test
 */

// استدعاء ملف الاتصال باستخدام المسار المطلق لضمان الدقة
require_once __DIR__ . '/admin/api/db_connect.php';

echo "<h3>اختبار الاتصال بقاعدة البيانات لـ Beethoven CMS</h3>";

try {
    // استعلام بسيط لاختبار جاهزية جدول المشرفين
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();

    echo "<p style='color: green;'>✔ تم الاتصال بقاعدة البيانات (beethoven_cms) بنجاح تام!</p>";
    echo "<p>الجداول الموجودة حالياً في القاعدة:</p>";
    echo "<ul>";
    foreach ($tables as $table) {
        // طباعة اسم الجدول الأول في المصفوفة
        $tableName = reset($table);
        echo "<li>" . htmlspecialchars($tableName, ENT_QUOTES, 'UTF-8') . "</li>";
    }
    echo "</ul>";

} catch (\Exception $e) {
    echo "<p style='color: red;'>✖ حدث خطأ أثناء الاتصال: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}
?>
