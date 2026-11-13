<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Education Admin Modals (Gold Standard Security, Multilingual & AJAX)
 * متطابق بالكامل مع EduSettingsService.php ونظام الحماية المركزي.
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
        'save_success'       => 'Änderungen erfolgreich gespeichert. Seite wird neu geladen...',
        'save_error'         => 'Speichern fehlgeschlagen: ',
        'invalid_resp'       => 'Unerwartete Serverantwort.',
        'network_error'      => 'Netzwerkfehler. Bitte versuchen Sie es später erneut.',
        'success_title'      => 'Erfolgreich!',
        'error_title'        => 'Fehler!',
        'warning_title'      => 'Hinweis',
        'max_steps_warn'     => 'Es können maximal 6 Schritte in der Zeitleiste hinzugefügt werden.',
        'edu_why_title'      => 'Titel',
        'edu_why_desc'       => 'Kurzbeschreibung',
        'edu_why_image'      => 'Symbol / Bild',
        'delete_item'        => 'Löschen',
        'step_title'         => 'Schrittname',
        'step_subtitle'      => 'Untertitel',
        'step_desc'          => 'Details',
        'step_order'         => 'Reihenfolge',
        'step_icon'          => 'Symbol',
        'delete_step'        => 'Schritt löschen',
        'edu_srv_title'      => 'Dienstleistungsname',
        'edu_srv_url'        => 'Dienstleistungs-URL',
        'edu_srv_img'        => 'Hintergrundbild',
        'delete_edu_service' => 'Dienstleistung löschen',
    ],
    'en' => [
        'save_success'       => 'Changes saved successfully. Reloading page...',
        'save_error'         => 'Failed to save changes: ',
        'invalid_resp'       => 'Unexpected server response.',
        'network_error'      => 'Network connection error. Please try again later.',
        'success_title'      => 'Success!',
        'error_title'        => 'Error!',
        'warning_title'      => 'Notice',
        'max_steps_warn'     => 'You cannot add more than 6 steps to the timeline.',
        'edu_why_title'      => 'Title',
        'edu_why_desc'       => 'Short Description',
        'edu_why_image'      => 'Icon / Image',
        'delete_item'        => 'Delete',
        'step_title'         => 'Step Title',
        'step_subtitle'      => 'Subtitle',
        'step_desc'          => 'Details',
        'step_order'         => 'Order',
        'step_icon'          => 'Icon',
        'delete_step'        => 'Delete Step',
        'edu_srv_title'      => 'Service Title',
        'edu_srv_url'        => 'Service URL',
        'edu_srv_img'        => 'Background Image',
        'delete_edu_service' => 'Delete Service',
    ],
    'ar' => [
        'save_success'       => 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...',
        'save_error'         => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'       => 'استجابة غير متوقعة من الخادم.',
        'network_error'      => 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.',
        'success_title'      => 'تم بنجاح!',
        'error_title'        => 'عذراً، حدث خطأ!',
        'warning_title'      => 'تنبيه هام',
        'max_steps_warn'     => 'عذراً، لا يمكن إضافة أكثر من 6 عناصر في خط الزمن (Timeline).',
        'edu_why_title'      => 'العنوان',
        'edu_why_desc'       => 'الوصف المختصر',
        'edu_why_image'      => 'الأيقونة / الصورة',
        'delete_item'        => 'حذف',
        'step_title'         => 'اسم الخطوة',
        'step_subtitle'      => 'العنوان الفرعي',
        'step_desc'          => 'التفاصيل',
        'step_order'         => 'الترتيب',
        'step_icon'          => 'الأيقونة',
        'delete_step'        => 'حذف الخطوة',
        'edu_srv_title'      => 'اسم الخدمة',
        'edu_srv_url'        => 'رابط الخدمة',
        'edu_srv_img'        => 'صورة الخلفية',
        'delete_edu_service' => 'حذف الخدمة',
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

$curEduHero     = $fetchRawMultilingualSetting('edu_hero');
$curEduWhy      = $fetchRawMultilingualSetting('edu_why');
$curEduTimeline = $fetchRawMultilingualSetting('edu_timeline');
$curEduServices = $fetchRawMultilingualSetting('edu_services');

$modalHeroTitle   = (string)($curEduHero['title'] ?? ($edu_hero['title'] ?? ''));
$modalHeroDesc    = (string)($curEduHero['desc'] ?? ($edu_hero['desc'] ?? ''));
$modalHeroBtnText = (string)($curEduHero['btn_text'] ?? ($edu_hero['btn_text'] ?? ''));
$modalHeroBtnUrl  = (string)($curEduHero['btn_url'] ?? ($edu_hero['btn_url'] ?? '#'));
$modalHeroImg     = (string)($curEduHero['img'] ?? ($edu_hero['img'] ?? ''));

$modalWhyTitle = (string)($curEduWhy['title'] ?? ($edu_why_title ?? ''));
$modalWhyDesc  = (string)($curEduWhy['desc'] ?? ($edu_why_desc ?? ''));
$modalWhyItems = is_array($curEduWhy['items'] ?? null) ? $curEduWhy['items'] : [];

$modalTimelineTitle = (string)($curEduTimeline['title'] ?? ($edu_timeline_title ?? ''));
$modalTimelineDesc  = (string)($curEduTimeline['desc'] ?? ($edu_timeline_desc ?? ''));
$modalTimelineSteps = is_array($curEduTimeline['steps'] ?? null)
    ? $curEduTimeline['steps']
    : (is_array($curEduTimeline['items'] ?? null) ? $curEduTimeline['items'] : []);

$modalServicesTitle = (string)($curEduServices['title'] ?? ($curEduServices['section_title'] ?? ($edu_services_title ?? '')));
$modalServicesDesc  = (string)($curEduServices['desc'] ?? ($curEduServices['section_subtitle'] ?? ($edu_services_desc ?? '')));
$modalServicesItems = is_array($curEduServices['items'] ?? null) ? $curEduServices['items'] : [];
?>

<!-- 1. Edu Hero Modal (قسم الهيرو) -->
<div class="modal fade custom-modal" id="eduHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-mortarboard text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_hero'] ?? 'Edit Education Hero', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_title'] ?? 'Title', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalHeroTitle, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_desc'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?></label>
                                <textarea class="form-control" name="edu_hero[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalHeroDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>

                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_btn_text'] ?? 'Button Text', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($modalHeroBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_url'] ?? 'Button URL', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="edu_hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars($modalHeroBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_hero_image'] ?? 'Hero Image', ENT_QUOTES, 'UTF-8'); ?></label>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($modalHeroImg !== ''): ?>
                                        <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                            <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Hero Image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="edu_hero_img" accept="image/*">
                                </div>
                                <input type="hidden" name="old_edu_hero_img" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Edu Why Modal (قسم لماذا الدراسة) -->
