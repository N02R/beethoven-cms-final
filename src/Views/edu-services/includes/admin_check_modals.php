<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Check Certificate Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'notes_sec_title'    => 'Wichtige Hinweise bearbeiten',
        'notes_title_lbl'    => 'Hinweistitel',
        'notes_item_lbl'     => 'Hinweis-Text',
        'add_note_btn'       => 'Hinweis hinzufügen',
        'links_sec_title'    => 'Links & Anweisungen bearbeiten',
        'links_intro_lbl'    => 'Einleitungstext zu Links',
        'anabin_lbl'         => 'Anabin URL',
        'uniassist_lbl'      => 'Uni-Assist URL',
        'uni_contact_lbl'    => 'Universitätskontakt Einleitung',
        'cond1_lbl'          => 'Bedingung 1',
        'cond2_lbl'          => 'Bedingung 2',
        'conclusion_lbl'     => 'Abschlusstext (HTML erlaubt)',
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
        'notes_sec_title'    => 'Edit Important Notes',
        'notes_title_lbl'    => 'Notes Title',
        'notes_item_lbl'     => 'Note Text',
        'add_note_btn'       => 'Add Note',
        'links_sec_title'    => 'Edit Links & Instructions',
        'links_intro_lbl'    => 'Links Intro Text',
        'anabin_lbl'         => 'Anabin URL',
        'uniassist_lbl'      => 'Uni-Assist URL',
        'uni_contact_lbl'    => 'University Contact Intro',
        'cond1_lbl'          => 'Condition 1',
        'cond2_lbl'          => 'Condition 2',
        'conclusion_lbl'     => 'Conclusion Text (HTML allowed)',
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
        'notes_sec_title'    => 'تعديل الملاحظات الهامة',
        'notes_title_lbl'    => 'عنوان الملاحظات',
        'notes_item_lbl'     => 'نص الملاحظة',
        'add_note_btn'       => 'إضافة ملاحظة جديدة',
        'links_sec_title'    => 'تعديل الروابط والفقرات التوضيحية',
        'links_intro_lbl'    => 'مقدمة الروابط',
        'anabin_lbl'         => 'رابط موقع Anabin',
        'uniassist_lbl'      => 'رابط موقع Uni-Assist',
        'uni_contact_lbl'    => 'مقدمة التواصل مع الجامعة',
        'cond1_lbl'          => 'الشرط الأول',
        'cond2_lbl'          => 'الشرط الثاني',
        'conclusion_lbl'     => 'النص الختامي (يدعم وسوم HTML)',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawCheckSetting = static function (string $pageKey) use ($current_lang): array {
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

$curCheckData = $fetchRawCheckSetting('check_page');

$modalBcUrl       = (string)($curCheckData['page_breadcrumb_url'] ?? ($check_data['page_breadcrumb_url'] ?? '#'));
$modalBcText      = (string)($curCheckData['page_breadcrumb'] ?? ($check_data['page_breadcrumb'] ?? ''));
$modalHeroImg     = (string)($curCheckData['hero_img'] ?? ($check_data['hero_img'] ?? ''));
$modalMainTitle   = (string)($curCheckData['main_title'] ?? ($check_data['main_title'] ?? ''));
$modalMainDesc    = (string)($curCheckData['main_desc'] ?? ($check_data['main_desc'] ?? ''));
$modalNoteTitle   = (string)($curCheckData['note_title'] ?? ($check_data['note_title'] ?? ''));
$modalNotesArr    = is_array($curCheckData['notes'] ?? null) ? $curCheckData['notes'] : ($check_data['notes'] ?? []);
$modalLinksIntro  = (string)($curCheckData['links_intro'] ?? ($check_data['links_intro'] ?? ''));
$modalAnabinUrl   = (string)($curCheckData['anabin_url'] ?? ($check_data['anabin_url'] ?? ''));
$modalUniassistUrl= (string)($curCheckData['uniassist_url'] ?? ($check_data['uniassist_url'] ?? ''));
$modalUniIntro    = (string)($curCheckData['uni_contact_intro'] ?? ($check_data['uni_contact_intro'] ?? ''));
$modalCond1       = (string)($curCheckData['condition_1'] ?? ($check_data['condition_1'] ?? ''));
$modalCond2       = (string)($curCheckData['condition_2'] ?? ($check_data['condition_2'] ?? ''));
$modalConclusion  = (string)($curCheckData['conclusion_text'] ?? ($check_data['conclusion_text'] ?? ''));
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="checkBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="checkBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_check_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="checkHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="checkHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_check_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="check_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="check_hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="checkMainContentModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="checkMainContentForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_check_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="check_data[<?php echo $current_lang; ?>][main_desc]" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkMainContentForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Important Notes Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="checkNotesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="checkNotesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_check_notes">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][note_title]" value="<?php echo htmlspecialchars($modalNoteTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="checkNotesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalNotesArr) ? $modalNotesArr : []) as $index => $noteText): ?>
                            <div class="p-3 shadow-sm check-note-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="check_note_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control check-note-input" name="check_data[<?php echo $current_lang; ?>][notes][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$noteText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('check_note_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCheckNoteBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_note_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkNotesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Links & Instructions Edit Modal -->
<div class="modal fade custom-modal" id="checkLinksModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-link-45deg text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['links_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="checkLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_check_links">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['links_intro_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="check_data[<?php echo $current_lang; ?>][links_intro]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalLinksIntro, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['anabin_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="url" class="form-control" name="check_data[<?php echo $current_lang; ?>][anabin_url]" value="<?php echo htmlspecialchars($modalAnabinUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uniassist_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="url" class="form-control" name="check_data[<?php echo $current_lang; ?>][uniassist_url]" value="<?php echo htmlspecialchars($modalUniassistUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['uni_contact_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="check_data[<?php echo $current_lang; ?>][uni_contact_intro]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalUniIntro, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['cond1_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][condition_1]" value="<?php echo htmlspecialchars($modalCond1, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['cond2_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="check_data[<?php echo $current_lang; ?>][condition_2]" value="<?php echo htmlspecialchars($modalCond2, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['conclusion_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="check_data[<?php echo $current_lang; ?>][conclusion_text]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalConclusion, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkLinksForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const CHECK_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const CHECK_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const CHECK_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = CHECK_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = CHECK_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = CHECK_MODAL_TXT.warning_title;
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

function addCheckNoteRow() {
    const container = document.getElementById('checkNotesContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.check-note-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm check-note-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'check_note_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${CHECK_MODAL_TXT.notes_item_lbl}</label>
                <input type="text" class="form-control check-note-input" name="check_data[${CHECK_ACTIVE_LANG}][notes][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${CHECK_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addCheckNoteBtn')?.addEventListener('click', addCheckNoteRow);

    const checkFormIds = new Set([
        'checkBreadcrumbForm',
        'checkHeroForm',
        'checkMainContentForm',
        'checkNotesForm',
        'checkLinksForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !checkFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || CHECK_ACTIVE_LANG;

        form.querySelectorAll('.check-note-row-item').forEach((row, index) => {
            const input = row.querySelector('.check-note-input');
            if (input) {
                input.name = `check_data[${targetLang}][notes][${index}]`;
            }
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || CHECK_CSRF_TOKEN;

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
                showNotification(CHECK_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(CHECK_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(CHECK_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(CHECK_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
