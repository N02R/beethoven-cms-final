<?php
// تأكدي من أن هذا الملف موجود في المسار: /public/api/upload_logo.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    
    // 1. تحديد مسار المجلد الذي ستُحفظ فيه الصور (خارج مجلد الـ api)
    $uploadDir = __DIR__ . '/../assets/img/';
    
    // 2. التأكد من أن المجلد موجود، وإذا لم يكن، قم بإنشائه
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // 3. تحديد اسم ثابت للملف (مثلاً logo.png) أو اسم الملف الأصلي
    $fileName = 'logo.png'; 
    $uploadPath = $uploadDir . $fileName;

    // 4. تنفيذ عملية النقل والتحقق من وجود المتغير
    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
        echo "تم تحديث الشعار بنجاح!";
    } else {
        echo "خطأ: فشل رفع الملف. تأكدي من صلاحيات الكتابة في المجلد.";
    }
} else {
    echo "خطأ: لم يتم اختيار ملف أو الطلب ليس POST.";
}
?>
