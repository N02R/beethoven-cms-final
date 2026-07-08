<?php
session_start();
if (!isset($_SESSION['is_admin'])) exit('غير مصرح');

if ($_FILES['logo']) {
    $uploadDir = '../assets/img/';
    $fileName = 'logo.png'; // الاسم ثابت ليتم التحديث في كل مكان تلقائياً
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
        echo "تم تحديث اللوجو بنجاح!";
    } else {
        echo "فشل الرفع.";
    }
}
