<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Courses Admin Modals (Gold Standard Security, Multilingual & AJAX)
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

$lang_file = __DIR__ . '/../../../Lang/' . $current_lang . '.php';
if (!is_file($lang_file)) {
    $lang_file = __DIR__ . '/../../../lang/' . $current_lang . '.php';
}
$lang = is_file($lang_file) ? require $lang_file : [];
if (!is_array($lang)) {
    $lang = [];
}

$activeCsrfToken = \App\Core\Security::generateCsrfToken();

$modalI18n = [
    'de' => [
        'save_success'       => 'Änderungen erfolgreich gespeichert. Seite wird neu geladen...',
        'save_error'         => 'Speichern fehlgeschlagen: ',
        'invalid_resp'       => 'Unerwartete Serverantwort.',
        'network_error'      => 'Netzwerkfehler. Bitte versuchen Sie es später erneut.',
        'success_title'      => 'Erfolgreich!',
        'error_title'        => 'Fehler!',
        'warning_title'      => 'Hinweis',
        'delete_item'        => 'Löschen',
        'bc_title'           => 'Breadcrumb bearbeiten',
        'bc_name_lbl'        => 'Seitenname im Pfad',
        'bc_url_lbl'         => 'Seiten-URL',
        'hero_title'         => 'Hero-Bild bearbeiten',
        'hero_file_lbl'      => 'Neues Bild hochladen',
        'main_sec_title'     => 'Haupttitel & Beschreibung bearbeiten',
        'main_title_lbl'     => 'Haupttitel',
        'main_desc_lbl'      => 'Ausführliche Beschreibung',
        'goals_sec_title'    => 'Kursziele bearbeiten',
        'goals_title_lbl'    => 'Abschnittstitel',
        'goal_item_lbl'      => 'Kursziel',
        'add_goal_btn'       => 'Ziel hinzufügen',
        'warn_sec_title'     => 'Hinweistext bearbeiten',
        'warn_text_lbl'      => 'Hinweistext (Rot)',
        'cost_sec_title'     => 'Studienorte & Kosten bearbeiten',
        'cost_title_lbl'     => 'Abschnittstitel',
        'cost_item_title'    => 'Ort / Einrichtung',
        'cost_item_desc'     => 'Kosten / Details',
        'add_cost_btn'       => 'Eintrag hinzufügen',
    ],
    'en' => [
        'save_success'       => 'Changes saved successfully. Reloading page...',
        'save_error'         => 'Failed to save changes: ',
        'invalid_resp'       => 'Unexpected server response.',
        'network_error'      => 'Network connection error. Please try again later.',
        'success_title'      => 'Success!',
        'error_title'        => 'Error!',
        'warning_title'      => 'Notice',
        'delete_item'        => 'Delete',
        'bc_title'           => 'Edit Breadcrumb',
        'bc_name_lbl'        => 'Page Name in Path',
        'bc_url_lbl'         => 'Page URL',
        'hero_title'         => 'Edit Hero Image',
        'hero_file_lbl'      => 'Upload New Image',
        'main_sec_title'     => 'Edit Main Title & Description',
        'main_title_lbl'     => 'Main Title',
        'main_desc_lbl'      => 'Detailed Description',
        'goals_sec_title'    => 'Edit Course Objectives',
        'goals_title_lbl'    => 'Section Title',
        'goal_item_lbl'      => 'Objective',
        'add_goal_btn'       => 'Add Objective',
        'warn_sec_title'     => 'Edit Warning & Conditions',
        'warn_text_lbl'      => 'Warning Text (Red)',
        'cost_sec_title'     => 'Edit Locations & Costs',
        'cost_title_lbl'     => 'Section Title',
        'cost_item_title'    => 'Location / Institution',
        'cost_item_desc'     => 'Cost / Details',
        'add_cost_btn'       => 'Add Item',
    ],
    'ar' => [
        'save_success'       => 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...',
        'save_error'         => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'       => 'استجابة غير متوقعة من الخادم.',
        'network_error'      => 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.',
        'success_title'      => 'تم بنجاح!',
        'error_title'        => 'عذراً، حدث خطأ!',
        'warning_title'      => 'تنبيه هام',
        'delete_item'        => 'حذف',
        'bc_title'           => 'تعديل مسار التنقل (Breadcrumb)',
        'bc_name_lbl'        => 'اسم الصفحة في المسار',
        'bc_url_lbl'         => 'رابط الصفحة (URL)',
        'hero_title'         => 'تعديل صورة الهيرو الرئيسية',
        'hero_file_lbl'      => 'رفع صورة جديدة',
        'main_sec_title'     => 'تعديل العنوان والوصف الرئيسي',
        'main_title_lbl'     => 'العنوان الرئيسي',
        'main_desc_lbl'      => 'الوصف التفصيلي',
        'goals_sec_title'    => 'تعديل أهداف الدورة التحضيرية',
        'goals_title_lbl'    => 'عنوان قسم الأهداف',
        'goal_item_lbl'      => 'الهدف',
        'add_goal_btn'       => 'إضافة هدف جديد',
        'warn_sec_title'     => 'تعديل نص التنبيه والشروط',
        'warn_text_lbl'      => 'نص التنبيه الأحمر',
        'cost_sec_title'     => 'تعديل أماكن الالتحاق والتكلفة',
        'cost_title_lbl'     => 'عنوان قسم التكلفة',
        'cost_item_title'    => 'المكان / الجهة',
        'cost_item_desc'     => 'التكلفة / التفاصيل',
        'add_cost_btn'       => 'إضافة مكان / تكلفة جديدة',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawCoursesSetting = static function (string $pageKey) use ($current_lang): array {
    $pageRawData = [];
    try {
        $pdo = \App\Config\Database::getConnection();
        $stmt = $pdo->prepare("SELECT page_data FROM pages WHERE page_key = :k LIMIT 1");
        $stmt->execute(['k' => $pageKey]);
        $dbRaw = $stmt->fetchColumn();

        if (is_string($dbRaw) && $dbRaw !== '') {
            $decoded = json_decode($dbRaw, true);
            if (is_array($decoded)) {
                $pageRawData = $decoded;
            }
        }
    } catch (\Throwable $e) {}

    if (isset($pageRawData[$current_lang]) && is_array($pageRawData[$current_lang])) {
        return $pageRawData[$current_lang];
    }
    if (isset($pageRawData['de']) && is_array($pageRawData['de'])) {
        return $pageRawData['de'];
    }
    if (isset($pageRawData['en']) && is_array($pageRawData['en'])) {
        return $pageRawData['en'];
    }
    if (isset($pageRawData['ar']) && is_array($pageRawData['ar'])) {
        return $pageRawData['ar'];
    }
    return $pageRawData;
};

$curCoursesData = $fetchRawCoursesSetting('courses_page');

$modalBcUrl       = (string)($curCoursesData['page_breadcrumb_url'] ?? ($lang_data['page_breadcrumb_url'] ?? '#'));
$modalBcText      = (string)($curCoursesData['page_breadcrumb'] ?? ($lang_data['page_breadcrumb'] ?? ''));
$modalHeroImg     = (string)($curCoursesData['hero_img'] ?? ($lang_data['hero_img'] ?? ''));
$modalMainTitle   = (string)($curCoursesData['main_title'] ?? ($lang_data['main_title'] ?? ''));
$modalMainDesc    = (string)($curCoursesData['main_desc'] ?? ($lang_data['main_desc'] ?? ''));
$modalGoalsTitle  = (string)($curCoursesData['goals_title'] ?? ($lang_data['goals_title'] ?? ''));
$modalGoalsArr    = is_array($curCoursesData['goals'] ?? null) ? $curCoursesData['goals'] : ($lang_data['goals'] ?? []);
$modalWarningText = (string)($curCoursesData['warning_text'] ?? ($lang_data['warning_text'] ?? ''));
$modalCostTitle   = (string)($curCoursesData['cost_title'] ?? ($lang_data['cost_title'] ?? ''));
$modalCostItems   = is_array($curCoursesData['cost_items'] ?? null) ? $curCoursesData['cost_items'] : ($lang_data['cost_items'] ?? []);
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="langBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-signpost-split text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['bc_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_courses_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="courses_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="courses_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="langHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-image text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['hero_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_courses_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="courses_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="courses_hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="langMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-card-heading text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['main_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_courses_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="courses_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="courses_data[<?php echo $current_lang; ?>][main_desc]" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Course Goals Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="langGoalsModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-check2-circle text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['goals_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesGoalsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_courses_goals">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['goals_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="courses_data[<?php echo $current_lang; ?>][goals_title]" value="<?php echo htmlspecialchars($modalGoalsTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="coursesGoalsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalGoalsArr) ? $modalGoalsArr : []) as $index => $goalText): ?>
                            <div class="p-3 shadow-sm courses-goal-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="course_goal_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['goal_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control courses-goal-input" name="courses_data[<?php echo $current_lang; ?>][goals][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$goalText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('course_goal_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCourseGoalBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_goal_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesGoalsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Warning Text Edit Modal -->
<div class="modal fade custom-modal" id="langWarningModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill text-danger <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['warn_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesWarningForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_courses_warning">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['warn_text_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="courses_data[<?php echo $current_lang; ?>][warning_text]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalWarningText, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesWarningForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Locations & Costs Edit Modal (صفوف ديناميكية ثنائية الحقول) -->
<div class="modal fade custom-modal" id="langCostModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-geo-alt-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['cost_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coursesCostForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_courses_cost">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['cost_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="courses_data[<?php echo $current_lang; ?>][cost_title]" value="<?php echo htmlspecialchars($modalCostTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="coursesCostContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalCostItems) ? $modalCostItems : []) as $index => $costItem): 
                            if (!is_array($costItem)) continue;
                        ?>
                            <div class="p-3 shadow-sm courses-cost-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="course_cost_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['cost_item_title'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control courses-cost-title" name="courses_data[<?php echo $current_lang; ?>][cost_items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars((string)($costItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['cost_item_desc'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control courses-cost-desc" name="courses_data[<?php echo $current_lang; ?>][cost_items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars((string)($costItem['desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('course_cost_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCourseCostBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_cost_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coursesCostForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const COURSES_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const COURSES_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const COURSES_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = COURSES_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = COURSES_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = COURSES_MODAL_TXT.warning_title;
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

function addCourseGoalRow() {
    const container = document.getElementById('coursesGoalsContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.courses-goal-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm courses-goal-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'course_goal_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${COURSES_MODAL_TXT.goal_item_lbl}</label>
                <input type="text" class="form-control courses-goal-input" name="courses_data[${COURSES_ACTIVE_LANG}][goals][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${COURSES_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addCourseCostRow() {
    const container = document.getElementById('coursesCostContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.courses-cost-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm courses-cost-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'course_cost_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-secondary">${COURSES_MODAL_TXT.cost_item_title}</label>
                <input type="text" class="form-control courses-cost-title" name="courses_data[${COURSES_ACTIVE_LANG}][cost_items][0][title]" value="" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary">${COURSES_MODAL_TXT.cost_item_desc}</label>
                <input type="text" class="form-control courses-cost-desc" name="courses_data[${COURSES_ACTIVE_LANG}][cost_items][0][desc]" value="">
            </div>
            <div class="col-md-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${COURSES_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addCourseGoalBtn')?.addEventListener('click', addCourseGoalRow);
    document.getElementById('addCourseCostBtn')?.addEventListener('click', addCourseCostRow);

    const coursesFormIds = new Set([
        'coursesBreadcrumbForm',
        'coursesHeroForm',
        'coursesMainForm',
        'coursesGoalsForm',
        'coursesWarningForm',
        'coursesCostForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !coursesFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || COURSES_ACTIVE_LANG;

        // 1. إعادة ترقيم صفوف الأهداف
        form.querySelectorAll('.courses-goal-row-item').forEach((row, index) => {
            const input = row.querySelector('.courses-goal-input');
            if (input) {
                input.name = `courses_data[${targetLang}][goals][${index}]`;
            }
        });

        // 2. إعادة ترقيم صفوف أماكن الالتحاق والتكلفة
        form.querySelectorAll('.courses-cost-row-item').forEach((row, index) => {
            const titleInput = row.querySelector('.courses-cost-title');
            const descInput  = row.querySelector('.courses-cost-desc');
            if (titleInput) titleInput.name = `courses_data[${targetLang}][cost_items][${index}][title]`;
            if (descInput)  descInput.name  = `courses_data[${targetLang}][cost_items][${index}][desc]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || COURSES_CSRF_TOKEN;

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
                showNotification(COURSES_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(COURSES_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(COURSES_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(COURSES_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
