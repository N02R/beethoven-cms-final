<?php
declare(strict_types=1);

/**
 * Beethoven CMS - CV Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'advice_sec_title'   => 'Tipps bearbeiten',
        'advice_title_lbl'   => 'Abschnittstitel',
        'advice_item_lbl'    => 'Tipp',
        'add_advice_btn'     => 'Tipp hinzufügen',
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
        'advice_sec_title'   => 'Edit Tips',
        'advice_title_lbl'   => 'Section Title',
        'advice_item_lbl'    => 'Tip',
        'add_advice_btn'     => 'Add Tip',
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
        'bc_title'           => 'تعديل مسار التنقل',
        'bc_name_lbl'        => 'اسم الصفحة في المسار',
        'bc_url_lbl'         => 'رابط الصفحة (URL)',
        'hero_title'         => 'تعديل صورة الهيرو الرئيسية',
        'hero_file_lbl'      => 'رفع صورة جديدة',
        'main_sec_title'     => 'تعديل العنوان والوصف الرئيسي',
        'main_title_lbl'     => 'العنوان الرئيسي',
        'main_desc_lbl'      => 'الوصف',
        'advice_sec_title'   => 'تعديل النصائح السريعة',
        'advice_title_lbl'   => 'عنوان القسم',
        'advice_item_lbl'    => 'النصيحة',
        'add_advice_btn'     => 'إضافة نصيحة جديدة',
        'dl_sec_title'       => 'تعديل ملفات وعناصر التحميل',
        'dl_title_lbl'       => 'عنوان الملف',
        'dl_sub_lbl'         => 'النص الفرعي (مثال: نموذج)',
        'dl_type_lbl'        => 'نوع الملف',
        'dl_file_lbl'        => 'مسار الملف أو الرابط',
        'add_dl_btn'         => 'إضافة عنصر تحميل جديد',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawCvSetting = static function (string $pageKey) use ($current_lang): array {
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

$curCvData = $fetchRawCvSetting('cv_page');

$modalBcUrl       = (string)($curCvData['page_breadcrumb_url'] ?? ($cv_data['page_breadcrumb_url'] ?? '#'));
$modalBcText      = (string)($curCvData['page_breadcrumb'] ?? ($cv_data['page_breadcrumb'] ?? ''));
$modalHeroImg     = (string)($curCvData['hero_img'] ?? ($cv_data['hero_img'] ?? ''));
$modalMainTitle   = (string)($curCvData['main_title'] ?? ($cv_data['main_title'] ?? ''));
$modalMainDesc    = (string)($curCvData['main_desc'] ?? ($cv_data['main_desc'] ?? ''));
$modalAdviceTitle = (string)($curCvData['advice_title'] ?? ($cv_data['advice_title'] ?? ''));
$modalAdviceArr   = is_array($curCvData['advice_points'] ?? null) ? $curCvData['advice_points'] : ($cv_data['advice_points'] ?? []);
$modalDownloadArr = is_array($curCvData['download_items'] ?? null) ? $curCvData['download_items'] : ($cv_data['download_items'] ?? []);
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="cvBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="cvBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cv_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cv_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cv_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="cvHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="cvHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_cv_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="cv_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="cv_hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="cvMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="cvMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cv_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cv_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="cv_data[<?php echo $current_lang; ?>][main_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Advice Points Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="cvAdviceModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-check2-all text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['advice_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="cvAdviceForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cv_advice">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['advice_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="cv_data[<?php echo $current_lang; ?>][advice_title]" value="<?php echo htmlspecialchars($modalAdviceTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="cvAdviceContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalAdviceArr) ? $modalAdviceArr : []) as $index => $pointText): ?>
                            <div class="p-3 shadow-sm cv-advice-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cv_advice_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['advice_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cv-advice-input" name="cv_data[<?php echo $current_lang; ?>][advice_points][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$pointText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('cv_advice_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addCvAdviceBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_advice_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvAdviceForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Items Edit Modal (صفوف ديناميكية مريحة ومرتبة) -->
<div class="modal fade custom-modal" id="cvDownloadModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="cvDownloadForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_cv_download">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="cvDownloadContainer" class="d-flex flex-column gap-4">
                        <?php foreach ((is_array($modalDownloadArr) ? $modalDownloadArr : []) as $index => $dlItem): 
                            if (!is_array($dlItem)) continue;
                            $itemType = strtolower((string)($dlItem['type'] ?? 'pdf'));
                        ?>
                            <div class="p-4 shadow-sm cv-dl-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="cv_dl_row_<?php echo $index; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#<?php echo $index + 1; ?></span>
                                    <button type="button" class="btn-icon-trash" onclick="removeRow('cv_dl_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['dl_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cv-dl-title" name="cv_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars((string)($dlItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['dl_type_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <select class="form-select cv-dl-type" name="cv_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][type]">
                                            <option value="pdf" <?php echo ($itemType === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                            <option value="word" <?php echo ($itemType !== 'pdf') ? 'selected' : ''; ?>>Word</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['dl_sub_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cv-dl-sub" name="cv_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][sub]" value="<?php echo htmlspecialchars((string)($dlItem['sub'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-7 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['dl_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control cv-dl-file" name="cv_data[<?php echo $current_lang; ?>][download_items][<?php echo $index; ?>][file]" value="<?php echo htmlspecialchars((string)($dlItem['file'] ?? '#'), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-4 py-3" id="addCvDownloadBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_dl_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="cvDownloadForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const CV_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const CV_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const CV_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = CV_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = CV_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = CV_MODAL_TXT.warning_title;
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

function addCvAdviceRow() {
    const container = document.getElementById('cvAdviceContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.cv-advice-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm cv-advice-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'cv_advice_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${CV_MODAL_TXT.advice_item_lbl}</label>
                <input type="text" class="form-control cv-advice-input" name="cv_data[${CV_ACTIVE_LANG}][advice_points][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${CV_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addCvDownloadRow() {
    const container = document.getElementById('cvDownloadContainer');
    if (!container) return;
    const itemIndex = container.querySelectorAll('.cv-dl-row-item').length + 1;
    const rowUid = Date.now() + '_' + itemIndex;

    const div = document.createElement('div');
    div.className = 'p-4 shadow-sm cv-dl-row-item';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'cv_dl_row_' + rowUid;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#${itemIndex}</span>
            <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="${CV_MODAL_TXT.delete_item}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold small text-secondary mb-1">${CV_MODAL_TXT.dl_title_lbl}</label>
                <input type="text" class="form-control cv-dl-title" name="cv_data[${CV_ACTIVE_LANG}][download_items][0][title]" value="" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-secondary mb-1">${CV_MODAL_TXT.dl_type_lbl}</label>
                <select class="form-select cv-dl-type" name="cv_data[${CV_ACTIVE_LANG}][download_items][0][type]">
                    <option value="pdf" selected>PDF</option>
                    <option value="word">Word</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-secondary mb-1">${CV_MODAL_TXT.dl_sub_lbl}</label>
                <input type="text" class="form-control cv-dl-sub" name="cv_data[${CV_ACTIVE_LANG}][download_items][0][sub]" value="">
            </div>
            <div class="col-md-7">
                <label class="form-label fw-semibold small text-secondary mb-1">${CV_MODAL_TXT.dl_file_lbl}</label>
                <input type="text" class="form-control cv-dl-file" name="cv_data[${CV_ACTIVE_LANG}][download_items][0][file]" value="#">
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addCvAdviceBtn')?.addEventListener('click', addCvAdviceRow);
    document.getElementById('addCvDownloadBtn')?.addEventListener('click', addCvDownloadRow);

    const cvFormIds = new Set([
        'cvBreadcrumbForm',
        'cvHeroForm',
        'cvMainForm',
        'cvAdviceForm',
        'cvDownloadForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !cvFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || CV_ACTIVE_LANG;

        // 1. إعادة ترقيم صفوف النصائح
        form.querySelectorAll('.cv-advice-row-item').forEach((row, index) => {
            const input = row.querySelector('.cv-advice-input');
            if (input) {
                input.name = `cv_data[${targetLang}][advice_points][${index}]`;
            }
        });

        // 2. إعادة ترقيم صفوف عناصر التحميل
        form.querySelectorAll('.cv-dl-row-item').forEach((row, index) => {
            const titleInput = row.querySelector('.cv-dl-title');
            const subInput   = row.querySelector('.cv-dl-sub');
            const typeSelect = row.querySelector('.cv-dl-type');
            const fileInput  = row.querySelector('.cv-dl-file');
            if (titleInput) titleInput.name = `cv_data[${targetLang}][download_items][${index}][title]`;
            if (subInput)   subInput.name   = `cv_data[${targetLang}][download_items][${index}][sub]`;
            if (typeSelect) typeSelect.name = `cv_data[${targetLang}][download_items][${index}][type]`;
            if (fileInput)  fileInput.name  = `cv_data[${targetLang}][download_items][${index}][file]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || CV_CSRF_TOKEN;

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
                showNotification(CV_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(CV_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(CV_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(CV_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
