<?php
declare(strict_types=1);

/**
 * Beethoven CMS - About Page Admin Modals (Gold Standard Security & Multilingual)
 * متطابق بالكامل مع AboutSettingsService.php ونظام الحماية المركزي.
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
        'warning_title'  => 'Hinweis',
        'service_title'  => 'Dienstleistungstitel',
        'service_url'    => 'Dienstleistungs-URL',
        'image_icon'     => 'Bild / Symbol',
        'delete_service' => 'Dienstleistung löschen',
        'member_name'    => 'Vollständiger Name',
        'member_role'    => 'Position / Rolle',
        'member_image'   => 'Mitgliedsfoto',
        'delete_member'  => 'Mitglied löschen',
        'count_number'   => 'Zahl / Wert',
        'count_title'    => 'Titel / Beschreibung',
        'count_image'    => 'Symbol / Bild',
        'delete_count'   => 'Zähler löschen',
        'partner_image'  => 'Partner-Logo / Bild',
        'delete_partner' => 'Partner löschen',
    ],
    'en' => [
        'save_success'   => 'Changes saved successfully. Reloading page...',
        'save_error'     => 'Failed to save changes: ',
        'invalid_resp'   => 'Unexpected server response.',
        'network_error'  => 'Network connection error. Please try again later.',
        'success_title'  => 'Success!',
        'error_title'    => 'Error!',
        'warning_title'  => 'Notice',
        'service_title'  => 'Service Title',
        'service_url'    => 'Service URL',
        'image_icon'     => 'Image / Icon',
        'delete_service' => 'Delete Service',
        'member_name'    => 'Full Name',
        'member_role'    => 'Job Title / Role',
        'member_image'   => 'Member Photo',
        'delete_member'  => 'Delete Member',
        'count_number'   => 'Number / Value',
        'count_title'    => 'Counter Title / Label',
        'count_image'    => 'Counter Icon / Image',
        'delete_count'   => 'Delete Counter',
        'partner_image'  => 'Partner Logo / Image',
        'delete_partner' => 'Delete Partner',
    ],
    'ar' => [
        'save_success'   => 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...',
        'save_error'     => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'   => 'استجابة غير متوقعة من الخادم.',
        'network_error'  => 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.',
        'success_title'  => 'تم بنجاح!',
        'error_title'    => 'عذراً، حدث خطأ!',
        'warning_title'  => 'تنبيه هام',
        'service_title'  => 'عنوان الخدمة',
        'service_url'    => 'رابط الخدمة',
        'image_icon'     => 'الصورة / الأيقونة',
        'delete_service' => 'حذف الخدمة',
        'member_name'    => 'الاسم الكامل',
        'member_role'    => 'المسمى الوظيفي',
        'member_image'   => 'صورة العضو',
        'delete_member'  => 'حذف العضو',
        'count_number'   => 'الرقم / القيمة',
        'count_title'    => 'عنوان العداد / الوصف',
        'count_image'    => 'أيقونة / صورة العداد',
        'delete_count'   => 'حذف العداد',
        'partner_image'  => 'شعار / صورة الشريك',
        'delete_partner' => 'حذف الشريك',
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
?>

<!-- 1. About Section Modal (قسم من نحن الرئيسي) -->
<div class="modal fade custom-modal" id="aboutEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_about_section'] ?? 'Edit About Section', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $curLangAbout      = $fetchRawMultilingualSetting('about_section');
                $modalAbTitle      = $curLangAbout['title'] ?? ($ab['title'] ?? '');
                $modalAbDesc       = $curLangAbout['desc'] ?? ($ab['desc'] ?? '');
                $modalAbBtnText    = $curLangAbout['btn_text'] ?? ($ab['btn_text'] ?? '');
                $modalAbBtnUrl     = $curLangAbout['btn_url'] ?? ($ab['btn_url'] ?? '#');
                $modalMainImg      = $curLangAbout['main_img'] ?? ($ab['main_img'] ?? '');
                $modalSubImg       = $curLangAbout['sub_img'] ?? ($ab['sub_img'] ?? '');
                $modalVisionTitle  = $curLangAbout['vision_title'] ?? ($ab['vision_title'] ?? '');
                $modalVisionDesc   = $curLangAbout['vision_desc'] ?? ($ab['vision_desc'] ?? '');
                $modalVisionIcon   = $curLangAbout['vision_icon'] ?? ($ab['vision_icon'] ?? '');
                $modalMessageTitle = $curLangAbout['message_title'] ?? ($ab['message_title'] ?? '');
                $modalMessageDesc  = $curLangAbout['message_desc'] ?? ($ab['message_desc'] ?? '');
                $modalMessageIcon  = $curLangAbout['message_icon'] ?? ($ab['message_icon'] ?? '');
                ?>

                <form id="aboutSectionForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_section">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="about_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['title'] ?? 'Title', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$modalAbTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['description'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="about[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalAbDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['hero_btn_text'] ?? ($lang['read_more'] ?? 'Button Text'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars((string)$modalAbBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['url'] ?? 'Button URL', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars((string)$modalAbBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['main_image'] ?? 'Main Image', ENT_QUOTES, 'UTF-8'); ?></label>
                                <?php if (!empty($modalMainImg)): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(get_image_url((string)$modalMainImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Main Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_main_img" accept="image/*">
                                <input type="hidden" name="about[<?php echo $current_lang; ?>][old_main_img]" value="<?php echo htmlspecialchars((string)$modalMainImg, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['sub_image'] ?? 'Sub Image', ENT_QUOTES, 'UTF-8'); ?></label>
                                <?php if (!empty($modalSubImg)): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <img src="<?php echo htmlspecialchars(get_image_url((string)$modalSubImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Sub Preview" class="img-thumbnail rounded-3 border-0 bg-transparent" style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" name="about_sub_img" accept="image/*">
                                <input type="hidden" name="about[<?php echo $current_lang; ?>][old_sub_img]" value="<?php echo htmlspecialchars((string)$modalSubImg, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12"><hr class="my-3 text-muted opacity-25"></div>

                            <!-- رؤية الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-eye"></i> <?php echo htmlspecialchars($lang['company_vision'] ?? 'Vision', ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Title', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][vision_title]" value="<?php echo htmlspecialchars((string)$modalVisionTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'Icon / Image', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($modalVisionIcon)): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url((string)$modalVisionIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="Vision Icon" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_vision_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="about[<?php echo $current_lang; ?>][old_vision_icon]" value="<?php echo htmlspecialchars((string)$modalVisionIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control" name="about[<?php echo $current_lang; ?>][vision_desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalVisionDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- رسالة الشركة -->
                            <div class="col-12 p-4 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary fw-bold mb-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-chat-square-text"></i> <?php echo htmlspecialchars($lang['company_message'] ?? 'Mission', ENT_QUOTES, 'UTF-8'); ?>
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Title', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="about[<?php echo $current_lang; ?>][message_title]" value="<?php echo htmlspecialchars((string)$modalMessageTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['icon_image'] ?? 'Icon / Image', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($modalMessageIcon)): ?>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 45px; height: 38px; border-color: #e2e8f0 !important;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url((string)$modalMessageIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="Message Icon" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control flex-grow-1" name="about_message_icon" accept="image/*">
                                        </div>
                                        <input type="hidden" name="about[<?php echo $current_lang; ?>][old_message_icon]" value="<?php echo htmlspecialchars((string)$modalMessageIcon, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control" name="about[<?php echo $current_lang; ?>][message_desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalMessageDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="aboutSectionForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. About Services Edit Modal -->
<div class="modal fade custom-modal" id="aboutServicesEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['edit_about_services'] ?? 'Edit About Services', ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <?php 
                $curLangAS = $fetchRawMultilingualSetting('about_services');
                $asTitle   = $curLangAS['section_title'] ?? ($curLangAS['title'] ?? '');
                $asDesc    = $curLangAS['section_subtitle'] ?? ($curLangAS['desc'] ?? '');
                $asItems   = is_array($curLangAS['items'] ?? null) ? $curLangAS['items'] : [];
                ?>

                <form id="aboutServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['services_section_title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="about_services[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars((string)$asTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['services_section_desc'] ?? 'Section Subtitle', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="about_services[<?php echo $current_lang; ?>][section_subtitle]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$asDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['added_services'] ?? 'Services', ENT_QUOTES, 'UTF-8'); ?></h6>
                    
                    <div id="aboutServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($asItems as $index => $item): 
                            if (!is_array($item)) continue;
                            $itemTitle = (string)($item['title'] ?? '');
                            $itemUrl   = (string)($item['url'] ?? '');
                            $itemImg   = (string)($item['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm service-item-row" id="about_service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['service_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control about-service-title" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($itemTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['service_url'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control about-service-url" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($itemUrl, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['image_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control about-service-file" name="about_services_img_<?php echo $index; ?>" accept="image/*">
                                            <?php if (!empty($itemImg)): ?>
                                                <img src="<?php echo htmlspecialchars(get_image_url($itemImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Service Preview" class="rounded border" style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <input type="hidden" class="about-service-old-img" name="about_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($itemImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('about_service_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_service'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addAboutServiceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_service'] ?? 'Add Service', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="aboutServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Team Edit Modal -->
<div class="modal fade custom-modal" id="teamEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-people text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_team'] ?? 'Manage Team', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <?php 
                $curLangTeam    = $fetchRawMultilingualSetting('team');
                $modalTeamTitle = $curLangTeam['title'] ?? ($data['team_title'] ?? '');
                $modalTeamDesc  = $curLangTeam['desc'] ?? ($data['team_desc'] ?? '');
                $modalTeamItems = is_array($curLangTeam['items'] ?? null) ? $curLangTeam['items'] : [];
                ?>

                <form id="teamForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_team">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="team_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['team_section_title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="team[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$modalTeamTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars($lang['team_section_desc'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="team[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars((string)$modalTeamDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['manage_team'] ?? 'Team Members', ENT_QUOTES, 'UTF-8'); ?></h6>

                    <div id="teamRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalTeamItems as $index => $member): 
                            if (!is_array($member)) continue;
                            $mName = (string)($member['name'] ?? '');
                            $mRole = (string)($member['role'] ?? '');
                            $mImg  = (string)($member['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm team-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="team_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['member_name'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control team-name" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($mName, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['member_role'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control team-role" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][role]" value="<?php echo htmlspecialchars($mRole, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['member_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($mImg)): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($mImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Member Image" class="rounded-2 w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control team-file w-100" name="team_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="team-old-img" name="team[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($mImg, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('team_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_member'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addTeamRowBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_member'] ?? 'Add Member', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="teamForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Counts Edit Modal -->
<div class="modal fade custom-modal" id="countsEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-bar-chart text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_counts'] ?? 'Manage Statistics', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <?php 
                $curLangCounts = $fetchRawMultilingualSetting('about_counts');
                $countsItems   = is_array($curLangCounts['items'] ?? null) ? $curLangCounts['items'] : [];
                ?>

                <form id="countsForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_about_counts">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['manage_counts'] ?? 'Counters', ENT_QUOTES, 'UTF-8'); ?></h6>

                    <div id="countsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($countsItems as $index => $c): 
                            if (!is_array($c)) continue;
                            $cNum   = (string)($c['number'] ?? '');
                            $cTitle = (string)($c['title'] ?? '');
                            $cImg   = (string)($c['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm count-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="count_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['count_number'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control count-number" name="about_counts[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][number]" value="<?php echo htmlspecialchars($cNum, ENT_QUOTES, 'UTF-8'); ?>" placeholder="+500">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['count_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control count-title" name="about_counts[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($cTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['count_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($cImg)): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($cImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Count Icon" class="rounded-2 w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control count-file w-100" name="count_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="count-old-img" name="about_counts[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($cImg, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('count_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_count'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCountRowBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_count'] ?? 'Add Counter', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="countsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Partners Edit Modal -->
<div class="modal fade custom-modal" id="partnersEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-briefcase text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_partners'] ?? 'Manage Partners', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <?php 
                $curLangPartners = $fetchRawMultilingualSetting('partners');
                $partnersTitle   = $curLangPartners['title'] ?? '';
                $partnersItems   = is_array($curLangPartners['items'] ?? null) ? $curLangPartners['items'] : [];
                ?>

                <form id="partnersForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_partners">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="col-12 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <input type="text" class="form-control" name="partners[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars((string)$partnersTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['manage_partners'] ?? 'Partner Logos', ENT_QUOTES, 'UTF-8'); ?></h6>

                    <div id="partnersRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($partnersItems as $index => $p): 
                            if (!is_array($p)) continue;
                            $pImg = (string)($p['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm partner-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="partner_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-11">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($mTxt['partner_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($pImg)): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 40px; height: 40px;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($pImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Partner Logo" class="rounded-2 w-100 h-100" style="object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control partner-file w-100" name="partner_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>
                                    <input type="hidden" class="partner-old-img" name="partners[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][img]" value="<?php echo htmlspecialchars($pImg, ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('partner_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_partner'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addPartnerRowBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['add_new_partner'] ?? 'Add Partner', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="partnersForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const ABOUT_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const ABOUT_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const ABOUT_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) {
        el.remove();
    }
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = ABOUT_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = ABOUT_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = ABOUT_MODAL_TXT.warning_title;
    }

    const alertDiv = document.createElement('div');
    alertDiv.id = 'customNotificationAlert';
    alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
    alertDiv.style.cssText = 'top: 20px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 320px; border-radius: 12px; border: none;';
    
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

function addAboutServiceRow() {
    const container = document.getElementById('aboutServicesContainer');
    if (!container) return;
    const itemCount = Date.now();

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm service-item-row mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'about_service_row_' + itemCount;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.service_title}</label>
                <input type="text" class="form-control about-service-title" name="about_services[${ABOUT_ACTIVE_LANG}][items][0][title]" value="">
            </div>
            <div class="col-md-6">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.service_url}</label>
                <input type="text" class="form-control about-service-url" name="about_services[${ABOUT_ACTIVE_LANG}][items][0][url]" value="">
            </div>
            <div class="col-md-11">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.image_icon}</label>
                <input type="file" class="form-control about-service-file w-100" name="about_services_img_0" accept="image/*">
            </div>
            <input type="hidden" class="about-service-old-img" name="about_services[${ABOUT_ACTIVE_LANG}][items][0][img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ABOUT_MODAL_TXT.delete_service}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addTeamRow() {
    const container = document.getElementById('teamRowsContainer');
    if (!container) return;
    const teamCount = Date.now();

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm team-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'team_row_' + teamCount;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.member_name}</label>
                <input type="text" class="form-control team-name" name="team[${ABOUT_ACTIVE_LANG}][items][0][name]" value="">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.member_role}</label>
                <input type="text" class="form-control team-role" name="team[${ABOUT_ACTIVE_LANG}][items][0][role]" value="">
            </div>
            <div class="col-md-5">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.member_image}</label>
                <input type="file" class="form-control team-file w-100" name="team_img_0" accept="image/*">
            </div>
            <input type="hidden" class="team-old-img" name="team[${ABOUT_ACTIVE_LANG}][items][0][img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ABOUT_MODAL_TXT.delete_member}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addCountRow() {
    const container = document.getElementById('countsRowsContainer');
    if (!container) return;
    const countsCount = Date.now();

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm count-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'count_row_' + countsCount;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.count_number}</label>
                <input type="text" class="form-control count-number" name="about_counts[${ABOUT_ACTIVE_LANG}][items][0][number]" value="" placeholder="+500">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.count_title}</label>
                <input type="text" class="form-control count-title" name="about_counts[${ABOUT_ACTIVE_LANG}][items][0][title]" value="">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.count_image}</label>
                <input type="file" class="form-control count-file w-100" name="count_img_0" accept="image/*">
            </div>
            <input type="hidden" class="count-old-img" name="about_counts[${ABOUT_ACTIVE_LANG}][items][0][img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ABOUT_MODAL_TXT.delete_count}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addPartnerRow() {
    const container = document.getElementById('partnersRowsContainer');
    if (!container) return;
    const partnerCount = Date.now();

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm partner-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'partner_row_' + partnerCount;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-11">
                <label class="small fw-bold mb-1 text-secondary">${ABOUT_MODAL_TXT.partner_image}</label>
                <input type="file" class="form-control partner-file w-100" name="partner_img_0" accept="image/*">
            </div>
            <input type="hidden" class="partner-old-img" name="partners[${ABOUT_ACTIVE_LANG}][items][0][img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ABOUT_MODAL_TXT.delete_partner}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addAboutServiceBtn')?.addEventListener('click', addAboutServiceRow);
    document.getElementById('addTeamRowBtn')?.addEventListener('click', addTeamRow);
    document.getElementById('addCountRowBtn')?.addEventListener('click', addCountRow);
    document.getElementById('addPartnerRowBtn')?.addEventListener('click', addPartnerRow);

    const aboutFormIds = new Set(['aboutSectionForm', 'aboutServicesForm', 'teamForm', 'countsForm', 'partnersForm']);

    // استخدام مرحلة الالتقاط (true) لضمان تنفيذ إعادة الترقيم ومنع أي مستمع مكرر في footer.php
    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !aboutFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || ABOUT_ACTIVE_LANG;

        // 1. إعادة ترقيم صفوف خدمات صفحة من نحن
        form.querySelectorAll('.service-item-row').forEach((row, index) => {
            const titleInput  = row.querySelector('.about-service-title');
            const urlInput    = row.querySelector('.about-service-url');
            const fileInput   = row.querySelector('.about-service-file');
            const oldImgInput = row.querySelector('.about-service-old-img');

            if (titleInput)  titleInput.name  = `about_services[${targetLang}][items][${index}][title]`;
            if (urlInput)    urlInput.name    = `about_services[${targetLang}][items][${index}][url]`;
            if (fileInput)   fileInput.name   = `about_services_img_${index}`;
            if (oldImgInput) oldImgInput.name = `about_services[${targetLang}][items][${index}][img]`;
        });

        // 2. إعادة ترقيم صفوف فريق العمل
        form.querySelectorAll('.team-row-item').forEach((row, index) => {
            const nameInput   = row.querySelector('.team-name');
            const roleInput   = row.querySelector('.team-role');
            const fileInput   = row.querySelector('.team-file');
            const oldImgInput = row.querySelector('.team-old-img');

            if (nameInput)   nameInput.name   = `team[${targetLang}][items][${index}][name]`;
            if (roleInput)   roleInput.name   = `team[${targetLang}][items][${index}][role]`;
            if (fileInput)   fileInput.name   = `team_img_${index}`;
            if (oldImgInput) oldImgInput.name = `team[${targetLang}][items][${index}][img]`;
        });

        // 3. إعادة ترقيم صفوف الإحصائيات
        form.querySelectorAll('.count-row-item').forEach((row, index) => {
            const numInput    = row.querySelector('.count-number');
            const titleInput  = row.querySelector('.count-title');
            const fileInput   = row.querySelector('.count-file');
            const oldImgInput = row.querySelector('.count-old-img');

            if (numInput)    numInput.name    = `about_counts[${targetLang}][items][${index}][number]`;
            if (titleInput)  titleInput.name  = `about_counts[${targetLang}][items][${index}][title]`;
            if (fileInput)   fileInput.name   = `count_img_${index}`;
            if (oldImgInput) oldImgInput.name = `about_counts[${targetLang}][items][${index}][img]`;
        });

        // 4. إعادة ترقيم صفوف الشركاء
        form.querySelectorAll('.partner-row-item').forEach((row, index) => {
            const fileInput   = row.querySelector('.partner-file');
            const oldImgInput = row.querySelector('.partner-old-img');

            if (fileInput)   fileInput.name   = `partner_img_${index}`;
            if (oldImgInput) oldImgInput.name = `partners[${targetLang}][items][${index}][img]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || ABOUT_CSRF_TOKEN;

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
                showNotification(ABOUT_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(ABOUT_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(ABOUT_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(ABOUT_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
