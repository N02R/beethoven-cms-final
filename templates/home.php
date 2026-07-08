<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <?php include __DIR__ . '/layout/head.php'; ?>
    <body>
        <?php 
        // استدعاء المكونات باستخدام المسار المطلق للمجلد الحالي
        include __DIR__ . '/layout/header.php';
        include __DIR__ . '/components/hero.php';
        include __DIR__ . '/components/services.php';
        include __DIR__ . '/layout/footer.php';
        ?>
    </body>
</html>
