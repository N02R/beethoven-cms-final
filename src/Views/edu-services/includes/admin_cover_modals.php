<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Cover Letter Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'main_sec_title'     => 'Titel & Beschreibung bearbeiten',
        'main_title_lbl'     => 'Haupttitel',
        'main_desc_lbl'      => 'Beschreibung',
        'advice_sec_title'   => 'Punkte & Tipps bearbeiten',
        'advice_title_lbl'   => 'Abschnittstitel',
        'advice_item_lbl'    => 'Punkt / Tipp',
        'add_advice_btn'     => 'Punkt hinzufügen',
        'notes_sec_title'    => 'Wichtige Hinweise bearbeiten',
        'notes_title_lbl'    => 'Hinweistitel',
        'notes_item_lbl'     => 'Hinweis-Text',
        'add_note_btn'       => 'Hinweis hinzufügen',
        'dl_sec_title'       => 'Download-Elemente bearbeiten',
        'dl_title_lbl'       => 'Dateiname / Titel',
        'dl_sub_lbl'         => 'Untertitel (z.B. Beispiel)',
        'dl_type_lbl'        => 'Dateityp',
        'dl_file_lbl'        => 'Dateipfad / URL',
        'add_dl_btn'         => 'Download-Element hinzufügen',
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
        'main_sec_title'     => 'Edit Title & Description',
        'main_title_lbl'     => 'Main Title',
        'main_desc_lbl'      => 'Description',
        'advice_sec_title'   => 'Edit Points & Tips',
        'advice_title_lbl'   => 'Section Title',
        'advice_item_lbl'    => 'Point / Tip',
        'add_advice_btn'     => 'Add Point',
        'notes_sec_title'    => 'Edit Important Notes',
        'notes_title_lbl'    => 'Notes Title',
        'notes_item_lbl'     => 'Note Text',
        'add_note_btn'       => 'Add Note',
        'dl_sec_title'       => 'Edit Download Items',
        'dl_title_lbl'       => 'File Title',
        'dl_sub_lbl'         => 'Subtitle (e.g. Example)',
        'dl_type_lbl'        => 'File Type',
        'dl_file_lbl'        => 'File Path / URL',
        'add_dl_btn'         => 'Add Download Item',
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
        'main_desc_lbl'      => 'الوصف',
        'advice_sec_title'   => 'تعديل النقاط التي يجب مراعاتها',
        'advice_title_lbl'   => 'عنوان القسم',
        'advice_item_lbl'    => 'النقطة / النصيحة',
        'add_advice_btn'     => 'إضافة نقطة جديدة',
        'notes_sec_title'    => 'تعديل الملاحظات الهامة',
        'notes_title_lbl'    => 'عنوان الملاحظات',
        'notes_item_lbl'     => 'نص الملاحظة',
        'add_note_btn'       => 'إضافة ملاحظة جديدة',
        'dl_sec_title'       => 'تعديل ملفات وعناصر التحميل',
        'dl_title_lbl'       => 'عنوان الملف',
        'dl_sub_lbl'         => 'النص الفرعي (مثال: نموذج)',
        'dl_type_lbl'        => 'نوع الملف',
        'dl_file_lbl'        => 'مسار الملف أو الرابط',
        'add_dl_btn'         => 'إضافة عنصر تحميل جديد',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawCoverSetting = static function (string $pageKey) use ($current_lang): array {
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

$curCoverData = $fetchRawCoverSetting('coverletter_page');

$modalBcUrl        = (string)($curCoverData['page_breadcrumb_url'] ?? ($cover['page_breadcrumb_url'] ?? '#'));
$modalBcText       = (string)($curCoverData['page_breadcrumb'] ?? ($cover['page_breadcrumb'] ?? ''));
$modalHeroImg      = (string)($curCoverData['hero_img'] ?? ($cover['hero_img'] ?? ''));
$modalMainTitle    = (string)($curCoverData['main_title'] ?? ($cover['main_title'] ?? ''));
$modalMainDesc     = (string)($curCoverData['main_desc'] ?? ($cover['main_desc'] ?? ''));
$modalAdviceTitle  = (string)($curCoverData['advice_title'] ?? ($cover['advice_title'] ?? ''));
$modalAdviceArr    = is_array($curCoverData['advice_points'] ?? null) ? $curCoverData['advice_points'] : ($cover['advice_points'] ?? []);
$modalNoteTitle    = (string)($curCoverData['note_title'] ?? ($cover['note_title'] ?? ''));
$modalNotesArr     = is_array($curCoverData['notes'] ?? null) ? $curCoverData['notes'] : ($cover['notes'] ?? []);
$modalDownloadArr  = is_array($curCoverData['download_items'] ?? null) ? $curCoverData['download_items'] : ($cover['download_items'] ?? []);
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="coverBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="coverBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cover_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cover_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cover_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="coverHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="coverHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_cover_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="cover_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="cover_hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="coverMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="coverMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cover_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cover_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="cover_data[<?php echo $current_lang; ?>][main_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Points Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="coverAdviceModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-star-half text-warning <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['advice_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverAdviceForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cover_advice">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['advice_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cover_data[<?php echo $current_lang; ?>][advice_title]" value="<?php echo htmlspecialchars($modalAdviceTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="coverAdviceContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalAdviceArr) ? $modalAdviceArr : []) as $index => $pointText): ?>
                            <div class="p-3 shadow-sm cover-advice-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_advice_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['advice_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cover-advice-input" name="cover_data[<?php echo $current_lang; ?>][advice_points][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$pointText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cover_advice_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCoverAdviceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_advice_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverAdviceForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Important Notes Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="coverNotesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-circle text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['notes_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverNotesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cover_notes">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cover_data[<?php echo $current_lang; ?>][note_title]" value="<?php echo htmlspecialchars($modalNoteTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="coverNotesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalNotesArr) ? $modalNotesArr : []) as $index => $noteText): ?>
                            <div class="p-3 shadow-sm cover-note-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_note_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cover-note-input" name="cover_data[<?php echo $current_lang; ?>][notes][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$noteText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cover_note_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCoverNoteBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_note_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverNotesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Download Items Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="coverDownloadModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-download text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['dl_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="coverDownloadForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cover_download">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="coverDownloadContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalDownloadArr) ? $modalDownloadArr : []) as $index => $dlItem): 
                            if (!is_array($dlItem)) continue;
                            $itemType = strtolower((string)($dlItem['type'] ?? 'pdf'));
                        ?>
                            <div class="p-3 shadow-sm cover-dl-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cover_dl_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['dl_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cover-dl-title" name="cover_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars((string)($dlItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-3 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['dl_sub_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cover-dl-sub" name="cover_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][sub]" value="<?php echo htmlspecialchars((string)($dlItem['sub'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-2 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['dl_type_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <select class="form-select cover-dl-type" name="cover_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][type]">
                                            <option value="pdf" <?php echo ($itemType === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                            <option value="word" <?php echo ($itemType !== 'pdf') ? 'selected' : ''; ?>>Word</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['dl_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cover-dl-file" name="cover_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][file]" value="<?php echo htmlspecialchars((string)($dlItem['file'] ?? '#'), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cover_dl_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCoverDownloadBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_dl_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="coverDownloadForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const COVER_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const COVER_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const COVER_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = COVER_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = COVER_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = COVER_MODAL_TXT.warning_title;
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

function addCoverAdviceRow() {
    const container = document.getElementById('coverAdviceContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.cover-advice-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm cover-advice-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'cover_advice_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.advice_item_lbl}</label>
                <input type="text" class="form-control cover-advice-input" name="cover_data[${COVER_ACTIVE_LANG}][advice_points][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${COVER_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addCoverNoteRow() {
    const container = document.getElementById('coverNotesContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.cover-note-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm cover-note-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'cover_note_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.notes_item_lbl}</label>
                <input type="text" class="form-control cover-note-input" name="cover_data[${COVER_ACTIVE_LANG}][notes][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${COVER_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addCoverDownloadRow() {
    const container = document.getElementById('coverDownloadContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.cover-dl-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm cover-dl-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'cover_dl_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.dl_title_lbl}</label>
                <input type="text" class="form-control cover-dl-title" name="cover_data[${COVER_ACTIVE_LANG}][download_items][0][title]" value="" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.dl_sub_lbl}</label>
                <input type="text" class="form-control cover-dl-sub" name="cover_data[${COVER_ACTIVE_LANG}][download_items][0][sub]" value="">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.dl_type_lbl}</label>
                <select class="form-select cover-dl-type" name="cover_data[${COVER_ACTIVE_LANG}][download_items][0][type]">
                    <option value="pdf" selected>PDF</option>
                    <option value="word">Word</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-secondary">${COVER_MODAL_TXT.dl_file_lbl}</label>
                <input type="text" class="form-control cover-dl-file" name="cover_data[${COVER_ACTIVE_LANG}][download_items][0][file]" value="#">
            </div>
            <div class="col-md-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${COVER_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addCoverAdviceBtn')?.addEventListener('click', addCoverAdviceRow);
    document.getElementById('addCoverNoteBtn')?.addEventListener('click', addCoverNoteRow);
    document.getElementById('addCoverDownloadBtn')?.addEventListener('click', addCoverDownloadRow);

    const coverFormIds = new Set([
        'coverBreadcrumbForm',
        'coverHeroForm',
        'coverMainForm',
        'coverAdviceForm',
        'coverNotesForm',
        'coverDownloadForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !coverFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || COVER_ACTIVE_LANG;

        // 1. إعادة ترقيم صفوف النقاط
        form.querySelectorAll('.cover-advice-row-item').forEach((row, index) => {
            const input = row.querySelector('.cover-advice-input');
            if (input) {
                input.name = `cover_data[${targetLang}][advice_points][${index}]`;
            }
        });

        // 2. إعادة ترقيم صفوف الملاحظات
        form.querySelectorAll('.cover-note-row-item').forEach((row, index) => {
            const input = row.querySelector('.cover-note-input');
            if (input) {
                input.name = `cover_data[${targetLang}][notes][${index}]`;
            }
        });

        // 3. إعادة ترقيم صفوف عناصر التحميل
        form.querySelectorAll('.cover-dl-row-item').forEach((row, index) => {
            const titleInput = row.querySelector('.cover-dl-title');
            const subInput   = row.querySelector('.cover-dl-sub');
            const typeSelect = row.querySelector('.cover-dl-type');
            const fileInput  = row.querySelector('.cover-dl-file');
            if (titleInput) titleInput.name = `cover_data[${targetLang}][download_items][${index}][title]`;
            if (subInput)   subInput.name   = `cover_data[${targetLang}][download_items][${index}][sub]`;
            if (typeSelect) typeSelect.name = `cover_data[${targetLang}][download_items][${index}][type]`;
            if (fileInput)  fileInput.name  = `cover_data[${targetLang}][download_items][${index}][file]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || COVER_CSRF_TOKEN;

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
                showNotification(COVER_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(COVER_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(COVER_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(COVER_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
