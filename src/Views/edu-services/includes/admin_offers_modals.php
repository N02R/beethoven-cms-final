<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Offers & Agreements Admin Modals (Gold Standard Security, Multilingual & AJAX)
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}

$supported_langs = ['de', 'en', 'ar'];
$rawLang = (string)($current_lang ?? ($_SESSION['site_lang'] ?? ($_GET['lang'] ?? 'de')));
$current_lang = in_array(strtolower(trim($rawLang)), $supported_langs, true)
    ? strtolower(trim($rawLang))
    : 'de';

$is_rtl      = ($current_lang === 'ar');
$modal_dir   = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

$lang_file = is_file(__DIR__ . '/../../../Lang/' . $current_lang . '.php')
    ? __DIR__ . '/../../../Lang/' . $current_lang . '.php'
    : __DIR__ . '/../../../lang/' . $current_lang . '.php';
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
        'hero_pos_lbl'       => 'Bildposition (z.B. center center)',
        'main_sec_title'     => 'Haupttitel & Beschreibung bearbeiten',
        'main_title_lbl'     => 'Haupttitel',
        'main_desc_lbl'      => 'Beschreibung',
        'notes_sec_title'    => 'Wichtige Hinweise bearbeiten',
        'notes_title_lbl'    => 'Abschnittstitel',
        'note_item_lbl'      => 'Hinweistext',
        'add_note_btn'       => 'Hinweis hinzufügen',
        'cards_sec_title'    => 'Pakete & Vereinbarungen bearbeiten',
        'card_title_lbl'     => 'Kartentitel',
        'card_type_lbl'      => 'Dateityp',
        'card_sub_lbl'       => 'Untertitel',
        'card_file_lbl'      => 'Dateipfad / URL',
        'card_active_lbl'    => 'Hervorgehobene Karte (Aktiv)',
        'add_card_btn'       => 'Karte hinzufügen',
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
        'hero_pos_lbl'       => 'Image Position (e.g. center center)',
        'main_sec_title'     => 'Edit Main Title & Description',
        'main_title_lbl'     => 'Main Title',
        'main_desc_lbl'      => 'Description',
        'notes_sec_title'    => 'Edit Important Notes',
        'notes_title_lbl'    => 'Section Title',
        'note_item_lbl'      => 'Note Text',
        'add_note_btn'       => 'Add Note',
        'cards_sec_title'    => 'Edit Packages & Agreements',
        'card_title_lbl'     => 'Card Title',
        'card_type_lbl'      => 'File Type',
        'card_sub_lbl'       => 'Subtitle',
        'card_file_lbl'      => 'File Path / URL',
        'card_active_lbl'    => 'Highlighted Card (Active)',
        'add_card_btn'       => 'Add Card',
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
        'hero_pos_lbl'       => 'تموضع الصورة (مثال: center center)',
        'main_sec_title'     => 'تعديل العنوان والوصف الرئيسي',
        'main_title_lbl'     => 'العنوان الرئيسي',
        'main_desc_lbl'      => 'الوصف',
        'notes_sec_title'    => 'تعديل الملاحظات الهامة',
        'notes_title_lbl'    => 'عنوان القسم',
        'note_item_lbl'      => 'نص الملاحظة',
        'add_note_btn'       => 'إضافة ملاحظة جديدة',
        'cards_sec_title'    => 'تعديل الحزم والاتفاقيات',
        'card_title_lbl'     => 'عنوان البطاقة',
        'card_type_lbl'      => 'نوع الملف',
        'card_sub_lbl'       => 'العنوان الفرعي',
        'card_file_lbl'      => 'مسار الملف أو الرابط',
        'card_active_lbl'    => 'بطاقة مميزة (Active)',
        'add_card_btn'       => 'إضافة بطاقة جديدة',
    ],
];
$mTxt = $modalI18n[$current_lang];

$fetchRawOffersSetting = static function (string $pageKey) use ($current_lang): array {
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

    return (isset($pageRawData[$current_lang]) && is_array($pageRawData[$current_lang]))
        ? $pageRawData[$current_lang]
        : [];
};

$curOffersData = $fetchRawOffersSetting('offers_page');
if (empty($curOffersData) && is_array($offers_data ?? null)) {
    $curOffersData = $offers_data;
}

$modalBcUrl     = (string)($curOffersData['page_breadcrumb_url'] ?? '#');
$modalBcText    = (string)($curOffersData['page_breadcrumb'] ?? '');
$modalHeroImg   = (string)($curOffersData['hero_img'] ?? '');
$modalHeroPos   = (string)($curOffersData['hero_position'] ?? 'center center');
$modalMainTitle = (string)($curOffersData['main_title'] ?? '');
$modalMainDesc  = (string)($curOffersData['main_desc'] ?? '');
$modalNoteTitle = (string)($curOffersData['note_title'] ?? '');
$modalNotesArr  = is_array($curOffersData['notes_list'] ?? null)
    ? $curOffersData['notes_list']
    : (!empty($curOffersData['note_text']) ? [(string)$curOffersData['note_text']] : []);
