<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Footer Partial (Production-Ready, Secure & Fully Multilingual)
 * يتضمن نموذج الاستشارة، موافقة الخصوصية (DSGVO)، روابط الفوتر، شريط الكوكيز المتوافق مع تسلسل اللغة، ومودالات الإدارة.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

// 1. تأكيد تهيئة المسار إذا لم يكن معرفاً
$path_prefix = $path_prefix ?? '/';

// 2. استخدام دالة جلب النصوص متعددة اللغات العامة إن وجدت، أو تعريف النسخة الآمنة الموحدة
if (!function_exists('get_multilang_text')) {
    function get_multilang_text(mixed $raw_data, string $current_lang, string $default = ''): string {
        $res = $default;
        if (is_string($raw_data)) {
            $trimmed = trim($raw_data);
            if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
                $arr = json_decode($trimmed, true);
                if (is_array($arr)) {
                    $res = $arr[$current_lang] ?? ($arr['de'] ?? ($arr['en'] ?? ($arr['ar'] ?? $default)));
                }
            } else {
                $res = $raw_data;
            }
        } elseif (is_array($raw_data)) {
            $res = $raw_data[$current_lang] ?? ($raw_data['de'] ?? ($raw_data['en'] ?? ($raw_data['ar'] ?? $default)));
        } elseif (is_numeric($raw_data)) {
            $res = (string)$raw_data;
        }

        if (is_array($res)) {
            $first = reset($res);
            return is_string($first) ? $first : $default;
        }
        return (string)$res;
    }
}

// 3. تحديد اللغة الحالية والاتجاه وضمان صحة الرمز
$supported_langs = ['de', 'en', 'ar'];
$current_lang = $current_lang ?? (function_exists('get_current_lang') ? get_current_lang() : ($_SESSION['site_lang'] ?? 'de'));
if (!in_array($current_lang, $supported_langs, true)) {
    $current_lang = 'de';
}
$current_lang_code = $current_lang;
$current_dir = ($current_lang === 'ar') ? 'rtl' : 'ltr';

// التأكد من تحميل ملف اللغة النشط إذا لم يكن معرفاً مسبقاً
if (!isset($lang) || !is_array($lang)) {
    $lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
    if (!file_exists($lang_file)) {
        $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
    }
    $lang = file_exists($lang_file) ? require $lang_file : [];
    if (!is_array($lang)) {
        $lang = [];
    }
}

