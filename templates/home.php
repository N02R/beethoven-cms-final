<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <?php include __DIR__ . '/layout/head.php'; ?>
<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <main>
        <?php 
        // بما أنكِ صممتِ هذه المكونات مسبقاً، فقط استدعيها هنا
        include __DIR__ . '/components/hero.php'; 
        include __DIR__ . '/components/services.php';      // تأكدي من اسم ملفاتك
        include __DIR__ . '/components/choose.php';
        include __DIR__ . '/components/reviews.php';
        include __DIR__ . '/components/guide.php';
        include __DIR__ . '/components/faq.php';
        ?>
    </main>

    <?php include __DIR__ . '/layout/footer.php'; ?>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/all.min.js"></script>
  <script src="/assets/js/main.js"></script>
<?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
    <script src="/assets/js/editor.js"></script>
<?php endif; ?>

</body>

</html>