$modalCardsArr  = is_array($curOffersData['download_cards'] ?? null) ? $curOffersData['download_cards'] : [];
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="offersBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="offersBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_offers_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="offers_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="offers_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Hero Image Edit Modal -->
<div class="modal fade custom-modal" id="offersHeroModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="offersHeroForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_offers_hero">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="offers_data[<?php echo $current_lang; ?>][old_hero_img]" value="<?php echo htmlspecialchars($modalHeroImg, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <?php if ($modalHeroImg !== ''): ?>
                            <div class="mb-4 text-center p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <img src="<?php echo htmlspecialchars(get_image_url($modalHeroImg), ENT_QUOTES, 'UTF-8'); ?>" style="max-height: 120px; object-fit: contain; border-radius: 8px;" alt="Hero Preview">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="file" class="form-control" name="offers_hero_img" accept="image/*">
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['hero_pos_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="offers_data[<?php echo $current_lang; ?>][hero_position]" value="<?php echo htmlspecialchars($modalHeroPos, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersHeroForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Main Title & Description Edit Modal -->
<div class="modal fade custom-modal" id="offersMainModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="offersMainForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_offers_main">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="offers_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['main_desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="offers_data[<?php echo $current_lang; ?>][main_desc]" rows="5" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersMainForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Important Notes Edit Modal (صفوف ديناميكية) -->
<div class="modal fade custom-modal" id="offersNotesModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="offersNotesForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_offers_notes">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['notes_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="offers_data[<?php echo $current_lang; ?>][note_title]" value="<?php echo htmlspecialchars($modalNoteTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                    </div>

                    <div id="offersNotesContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalNotesArr as $index => $noteText): ?>
                            <div class="p-3 shadow-sm offers-note-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="offers_note_row_<?php echo $index; ?>">
                                <div class="row g-2 align-items-end">
                                    <div class="col-11 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['note_item_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control offers-note-input" name="offers_data[<?php echo $current_lang; ?>][notes_list][<?php echo $index; ?>]" value="<?php echo htmlspecialchars((string)$noteText, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-1 text-center pb-1">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('offers_note_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" id="addOffersNoteBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_note_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersNotesForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Download Cards Edit Modal (صفوف ديناميكية مريحة) -->
<div class="modal fade custom-modal" id="offersCardsModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-grid-3x3-gap text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['cards_sec_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="offersCardsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_offers_cards">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="offersCardsContainer" class="d-flex flex-column gap-4">
                        <?php foreach ($modalCardsArr as $index => $cardItem): 
                            if (!is_array($cardItem)) continue;
                            $cardType   = strtolower((string)($cardItem['type'] ?? 'pdf'));
                            $cardActive = !empty($cardItem['active']);
                        ?>
                            <div class="p-4 shadow-sm offers-card-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="offers_card_row_<?php echo $index; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#<?php echo $index + 1; ?></span>
                                    <button type="button" class="btn-icon-trash" onclick="removeRow('offers_card_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars($mTxt['delete_item'], ENT_QUOTES, 'UTF-8'); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['card_title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control offers-card-title" name="offers_data[<?php echo $current_lang; ?>][download_cards][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars((string)($cardItem['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['card_type_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <select class="form-select offers-card-type" name="offers_data[<?php echo $current_lang; ?>][download_cards][<?php echo $index; ?>][type]">
                                            <option value="pdf" <?php echo ($cardType === 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                            <option value="word" <?php echo ($cardType === 'word' || $cardType === 'docx') ? 'selected' : ''; ?>>Word</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['card_sub_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control offers-card-sub" name="offers_data[<?php echo $current_lang; ?>][download_cards][<?php echo $index; ?>][sub]" value="<?php echo htmlspecialchars((string)($cardItem['sub'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="form-label fw-semibold small text-secondary mb-1"><?php echo htmlspecialchars($mTxt['card_file_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control offers-card-file" name="offers_data[<?php echo $current_lang; ?>][download_cards][<?php echo $index; ?>][file]" value="<?php echo htmlspecialchars((string)($cardItem['file'] ?? '#'), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input offers-card-active" type="checkbox" role="switch" id="offers_active_switch_<?php echo $index; ?>" name="offers_data[<?php echo $current_lang; ?>][download_cards][<?php echo $index; ?>][active]" value="1" <?php echo $cardActive ? 'checked' : ''; ?>>
                                            <label class="form-check-label fw-semibold small text-secondary" for="offers_active_switch_<?php echo $index; ?>"><?php echo htmlspecialchars($mTxt['card_active_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-4 py-3" id="addOffersCardBtn" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars($mTxt['add_card_btn'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="offersCardsForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic JS Engine (Gold Standard AJAX & Dynamic Rows) -->
<script>
const OFFERS_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const OFFERS_ACTIVE_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const OFFERS_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

function removeRow(id) {
    const el = document.getElementById(id);
    if (el) el.remove();
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = OFFERS_MODAL_TXT.success_title;

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = OFFERS_MODAL_TXT.error_title;
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = OFFERS_MODAL_TXT.warning_title;
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

function addOffersNoteRow() {
    const container = document.getElementById('offersNotesContainer');
    if (!container) return;
    const rowUid = Date.now() + '_' + container.querySelectorAll('.offers-note-row-item').length;

    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm offers-note-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'offers_note_row_' + rowUid;
    div.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-11">
                <label class="form-label fw-semibold small text-secondary">${OFFERS_MODAL_TXT.note_item_lbl}</label>
                <input type="text" class="form-control offers-note-input" name="offers_data[${OFFERS_ACTIVE_LANG}][notes_list][0]" value="" required>
            </div>
            <div class="col-1 text-center pb-1">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${OFFERS_MODAL_TXT.delete_item}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
}

function addOffersCardRow() {
    const container = document.getElementById('offersCardsContainer');
    if (!container) return;
    const itemIndex = container.querySelectorAll('.offers-card-row-item').length + 1;
    const rowUid = Date.now() + '_' + itemIndex;

    const div = document.createElement('div');
    div.className = 'p-4 shadow-sm offers-card-row-item';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'offers_card_row_' + rowUid;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">#${itemIndex}</span>
            <button type="button" class="btn-icon-trash" onclick="removeRow('${div.id}')" title="${OFFERS_MODAL_TXT.delete_item}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold small text-secondary mb-1">${OFFERS_MODAL_TXT.card_title_lbl}</label>
                <input type="text" class="form-control offers-card-title" name="offers_data[${OFFERS_ACTIVE_LANG}][download_cards][0][title]" value="" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-secondary mb-1">${OFFERS_MODAL_TXT.card_type_lbl}</label>
                <select class="form-select offers-card-type" name="offers_data[${OFFERS_ACTIVE_LANG}][download_cards][0][type]">
                    <option value="pdf" selected>PDF</option>
                    <option value="word">Word</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary mb-1">${OFFERS_MODAL_TXT.card_sub_lbl}</label>
                <input type="text" class="form-control offers-card-sub" name="offers_data[${OFFERS_ACTIVE_LANG}][download_cards][0][sub]" value="">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold small text-secondary mb-1">${OFFERS_MODAL_TXT.card_file_lbl}</label>
                <input type="text" class="form-control offers-card-file" name="offers_data[${OFFERS_ACTIVE_LANG}][download_cards][0][file]" value="#">
            </div>
            <div class="col-12">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input offers-card-active" type="checkbox" role="switch" id="offers_active_switch_${rowUid}" name="offers_data[${OFFERS_ACTIVE_LANG}][download_cards][0][active]" value="1">
                    <label class="form-check-label fw-semibold small text-secondary" for="offers_active_switch_${rowUid}">${OFFERS_MODAL_TXT.card_active_lbl}</label>
                </div>
            </div>
        </div>`;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addOffersNoteBtn')?.addEventListener('click', addOffersNoteRow);
    document.getElementById('addOffersCardBtn')?.addEventListener('click', addOffersCardRow);

    const offersFormIds = new Set([
        'offersBreadcrumbForm',
        'offersHeroForm',
        'offersMainForm',
        'offersNotesForm',
        'offersCardsForm'
    ]);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !offersFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const targetLang = form.querySelector('input[name="targetLang"]')?.value || OFFERS_ACTIVE_LANG;

        form.querySelectorAll('.offers-note-row-item').forEach((row, index) => {
            const input = row.querySelector('.offers-note-input');
            if (input) {
                input.name = `offers_data[${targetLang}][notes_list][${index}]`;
            }
        });

        form.querySelectorAll('.offers-card-row-item').forEach((row, index) => {
            const titleInput  = row.querySelector('.offers-card-title');
            const subInput    = row.querySelector('.offers-card-sub');
            const typeSelect  = row.querySelector('.offers-card-type');
            const fileInput   = row.querySelector('.offers-card-file');
            const activeCheck = row.querySelector('.offers-card-active');
            if (titleInput)  titleInput.name  = `offers_data[${targetLang}][download_cards][${index}][title]`;
            if (subInput)    subInput.name    = `offers_data[${targetLang}][download_cards][${index}][sub]`;
            if (typeSelect)  typeSelect.name  = `offers_data[${targetLang}][download_cards][${index}][type]`;
            if (fileInput)   fileInput.name   = `offers_data[${targetLang}][download_cards][${index}][file]`;
            if (activeCheck) activeCheck.name = `offers_data[${targetLang}][download_cards][${index}][active]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || OFFERS_CSRF_TOKEN;

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
                showNotification(OFFERS_MODAL_TXT.invalid_resp, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(OFFERS_MODAL_TXT.save_success, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification(OFFERS_MODAL_TXT.save_error + (data.message || ''), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(OFFERS_MODAL_TXT.network_error, 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
