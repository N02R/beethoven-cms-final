<?php
// تأكيد تهيئة المتغير إذا لم يكن معرفاً في الصفحات الرئيسية
if (!isset($path_prefix)) {
    $path_prefix = '';
}
?>

<!-- footer start -->
<section class="consult-banner-section" style="position: relative;">
    <?php if ($is_admin): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#footerEditModal"><i class="bi bi-pencil-fill"></i></button>
    <?php endif; ?>
    
    <div class="container-fluid custom-container">
        <div class="consult-banner">
            <div class="consult-banner-inner">
                <div class="consult-banner-text">
                    <h4><?php echo htmlspecialchars($data['consult_title'] ?? 'احصل على استشارة مجانية'); ?></h4>
                    <p><?php echo htmlspecialchars($data['consult_desc'] ?? 'نص تجريبي للاستشارة'); ?></p>
                </div>
                <form class="consult-banner-form" action="<?php echo $path_prefix; ?>send_consult.php" method="POST">
                    <input type="email" name="email" placeholder="ادخل إيميلك..." required />
                    <button type="submit"><img src="<?php echo $path_prefix; ?>assets/img/home/send-2.svg" alt="إرسال"></button>
                </form>
            </div>
        </div>
    </div>
</section>

<footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
        <div class="row gy-5">
            <!-- العمود 1: اللوجو والوصف -->
            <div class="col-12 col-lg-5">
                <img src="<?php echo $path_prefix . ($data['site_logo_path'] ?? 'assets/img/logo.png'); ?>" alt="BCS Logo" class="mb-4">
                <p class="footer-desc"><?php echo htmlspecialchars($data['footer_desc'] ?? ''); ?></p>
            </div>

            <!-- العمود 2: روابط سريعة (ديناميكي) -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5><?php echo htmlspecialchars($data['footer_col2_title'] ?? 'روابط سريعة'); ?></h5>
                <div class="quick-link">
                    <?php foreach(($data['footer_col2_links'] ?? []) as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="d-flex align-items-center gap-2">
                            <?php if(!empty($link['img'])): ?><img src="<?php echo $path_prefix . $link['img']; ?>" style="width:18px;"><?php endif; ?>
                            <?php echo htmlspecialchars($link['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- العمود 3: تواصل معنا (ديناميكي) -->
            <div class="col-12 col-md-6 col-lg-4">
                <h5><?php echo htmlspecialchars($data['footer_col3_title'] ?? 'تواصل معنا'); ?></h5>
                <div class="contact-link">
                    <?php foreach(($data['footer_col3_links'] ?? []) as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="d-flex align-items-center gap-2">
                            <?php if(!empty($link['img'])): ?><img src="<?php echo $path_prefix . $link['img']; ?>" style="width:20px;"><?php endif; ?>
                            <?php echo htmlspecialchars($link['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
            <p class="mb-0 text-center text-md-start">
                <span>&copy;</span> جميع الحقوق محفوظة | Beethoven City Services
            </p>
            <a href="#">سياسة الخصوصية وشروط الإستخدام</a>
        </div>
    </div>
</footer>


<?php 
// جلب السكربتات الإضافية
if (isset($page_js) && is_array($page_js)) {
    $cleaned_js_files = array_map('basename', $page_js);
    echo "<!-- Dynamic JS Injector -->";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $path_prefix; ?>assets/js/main.js?v=<?php echo time(); ?>"></script>

<?php if (isset($custom_script)) { echo $custom_script; } ?>

</body>
</html>