<div class="modal fade custom-modal" id="eduWhyModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-patch-question text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['edu_why_section_title'] ?? 'Manage Why Study', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_why">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_why_title_label'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_why[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalWhyTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_why_desc_label'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_why[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalWhyDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="eduWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalWhyItems as $index => $item): 
                            if (!is_array($item)) continue;
                            $wTitle = (string)($item['title'] ?? '');
                            $wDesc  = (string)($item['desc'] ?? '');
                            $wImg   = (string)($item['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm edu-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="why_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_why_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-why-title" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($wTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_why_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-why-desc" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($wDesc, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_why_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($wImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($wImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control edu-why-file" name="edu_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-why-old-img" name="edu_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($wImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('why_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addEduWhyBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['edu_why_add_btn'] ?? 'Add Item', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduWhyForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Edu Timeline Modal (قسم خطوات الرحلة) -->
<div class="modal fade custom-modal" id="eduTimelineModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3 text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_timeline'] ?? 'Manage Timeline', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_timeline">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_timeline_title_lbl'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_timeline[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalTimelineTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['edu_timeline_desc_lbl'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_timeline[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalTimelineDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="eduTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalTimelineSteps as $index => $step): 
                            if (!is_array($step)) continue;
                            $sTitle    = (string)($step['title'] ?? '');
                            $sSubtitle = (string)($step['subtitle'] ?? '');
                            $sDesc     = (string)($step['desc'] ?? '');
                            $sOrder    = (string)($step['order'] ?? $index);
                            $sIcon     = (string)($step['icon'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm edu-timeline-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="step_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['step_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-step-title" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($sTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['step_subtitle'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-step-subtitle" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($sSubtitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-9 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['step_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-step-desc" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($sDesc, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['step_order'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="number" class="form-control edu-step-order" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($sOrder, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['step_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($sIcon !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($sIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control edu-step-file" name="edu_timeline_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-step-old-icon" name="edu_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($sIcon, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('step_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_step'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addEduStepBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['edu_timeline_add_btn'] ?? 'Add Step', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduTimelineForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Edu Services Modal (قسم خدمات التعليم العالي) -->
<div class="modal fade custom-modal" id="eduServicesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_edu_services'] ?? 'Manage Education Services', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="eduServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_edu_services">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="edu_services[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalServicesTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="edu_services[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalServicesDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="eduServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalServicesItems as $index => $item): 
                            if (!is_array($item)) continue;
                            $srvTitle = (string)($item['title'] ?? '');
                            $srvUrl   = (string)($item['url'] ?? '');
                            $srvImg   = (string)($item['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm edu-service-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="edu_srv_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_srv_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-srv-title" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($srvTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_srv_url'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control edu-srv-url" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($srvUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="bachelor-package">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['edu_srv_img'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($srvImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($srvImg), ENT_QUOTES, 'UTF-8'); ?>" alt="service image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control edu-srv-file" name="edu_service_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="edu-srv-old-img" name="edu_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($srvImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('edu_srv_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_edu_service'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addEduServiceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['edu_services_add_btn'] ?? 'Add Service', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="eduServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const EDU_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const EDU_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const EDU_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = EDU_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = EDU_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = EDU_MODAL_TXT.warning_title;
    }

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

function addEduWhyRow() {
    const container = document.getElementById('eduWhyContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.edu-why-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm edu-why-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'why_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_why_title}</label>
                <input type="text" class="form-control edu-why-title" name="edu_why[${EDU_ACTIVE_LANG}][items][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_why_desc}</label>
                <input type="text" class="form-control edu-why-desc" name="edu_why[${EDU_ACTIVE_LANG}][items][0][desc]">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_why_image}</label>
                <input type="file" class="form-control edu-why-file" name="edu_why_img_0" accept="image/*">
            </div>
            <input type="hidden" class="edu-why-old-img" name="edu_why[${EDU_ACTIVE_LANG}][items][0][old_img]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${EDU_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addEduStepRow() {
    const container = document.getElementById('eduTimelineContainer');
    if (!container) return;

    const currentRows = container.querySelectorAll('.edu-timeline-row-item').length;
    if (currentRows >= 6) {
        showNotification(EDU_MODAL_TXT.max_steps_warn, 'warning');
        return;
    }

    const rowUid = Date.now() + '_' + currentRows;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm edu-timeline-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'step_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.step_title}</label>
                <input type="text" class="form-control edu-step-title" name="edu_timeline[${EDU_ACTIVE_LANG}][steps][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.step_subtitle}</label>
                <input type="text" class="form-control edu-step-subtitle" name="edu_timeline[${EDU_ACTIVE_LANG}][steps][0][subtitle]">
            </div>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-9">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.step_desc}</label>
                <input type="text" class="form-control edu-step-desc" name="edu_timeline[${EDU_ACTIVE_LANG}][steps][0][desc]">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.step_order}</label>
                <input type="number" class="form-control edu-step-order" name="edu_timeline[${EDU_ACTIVE_LANG}][steps][0][order]" value="${currentRows}">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.step_icon}</label>
                <input type="file" class="form-control edu-step-file" name="edu_timeline_icon_0" accept="image/*">
            </div>
            <input type="hidden" class="edu-step-old-icon" name="edu_timeline[${EDU_ACTIVE_LANG}][steps][0][old_icon]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${EDU_MODAL_TXT.delete_step}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addEduServiceRow() {
    const container = document.getElementById('eduServicesContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.edu-service-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm edu-service-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'edu_srv_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_srv_title}</label>
                <input type="text" class="form-control edu-srv-title" name="edu_services[${EDU_ACTIVE_LANG}][items][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_srv_url}</label>
                <input type="text" class="form-control edu-srv-url" name="edu_services[${EDU_ACTIVE_LANG}][items][0][url]" placeholder="bachelor-package">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${EDU_MODAL_TXT.edu_srv_img}</label>
                <input type="file" class="form-control edu-srv-file" name="edu_service_img_0" accept="image/*">
            </div>
            <input type="hidden" class="edu-srv-old-img" name="edu_services[${EDU_ACTIVE_LANG}][items][0][old_img]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${EDU_MODAL_TXT.delete_edu_service}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addEduWhyBtn')?.addEventListener('click', addEduWhyRow);
    document.getElementById('addEduStepBtn')?.addEventListener('click', addEduStepRow);
    document.getElementById('addEduServiceBtn')?.addEventListener('click', addEduServiceRow);

    const eduFormIds = new Set(['eduHeroForm', 'eduWhyForm', 'eduTimelineForm', 'eduServicesForm']);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !eduFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || EDU_ACTIVE_LANG;

        // 1. إعادة ترقيم "لماذا الدراسة"
        form.querySelectorAll('.edu-why-row-item').forEach((row, index) => {
            const titleInput  = row.querySelector('.edu-why-title');
            const descInput   = row.querySelector('.edu-why-desc');
            const fileInput   = row.querySelector('.edu-why-file');
            const oldImgInput = row.querySelector('.edu-why-old-img');

            if (titleInput)  titleInput.name  = `edu_why[${targetLang}][items][${index}][title]`;
            if (descInput)   descInput.name   = `edu_why[${targetLang}][items][${index}][desc]`;
            if (fileInput)   fileInput.name   = `edu_why_img_${index}`;
            if (oldImgInput) oldImgInput.name = `edu_why[${targetLang}][items][${index}][old_img]`;
        });

        // 2. إعادة ترقيم "خطوات الرحلة التعليمية"
        form.querySelectorAll('.edu-timeline-row-item').forEach((row, index) => {
            const titleInput    = row.querySelector('.edu-step-title');
            const subtitleInput = row.querySelector('.edu-step-subtitle');
            const orderInput    = row.querySelector('.edu-step-order');
            const descInput     = row.querySelector('.edu-step-desc');
            const fileInput     = row.querySelector('.edu-step-file');
            const oldIconInput  = row.querySelector('.edu-step-old-icon');

            if (titleInput)    titleInput.name    = `edu_timeline[${targetLang}][steps][${index}][title]`;
            if (subtitleInput) subtitleInput.name = `edu_timeline[${targetLang}][steps][${index}][subtitle]`;
            if (orderInput)    orderInput.name    = `edu_timeline[${targetLang}][steps][${index}][order]`;
            if (descInput)     descInput.name     = `edu_timeline[${targetLang}][steps][${index}][desc]`;
            if (fileInput)     fileInput.name     = `edu_timeline_icon_${index}`;
            if (oldIconInput)  oldIconInput.name  = `edu_timeline[${targetLang}][steps][${index}][old_icon]`;
        });

        // 3. إعادة ترقيم "خدمات التعليم"
        form.querySelectorAll('.edu-service-row-item').forEach((row, index) => {
            const titleInput  = row.querySelector('.edu-srv-title');
            const urlInput    = row.querySelector('.edu-srv-url');
            const fileInput   = row.querySelector('.edu-srv-file');
            const oldImgInput = row.querySelector('.edu-srv-old-img');

            if (titleInput) titleInput.name = `edu_services[${targetLang}][items][${index}][title]`;

            if (urlInput) {
                let val = urlInput.value.trim();
                if (val && val !== '#' && !val.startsWith('http') && !val.startsWith('/') && !val.startsWith('edu-services/') && !val.startsWith('job-services/')) {
                    val = 'edu-services/' + val;
                }
                urlInput.value = val;
                urlInput.name  = `edu_services[${targetLang}][items][${index}][url]`;
            }

            if (fileInput)   fileInput.name   = `edu_service_img_${index}`;
            if (oldImgInput) oldImgInput.name = `edu_services[${targetLang}][items][${index}][old_img]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || EDU_CSRF_TOKEN;

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
                showNotification(EDU_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(EDU_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(EDU_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(EDU_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