// 4. مصفوفة ترجمة افتراضية كاملة للغات الثلاث
$fallback_translations = [
    'de' => [
        'consult_title'          => 'Kostenlose Beratung anfordern',
        'email_placeholder'      => 'Geben Sie Ihre E-Mail-Adresse ein...',
        'privacy_modal_title'    => 'Zustimmung zur Datenschutzerklärung',
        'privacy_modal_desc'     => 'Zum Schutz Ihrer Daten gemäß DSGVO stimmen Sie bitte der Speicherung Ihrer E-Mail-Adresse zu, damit unsere Experten Sie kontaktieren können. Weitere Details finden Sie in unserer',
        'privacy_link_text'      => 'Datenschutzerklärung',
        'impressum_link_text'    => 'Impressum',
        'privacy_checkbox'       => 'Ich stimme der Speicherung und Verarbeitung meiner Daten zu.',
        'cancel'                 => 'Abbrechen',
        'agree_and_send'         => 'Zustimmen & Senden',
        'alert_checkbox'         => 'Bitte stimmen Sie der Datenschutzerklärung zu, um fortzufahren.',
        'alert_success'          => 'Ihre Anfrage wurde erfolgreich gesendet. Vielen Dank!',
        'alert_error'            => 'Fehler: ',
        'alert_connection_error' => 'Verbindungsfehler zum Server.',
        'rights_reserved'        => 'Alle Rechte vorbehalten.',
        'cookie_text'            => 'Wir verwenden Cookies, um Ihre Erfahrung auf unserer Website zu verbessern und Zugriffe zu analysieren. Details finden Sie in unserer',
        'cookie_accept'          => 'Alle akzeptieren',
        'cookie_reject'          => 'Nur essenzielle',
        'col2_default'           => 'Schnelle Links',
        'col3_default'           => 'Kontaktieren Sie uns'
    ],
    'en' => [
        'consult_title'          => 'Get a Free Consultation',
        'email_placeholder'      => 'Enter your email address...',
        'privacy_modal_title'    => 'Privacy Policy Consent',
        'privacy_modal_desc'     => 'To protect your data under European GDPR regulations, please consent to saving your email address so our experts can contact you. Full details are available in our',
        'privacy_link_text'      => 'Privacy Policy',
        'impressum_link_text'    => 'Legal Notice (Impressum)',
        'privacy_checkbox'       => 'I agree to the storage and processing of my data.',
        'cancel'                 => 'Cancel',
        'agree_and_send'         => 'Agree & Send Request',
        'alert_checkbox'         => 'You must agree to the privacy policy terms to continue.',
        'alert_success'          => 'Your request has been sent successfully. Thank you!',
        'alert_error'            => 'Error: ',
        'alert_connection_error' => 'Server connection error.',
        'rights_reserved'        => 'All rights reserved.',
        'cookie_text'            => 'We use cookies to improve your browsing experience and analyze traffic. You can view the full details in our',
        'cookie_accept'          => 'Accept All',
        'cookie_reject'          => 'Reject Non-Essential',
        'col2_default'           => 'Quick Links',
        'col3_default'           => 'Contact Us'
    ],
    'ar' => [
        'consult_title'          => 'احصل على استشارة مجانية',
        'email_placeholder'      => 'ادخل إيميلك...',
        'privacy_modal_title'    => 'الموافقة على سياسة الخصوصية',
        'privacy_modal_desc'     => 'لحماية بياناتك وفقاً للمتطلبات الأوروبية (DSGVO)، يرجى الموافقة على حفظ بريدك الإلكتروني لتمكين خبرائنا من التواصل معك وتقديم الاستشارة المجانية. يمكنك الاطلاع على التفاصيل الكاملة في',
        'privacy_link_text'      => 'سياسة الخصوصية',
        'impressum_link_text'    => 'بيان قانوني (Impressum)',
        'privacy_checkbox'       => 'أوافق على شروط تخزين ومعالجة بياناتي.',
        'cancel'                 => 'إلغاء',
        'agree_and_send'         => 'موافق وإرسال الطلب',
        'alert_checkbox'         => 'يجب الموافقة على شروط سياسة الخصوصية للمتابعة.',
        'alert_success'          => 'تم إرسال طلبك بنجاح. شكراً لك!',
        'alert_error'            => 'خطأ: ',
        'alert_connection_error' => 'حدث خطأ في الاتصال بالخادم',
        'rights_reserved'        => 'جميع الحقوق محفوظة.',
        'cookie_text'            => 'نحن نستخدم ملفات تعريف الارتباط لتحسين تجربة الموقع وتحليل الزيارات. يمكنك الاطلاع على التفاصيل في',
        'cookie_accept'          => 'قبول الكل',
        'cookie_reject'          => 'رفض غير الضروري',
        'col2_default'           => 'روابط سريعة',
        'col3_default'           => 'تواصل معنا'
    ]
];

$fb = $fallback_translations[$current_lang];

// 5. استخراج بيانات الفوتر الموحدة من $data
$footerRaw = $data['footer'] ?? [];
if (is_string($footerRaw)) {
    $footerRaw = json_decode($footerRaw, true) ?? [];
}

$currentLangData = $footerRaw[$current_lang] ?? ($footerRaw['de'] ?? ($footerRaw['en'] ?? ($footerRaw['ar'] ?? [])));

$consultTitle = $currentLangData['consult_title'] ?? ($lang['consult_title'] ?? $fb['consult_title']);
$consultDesc  = $currentLangData['consult_desc'] ?? '';
$footerDesc   = $currentLangData['footer_desc'] ?? '';
$col2Title    = $currentLangData['col2_title'] ?? $fb['col2_default'];
$col3Title    = $currentLangData['col3_title'] ?? $fb['col3_default'];
$col3Items    = $currentLangData['col3_items'] ?? [];

