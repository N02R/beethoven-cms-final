<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Financial Guarantees Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'main_sec_title'     => 'Haupttitel, Beschreibung & Bedeutung bearbeiten',
        'main_title_lbl'     => 'Haupttitel',
        'main_desc_lbl'      => 'Hauptbeschreibung',
        'imp_title_lbl'      => 'Titel: Bedeutung des Finanzierungsnachweises',
        'imp_desc_lbl'       => 'Beschreibung: Bedeutung',
        'opts_sec_title'     => 'Finanzierungsoptionen bearbeiten',
        'opts_title_lbl'     => 'Abschnittstitel',
        'opt_item_lbl'       => 'Option',
        'add_opt_btn'        => 'Option hinzufügen',
        'acc_sec_title'      => 'Sperrkonto-Punkte bearbeiten',
        'acc_title_lbl'      => 'Abschnittstitel',
        'acc_item_lbl'       => 'Punkt',
        'add_acc_btn'        => 'Punkt hinzufügen',
        'links_sec_title'    => 'Externe Links & Anbieter bearbeiten',
        'link_text_lbl'      => 'Anzeigetext',
        'link_url_lbl'       => 'Ziel-URL',
        'link_active_lbl'    => 'Hervorgehoben (Active)',
        'add_link_btn'       => 'Link hinzufügen',
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
        'main_sec_title'     => 'Edit Main Title, Description & Importance',
        'main_title_lbl'     => 'Main Title',
        'main_desc_lbl'      => 'Main Description',
        'imp_title_lbl'      => 'Importance Title',
        'imp_desc_lbl'       => 'Importance Description',
        'opts_sec_title'     => 'Edit Financial Guarantee Options',
        'opts_title_lbl'     => 'Section Title',
        'opt_item_lbl'       => 'Option',
        'add_opt_btn'        => 'Add Option',
        'acc_sec_title'      => 'Edit Blocked Account Points',
        'acc_title_lbl'      => 'Section Title',
        'acc_item_lbl'       => 'Point',
        'add_acc_btn'        => 'Add Point',
        'links_sec_title'    => 'Edit External Links & Providers',
        'link_text_lbl'      => 'Display Text',
        'link_url_lbl'       => 'Destination URL',
        'link_active_lbl'    => 'Highlighted (Active)',
        'add_link_btn'       => 'Add Link',
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
        'main_sec_title'     => 'تعديل العنوان والوصف الرئيسي والأهمية',
        'main_title_lbl'     => 'العنوان الرئيسي',
        'main_desc_lbl'      => 'الوصف الرئيسي',
        'imp_title_lbl'      => 'عنوان أهمية إثبات الضمان المالي',
        'imp_desc_lbl'       => 'وصف الأهمية',
        'opts_sec_title'     => 'تعديل خيارات الضمان المالي',
        'opts_title_lbl'     => 'عنوان القسم',
        'opt_item_lbl'       => 'الخيار',
        'add_opt_btn'        => 'إضافة خيار جديد',
        'acc_sec_title'      => 'تعديل نقاط الحساب البنكي المغلق',
        'acc_title_lbl'      => 'عنوان القسم',
        'acc_item_lbl'       => 'النقطة',
        'add_acc_btn'        => 'إضافة نقطة جديدة',
        'links_sec_title'    => 'تعديل الروابط الخارجية والشركات',
        'link_text_lbl'      => 'النص المعروض',
        'link_url_lbl'       => 'الرابط (URL)',
        'link_active_lbl'    => 'تمييز العنصر (Active)',
        'add_link_btn'       => 'إضافة رابط جديد',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawFinancialSetting = static function (string $pageKey) use ($current_lang): array {
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

$curFinancialData = $fetchRawFinancialSetting('financial_page');

$modalBcUrl       = (string)($curFinancialData['page_breadcrumb_url'] ?? ($financial_data['page_breadcrumb_url'] ?? '#'));
$modalBcText      = (string)($curFinancialData['page_breadcrumb'] ?? ($financial_data['page_breadcrumb'] ?? ''));
$modalHeroImg     = (string)($curFinancialData['hero_img'] ?? ($financial_data['hero_img'] ?? ''));
$modalMainTitle   = (string)($curFinancialData['main_title'] ?? ($financial_data['main_title'] ?? ''));
$modalMainDesc    = (string)($curFinancialData['main_desc'] ?? ($financial_data['main_desc'] ?? ''));
$modalImpTitle    = (string)($curFinancialData['importance_title'] ?? ($financial_data['importance_title'] ?? ''));
$modalImpDesc     = (string)($curFinancialData['importance_desc'] ?? ($financial_data['importance_desc'] ?? ''));
$modalOptsTitle   = (string)($curFinancialData['options_title'] ?? ($financial_data['options_title'] ?? ''));
$modalOptsArr     = is_array($curFinancialData['options_items'] ?? null) ? $curFinancialData['options_items'] : ($financial_data['options_items'] ?? []);
$modalAccTitle    = (string)($curFinancialData['account_title'] ?? ($financial_data['account_title'] ?? ''));
$modalAccArr      = is_array($curFinancialData['account_points'] ?? null) ? $curFinancialData['account_points'] : ($financial_data['account_points'] ?? []);
$modalLinksArr    = is_array($curFinancialData['service_links'] ?? null) ? $curFinancialData['service_links'] : ($financial_data['service_links'] ?? []);
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="blockedBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="financialBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_financial_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="blockedHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="financialHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_financial_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="financial_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="financial_hero_img" accept="image/*">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title, Description & Importance Edit Modal -->
<div class="modal fade custom-modal" id="blockedMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="financialMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_financial_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-4 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="financial_data[<?php echo $current_lang; ?>][main_desc]" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <hr class="my-3">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['imp_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][importance_title]" value="<?php echo htmlspecialchars($modalImpTitle, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['imp_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="financial_data[<?php echo $current_lang; ?>][importance_desc]" rows="4" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars($modalImpDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Financial Guarantee Options Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="blockedOptionsModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-check2-square text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['opts_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="financialOptionsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_financial_options">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['opts_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][options_title]" value="<?php echo htmlspecialchars($modalOptsTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="financialOptionsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalOptsArr) ? $modalOptsArr : []) as $index => $optText): ?>
                            <div class="p-3 shadow-sm financial-opt-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="fin_opt_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['opt_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control financial-opt-input" name="financial_data[<?php echo $current_lang; ?>][options_items][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$optText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('fin_opt_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addFinancialOptBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_opt_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialOptionsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Blocked Account Points Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="blockedAccountModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-bank text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['acc_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="financialAccountForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_financial_account">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['acc_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="financial_data[<?php echo $current_lang; ?>][account_title]" value="<?php echo htmlspecialchars($modalAccTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="financialAccountContainer" class="d-flex flex-column gap-3">
                        <?php foreach ((is_array($modalAccArr) ? $modalAccArr : []) as $index => $accPoint): ?>
                            <div class="p-3 shadow-sm financial-acc-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="fin_acc_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['acc_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control financial-acc-input" name="financial_data[<?php echo $current_lang; ?>][account_points][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$accPoint, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('fin_acc_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addFinancialAccBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_acc_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialAccountForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. External Links & Providers Edit Modal (صفوف ديناميكية مريحة) -->
<div class="modal fade custom-modal" id="blockedLinksModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="financialLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_financial_links">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="financialLinksContainer" class="d-flex flex-column gap-4">
                        <?php foreach ((is_array($modalLinksArr) ? $modalLinksArr : []) as $index => $linkItem): 
                            if (!is_array($linkItem)) continue;
                            $isActive = !empty($linkItem['active']);
                        ?>
                            <div class="p-4 shadow-sm financial-link-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="fin_link_row_<?php echo $index; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#<?php echo $index + 1; ?></span>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input financial-link-active" type="checkbox" name="financial_data[<?php echo $current_lang; ?>][service_links][<?php echo $index; ?>][active]" value="1" <?php echo $isActive ? 'checked' : ''; ?>>
                                            <label class="form-check-label small fw-semibold text-secondary"><?php echo htmlspecialchars($mTxt['link_active_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        </div>
                                        <button type="button" class="btn-icon-trash" onclick="removeRow('fin_link_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['link_text_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control financial-link-text" name="financial_data[<?php echo $current_lang; ?>][service_links][<?php echo $index; ?>][text]" value="<?php echo htmlspecialchars((string)($linkItem['text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['link_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control financial-link-url" name="financial_data[<?php echo $current_lang; ?>][service_links][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars((string)($linkItem['url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-4 py-3" id="addFinancialLinkBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_link_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="financialLinksForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const FINANCIAL_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const FINANCIAL_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const FINANCIAL_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = FINANCIAL_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = FINANCIAL_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = FINANCIAL_MODAL_TXT.warning_title;
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

function addFinancialOptRow() {
    const container = document.getElementById('financialOptionsContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.financial-opt-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm financial-opt-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'fin_opt_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${FINANCIAL_MODAL_TXT.opt_item_lbl}</label>
                <input type="text" class="form-control financial-opt-input" name="financial_data[${FINANCIAL_ACTIVE_LANG}][options_items][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${FINANCIAL_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addFinancialAccRow() {
    const container = document.getElementById('financialAccountContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.financial-acc-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm financial-acc-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'fin_acc_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${FINANCIAL_MODAL_TXT.acc_item_lbl}</label>
                <input type="text" class="form-control financial-acc-input" name="financial_data[${FINANCIAL_ACTIVE_LANG}][account_points][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${FINANCIAL_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addFinancialLinkRow() {
    const container = document.getElementById('financialLinksContainer');
    if (!container) return;
    const itemIndex = container.querySelectorAll('.financial-link-row-item').length + 1;
    const rowUid = Date.now() + '_' + itemIndex;

    const div = document.createElement('div');
    div.className = 'p-4 shadow-sm financial-link-row-item';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'fin_link_row_' + rowUid;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#${itemIndex}</span>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check form-switch m-0">
                    <input class="form-check-input financial-link-active" type="checkbox" name="financial_data[${FINANCIAL_ACTIVE_LANG}][service_links][0][active]" value="1">
                    <label class="form-check-label small fw-semibold text-secondary">${FINANCIAL_MODAL_TXT.link_active_lbl}</label>
                </div>
                <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="${FINANCIAL_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold small text-secondary mb-1">${FINANCIAL_MODAL_TXT.link_text_lbl}</label>
                <input type="text" class="form-control financial-link-text" name="financial_data[${FINANCIAL_ACTIVE_LANG}][service_links][0][text]" value="" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-secondary mb-1">${FINANCIAL_MODAL_TXT.link_url_lbl}</label>
                <input type="text" class="form-control financial-link-url" name="financial_data[${FINANCIAL_ACTIVE_LANG}][service_links][0][url]" value="#">
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addFinancialOptBtn')?.addEventListener('click', addFinancialOptRow);
    document.getElementById('addFinancialAccBtn')?.addEventListener('click', addFinancialAccRow);
    document.getElementById('addFinancialLinkBtn')?.addEventListener('click', addFinancialLinkRow);

    const financialFormIds = new Set([
        'financialBreadcrumbForm',
        'financialHeroForm',
        'financialMainForm',
        'financialOptionsForm',
        'financialAccountForm',
        'financialLinksForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !financialFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || FINANCIAL_ACTIVE_LANG;

        // 1. إعادة ترقيم خيارات الضمان المالي
        form.querySelectorAll('.financial-opt-row-item').forEach((row, index) => {
            const input = row.querySelector('.financial-opt-input');
            if (input) {
                input.name = `financial_data[${targetLang}][options_items][${index}]`;
            }
        });

        // 2. إعادة ترقيم نقاط الحساب المغلق
        form.querySelectorAll('.financial-acc-row-item').forEach((row, index) => {
            const input = row.querySelector('.financial-acc-input');
            if (input) {
                input.name = `financial_data[${targetLang}][account_points][${index}]`;
            }
        });

        // 3. إعادة ترقيم الروابط الخارجية
        form.querySelectorAll('.financial-link-row-item').forEach((row, index) => {
            const textInput   = row.querySelector('.financial-link-text');
            const urlInput    = row.querySelector('.financial-link-url');
            const activeInput = row.querySelector('.financial-link-active');
            if (textInput)   textInput.name   = `financial_data[${targetLang}][service_links][${index}][text]`;
            if (urlInput)    urlInput.name    = `financial_data[${targetLang}][service_links][${index}][url]`;
            if (activeInput) activeInput.name = `financial_data[${targetLang}][service_links][${index}][active]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || FINANCIAL_CSRF_TOKEN;

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
                showNotification(FINANCIAL_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(FINANCIAL_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(FINANCIAL_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(FINANCIAL_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
