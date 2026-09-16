<?php
// تأكيد تهيئة المتغير إذا لم يكن معرفاً في الصفحات الرئيسية
if (!isset($path_prefix)) {
    $path_prefix = '';
}

// دالة مساعدة محصنة جداً لاستخراج النصوص بأمان ومنع خطأ Array to String
if (!function_exists('get_multilang_text')) {
    function get_multilang_text($raw_data, $current_lang, $default = '') {
        $res = $default;
        if (is_string($raw_data) && (str_starts_with(trim($raw_data), '{') || str_starts_with(trim($raw_data), '['))) {
            $arr = json_decode($raw_data, true);
            if (is_array($arr)) {
                $res = $arr[$current_lang] ?? $arr['ar'] ?? $arr['de'] ?? $default;
            }
        } elseif (is_array($raw_data)) {
            $res = $raw_data[$current_lang] ?? $raw_data['ar'] ?? $raw_data['de'] ?? $default;
        } elseif (is_string($raw_data) || is_numeric($raw_data)) {
            $res = $raw_data;
        }

        // في حال كان الناتج لا يزال مصفوفة، نعيد أول قيمة نصية منها لتجنب الخطأ
        if (is_array($res)) {
            return is_string(reset($res)) ? reset($res) : $default;
        }
        return (string)$res;
    }
}

// تحديد اللغة الحالية (افتراضياً العربية إن لم تكن معرفة)
$current_lang = $current_lang ?? 'ar';

// ترجمات ثابتة لعناصر الفوتر ونافذة الخصوصية والكوكيز بناءً على اللغة
$footer_translations = [
    'ar' => [
        'consult_title' => 'احصل على استشارة مجانية',
        'email_placeholder' => 'ادخل إيميلك...',
        'privacy_modal_title' => 'الموافقة على سياسة الخصوصية',
        'privacy_modal_desc' => 'لحماية بياناتك وفقاً للمتطلبات الأوروبية (DSGVO)، يرجى الموافقة على حفظ بريدك الإلكتروني لتمكين خبرائنا من التواصل معك وتقديم الاستشارة المجانية. يمكنك الاطلاع على التفاصيل الكاملة في',
        'privacy_link_text' => 'سياسة الخصوصية',
        'privacy_checkbox' => 'أوافق على شروط تخزين ومعالجة بياناتي.',
        'cancel' => 'إلغاء',
        'agree_and_send' => 'موافق وإرسال الطلب',
        'alert_checkbox' => 'يجب الموافقة على شروط سياسة الخصوصية للمتابعة.',
        'alert_success' => 'تم إرسال طلبك بنجاح. شكراً لك!',
        'alert_error' => 'خطأ: ',
        'alert_connection_error' => 'حدث خطأ في الاتصال بالخادم',
        'rights_reserved' => 'Alle Rechte vorbehalten.',
        'cookie_text' => 'نحن نستخدم ملفات تعريف الارتباط لتحسين تجربة المتجر وتحليل الزيارات. يمكنك الاطلاع على التفاصيل في',
        'cookie_accept' => 'قبول الكل',
        'cookie_reject' => 'رفض غير الضروري'
    ],
    'en' => [
        'consult_title' => 'Get a Free Consultation',
        'email_placeholder' => 'Enter your email...',
        'privacy_modal_title' => 'Privacy Policy Consent',
        'privacy_modal_desc' => 'To protect your data in accordance with European requirements (GDPR), please agree to store your email so our experts can contact you and provide a free consultation. You can view full details in our',
        'privacy_link_text' => 'Privacy Policy',
        'privacy_checkbox' => 'I agree to the terms of storing and processing my data.',
        'cancel' => 'Cancel',
        'agree_and_send' => 'Agree and Send Request',
        'alert_checkbox' => 'You must agree to the privacy policy terms to proceed.',
        'alert_success' => 'Your request has been sent successfully. Thank you!',
        'alert_error' => 'Error: ',
        'alert_connection_error' => 'Server connection error occurred',
        'rights_reserved' => 'All rights reserved.',
        'cookie_text' => 'We use cookies to improve your experience and analyze traffic. You can check details in our',
        'cookie_accept' => 'Accept All',
        'cookie_reject' => 'Reject Non-Essential'
    ],
    'de' => [
        'consult_title' => 'Kostenlose Beratung erhalten',
        'email_placeholder' => 'E-Mail eingeben...',
        'privacy_modal_title' => 'Datenschutz-Einwilligung',
        'privacy_modal_desc' => 'Um Ihre Daten gemäß der DSGVO zu schützen, stimmen Sie bitte der Speicherung Ihrer E-Mail zu, damit unsere Experten Sie kontaktieren können. Weitere Details finden Sie in unserer',
        'privacy_link_text' => 'Datenschutzerklärung',
        'privacy_checkbox' => 'Ich stimme den Bedingungen zur Speicherung und Verarbeitung meiner Daten zu.',
        'cancel' => 'Abbrechen',
        'agree_and_send' => 'Zustimmen & Senden',
        'alert_checkbox' => 'Sie müssen den Datenschutzbestimmungen zustimmen, um fortzufahren.',
        'alert_success' => 'Ihre Anfrage wurde erfolgreich gesendet. Vielen Dank!',
        'alert_error' => 'Fehler: ',
        'alert_connection_error' => 'Serververbindungsfehler aufgetreten',
        'rights_reserved' => 'Alle Rechte vorbehalten.',
        'cookie_text' => 'Wir verwenden Cookies, um Ihre Erfahrung zu verbessern. Details finden Sie in unserer',
        'cookie_accept' => 'Alle akzeptieren',
        'cookie_reject' => 'Nur essenzielle'
    ]
];

