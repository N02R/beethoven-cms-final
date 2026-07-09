<?php
// استلام الرابط
$newUrl = $_POST['url'];
$page = $_POST['page'];
$section = $_POST['section'];
$field = $_POST['field'];

// 1. تحديث الرابط في قاعدة البيانات (دالة الـ CMS الخاصة بك)
\App\Core\CMS::update($page, $section, $field . '_link', $newUrl);

// 2. إذا تم رفع أيقونة جديدة
if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
    $uploadDir = '../assets/img/socialicons/';
    $fileName = $field . '.png'; // مثلا facebook.png
    move_uploaded_file($_FILES['icon']['tmp_name'], $uploadDir . $fileName);
}

echo "تم التحديث بنجاح";
?>
