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
                
                <!-- تصميمك القديم تماماً بدون أي تعديل خارجي -->
                <form id="consultForm" class="consult-banner-form" action="<?php echo $path_prefix; ?>send_consult.php" method="POST">
                    <input type="email" id="consultEmailInput" name="email" placeholder="ادخل إيميلك..." required />
                    <button type="button" id="openConsentModalBtn"><img src="<?php echo $path_prefix; ?>assets/img/home/send-2.svg" alt="إرسال"></button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- نافذة منبثقة (Popup Modal) للموافقة على سياسة الخصوصية (DSGVO) -->
<div class="modal fade custom-modal" id="privacyConsentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-end" dir="rtl">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check text-primary"></i> الموافقة على سياسة الخصوصية</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                    لحماية بياناتك وفقاً للمتطلبات الأوروبية (DSGVO)، يرجى الموافقة على حفظ بريدك الإلكتروني لتمكين خبرائنا من التواصل معك وتقديم الاستشارة المجانية. يمكنك الاطلاع على التفاصيل الكاملة في <a href="<?php echo $path_prefix; ?>privacy.php" target="_blank" class="text-primary text-decoration-underline">سياسة الخصوصية</a>.
                </p>
                <div class="form-check bg-light p-3 rounded border">
                    <input class="form-check-input float-end ms-2" type="checkbox" id="modalPrivacyCheckbox">
                    <label class="form-check-label text-dark fw-semibold" for="modalPrivacyCheckbox" style="font-size: 0.9rem; cursor: pointer;">
                        أوافق على شروط تخزين ومعالجة بياناتي.
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="confirmAndSendBtn" class="btn btn-primary" style="background-color: #0d6efd; border: none;">موافق وإرسال الطلب</button>
            </div>
        </div>
    </div>
</div>

<!-- سكريبت إدارة الـ Popup والتحقق الذكي -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const consultForm = document.getElementById('consultForm');
    const emailInput = document.getElementById('consultEmailInput');
    const openBtn = document.getElementById('openConsentModalBtn');
    
    // إنشاء كائن Bootstrap Modal
    const consentModalElement = document.getElementById('privacyConsentModal');
    const consentModal = new bootstrap.Modal(consentModalElement);
    const confirmBtn = document.getElementById('confirmAndSendBtn');
    const modalCheckbox = document.getElementById('modalPrivacyCheckbox');

    // عند الضغط على زر الإرسال في التصميم الأصلي
    openBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // التحقق أولاً إذا كان حقل الإيميل صالحاً وممتلئاً
        if (!emailInput.value || !emailInput.checkValidity()) {
            emailInput.reportValidity(); // إظهار رسالة المتصفح الافتراضية للإيميل
            return;
        }

        // إظهار النافذة المنبثقة العالمية
        modalCheckbox.checked = false; // إعادة ضبط الـ Checkbox داخل البوب أب
        consentModal.show();
    });

    // عند الضغط على "موافق وإرسال الطلب" من داخل الـ Popup
    confirmBtn.addEventListener('click', function() {
        if (!modalCheckbox.checked) {
            alert('يجب الموافقة على شروط سياسة الخصوصية للمتابعة.');
            return;
        }

        // إغلاق النافذة المنبثقة
        consentModal.hide();

        // تجهيز البيانات وإرسالها عبر AJAX
        const formData = new FormData();
        formData.append('email', emailInput.value);
        formData.append('privacy_consent', 'on');

        fetch('<?php echo $path_prefix; ?>send_consult.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال طلبك بنجاح. شكراً لك!');
                consultForm.reset();
            } else {
                alert('خطأ: ' + (data.message || 'حدث خطأ ما'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('حدث خطأ في الاتصال بالخادم');
        });
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