$t = $footer_translations[$current_lang] ?? $footer_translations['ar'];
$modal_dir = ($current_lang === 'ar') ? 'text-end' : 'text-start';
$modal_align_class = ($current_lang === 'ar') ? 'float-end ms-2' : 'float-start me-2';
?>

<!-- تنسيق خاص لعكس اتجاه الأسهم تلقائياً في حالة اللغة العربية (RTL) -->
<style>
    <?php if ($current_lang === 'ar'): ?>
    .consult-banner-form button img,
    .contact-link a img {
        transform: scaleX(-1);
    }
    <?php endif; ?>
</style>

<!--footer start -->
<section class="consult-banner-section" style="position: relative;">
    <?php if (!empty($is_admin)): ?>
        <button class="edit-pen" data-bs-toggle="modal" data-bs-target="#footerEditModal" style="top: 10px; right: 10px;">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>
    
    <div class="container-fluid custom-container">
        <div class="consult-banner">
            <div class="consult-banner-inner">
                <div class="consult-banner-text">
                    <h4><?php echo htmlspecialchars(get_multilang_text($data['consult_title'] ?? '', $current_lang, $t['consult_title'])); ?></h4>
                    <p><?php echo htmlspecialchars(get_multilang_text($data['consult_desc'] ?? '', $current_lang, '')); ?></p>
                </div>
                
                <form id="consultForm" class="consult-banner-form" action="<?php echo $path_prefix; ?>send_consult.php" method="POST">
                    <input type="email" id="consultEmailInput" name="email" placeholder="<?php echo htmlspecialchars($t['email_placeholder']); ?>" required />
                    <button type="button" id="openConsentModalBtn">
                        <img src="<?php echo get_image_url('assets/img/home/send.svg.webp'); ?>" alt="إرسال" width="35">
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- نافذة منبثقة (Popup Modal) للموافقة على سياسة الخصوصية (DSGVO) -->
<div class="modal fade custom-modal" id="privacyConsentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content <?php echo $modal_dir; ?>" dir="<?php echo ($current_lang === 'ar') ? 'rtl' : 'ltr'; ?>">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check text-primary"></i> <?php echo htmlspecialchars($t['privacy_modal_title']); ?></h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                    <?php echo $t['privacy_modal_desc']; ?> <a href="<?php echo $path_prefix; ?>index.php?url=datenschutz" target="_blank" class="text-primary text-decoration-underline"><?php echo htmlspecialchars($t['privacy_link_text']); ?></a>.
                </p>
                <div class="form-check bg-light p-3 rounded border">
                    <input class="form-check-input <?php echo $modal_align_class; ?>" type="checkbox" id="modalPrivacyCheckbox">
                    <label class="form-check-label text-dark fw-semibold" for="modalPrivacyCheckbox" style="font-size: 0.9rem; cursor: pointer;">
                        <?php echo htmlspecialchars($t['privacy_checkbox']); ?>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo htmlspecialchars($t['cancel']); ?></button>
                <button type="button" id="confirmAndSendBtn" class="btn btn-primary" style="background-color: #0d6efd; border: none;"><?php echo htmlspecialchars($t['agree_and_send']); ?></button>
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
    
    const consentModalElement = document.getElementById('privacyConsentModal');
    const consentModal = new bootstrap.Modal(consentModalElement);
    const confirmBtn = document.getElementById('confirmAndSendBtn');
    const modalCheckbox = document.getElementById('modalPrivacyCheckbox');

    if (openBtn) {
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!emailInput.value || !emailInput.checkValidity()) {
                emailInput.reportValidity();
                return;
            }
            modalCheckbox.checked = false;
            consentModal.show();
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!modalCheckbox.checked) {
                alert('<?php echo $t['alert_checkbox']; ?>');
                return;
            }
            consentModal.hide();

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
                    alert('<?php echo $t['alert_success']; ?>');
                    consultForm.reset();
                } else {
                    alert('<?php echo $t['alert_error']; ?>' + (data.message || ''));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('<?php echo $t['alert_connection_error']; ?>');
            });
        });
    }
});
</script>

<footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
        <div class="row gy-5">
            <div class="col-12 col-lg-5">
                <img src="<?php echo get_image_url($data['site_logo_path'] ?? null, 'assets/img/logo.png'); ?>" alt="BCS Logo" class="mb-4">
                <p class="footer-desc"><?php echo htmlspecialchars(get_multilang_text($data['footer_desc'] ?? '', $current_lang, '')); ?></p>
            </div>

            <!-- قسم الروابط السريعة (مجلوبة مباشرة من منيو الهيدر) -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5><?php echo htmlspecialchars(get_multilang_text($data['footer_col2_title'] ?? '', $current_lang, ($current_lang === 'ar' ? 'روابط سريعة' : ($current_lang === 'de' ? 'Schnelle Links' : 'Quick Links')))); ?></h5>
                <div class="quick-link">
                    <?php 
                    $menu_links = $menu_links ?? ($data['menu_links'] ?? get_setting('menu_links', []));
                    if (is_string($menu_links)) {
                        $menu_links = json_decode($menu_links, true) ?? [];
                    }
                    
                    if (is_array($menu_links)) {
                        foreach ($menu_links as $link) {
                            $link_url = ltrim(is_array($link['url'] ?? null) ? reset($link['url']) : ($link['url'] ?? ''), '/');
                            
                            $link_title = $link[$current_lang]['title'] ?? $link['de']['title'] ?? $link['ar']['title'] ?? ($link['title'] ?? '');
                            if (is_array($link_title)) {
                                $link_title = get_multilang_text($link_title, $current_lang);
                            }
                            ?>
                            <a href="/<?php echo $current_lang_code . '/' . $link_url; ?>">
                                <?php echo htmlspecialchars((string)$link_title); ?>
                            </a>
                            <?php 
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <h5><?php echo htmlspecialchars(get_multilang_text($data['footer_col3_title'] ?? '', $current_lang, 'תواصل معنا')); ?></h5>
                <div class="contact-link">
                    <?php 
                    $footer_col3_links = $data['footer_col3_links'] ?? [];
                    if (is_string($footer_col3_links)) {
                        $footer_col3_links = json_decode($footer_col3_links, true) ?? [];
                    }
                    foreach((is_array($footer_col3_links) ? $footer_col3_links : []) as $link): 
                        $icon_img = get_image_url(is_array($link['img'] ?? null) ? reset($link['img']) : ($link['img'] ?? null));
                        $col3_title = get_multilang_text($link['title'] ?? '', $current_lang);
                        $col3_url = is_array($link['url'] ?? null) ? reset($link['url']) : ($link['url'] ?? '#');
                    ?>
                        <a href="<?php echo htmlspecialchars((string)$col3_url); ?>" class="d-flex align-items-center gap-2">
                            <?php if(!empty($icon_img)): ?>
                                <img src="<?php echo htmlspecialchars($icon_img); ?>" style="width:20px;" alt="">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($col3_title); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
            <p class="mb-0 text-center text-md-start">
                <span>&copy;</span> <?php echo date('Y'); ?> Beethoven City Services. <?php echo htmlspecialchars($t['rights_reserved']); ?>
            </p>
            <div class="legal-links d-flex gap-3 align-items-center">
                <a href="<?php echo $path_prefix; ?>index.php?url=impressum" class="text-muted text-decoration-none small">Impressum</a>
                <span class="text-muted">|</span>
                <a href="<?php echo $path_prefix; ?>index.php?url=datenschutz" class="text-muted text-decoration-none small">Datenschutzerklärung</a>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

<?php 
// استدعاء ملفات مودالات الإدارة بشكل آمن من داخل الفوتر
if (!empty($is_admin)) {
    // 1. استدعاء ملف المودالات الرئيسي (الهيدر والأقسام العامة)
    $admin_modals_file = __DIR__ . '/../admin/admin_header_modals.php';
    if (file_exists($admin_modals_file)) { 
        include_once $admin_modals_file; 
    } 

    // 2. استدعاء ملف مودالات إضافية (مثل التعليم أو غيره إن وجد)
    $edu_modals_file = __DIR__ . '/../admin/admin_edu_modals.php';
    if (file_exists($edu_modals_file)) { 
        include_once $edu_modals_file; 
    }
}
?>

<?php 
if (isset($page_js) && is_array($page_js)) {
    echo "<!-- Dynamic JS Injector -->" . PHP_EOL;
    foreach ($page_js as $js_file) {
        $clean_js = ltrim($js_file, '/');
        echo '<script src="' . $path_prefix . $clean_js . '"></script>' . PHP_EOL;
    }
}
?>
<!-- شريط الموافقة على الكوكيز -->
<div id="cookie-banner" class="cookie-banner" style="display: none;">
    <div class="cookie-content">
        <p>
            <?php echo $t['cookie_text']; ?> <a href="<?php echo $path_prefix; ?>index.php?url=datenschutz" target="_blank" style="color: #fff; text-decoration: underline;"><?php echo htmlspecialchars($t['privacy_link_text']); ?></a>.
        </p>
        <div class="cookie-buttons">
            <button id="accept-cookies" class="btn-accept"><?php echo htmlspecialchars($t['cookie_accept']); ?></button>
            <button id="reject-cookies" class="btn-reject"><?php echo htmlspecialchars($t['cookie_reject']); ?></button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $path_prefix; ?>assets/js/main.js"></script>

<?php if (isset($custom_script)) { echo $custom_script; } ?>
</body>
</html>
