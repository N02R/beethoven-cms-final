<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Job Admin Modals (Gold Standard Security, Multilingual & AJAX)
 * متطابق بالكامل مع JobSettingsService.php ونظام الحماية المركزي.
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
        'job_why_title'      => 'Titel',
        'job_why_desc'       => 'Beschreibung',
        'job_why_image'      => 'Symbol / Bild',
        'delete_item'        => 'Löschen',
        'prog_title'         => 'Programmname',
        'prog_desc'          => 'Programmdetails',
        'prog_btn_text'      => 'Button-Text',
        'prog_btn_url'       => 'Button-URL',
        'prog_image'         => 'Programmbild',
        'prog_dark'          => 'Dunkles Design (Highlight)',
        'delete_prog'        => 'Löschen',
        'job_step_title'     => 'Schrittname',
        'job_step_subtitle'  => 'Untertitel',
        'job_step_desc'      => 'Details',
        'job_step_order'     => 'Reihenfolge',
        'job_step_icon'      => 'Symbol',
        'delete_job_step'    => 'Schritt löschen',
        'job_srv_title'      => 'Dienstleistungsname',
        'job_srv_url'        => 'Dienstleistungs-URL',
        'job_srv_img'        => 'Hintergrundbild',
        'delete_job_service' => 'Dienstleistung löschen',
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
        'job_why_title'      => 'Title',
        'job_why_desc'       => 'Description',
        'job_why_image'      => 'Icon / Image',
        'delete_item'        => 'Delete',
        'prog_title'         => 'Program Name',
        'prog_desc'          => 'Program Details',
        'prog_btn_text'      => 'Button Text',
        'prog_btn_url'       => 'Button URL',
        'prog_image'         => 'Program Image',
        'prog_dark'          => 'Dark Highlight Theme',
        'delete_prog'        => 'Delete',
        'job_step_title'     => 'Step Title',
        'job_step_subtitle'  => 'Subtitle',
        'job_step_desc'      => 'Details',
        'job_step_order'     => 'Order',
        'job_step_icon'      => 'Icon',
        'delete_job_step'    => 'Delete Step',
        'job_srv_title'      => 'Service Title',
        'job_srv_url'        => 'Service URL',
        'job_srv_img'        => 'Background Image',
        'delete_job_service' => 'Delete Service',
    ],
    'ar' => [
        'save_success'       => 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...',
        'save_error'         => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'       => 'استجابة غير متوقعة من الخادم.',
        'network_error'      => 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.',
        'success_title'      => 'تم بنجاح!',
        'error_title'        => 'عذراً، حدث خطأ!',
        'warning_title'      => 'تنبيه هام',
        'max_steps_warn'     => 'عذراً، الحد الأقصى المسموح به هو 6 عناصر فقط في خط الزمن (Timeline).',
        'job_why_title'      => 'العنوان',
        'job_why_desc'       => 'الوصف',
        'job_why_image'      => 'الصورة / الأيقونة',
        'delete_item'        => 'حذف',
        'prog_title'         => 'اسم البرنامج',
        'prog_desc'          => 'تفاصيل البرنامج',
        'prog_btn_text'      => 'نص الزر',
        'prog_btn_url'       => 'رابط الزر',
        'prog_image'         => 'صورة البرنامج',
        'prog_dark'          => 'تصميم داكن (Highlight)',
        'delete_prog'        => 'حذف',
        'job_step_title'     => 'اسم الخطوة',
        'job_step_subtitle'  => 'العنوان الفرعي',
        'job_step_desc'      => 'التفاصيل',
        'job_step_order'     => 'الترتيب',
        'job_step_icon'      => 'الأيقونة',
        'delete_job_step'    => 'حذف الخطوة',
        'job_srv_title'      => 'اسم الخدمة',
        'job_srv_url'        => 'رابط الخدمة',
        'job_srv_img'        => 'صورة الخلفية',
        'delete_job_service' => 'حذف الخدمة',
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

$curJobHero     = $fetchRawMultilingualSetting('job_hero');
$curJobWhy      = $fetchRawMultilingualSetting('job_why');
$curJobPrograms = $fetchRawMultilingualSetting('job_programs');
$curJobTimeline = $fetchRawMultilingualSetting('job_timeline');
$curJobServices = $fetchRawMultilingualSetting('job_services');

$modalHeroTitle   = (string)($curJobHero['title'] ?? ($job_hero['title'] ?? ''));
$modalHeroDesc    = (string)($curJobHero['desc'] ?? ($job_hero['desc'] ?? ''));
$modalHeroBtnText = (string)($curJobHero['btn_text'] ?? ($job_hero['btn_text'] ?? ''));
$modalHeroBtnUrl  = (string)($curJobHero['btn_url'] ?? ($job_hero['btn_url'] ?? 'contact'));
$modalHeroImg     = (string)($curJobHero['img'] ?? ($job_hero['img'] ?? ''));

$modalWhyTitle = (string)($curJobWhy['title'] ?? ($job_why_title ?? ''));
$modalWhyDesc  = (string)($curJobWhy['desc'] ?? ($job_why_desc ?? ''));
$modalWhyItems = is_array($curJobWhy['items'] ?? null) ? $curJobWhy['items'] : [];

$modalProgTitle = (string)($curJobPrograms['title'] ?? ($job_program_title ?? ''));
$modalProgDesc  = (string)($curJobPrograms['desc'] ?? ($job_program_desc ?? ''));
$modalProgItems = is_array($curJobPrograms['items'] ?? null) ? $curJobPrograms['items'] : [];

$modalTimelineTitle = (string)($curJobTimeline['title'] ?? ($job_timeline_title ?? ''));
$modalTimelineDesc  = (string)($curJobTimeline['desc'] ?? ($job_timeline_desc ?? ''));
$modalTimelineSteps = is_array($curJobTimeline['steps'] ?? null)
    ? $curJobTimeline['steps']
    : (is_array($curJobTimeline['items'] ?? null) ? $curJobTimeline['items'] : []);

$modalServicesTitle = (string)($curJobServices['title'] ?? ($curJobServices['section_title'] ?? ($job_services_title ?? '')));
$modalServicesDesc  = (string)($curJobServices['desc'] ?? ($curJobServices['section_subtitle'] ?? ($job_services_desc ?? '')));
$modalServicesItems = is_array($curJobServices['items'] ?? null) ? $curJobServices['items'] : [];
?>

<!-- 1. Job Hero Modal -->
<div class="modal fade custom-modal" id="jobHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-briefcase-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_hero'] ?? 'Edit Job Hero', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalHeroTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_hero[<?php echo $current_lang; ?>][desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalHeroDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['hero_btn_text'] ?? 'Button Text', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($modalHeroBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['url'] ?? 'Button URL', ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="job_hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars($modalHeroBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-2 text-secondary d-block <?php echo $modal_align; ?>"><?php echo htmlspecialchars($lang['current_image'] ?? 'Hero Image', ENT_QUOTES, 'UTF-8'); ?></label>
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-3 p-2 border rounded-3 bg-light text-center" style="max-width: 200px;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Job Hero Image" class="rounded-2" style="max-height: 100px; object-fit: contain;">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="job_hero_img" accept="image/*">
                        <input type="hidden" name="old_job_hero_img" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Job Why Modal -->
<div class="modal fade custom-modal" id="jobWhyModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_why'] ?? 'Manage Why Job', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobWhyForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_why">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_why[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalWhyTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_why[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalWhyDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="jobWhyContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalWhyItems as $index => $item): 
                            if (!is_array($item)) continue;
                            $wTitle = (string)($item['title'] ?? '');
                            $wDesc  = (string)($item['desc'] ?? '');
                            $wImg   = (string)($item['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm job-why-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_why_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_why_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-why-title" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($wTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_why_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-why-desc" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($wDesc, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_why_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($wImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($wImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-why-file" name="job_why_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-why-old-img" name="job_why[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($wImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_why_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addJobWhyBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['job_why_add_btn'] ?? 'Add Item', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobWhyForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Job Program Modal (أنواع التدريب) -->
<div class="modal fade custom-modal" id="jobProgramModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-journal-bookmark-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_programs'] ?? 'Manage Training Programs', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobProgramForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_programs">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_programs[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalProgTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_programs[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalProgDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="jobProgramContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalProgItems as $index => $prog): 
                            if (!is_array($prog)) continue;
                            $pTitle   = (string)($prog['title'] ?? '');
                            $pDesc    = (string)($prog['desc'] ?? '');
                            $pBtnText = (string)($prog['btn_text'] ?? '');
                            $pBtnUrl  = (string)($prog['btn_url'] ?? '#');
                            $pImg     = (string)($prog['img'] ?? '');
                            $pDark    = !empty($prog['is_dark']);
                        ?>
                            <div class="p-3 shadow-sm job-prog-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="prog_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['prog_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-title" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($pTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['prog_btn_text'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-btn-text" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][btn_text]" value="<?php echo htmlspecialchars($pBtnText, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['prog_btn_url'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control prog-btn-url" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][btn_url]" value="<?php echo htmlspecialchars($pBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['prog_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <textarea class="form-control prog-desc" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($pDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end mb-3">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['prog_image'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($pImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($pImg), ENT_QUOTES, 'UTF-8'); ?>" alt="Img" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control prog-file" name="prog_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="prog-old-img" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($pImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('prog_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_prog'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <div class="form-check form-switch p-2 ps-4 bg-light rounded-3 border">
                                            <input class="form-check-input prog-dark-check ms-0 me-2" type="checkbox" name="job_programs[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][is_dark]" value="1" <?php echo $pDark ? 'checked' : ''; ?>>
                                            <label class="form-check-label small text-secondary fw-semibold cursor-pointer"><?php echo htmlspecialchars($mTxt['prog_dark'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addJobProgramBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['prog_add_btn'] ?? 'Add Program', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobProgramForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Job Timeline Modal (خطوات التدريب والتوظيف) -->
<div class="modal fade custom-modal" id="jobTimelineModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_timeline'] ?? 'Manage Job Timeline', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobTimelineForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_timeline">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_timeline[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalTimelineTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_timeline[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalTimelineDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="jobTimelineContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalTimelineSteps as $index => $step): 
                            if (!is_array($step)) continue;
                            $sTitle    = (string)($step['title'] ?? '');
                            $sSubtitle = (string)($step['subtitle'] ?? '');
                            $sDesc     = (string)($step['desc'] ?? '');
                            $sOrder    = (string)($step['order'] ?? $index);
                            $sIcon     = (string)($step['icon'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm job-step-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_step_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_step_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-step-title" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($sTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_step_subtitle'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-step-subtitle" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][subtitle]" value="<?php echo htmlspecialchars($sSubtitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-9 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_step_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-step-desc" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($sDesc, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_step_order'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="number" class="form-control job-step-order" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][order]" value="<?php echo htmlspecialchars($sOrder, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_step_icon'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($sIcon !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($sIcon), ENT_QUOTES, 'UTF-8'); ?>" alt="icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-step-file" name="steps_icon_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-step-old-icon" name="job_timeline[<?php echo $current_lang; ?>][steps][<?php echo $index; ?>][old_icon]" value="<?php echo htmlspecialchars($sIcon, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_step_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_job_step'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addJobStepBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['job_timeline_add_btn'] ?? 'Add Step', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobTimelineForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Job Services Modal -->
<div class="modal fade custom-modal" id="jobServicesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($lang['manage_job_services'] ?? 'Manage Job Services', ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="jobServicesForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_job_services">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['title'] ?? 'Section Title', ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="job_services[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars($modalServicesTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars($lang['description'] ?? 'Section Description', ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="job_services[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalServicesDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>

                    <div id="jobServicesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalServicesItems as $index => $item): 
                            if (!is_array($item)) continue;
                            $srvTitle = (string)($item['title'] ?? '');
                            $srvUrl   = (string)($item['url'] ?? '');
                            $srvImg   = (string)($item['img'] ?? '');
                        ?>
                            <div class="p-3 shadow-sm job-srv-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="job_srv_row_<?php echo $index; ?>">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_srv_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-srv-title" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($srvTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_srv_url'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control job-srv-url" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($srvUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="job-package">
                                    </div>
                                </div>

                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['job_srv_img'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($srvImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                    <img src="<?php echo htmlspecialchars(get_image_url($srvImg), ENT_QUOTES, 'UTF-8'); ?>" alt="service image" class="rounded-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control job-srv-file" name="srv_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                    </div>

                                    <input type="hidden" class="job-srv-old-img" name="job_services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($srvImg, ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('job_srv_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_job_service'], ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addJobServiceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($lang['job_services_add_btn'] ?? 'Add Service', ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="jobServicesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const JOB_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const JOB_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const JOB_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = JOB_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = JOB_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = JOB_MODAL_TXT.warning_title;
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

function addJobWhyRow() {
    const container = document.getElementById('jobWhyContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.job-why-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm job-why-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'job_why_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_why_title}</label>
                <input type="text" class="form-control job-why-title" name="job_why[${JOB_ACTIVE_LANG}][items][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_why_desc}</label>
                <input type="text" class="form-control job-why-desc" name="job_why[${JOB_ACTIVE_LANG}][items][0][desc]">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_why_image}</label>
                <input type="file" class="form-control job-why-file" name="job_why_img_0" accept="image/*">
            </div>
            <input type="hidden" class="job-why-old-img" name="job_why[${JOB_ACTIVE_LANG}][items][0][old_img]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${JOB_MODAL_TXT.delete_item}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addJobProgramRow() {
    const container = document.getElementById('jobProgramContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.job-prog-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm job-prog-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'prog_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.prog_title}</label>
                <input type="text" class="form-control prog-title" name="job_programs[${JOB_ACTIVE_LANG}][items][0][title]">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.prog_btn_text}</label>
                <input type="text" class="form-control prog-btn-text" name="job_programs[${JOB_ACTIVE_LANG}][items][0][btn_text]">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.prog_btn_url}</label>
                <input type="text" class="form-control prog-btn-url" name="job_programs[${JOB_ACTIVE_LANG}][items][0][btn_url]" value="#">
            </div>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-12">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.prog_desc}</label>
                <textarea class="form-control prog-desc" name="job_programs[${JOB_ACTIVE_LANG}][items][0][desc]" rows="2" style="height: auto; padding: 12px 16px;"></textarea>
            </div>
        </div>
        <div class="row g-2 align-items-end mb-3">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.prog_image}</label>
                <input type="file" class="form-control prog-file" name="prog_img_0" accept="image/*">
            </div>
            <input type="hidden" class="prog-old-img" name="job_programs[${JOB_ACTIVE_LANG}][items][0][old_img]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${JOB_MODAL_TXT.delete_prog}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <div class="form-check form-switch p-2 ps-4 bg-light rounded-3 border">
                    <input class="form-check-input prog-dark-check ms-0 me-2" type="checkbox" name="job_programs[${JOB_ACTIVE_LANG}][items][0][is_dark]" value="1">
                    <label class="form-check-label small text-secondary fw-semibold cursor-pointer">${JOB_MODAL_TXT.prog_dark}</label>
                </div>
            </div>
        </div>`;
    container.appendChild(div);
}

function addJobStepRow() {
    const container = document.getElementById('jobTimelineContainer');
    if (!container) return;

    const currentRows = container.querySelectorAll('.job-step-row-item').length;
    if (currentRows >= 6) {
        showNotification(JOB_MODAL_TXT.max_steps_warn, 'warning');
        return;
    }

    const rowUid = Date.now() + '_' + currentRows;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm job-step-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'job_step_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_step_title}</label>
                <input type="text" class="form-control job-step-title" name="job_timeline[${JOB_ACTIVE_LANG}][steps][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_step_subtitle}</label>
                <input type="text" class="form-control job-step-subtitle" name="job_timeline[${JOB_ACTIVE_LANG}][steps][0][subtitle]">
            </div>
        </div>
        <div class="row g-2 mb-3">
            <div class="col-md-9">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_step_desc}</label>
                <input type="text" class="form-control job-step-desc" name="job_timeline[${JOB_ACTIVE_LANG}][steps][0][desc]">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_step_order}</label>
                <input type="number" class="form-control job-step-order" name="job_timeline[${JOB_ACTIVE_LANG}][steps][0][order]" value="${currentRows}">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_step_icon}</label>
                <input type="file" class="form-control job-step-file" name="steps_icon_0" accept="image/*">
            </div>
            <input type="hidden" class="job-step-old-icon" name="job_timeline[${JOB_ACTIVE_LANG}][steps][0][old_icon]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${JOB_MODAL_TXT.delete_job_step}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addJobServiceRow() {
    const container = document.getElementById('jobServicesContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.job-srv-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm job-srv-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'job_srv_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_srv_title}</label>
                <input type="text" class="form-control job-srv-title" name="job_services[${JOB_ACTIVE_LANG}][items][0][title]">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_srv_url}</label>
                <input type="text" class="form-control job-srv-url" name="job_services[${JOB_ACTIVE_LANG}][items][0][url]" placeholder="job-package">
            </div>
        </div>
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${JOB_MODAL_TXT.job_srv_img}</label>
                <input type="file" class="form-control job-srv-file" name="srv_img_0" accept="image/*">
            </div>
            <input type="hidden" class="job-srv-old-img" name="job_services[${JOB_ACTIVE_LANG}][items][0][old_img]" value="">
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${JOB_MODAL_TXT.delete_job_service}"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addJobWhyBtn')?.addEventListener('click', addJobWhyRow);
    document.getElementById('addJobProgramBtn')?.addEventListener('click', addJobProgramRow);
    document.getElementById('addJobStepBtn')?.addEventListener('click', addJobStepRow);
    document.getElementById('addJobServiceBtn')?.addEventListener('click', addJobServiceRow);

    const jobFormIds = new Set(['jobHeroForm', 'jobWhyForm', 'jobProgramForm', 'jobTimelineForm', 'jobServicesForm']);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !jobFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || JOB_ACTIVE_LANG;

        // 1. إعادة ترقيم "لماذا التدريب معنا" (متطابق مع JobSettingsService: job_why_img_*)
        form.querySelectorAll('.job-why-row-item').forEach((row, index) => {
            const titleInput  = row.querySelector('.job-why-title');
            const descInput   = row.querySelector('.job-why-desc');
            const fileInput   = row.querySelector('.job-why-file');
            const oldImgInput = row.querySelector('.job-why-old-img');

            if (titleInput)  titleInput.name  = `job_why[${targetLang}][items][${index}][title]`;
            if (descInput)   descInput.name   = `job_why[${targetLang}][items][${index}][desc]`;
            if (fileInput)   fileInput.name   = `job_why_img_${index}`;
            if (oldImgInput) oldImgInput.name = `job_why[${targetLang}][items][${index}][old_img]`;
        });

        // 2. إعادة ترقيم "برامج التدريب"
        form.querySelectorAll('.job-prog-row-item').forEach((row, index) => {
            const titleInput   = row.querySelector('.prog-title');
            const btnTextInput = row.querySelector('.prog-btn-text');
            const btnUrlInput  = row.querySelector('.prog-btn-url');
            const descInput    = row.querySelector('.prog-desc');
            const fileInput    = row.querySelector('.prog-file');
            const oldImgInput  = row.querySelector('.prog-old-img');
            const darkInput    = row.querySelector('.prog-dark-check') || row.querySelector('input[type="checkbox"]');

            if (titleInput)   titleInput.name   = `job_programs[${targetLang}][items][${index}][title]`;
            if (btnTextInput) btnTextInput.name = `job_programs[${targetLang}][items][${index}][btn_text]`;
            if (btnUrlInput)  btnUrlInput.name  = `job_programs[${targetLang}][items][${index}][btn_url]`;
            if (descInput)    descInput.name    = `job_programs[${targetLang}][items][${index}][desc]`;
            if (fileInput)    fileInput.name    = `prog_img_${index}`;
            if (oldImgInput)  oldImgInput.name  = `job_programs[${targetLang}][items][${index}][old_img]`;
            if (darkInput)    darkInput.name    = `job_programs[${targetLang}][items][${index}][is_dark]`;
        });

        // 3. إعادة ترقيم "خطوات التوظيف" (Timeline)
        form.querySelectorAll('.job-step-row-item').forEach((row, index) => {
            const titleInput    = row.querySelector('.job-step-title');
            const subtitleInput = row.querySelector('.job-step-subtitle');
            const orderInput    = row.querySelector('.job-step-order');
            const descInput     = row.querySelector('.job-step-desc');
            const fileInput     = row.querySelector('.job-step-file');
            const oldIconInput  = row.querySelector('.job-step-old-icon');

            if (titleInput)    titleInput.name    = `job_timeline[${targetLang}][steps][${index}][title]`;
            if (subtitleInput) subtitleInput.name = `job_timeline[${targetLang}][steps][${index}][subtitle]`;
            if (orderInput)    orderInput.name    = `job_timeline[${targetLang}][steps][${index}][order]`;
            if (descInput)     descInput.name     = `job_timeline[${targetLang}][steps][${index}][desc]`;
            if (fileInput)     fileInput.name     = `steps_icon_${index}`;
            if (oldIconInput)  oldIconInput.name  = `job_timeline[${targetLang}][steps][${index}][old_icon]`;
        });

        // 4. إعادة ترقيم "كروت الخدمات"
        form.querySelectorAll('.job-srv-row-item').forEach((row, index) => {
            const titleInput  = row.querySelector('.job-srv-title');
            const urlInput    = row.querySelector('.job-srv-url');
            const fileInput   = row.querySelector('.job-srv-file');
            const oldImgInput = row.querySelector('.job-srv-old-img');

            if (titleInput) titleInput.name = `job_services[${targetLang}][items][${index}][title]`;

            if (urlInput) {
                let val = urlInput.value.trim();
                if (val && val !== '#' && !val.startsWith('http') && !val.startsWith('/') && !val.startsWith('edu-services/') && !val.startsWith('job-services/')) {
                    val = 'job-services/' + val;
                }
                urlInput.value = val;
                urlInput.name  = `job_services[${targetLang}][items][${index}][url]`;
            }

            if (fileInput)   fileInput.name   = `srv_img_${index}`;
            if (oldImgInput) oldImgInput.name = `job_services[${targetLang}][items][${index}][old_img]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || JOB_CSRF_TOKEN;

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
                showNotification(JOB_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(JOB_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(JOB_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(JOB_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
