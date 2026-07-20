<?php
// تأكيد تهيئة المتغير إذا لم يكن معرفاً في الصفحات الرئيسية
if (!isset($path_prefix)) {
    $path_prefix = '';
}
?>

<!--footer start -->

<section class="consult-banner-section" style="position: relative;">
    <?php if ($is_admin): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#footerEditModal" style="top: 10px; right: 10px;">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>
    
    <div class="container-fluid custom-container">
        <div class="consult-banner">
            <div class="consult-banner-inner">
                <div class="consult-banner-text">
                    <h4><?php echo htmlspecialchars($data['consult_title'] ?? 'احصل على استشارة مجانية'); ?></h4>
                    <p><?php echo htmlspecialchars($data['consult_desc'] ?? ''); ?></p>
                </div>
                
                <form id="consultForm" class="consult-banner-form" action="<?php echo $path_prefix; ?>send_consult.php" method="POST">
                    <div class="d-flex flex-column w-100">
                        <div class="d-flex align-items-center">
                            <input type="email" name="email" placeholder="ادخل إيميلك..." required />
                            <button type="submit"><img src="<?php echo $path_prefix; ?>assets/img/home/send-2.svg" alt="إرسال"></button>
                        </div>
                        
                        <!-- متطلب قانوني ألماني: خانة الموافقة وسياسة الخصوصية (DSGVO) -->
                        <div class="form-check mt-2 text-start" style="font-size: 0.85rem; color: #fff;">
                            <input class="form-check-input" type="checkbox" name="privacy_consent" id="privacyConsent" required>
                            <label class="form-check-label" for="privacyConsent">
                                أوافق على شروط تخزين ومعالجة بياناتي وفقاً لـ <a href="<?php echo $path_prefix; ?>privacy.php" target="_blank" style="color: #fff; text-decoration: underline;">سياسة الخصوصية (Datenschutzerklärung)</a>.
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- جافاسكريبت لضمان الإرسال الآمن عبر AJAX -->
<script>
document.getElementById('consultForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // التحقق البرمجي من تفعيل الشروط الألمانية
    const checkbox = document.getElementById('privacyConsent');
    if (!checkbox.checked) {
        alert('يجب الموافقة على سياسة الخصوصية أولاً وفقاً للقوانين الأوروبية.');
        return;
    }

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم استلام طلبك بنجاح. شكراً لك!');
            this.reset();
        } else {
            alert('خطأ: ' + (data.message || 'حدث خطأ ما'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('حدث خطأ في الاتصال بالخادم');
    });
});
</script>


<footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
        <div class="row gy-5">
            <!-- 1. اللوجو والوصف -->
            <div class="col-12 col-lg-5">
                <img src="<?php echo $path_prefix . ($data['site_logo_path'] ?? 'assets/img/logo.png'); ?>" alt="BCS Logo" class="mb-4">
                <p class="footer-desc"><?php echo htmlspecialchars($data['footer_desc'] ?? ''); ?></p>
            </div>

            <!-- 2. العمود الثاني (مربوط بالـ Menu) -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5><?php echo htmlspecialchars($data['footer_col2_title'] ?? 'روابط سريعة'); ?></h5>
                <div class="quick-link">
                    <?php foreach(($data['menu_links'] ?? []) as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 3. العمود الثالث (تواصل معنا - ديناميكي) -->
            <div class="col-12 col-md-6 col-lg-4">
                <h5><?php echo htmlspecialchars($data['footer_col3_title'] ?? 'تواصل معنا'); ?></h5>
                <div class="contact-link">
                    <?php foreach(($data['footer_col3_links'] ?? []) as $link): ?>
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="d-flex align-items-center gap-2">
                            <?php if(!empty($link['img'])): ?>
                                <img src="<?php echo $path_prefix . $link['img']; ?>" style="width:20px;">
                            <?php endif; ?>
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
<!-- footer end -->

<?php 
// 1. حقن ملفات الـ JS الديناميكية الخاصة بكل صفحة (مثل Swiper JS)
if (isset($page_js) && is_array($page_js)) {
    echo "<!-- Dynamic JS Injector -->" . PHP_EOL;
    foreach ($page_js as $js_file) {
        // تنظيف المسار لضمان عدم تكرار الشرطة المائلة /
        $clean_js = ltrim($js_file, '/');
        echo '<script src="' . $path_prefix . $clean_js . '?v=' . time() . '"></script>' . PHP_EOL;
    }
}
?>

<!-- المكتبات والملفات الأساسية للموقع -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $path_prefix; ?>assets/js/main.js?v=<?php echo time(); ?>"></script>

<!-- 2. تشغيل السكربت المخصص الممرّر من الصفحة (مثل تهيئة Swiper) -->
<?php if (isset($custom_script)) { echo $custom_script; } ?>
</body>
</html>
