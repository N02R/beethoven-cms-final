<?php
/**
 * Beethoven CMS - Super Admin Seeder Script
 * يقوم بإنشاء أول مشرف في النظام بشكل آمن ومشفر
 */

// استدعاء ملف الاتصال بقاعدة البيانات
require_once __DIR__ . '/api/db_connect.php';


// بيانات المشرف الأساسية (يمكنك تغييرها حسب رغبتك)
$fullName = "Nour Admin";
$email    = "admin@beethoven-cms.local";
$plainPassword = "SecurePassword123!"; // كلمة المرور السرية مؤقتاً

// 1. تشفير كلمة المرور باستخدام أقوى معايير PHP الحديثة (Bcrypt/Argon2)
$passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

try {
    // 2. استخدام Prepared Statements لمنع هجمات SQL Injection تماماً
    $stmt = $pdo->prepare("INSERT INTO admins (full_name, email, password_hash, role, is_active) VALUES (?, ?, ?, 'super_admin', 1)");
    
    $stmt->execute([$fullName, $email, $passwordHash]);
    
    echo "<p style='color: green;'>✔ تم إنشاء حساب المشرف بنجاح تام!</p>";
    echo "<p><strong>البريد الإلكتروني:</strong> " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p><strong>كلمة المرور المؤقتة:</strong> " . htmlspecialchars($plainPassword, ENT_QUOTES, 'UTF-8') . "</p>";
    echo "<p style='color: red;'>ملاحظة أمنية: احذف هذا الملف (create_admin.php) فوراً بعد التنفيذ لئلا يستغله شخص آخر!</p>";

} catch (\PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "<p style='color: orange;'>⚠ تنبيه: هذا البريد الإلكتروني مسجل مسبقاً في قاعدة البيانات.</p>";
    } else {
        echo "<p style='color: red;'>✖ حدث خطأ: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
}
?>