$t = [
    'consult_title'          => $lang['consult_title'] ?? $consultTitle,
    'email_placeholder'      => $lang['email_placeholder'] ?? $fb['email_placeholder'],
    'privacy_modal_title'    => $lang['privacy_modal_title'] ?? $fb['privacy_modal_title'],
    'privacy_modal_desc'     => $lang['privacy_modal_desc'] ?? $fb['privacy_modal_desc'],
    'privacy_link_text'      => $lang['privacy_link_text'] ?? $fb['privacy_link_text'],
    'impressum_link_text'    => $lang['impressum_link_text'] ?? $fb['impressum_link_text'],
    'privacy_checkbox'       => $lang['privacy_checkbox'] ?? $fb['privacy_checkbox'],
    'cancel'                 => $lang['cancel'] ?? $fb['cancel'],
    'agree_and_send'         => $lang['agree_and_send'] ?? $fb['agree_and_send'],
    'alert_checkbox'         => $lang['alert_checkbox'] ?? $fb['alert_checkbox'],
    'alert_success'          => $lang['alert_success'] ?? $fb['alert_success'],
    'alert_error'            => $lang['alert_error'] ?? $fb['alert_error'],
    'alert_connection_error' => $lang['alert_connection_error'] ?? $fb['alert_connection_error'],
    'rights_reserved'        => $lang['rights_reserved'] ?? $fb['rights_reserved'],
    'cookie_text'            => $lang['cookie_text'] ?? $fb['cookie_text'],
    'cookie_accept'          => $lang['cookie_accept'] ?? $fb['cookie_accept'],
    'cookie_reject'          => $lang['cookie_reject'] ?? $fb['cookie_reject'],
];

// روابط الصفحات القانونية بتسلسل اللغة الموحد
$privacyUrl     = '/' . $current_lang_code . '/datenschutz';
$impressumUrl   = '/' . $current_lang_code . '/impressum';
$consultPostUrl = '/' . $current_lang_code . '/send-consult';

$modal_dir         = ($current_lang === 'ar') ? 'text-end' : 'text-start';
$modal_align_class = ($current_lang === 'ar') ? 'float-end ms-2' : 'float-start me-2';
$is_admin          = !empty($is_admin) && $is_admin === true;
$footerLogoPath    = $site_logo_path ?? ($data['site_logo_path'] ?? 'assets/img/logo.png');
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

