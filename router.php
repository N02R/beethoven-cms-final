<?php
// استدعاء ملف الإعدادات أو الاتصال بقاعدة البيانات إن وجد
require_once 'includes/header.php';

// الحصول على الصفحة المطلوبة من الرابط (مثلاً: index.php?page=about)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// مصفوفة الصفحات المسموح بها (أمان عالي لمنع اختراق المسارات)
$allowed_pages = ['home', 'about', 'contact', 'education', 'job'];

if (in_array($page, $allowed_pages)) {
    include($page . '.php');
} else {
    include('404.php');
}

require_once 'includes/footer.php';
?>
