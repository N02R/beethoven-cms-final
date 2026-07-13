<?php
// includes/db_connect.php

$host = 'localhost';
$db_name = 'BCS-CMS';
$username = 'root'; // اسم المستخدم الافتراضي في XAMPP
$password = '';     // كلمة المرور الافتراضية تكون فارغة

try {
    // الاتصال باستخدام PDO لأنه الأكثر أماناً واحترافية
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // تفعيل وضع إظهار الأخطاء للمساعدة أثناء التطوير
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // تفعيل جلب البيانات على شكل مصفوفات ترابطية بشكل افتراضي
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // في حال فشل الاتصال، يطبع الخطأ ويتوقف
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