<!-- footer start -->
<section class="consult-banner-section editable-wrapper" style="position: relative;">
    <?php if ($is_admin): ?>
        <button type="button" class="edit-pen" data-bs-toggle="modal" data-bs-target="#footerEditModal" title="<?php echo htmlspecialchars($lang['manage_footer'] ?? 'Edit Footer', ENT_QUOTES, 'UTF-8'); ?>">
            <i class="bi bi-pencil-fill"></i>
        </button>
    <?php endif; ?>
    
    <div class="container-fluid custom-container">
        <div class="consult-banner">
            <div class="consult-banner-inner">
                <div class="consult-banner-text">
                    <h4><?php echo htmlspecialchars((string)$consultTitle, ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p><?php echo htmlspecialchars((string)$consultDesc, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                
                <form id="consultForm" class="consult-banner-form" action="<?php echo htmlspecialchars($consultPostUrl, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
                    <input type="email" id="consultEmailInput" name="email" placeholder="<?php echo htmlspecialchars($t['email_placeholder'], ENT_QUOTES, 'UTF-8'); ?>" required />
                    <button type="button" id="openConsentModalBtn" aria-label="Send Consultation Request">
                        <img src="<?php echo htmlspecialchars(get_image_url('assets/img/home/send.svg.webp'), ENT_QUOTES, 'UTF-8'); ?>" alt="Send" width="35">
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- نافذة منبثقة (Popup Modal) للموافقة على سياسة الخصوصية (DSGVO) -->
<div class="modal fade custom-modal" id="privacyConsentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content <?php echo htmlspecialchars($modal_dir, ENT_QUOTES, 'UTF-8'); ?>" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-check text-primary"></i> <?php echo htmlspecialchars($t['privacy_modal_title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.6;">
                    <?php echo htmlspecialchars($t['privacy_modal_desc'], ENT_QUOTES, 'UTF-8'); ?> 
                    <a href="<?php echo htmlspecialchars($privacyUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-underline"><?php echo htmlspecialchars($t['privacy_link_text'], ENT_QUOTES, 'UTF-8'); ?></a>.
                </p>
                <div class="form-check bg-light p-3 rounded border">
                    <input class="form-check-input <?php echo htmlspecialchars($modal_align_class, ENT_QUOTES, 'UTF-8'); ?>" type="checkbox" id="modalPrivacyCheckbox">
                    <label class="form-check-label text-dark fw-semibold" for="modalPrivacyCheckbox" style="font-size: 0.9rem; cursor: pointer;">
                        <?php echo htmlspecialchars($t['privacy_checkbox'], ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo htmlspecialchars($t['cancel'], ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" id="confirmAndSendBtn" class="btn btn-primary" style="background-color: #0d6efd; border: none;"><?php echo htmlspecialchars($t['agree_and_send'], ENT_QUOTES, 'UTF-8'); ?></button>
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
    const consentModal = (consentModalElement && typeof bootstrap !== 'undefined') ? new bootstrap.Modal(consentModalElement) : null;
    const confirmBtn = document.getElementById('confirmAndSendBtn');
    const modalCheckbox = document.getElementById('modalPrivacyCheckbox');

    if (openBtn && consentModal) {
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
                alert(<?php echo json_encode($t['alert_checkbox'], JSON_UNESCAPED_UNICODE); ?>);
                return;
            }
            if (consentModal) {
                consentModal.hide();
            }

            const formData = new FormData();
            formData.append('email', emailInput.value);
            formData.append('privacy_consent', 'on');
            formData.append('lang', <?php echo json_encode($current_lang_code, JSON_UNESCAPED_UNICODE); ?>);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            if (csrfToken) {
                formData.append('csrf_token', csrfToken);
            }

            fetch(<?php echo json_encode($consultPostUrl, JSON_UNESCAPED_UNICODE); ?>, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(<?php echo json_encode($t['alert_success'], JSON_UNESCAPED_UNICODE); ?>);
                    consultForm.reset();
                } else {
                    alert(<?php echo json_encode($t['alert_error'], JSON_UNESCAPED_UNICODE); ?> + (data.message || ''));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert(<?php echo json_encode($t['alert_connection_error'], JSON_UNESCAPED_UNICODE); ?>);
            });
        });
    }
});
</script>

