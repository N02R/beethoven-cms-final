<?php
// templates/home.php
// هنا سنقوم لاحقاً بجلب المحتوى من قاعدة البيانات قبل عرض الـ Header
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
    
    <?php include 'layout/head.php'; ?>
    <body></body>
<?php
include __DIR__ . '/layout/header.php';

// هنا سنستدعي الأقسام كـ Components
include __DIR__ . '/components/hero.php';
include __DIR__ . '/components/services.php';
include __DIR__ . '/components/choose.php';
include __DIR__ . '/components/reviews.php';
include __DIR__ . '/components/guide.php';
include __DIR__ . '/components/faq.php';

include __DIR__ . '/layout/footer.php';
?>
