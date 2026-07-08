<?php
session_start();
// ... (كود التحقق من تسجيل الدخول) ...

if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
    // التعديل هنا: تحديث قاعدة البيانات لتسجيل المسار الجديد
    // نستخدم REPLACE ليقوم بتحديث السجل إذا كان موجوداً
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=beethoven_db;charset=utf8", "root", "");
    $stmt = $pdo->prepare("REPLACE INTO site_content (page_key, section_key, field_key, content) VALUES ('global', 'header', 'logo_path', 'assets/img/logo.png')");
    $stmt->execute();
    
    echo "تم تحديث الشعار بنجاح!";
}
