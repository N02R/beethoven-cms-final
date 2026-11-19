<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Foundation Course / Studienkolleg Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'hero_pos_lbl'       => 'Bildposition (z.B. center -20rem)',
        'main_sec_title'     => 'Haupttitel & Beschreibung bearbeiten',
        'main_title_lbl'     => 'Haupttitel',
        'main_desc_lbl'      => 'Hauptbeschreibung',
        'goals_sec_title'    => 'Kursziele bearbeiten',
        'goals_title_lbl'    => 'Abschnittstitel',
        'goal_item_lbl'      => 'Kursziel',
        'add_goal_btn'       => 'Ziel hinzufügen',
        'learn_sec_title'    => 'Studieninhalte bearbeiten',
        'learn_title_lbl'    => 'Abschnittstitel',
        'learn_intro_lbl'    => 'Einleitungstext',
        'learn_p1_lbl'       => 'Absatz 1',
        'learn_p2_lbl'       => 'Absatz 2',
        'courses_sec_title'  => 'Kurstypen bearbeiten',
        'courses_title_lbl'  => 'Abschnittstitel',
        'course_item_lbl'    => 'Kurstyp (z.B. T-Kurs)',
        'add_course_btn'     => 'Kurstyp hinzufügen',
        'uni_sec_title'      => 'Hochschulbezug bearbeiten',
        'uni_title_lbl'      => 'Abschnittstitel',
        'uni_intro_lbl'      => 'Einleitungstext',
        'uni_pub_lbl'        => 'Universitäten',
        'uni_app_lbl'        => 'Hochschulen für angewandte Wissenschaften (FH)',
        'types_sec_title'    => 'Staatlich & Privat bearbeiten',
        'types_title_lbl'    => 'Abschnittstitel',
        'type_pub_lbl'       => 'Staatliche Studienkollegs',
        'type_priv_lbl'      => 'Private Studienkollegs',
        'notes_sec_title'    => 'Wichtige Hinweise bearbeiten',
        'notes_title_lbl'    => 'Hinweistitel',
        'note_item_lbl'      => 'Hinweis',
        'add_note_btn'       => 'Hinweis hinzufügen',
        'exam_sec_title'     => 'Aufnahmeprüfung & FSP bearbeiten',
        'exam_title_lbl'     => 'Titel: Aufnahmeprüfung',
        'exam_desc_lbl'      => 'Beschreibung: Aufnahmeprüfung',
        'fsp_title_lbl'      => 'Titel: Abschlussprüfung (FSP)',
        'fsp_desc_lbl'       => 'Beschreibung: FSP',
        'tips_sec_title'     => 'Tipps vor der Bewerbung bearbeiten',
        'tips_title_lbl'     => 'Abschnittstitel',
        'tip_item_lbl'       => 'Tipp',
        'add_tip_btn'        => 'Tipp hinzufügen',
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
        'hero_pos_lbl'       => 'Image Position (e.g. center -20rem)',
        'main_sec_title'     => 'Edit Main Title & Description',
        'main_title_lbl'     => 'Main Title',
        'main_desc_lbl'      => 'Main Description',
        'goals_sec_title'    => 'Edit Course Objectives',
        'goals_title_lbl'    => 'Section Title',
        'goal_item_lbl'      => 'Objective',
        'add_goal_btn'       => 'Add Objective',
        'learn_sec_title'    => 'Edit Learning Content',
        'learn_title_lbl'    => 'Section Title',
        'learn_intro_lbl'    => 'Intro Text',
        'learn_p1_lbl'       => 'Paragraph 1',
        'learn_p2_lbl'       => 'Paragraph 2',
        'courses_sec_title'  => 'Edit Course Types',
        'courses_title_lbl'  => 'Section Title',
        'course_item_lbl'    => 'Course Type',
        'add_course_btn'     => 'Add Course Type',
        'uni_sec_title'      => 'Edit University Relationship',
        'uni_title_lbl'      => 'Section Title',
        'uni_intro_lbl'      => 'Intro Text',
        'uni_pub_lbl'        => 'Universities',
        'uni_app_lbl'        => 'Universities of Applied Sciences',
        'types_sec_title'    => 'Edit Public & Private Types',
        'types_title_lbl'    => 'Section Title',
        'type_pub_lbl'       => 'Public Studienkollegs',
        'type_priv_lbl'      => 'Private Studienkollegs',
        'notes_sec_title'    => 'Edit Important Notes',
        'notes_title_lbl'    => 'Notes Title',
        'note_item_lbl'      => 'Note',
        'add_note_btn'       => 'Add Note',
        'exam_sec_title'     => 'Edit Entrance Exam & FSP',
        'exam_title_lbl'     => 'Entrance Exam Title',
        'exam_desc_lbl'      => 'Entrance Exam Description',
        'fsp_title_lbl'      => 'Assessment Test (FSP) Title',
        'fsp_desc_lbl'       => 'Assessment Test (FSP) Description',
        'tips_sec_title'     => 'Edit Tips Before Applying',
        'tips_title_lbl'     => 'Section Title',
        'tip_item_lbl'       => 'Tip',
        'add_tip_btn'        => 'Add Tip',
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
        'hero_pos_lbl'       => 'تموضع الصورة (مثال: center -20rem)',
        'main_sec_title'     => 'تعديل العنوان الرئيسي والوصف',
        'main_title_lbl'     => 'العنوان الرئيسي',
        'main_desc_lbl'      => 'الوصف الرئيسي',
        'goals_sec_title'    => 'تعديل أهداف الدورة التأسيسية',
        'goals_title_lbl'    => 'عنوان قسم الأهداف',
        'goal_item_lbl'      => 'الهدف',
        'add_goal_btn'       => 'إضافة هدف جديد',
        'learn_sec_title'    => 'تعديل المحتوى الدراسي (ماذا يدرس ويتعلم الطالب)',
        'learn_title_lbl'    => 'عنوان القسم',
        'learn_intro_lbl'    => 'المقدمة',
        'learn_p1_lbl'       => 'الفقرة الأولى',
        'learn_p2_lbl'       => 'الفقرة الثانية',
        'courses_sec_title'  => 'تعديل أنواع دورات السنة التحضيرية',
        'courses_title_lbl'  => 'عنوان القسم',
        'course_item_lbl'    => 'نوع الدورة',
        'add_course_btn'     => 'إضافة نوع دورة جديد',
        'uni_sec_title'      => 'تعديل علاقة الدورة بالجامعات',
        'uni_title_lbl'      => 'عنوان القسم',
        'uni_intro_lbl'      => 'المقدمة',
        'uni_pub_lbl'        => 'الجامعات (Universitäten)',
        'uni_app_lbl'        => 'الجامعات التطبيقية (Hochschulen / FH)',
        'types_sec_title'    => 'تعديل أنواع السنة التحضيرية (حكومية / خاصة)',
        'types_title_lbl'    => 'عنوان القسم',
        'type_pub_lbl'       => 'السنة التحضيرية الحكومية',
        'type_priv_lbl'      => 'السنة التحضيرية الخاصة',
        'notes_sec_title'    => 'تعديل الملاحظات الهامة',
        'notes_title_lbl'    => 'عنوان الملاحظات',
        'note_item_lbl'      => 'الملاحظة',
        'add_note_btn'       => 'إضافة ملاحظة جديدة',
        'exam_sec_title'     => 'تعديل اختبار القبول والتقييم النهائي (FSP)',
        'exam_title_lbl'     => 'عنوان اختبار القبول',
        'exam_desc_lbl'      => 'وصف اختبار القبول',
        'fsp_title_lbl'      => 'عنوان امتحان التقييم (FSP)',
        'fsp_desc_lbl'       => 'وصف امتحان التقييم (FSP)',
        'tips_sec_title'     => 'تعديل نصائح مهمة قبل التقديم',
        'tips_title_lbl'     => 'عنوان القسم',
        'tip_item_lbl'       => 'النصيحة',
        'add_tip_btn'        => 'إضافة نصيحة جديدة',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawFoundationSetting = static function (string $pageKey) use ($current_lang): array {
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

$curStkData = $fetchRawFoundationSetting('foundation_page');

$modalBcUrl         = (string)($curStkData['page_breadcrumb_url'] ?? ($stk_data['page_breadcrumb_url'] ?? '#'));
$modalBcText        = (string)($curStkData['page_breadcrumb'] ?? ($stk_data['page_breadcrumb'] ?? ''));
$modalHeroImg       = (string)($curStkData['hero_img'] ?? ($stk_data['hero_img'] ?? ''));
$modalHeroPos       = (string)($curStkData['hero_position'] ?? ($stk_data['hero_position'] ?? 'center -20rem'));
$modalMainTitle     = (string)($curStkData['main_title'] ?? ($stk_data['main_title'] ?? ''));
$modalMainDesc      = (string)($curStkData['main_desc'] ?? ($stk_data['main_desc'] ?? ''));
$modalGoalsTitle    = (string)($curStkData['goals_title'] ?? ($stk_data['goals_title'] ?? ''));
$modalGoalsArr      = is_array($curStkData['goals_items'] ?? null) ? $curStkData['goals_items'] : ($stk_data['goals_items'] ?? []);
$modalLearnTitle    = (string)($curStkData['learning_title'] ?? ($stk_data['learning_title'] ?? ''));
$modalLearnIntro    = (string)($curStkData['learning_intro'] ?? ($stk_data['learning_intro'] ?? ''));
$modalLearnP1       = (string)($curStkData['learning_p1'] ?? ($stk_data['learning_p1'] ?? ''));
$modalLearnP2       = (string)($curStkData['learning_p2'] ?? ($stk_data['learning_p2'] ?? ''));
$modalCoursesTitle  = (string)($curStkData['courses_title'] ?? ($stk_data['courses_title'] ?? ''));
$modalCoursesArr    = is_array($curStkData['courses_items'] ?? null) ? $curStkData['courses_items'] : ($stk_data['courses_items'] ?? []);
$modalUniTitle      = (string)($curStkData['uni_type_title'] ?? ($stk_data['uni_type_title'] ?? ''));
$modalUniIntro      = (string)($curStkData['uni_type_intro'] ?? ($stk_data['uni_type_intro'] ?? ''));
$modalUniPublic     = (string)($curStkData['uni_public'] ?? ($stk_data['uni_public'] ?? ''));
$modalUniApplied    = (string)($curStkData['uni_applied'] ?? ($stk_data['uni_applied'] ?? ''));
$modalTypesTitle    = (string)($curStkData['types_title'] ?? ($stk_data['types_title'] ?? ''));
$modalTypePubDesc   = (string)($curStkData['type_public_desc'] ?? ($stk_data['type_public_desc'] ?? ''));
$modalTypePrivDesc  = (string)($curStkData['type_private_desc'] ?? ($stk_data['type_private_desc'] ?? ''));
$modalNotesTitle    = (string)($curStkData['notes_title'] ?? ($stk_data['notes_title'] ?? ''));
$modalNotesArr      = is_array($curStkData['notes_items'] ?? null) ? $curStkData['notes_items'] : ($stk_data['notes_items'] ?? []);
$modalExamTitle     = (string)($curStkData['exam_title'] ?? ($stk_data['exam_title'] ?? ''));
$modalExamDesc      = (string)($curStkData['exam_desc'] ?? ($stk_data['exam_desc'] ?? ''));
$modalFspTitle      = (string)($curStkData['fsp_title'] ?? ($stk_data['fsp_title'] ?? ''));
$modalFspDesc       = (string)($curStkData['fsp_desc'] ?? ($stk_data['fsp_desc'] ?? ''));
$modalTipsTitle     = (string)($curStkData['tips_title'] ?? ($stk_data['tips_title'] ?? ''));
$modalTipsArr       = is_array($curStkData['tips_items'] ?? null) ? $curStkData['tips_items'] : ($stk_data['tips_items'] ?? []);
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="stkBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="stkBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="stkHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="stkHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_stk_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="stk_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="stk_hero_img" accept="image/*">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_pos_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][hero_position]" value="<?php echo htmlspecialchars($modalHeroPos, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="stkMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="stkMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][main_desc]" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Goals Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="stkGoalsModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="stkGoalsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_goals">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['goals_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][goals_title]" value="<?php echo htmlspecialchars($modalGoalsTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="stkGoalsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalGoalsArr) ? $modalGoalsArr : []) as $index => $goalText): ?>
                            <div class="p-3 shadow-sm stk-goal-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_goal_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['goal_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control stk-goal-input" name="stk_data[<?php echo $current_lang; ?>][goals_items][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$goalText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('stk_goal_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addStkGoalBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_goal_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkGoalsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Learning Content Edit Modal -->
<div class="modal fade custom-modal" id="stkLearningModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-book-half text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['learn_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkLearningForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_learning">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['learn_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][learning_title]" value="<?php echo htmlspecialchars($modalLearnTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['learn_intro_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][learning_intro]" value="<?php echo htmlspecialchars($modalLearnIntro, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['learn_p1_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][learning_p1]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalLearnP1, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['learn_p2_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][learning_p2]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalLearnP2, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkLearningForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Course Types Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="stkCoursesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid-3x3-gap text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['courses_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkCoursesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_courses">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['courses_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][courses_title]" value="<?php echo htmlspecialchars($modalCoursesTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="stkCoursesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalCoursesArr) ? $modalCoursesArr : []) as $index => $courseText): ?>
                            <div class="p-3 shadow-sm stk-course-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_course_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['course_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control stk-course-input" name="stk_data[<?php echo $current_lang; ?>][courses_items][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$courseText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('stk_course_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addStkCourseBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_course_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkCoursesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 7. University Relationship Edit Modal -->
<div class="modal fade custom-modal" id="stkUniTypeModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-mortarboard text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['uni_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkUniTypeForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_uni_type">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uni_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][uni_type_title]" value="<?php echo htmlspecialchars($modalUniTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uni_intro_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][uni_type_intro]" value="<?php echo htmlspecialchars($modalUniIntro, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uni_pub_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][uni_public]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalUniPublic, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uni_app_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][uni_applied]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalUniApplied, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkUniTypeForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 8. Public & Private Types Edit Modal -->
<div class="modal fade custom-modal" id="stkTypesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-building text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['types_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkTypesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_types">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['types_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][types_title]" value="<?php echo htmlspecialchars($modalTypesTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['type_pub_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][type_public_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalTypePubDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['type_priv_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][type_private_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalTypePrivDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkTypesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 9. Important Notes Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="stkNotesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-star-fill text-warning <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['notes_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkNotesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_notes">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][notes_title]" value="<?php echo htmlspecialchars($modalNotesTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="stkNotesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalNotesArr) ? $modalNotesArr : []) as $index => $noteText): ?>
                            <div class="p-3 shadow-sm stk-note-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_note_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['note_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control stk-note-input" name="stk_data[<?php echo $current_lang; ?>][notes_items][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$noteText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('stk_note_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addStkNoteBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_note_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkNotesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 10. Entrance Exam & FSP Edit Modal -->
<div class="modal fade custom-modal" id="stkExamFspModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard-check text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['exam_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkExamFspForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_exam_fsp">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['exam_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][exam_title]" value="<?php echo htmlspecialchars($modalExamTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-4 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['exam_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][exam_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalExamDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['fsp_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][fsp_title]" value="<?php echo htmlspecialchars($modalFspTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['fsp_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="stk_data[<?php echo $current_lang; ?>][fsp_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalFspDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkExamFspForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 11. Tips Before Applying Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="stkTipsModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-lightbulb-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['tips_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="stkTipsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_stk_tips">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['tips_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="stk_data[<?php echo $current_lang; ?>][tips_title]" value="<?php echo htmlspecialchars($modalTipsTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="stkTipsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalTipsArr) ? $modalTipsArr : []) as $index => $tipText): ?>
                            <div class="p-3 shadow-sm stk-tip-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="stk_tip_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['tip_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control stk-tip-input" name="stk_data[<?php echo $current_lang; ?>][tips_items][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$tipText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('stk_tip_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addStkTipBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_tip_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="stkTipsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const STK_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const STK_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const STK_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = STK_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = STK_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = STK_MODAL_TXT.warning_title;
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

function appendDynamicSimpleRow(containerId, rowClass, rowPrefix, labelText, nameBuilder) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.' + rowClass).length;

    const div = document.createElement('div');
    div.className = `p-3 shadow-sm ${rowClass} mb-3`;
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = rowPrefix + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${labelText}</label>
                <input type="text" class="form-control stk-dyn-input" name="${nameBuilder(0)}" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${STK_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addStkGoalBtn')?.addEventListener('click', () => {
        appendDynamicSimpleRow('stkGoalsContainer', 'stk-goal-row-item', 'stk_goal_row_', STK_MODAL_TXT.goal_item_lbl, (i) => `stk_data[${STK_ACTIVE_LANG}][goals_items][${i}]`);
    });
    document.getElementById('addStkCourseBtn')?.addEventListener('click', () => {
        appendDynamicSimpleRow('stkCoursesContainer', 'stk-course-row-item', 'stk_course_row_', STK_MODAL_TXT.course_item_lbl, (i) => `stk_data[${STK_ACTIVE_LANG}][courses_items][${i}]`);
    });
    document.getElementById('addStkNoteBtn')?.addEventListener('click', () => {
        appendDynamicSimpleRow('stkNotesContainer', 'stk-note-row-item', 'stk_note_row_', STK_MODAL_TXT.note_item_lbl, (i) => `stk_data[${STK_ACTIVE_LANG}][notes_items][${i}]`);
    });
    document.getElementById('addStkTipBtn')?.addEventListener('click', () => {
        appendDynamicSimpleRow('stkTipsContainer', 'stk-tip-row-item', 'stk_tip_row_', STK_MODAL_TXT.tip_item_lbl, (i) => `stk_data[${STK_ACTIVE_LANG}][tips_items][${i}]`);
    });

    const stkFormIds = new Set([
        'stkBreadcrumbForm',
        'stkHeroForm',
        'stkMainForm',
        'stkGoalsForm',
        'stkLearningForm',
        'stkCoursesForm',
        'stkUniTypeForm',
        'stkTypesForm',
        'stkNotesForm',
        'stkExamFspForm',
        'stkTipsForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !stkFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || STK_ACTIVE_LANG;

        const listConfigs = [
            { cls: '.stk-goal-row-item',   sel: '.stk-goal-input',   field: 'goals_items' },
            { cls: '.stk-course-row-item', sel: '.stk-course-input', field: 'courses_items' },
            { cls: '.stk-note-row-item',   sel: '.stk-note-input',   field: 'notes_items' },
            { cls: '.stk-tip-row-item',    sel: '.stk-tip-input',    field: 'tips_items' },
        ];

        listConfigs.forEach((cfg) => {
            form.querySelectorAll(cfg.cls).forEach((row, index) => {
                const input = row.querySelector(cfg.sel);
                if (input) {
                    input.name = `stk_data[${targetLang}][${cfg.field}][${index}]`;
                }
            });
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || STK_CSRF_TOKEN;

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
                showNotification(STK_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(STK_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(STK_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(STK_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
