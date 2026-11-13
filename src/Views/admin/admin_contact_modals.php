<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Contact Admin Modals (Gold Standard Security, Multilingual & AJAX)
 * متطابق بالكامل مع ContactSettingsService.php ونظام الحماية المركزي.
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$rawLang = (string)($_SESSION['site_lang'] ?? ($_GET['lang'] ?? 'de'));
$current_lang = in_array(strtolower(trim($rawLang)), $supported_langs, true)
    ? strtolower(trim($rawLang))
    : 'de';

$is_rtl      = ($current_lang === 'ar');
$modal_dir   = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!is_file($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$lang = is_file($lang_file) ? require $lang_file : [];
if (!is_array($lang)) {
    $lang = [];
}

$activeCsrfToken = \App\Core\Security::generateCsrfToken();

// نصوص الواجهة للمودلات باللغات الثلاث
$modalI18n = [
    'de' => [
        'save_success'   => 'Änderungen erfolgreich gespeichert. Seite wird neu geladen...',
        'save_error'     => 'Speichern fehlgeschlagen: ',
        'invalid_resp'   => 'Unerwartete Serverantwort.',
        'network_error'  => 'Netzwerkfehler. Bitte versuchen Sie es später erneut.',
        'success_title'  => 'Erfolgreich!',
        'error_title'    => 'Fehler!',
        'info_sec_title' => 'Titel des Kontaktbereichs',
        'info_sec_desc'  => 'Untertitel / Beschreibung',
        'addr_box'       => 'Adressbereich',
        'addr_label'     => 'Adresse',
        'addr_icon'      => 'Adress-Symbol',
        'email_box'      => 'E-Mail-Bereich',
        'email_label'    => 'E-Mail-Adresse',
        'email_icon'     => 'E-Mail-Symbol',
        'phone_box'      => 'Telefonbereich',
        'phone_label'    => 'Telefonnummer',
        'phone_icon'     => 'Telefon-Symbol',
        'wa_text_label'  => 'WhatsApp-Einladungstext',
        'wa_url_label'   => 'WhatsApp-Chat-URL',
        'wa_btn_label'   => 'Button-Text',
    ],
    'en' => [
        'save_success'   => 'Changes saved successfully. Reloading page...',
        'save_error'     => 'Failed to save changes: ',
        'invalid_resp'   => 'Unexpected server response.',
        'network_error'  => 'Network connection error. Please try again later.',
        'success_title'  => 'Success!',
        'error_title'    => 'Error!',
        'info_sec_title' => 'Contact Section Title',
        'info_sec_desc'  => 'Section Subtitle / Description',
        'addr_box'       => 'Address Card',
        'addr_label'     => 'Address Text',
        'addr_icon'      => 'Address Icon',
        'email_box'      => 'Email Card',
        'email_label'    => 'Email Address',
        'email_icon'     => 'Email Icon',
        'phone_box'      => 'Phone Card',
        'phone_label'    => 'Phone Number',
        'phone_icon'     => 'Phone Icon',
        'wa_text_label'  => 'WhatsApp Promotional Text',
        'wa_url_label'   => 'WhatsApp Chat URL',
        'wa_btn_label'   => 'WhatsApp Button Text',
    ],
    'ar' => [
        'save_success'   => 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...',
        'save_error'     => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'   => 'استجابة غير متوقعة من الخادم.',
        'network_error'  => 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.',
        'success_title'  => 'تم بنجاح!',
        'error_title'    => 'عذراً، حدث خطأ!',
        'info_sec_title' => 'عنوان قسم التواصل',
        'info_sec_desc'  => 'الوصف الفرعي لقسم التواصل',
        'addr_box'       => 'خانة العنوان',
        'addr_label'     => 'نص العنوان',
        'addr_icon'      => 'أيقونة العنوان',
        'email_box'      => 'خانة البريد الإلكتروني',
        'email_label'    => 'البريد الإلكتروني',
        'email_icon'     => 'أيقونة البريد',
        'phone_box'      => 'خانة الهاتف',
        'phone_label'    => 'رقم الهاتف',
        'phone_icon'     => 'أيقونة الهاتف',
        'wa_text_label'  => 'النص الترويجي للواتساب',
        'wa_url_label'   => 'رابط المحادثة (URL)',
        'wa_btn_label'   => 'نص زر الواتساب',
    ],
];
$mTxt = $modalI18n[$current_lang];

// دالة مساعدة لجلب البيانات الخام لكل قسم متعدد اللغات
$fetchRawMultilingualSetting = static function (string $key) use ($current_lang): array {
    $raw = get_setting($key, '{}');
    $decoded = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
    if (!is_array($decoded)) {
        return [];
    }
    if (isset($decoded[$current_lang]) && is_array($decoded[$current_lang])) {
        return $decoded[$current_lang];
    }
    if (isset($decoded['de']) && is_array($decoded['de'])) {
        return $decoded['de'];
    }
    if (isset($decoded['en']) && is_array($decoded['en'])) {
        return $decoded['en'];
    }
    if (isset($decoded['ar']) && is_array($decoded['ar'])) {
        return $decoded['ar'];
    }
    return $decoded;
};

$curContactHero     = $fetchRawMultilingualSetting('contact_hero');
$curContactInfo     = $fetchRawMultilingualSetting('contact_info');
$curWhatsappSection = $fetchRawMultilingualSetting('whatsapp_section');

$modalHeroImg = (string)($curContactHero['img'] ?? ($contact_hero_img ?? 'assets/img/contacthero.png'));

$modalInfoTitle   = (string)($curContactInfo['title'] ?? ($contact_info_title ?? ''));
$modalInfoDesc    = (string)($curContactInfo['desc'] ?? ($contact_info_desc ?? ''));
$modalAddress     = (string)($curContactInfo['address'] ?? ($contact_address ?? ''));
$modalEmail       = (string)($curContactInfo['email'] ?? ($contact_email ?? ''));
$modalPhone       = (string)($curContactInfo['phone'] ?? ($contact_phone ?? ''));
$modalAddressIcon = (string)($curContactInfo['address_icon'] ?? ($contact_address_icon ?? 'assets/img/Location.svg'));
$modalEmailIcon   = (string)($curContactInfo['email_icon'] ?? ($contact_email_icon ?? 'assets/img/Mail.svg'));
$modalPhoneIcon   = (string)($curContactInfo['phone_icon'] ?? ($contact_phone_icon ?? 'assets/img/Call.svg'));

$modalWaText   = (string)($curWhatsappSection['text'] ?? ($whatsapp_text ?? ''));
$modalWaUrl    = (string)($curWhatsappSection['url'] ?? ($whatsapp_url ?? '#'));
$modalWaBtnTxt = (string)($curWhatsappSection['btn_text'] ?? ($whatsapp_btn_txt ?? ''));
?>

<!-- 1. Contact Hero Modal -->
<div class="modal fade custom-modal" id="contactHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-image-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_contact_hero'] ?? 'Edit Contact Hero', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary d-block mb-3">
                                <?php echo htmlspecialchars($lang['current_image'] ?? 'Current Image', ENT_QUOTES, 'UTF-8'); ?>
                            </label>

                            <?php if ($modalHeroImg !== ''): ?>
                                <div class="mb-3 p-3 bg-light rounded-3 border text-center">
                                    <div class="p-1 bg-white rounded-3 border d-inline-flex align-items-center justify-content-center shadow-sm">
                                        <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Preview" class="rounded-2" style="max-height: 120px; object-fit: contain;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <label for="contact_hero_file_input" class="form-label fw-semibold small text-secondary mt-2">
                                <?php echo htmlspecialchars($lang['choose_file'] ?? 'Choose New Image', ENT_QUOTES, 'UTF-8'); ?>
                            </label>
                            <input type="file" id="contact_hero_file_input" class="form-control" name="contact_hero_img" accept="image/*">
                            <input type="hidden" name="old_contact_hero_img" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Contact Info Modal (معلومات والأيقونات الثلاث) -->
<div class="modal fade custom-modal" id="contactInfoModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_contact_info'] ?? 'Edit Contact Information', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contactInfoForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_contact_info">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['info_sec_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalInfoTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['info_sec_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][desc]" value="<?php echo htmlspecialchars($modalInfoDesc, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <!-- 1. العنوان -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-geo-alt text-danger <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['addr_box'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['addr_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][address]" value="<?php echo htmlspecialchars($modalAddress, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['addr_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($modalAddressIcon !== ''): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($modalAddressIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_address_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_address_icon]" value="<?php echo htmlspecialchars($modalAddressIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 2. البريد الإلكتروني -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-envelope text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['email_box'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['email_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="email" class="form-control" name="contact_info[<?php echo $current_lang; ?>][email]" value="<?php echo htmlspecialchars($modalEmail, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['email_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($modalEmailIcon !== ''): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($modalEmailIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_email_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_email_icon]" value="<?php echo htmlspecialchars($modalEmailIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- 3. الهاتف -->
                        <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold text-dark mb-3 <?php echo $modal_align; ?>"><i class="bi bi-telephone text-success <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['phone_box'], ENT_QUOTES, 'UTF-8'); ?></h6>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['phone_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="text" class="form-control" name="contact_info[<?php echo $current_lang; ?>][phone]" value="<?php echo htmlspecialchars($modalPhone, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['phone_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($modalPhoneIcon !== ''): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(get_image_url($modalPhoneIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="contact_phone_icon" accept="image/*">
                                    </div>
                                    <input type="hidden" name="contact_info[<?php echo $current_lang; ?>][old_phone_icon]" value="<?php echo htmlspecialchars($modalPhoneIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="contactInfoForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. WhatsApp Section Modal -->
<div class="modal fade custom-modal" id="whatsappSectionModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-whatsapp text-success <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_whatsapp_section'] ?? 'Edit WhatsApp Section', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="whatsappForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_whatsapp_section">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label for="whatsapp_text_input" class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['wa_text_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <textarea id="whatsapp_text_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][text]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalWaText, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label for="whatsapp_url_input" class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['wa_url_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" id="whatsapp_url_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][url]" value="<?php echo htmlspecialchars($modalWaUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://wa.me/...">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label for="whatsapp_btn_txt_input" class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['wa_btn_label'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" id="whatsapp_btn_txt_input" class="form-control" name="whatsapp_section[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($modalWaBtnTxt, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="whatsappForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const CONTACT_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const CONTACT_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function showContactNotification(message, type = 'success') {
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
        return;
    }

    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    const bgClass = type === 'danger' ? 'alert-danger' : 'alert-success';
    const icon    = type === 'danger' ? 'bi-x-circle-fill' : 'bi-check-circle-fill';
    const title   = type === 'danger' ? CONTACT_MODAL_TXT.error_title : CONTACT_MODAL_TXT.success_title;

    const alertDiv = document.createElement('div');
    alertDiv.id = 'customNotificationAlert';
    alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
    alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center gap-2">
            <i class="bi ${icon} fs-4"></i>
            <div>
                <strong>${title}</strong>
                <div class="small">${message}</div>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 4000);
}

document.addEventListener('DOMContentLoaded', function() {
    const contactFormIds = new Set(['contactHeroForm', 'contactInfoForm', 'whatsappForm']);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !contactFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || CONTACT_CSRF_TOKEN;

        formData.set('csrf_token', csrfToken);

        try {
            const response = await fetch('/admin/settings/save', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const rawText = await response.text();
            let data;
            try {
                data = JSON.parse(rawText);
            } catch (parseErr) {
                console.error('Raw Server Response:', rawText);
                showContactNotification(CONTACT_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showContactNotification(CONTACT_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                const errorMsg = data.message || data.error || '';
                showContactNotification(CONTACT_MODAL_TXT.save_error + errorMsg, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showContactNotification(CONTACT_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