<footer class="footer-section pt-5">
    <div class="container-fluid custom-container">
        <div class="row gy-5">
            <div class="col-12 col-lg-5">
                <img src="<?php echo htmlspecialchars(get_image_url($footerLogoPath, 'assets/img/logo.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="BCS Logo" class="mb-4">
                <p class="footer-desc"><?php echo htmlspecialchars((string)$footerDesc, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- العمود الثاني (روابط سريعة من المنيو) -->
            <div class="col-12 col-md-6 col-lg-3">
                <h5><?php echo htmlspecialchars((string)$col2Title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <div class="quick-link">
                    <?php 
                    $menu_links = $menu_links ?? ($data['menu_links'] ?? (function_exists('get_setting') ? get_setting('menu_links', []) : []));
                    if (is_string($menu_links)) {
                        $menu_links = json_decode($menu_links, true) ?? [];
                    }
                    
                    if (is_array($menu_links)) {
                        foreach ($menu_links as $link) {
                            if (!is_array($link)) continue;
                            $raw_link_url = ltrim(is_array($link['url'] ?? null) ? (string)reset($link['url']) : (string)($link['url'] ?? ''), '/');
                            $link_url     = preg_replace('/^(ar|en|de)\//i', '', $raw_link_url);
                            
                            $link_title = $link[$current_lang]['title'] ?? ($link['de']['title'] ?? ($link['en']['title'] ?? ($link['ar']['title'] ?? ($link['title'] ?? ''))));
                            if (is_array($link_title)) {
                                $link_title = get_multilang_text($link_title, $current_lang);
                            }
                            ?>
                            <a href="/<?php echo htmlspecialchars($current_lang_code . '/' . $link_url, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars((string)$link_title, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                            <?php 
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- العمود الثالث (وسائل التواصل من col3_items) -->
            <div class="col-12 col-md-6 col-lg-4">
                <h5><?php echo htmlspecialchars((string)$col3Title, ENT_QUOTES, 'UTF-8'); ?></h5>
                <div class="contact-link">
                    <?php 
                    foreach((is_array($col3Items) ? $col3Items : []) as $link): 
                        if (!is_array($link)) continue;
                        $icon_img   = get_image_url($link['img'] ?? null);
                        $col3_title = (string)($link['title'] ?? '');
                        $col3_url   = trim((string)($link['url'] ?? '#'));
                        if (preg_match('/^(javascript|data|vbscript):/i', $col3_url)) {
                            $col3_url = '#';
                        }
                    ?>
                        <a href="<?php echo htmlspecialchars($col3_url, ENT_QUOTES, 'UTF-8'); ?>" class="d-flex align-items-center gap-2">
                            <?php if(!empty($icon_img)): ?>
                                <img src="<?php echo htmlspecialchars($icon_img, ENT_QUOTES, 'UTF-8'); ?>" style="width:20px;" alt="">
                            <?php endif; ?>
                            <?php echo htmlspecialchars($col3_title, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        <div class="foot-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pb-4">
            <p class="mb-0 text-center text-md-start">
                <span>&copy;</span> <?php echo date('Y'); ?> Beethoven City Services. <?php echo htmlspecialchars($t['rights_reserved'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <div class="legal-links d-flex gap-3 align-items-center">
                <a href="<?php echo htmlspecialchars($impressumUrl, ENT_QUOTES, 'UTF-8'); ?>" class="text-muted text-decoration-none small"><?php echo htmlspecialchars($t['impressum_link_text'], ENT_QUOTES, 'UTF-8'); ?></a>
                <span class="text-muted">|</span>
                <a href="<?php echo htmlspecialchars($privacyUrl, ENT_QUOTES, 'UTF-8'); ?>" class="text-muted text-decoration-none small"><?php echo htmlspecialchars($t['privacy_link_text'], ENT_QUOTES, 'UTF-8'); ?></a>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->

<!-- 🌟 تضمين مودالات لوحة التحكم المركزية لكل صفحات الموقع -->
<?php if ($is_admin): ?>
    <?php 
    $modals_file = __DIR__ . '/../admin/admin_header_modals.php';
    if (file_exists($modals_file)) {
        include_once $modals_file;
    }
    ?>
<?php endif; ?>

<?php 
if (isset($page_js) && is_array($page_js)) {
    echo "<!-- Dynamic JS Injector -->" . PHP_EOL;
    foreach ($page_js as $js_file) {
        $clean_js = ltrim((string)$js_file, '/');
        echo '<script src="' . htmlspecialchars('/' . $clean_js, ENT_QUOTES, 'UTF-8') . '"></script>' . PHP_EOL;
    }
}
?>

<!-- شريط الموافقة على الكوكيز (DSGVO Compliant & Language-Aware) -->
<div id="cookie-banner" class="cookie-banner" dir="<?php echo htmlspecialchars($current_dir, ENT_QUOTES, 'UTF-8'); ?>" style="display: none;">
    <div class="cookie-content">
        <p>
            <?php echo htmlspecialchars($t['cookie_text'], ENT_QUOTES, 'UTF-8'); ?> 
            <a href="<?php echo htmlspecialchars($privacyUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: underline;"><?php echo htmlspecialchars($t['privacy_link_text'], ENT_QUOTES, 'UTF-8'); ?></a>.
        </p>
        <div class="cookie-buttons">
            <button id="accept-cookies" class="btn-accept"><?php echo htmlspecialchars($t['cookie_accept'], ENT_QUOTES, 'UTF-8'); ?></button>
            <button id="reject-cookies" class="btn-reject"><?php echo htmlspecialchars($t['cookie_reject'], ENT_QUOTES, 'UTF-8'); ?></button>
        </div>
    </div>
</div>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>

<?php if (isset($custom_script)) { echo $custom_script; } ?>
</body>
</html>
