<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Header, Footer & Global Sections Admin Modals
 * (Gold Standard Security, Multilingual, Responsive & AJAX)
 */

if (!defined('ALLOWED_ACCESS')) {
    http_response_code(403);
    exit('Access Denied');
}
?>
<style>
    /* ==========================================================================
       1. التنسيق الأساسي للمودلات (Glassmorphism & Custom Styling)
       ========================================================================== */
    .custom-modal .modal-content { 
        border-radius: 24px; 
        border: none; 
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25); 
        background: #ffffff;
        overflow: hidden; 
    }
    .custom-modal .modal-header { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 22px 28px; 
        border-bottom: 1px solid #f1f5f9; 
        background: #ffffff;
    }
    .custom-modal .modal-title { 
        margin: 0; 
        display: flex; 
        align-items: center; 
        gap: .75rem; 
        font-weight: 700; 
        color: #0f172a; 
        font-size: 1.2rem;
    }
    .custom-modal .btn-close { 
        margin: 0; 
        flex-shrink: 0; 
    }
    
    .custom-modal .modal-body {
        background: #f8fafc;
        padding: 28px;
        padding-bottom: 24px !important;
        max-height: calc(100vh - 180px) !important;
        overflow-y: auto;
    }

    /* الحقول والإدخالات (Inputs) */
    .custom-modal .form-control, .custom-modal .form-select { 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        height: 46px; 
        padding: 0 16px; 
        transition: all 0.2s ease; 
        width: 100%; 
        background-color: #ffffff;
        font-size: 0.95rem;
    }
    .custom-modal .form-control:focus, .custom-modal .form-select:focus { 
        border-color: #3b82f6; 
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); 
        background-color: #ffffff;
    }

    /* تنسيق القوائم المنسدلة الموحدة في الإعلانات (RTL / LTR) */
    .custom-modal[dir="rtl"] .ad-dropdown-btn {
        direction: rtl;
        text-align: right;
        background-position: left 0.95rem center !important;
        padding-left: 2.5rem !important;
        padding-right: 1rem !important;
        border-color: #93c5fd;
        background-color: #eff6ff;
        color: #1e3a8a;
        font-weight: 600;
    }

    .custom-modal[dir="ltr"] .ad-dropdown-btn {
        direction: ltr;
        text-align: left;
        background-position: right 0.95rem center !important;
        padding-right: 2.5rem !important;
        padding-left: 1rem !important;
        border-color: #93c5fd;
        background-color: #eff6ff;
        color: #1e3a8a;
        font-weight: 600;
    }

    .custom-modal .ad-type-menu {
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%) !important;
        border: 1px solid #93c5fd !important;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.16) !important;
        padding: 6px !important;
        z-index: 1065 !important;
    }

    .custom-modal .ad-type-menu .dropdown-item {
        border-radius: 10px;
        color: #1e3a8a;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .custom-modal .ad-type-menu .dropdown-item:hover {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }

    .custom-modal .ad-type-menu .dropdown-item.active {
        background-color: #1d4ed8 !important;
        color: #ffffff !important;
    }
    
    /* رفع الملفات (File Upload) */
    .custom-modal input[type="file"].form-control { 
        padding: 9px 16px; 
        background: #ffffff; 
        cursor: pointer; 
        width: 100%; 
        height: auto;
    }

    /* تنسيق الفوتر الموحد */
    .custom-modal .modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        padding: 20px 28px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }

    .custom-modal .modal-footer button {
        flex: 1;
        height: 48px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
    }

    /* تنسيق زر الإلغاء */
    .btn-cancel {
        background-color: #f1f5f9;
        color: #475569;   
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .btn-cancel:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    /* تنسيق زر الحفظ */
    .btn-premium { 
        background: linear-gradient(135deg, #2563eb, #3b82f6); 
        color: white; 
        border: none; 
        transition: all 0.3s ease; 
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .btn-premium:hover {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    .btn-icon-trash { 
        width: 44px; 
        height: 44px; 
        border-radius: 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #fee2e2; 
        color: #dc2626; 
        border: none; 
        transition: all 0.2s ease;
    }
    .btn-icon-trash:hover { 
        background: #fecaca; 
        transform: translateY(-1px);
    }

    /* ==========================================================================
       2. قواعد التجاوب مع الشاشات (RESPONSIVE DESIGN: Mobile to 27" Desktop)
       ========================================================================== */
    @media (min-width: 1920px) {
        .custom-modal .modal-lg {
            max-width: 950px;
        }
        .custom-modal .modal-xl {
            max-width: 1300px;
        }
        .custom-modal .modal-header {
            padding: 28px 36px;
        }
        .custom-modal .modal-title {
            font-size: 1.4rem;
        }
        .custom-modal .modal-body {
            padding: 36px;
        }
        .custom-modal .form-control, .custom-modal .form-select {
            height: 52px;
            font-size: 1.05rem;
        }
    }

    @media (max-width: 768px) {
        .custom-modal .modal-dialog {
            margin: 10px;
            max-width: calc(100% - 20px);
        }
        .custom-modal .modal-header {
            padding: 16px 20px;
        }
        .custom-modal .modal-title {
            font-size: 1.05rem;
        }
        .custom-modal .modal-body {
            padding: 18px;
        }
        .custom-modal .modal-footer {
            padding: 15px 18px;
            flex-direction: column-reverse;
            gap: 10px;
        }
        .custom-modal .modal-footer button {
            width: 100%;
            height: 44px;
        }
        .social-row-item, .menu-row-item, .lang-row-item, 
        .service-row-item, .choose-row-item, .review-row-item, 
        .guide-row-item, .faq-row-item, .footer-col3-item {
            padding: 12px !important;
        }
    }

    @media (max-width: 480px) {
        .custom-modal .modal-content {
            border-radius: 16px;
        }
        .custom-modal .form-control, .custom-modal .form-select {
            height: 42px;
            font-size: 0.9rem;
        }
    }
</style>

<?php
if (!function_exists('safe_admin_string')) {
    function safe_admin_string(mixed $val, string $current_lang = 'de'): string {
        if (is_array($val)) {
            $chosen = $val[$current_lang] ?? ($val['de'] ?? ($val['en'] ?? ($val['ar'] ?? reset($val))));
            return is_scalar($chosen) ? (string)$chosen : '';
        }
        return is_scalar($val) ? (string)$val : '';
    }
}

$decodeAdminJson = static function (mixed $raw): array {
    if (is_array($raw)) {
        return $raw;
    }
    if (is_string($raw) && trim($raw) !== '') {
        $decoded = json_decode(trim($raw), true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    return [];
};

$supported_langs = ['de', 'en', 'ar'];
$rawModalLang = strtolower(trim((string)($current_lang ?? ($_SESSION['site_lang'] ?? 'de'))));
$current_lang = in_array($rawModalLang, $supported_langs, true) ? $rawModalLang : 'de';

$lang_file = __DIR__ . '/../../Lang/' . $current_lang . '.php';
if (!is_file($lang_file)) {
    $lang_file = __DIR__ . '/../../lang/' . $current_lang . '.php';
}
$ml = is_file($lang_file) ? require $lang_file : [];
if (!is_array($ml)) {
    $ml = [];
}

$is_rtl      = ($current_lang === 'ar');
$modal_dir   = $is_rtl ? 'rtl' : 'ltr';
$modal_align = $is_rtl ? 'text-end' : 'text-start';

$activeCsrfToken = (string)($csrf_token ?? ($_SESSION['csrf_token'] ?? \App\Core\Security::generateCsrfToken()));
$settingsPool = is_array($currentSettings ?? null) ? $currentSettings : (is_array($data ?? null) ? $data : []);
?>

<!-- 1. Social Links Modal -->
<div class="modal fade custom-modal" id="socialLinksEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-share-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_social_platforms'] ?? 'إدارة منصات التواصل'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="socialLinksForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_social">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="socialRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $socialLinksData = $decodeAdminJson($settingsPool['social_links'] ?? ($data['social_links'] ?? []));
                        foreach ($socialLinksData as $index => $link): 
                            if (!is_array($link)) continue;
                        ?>
                        <div class="p-3 shadow-sm social-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['platform_name'] ?? 'اسم المنصة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="text" class="form-control social-name" name="social[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($link['name'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars((string)($ml['name_placeholder'] ?? 'الاسم'), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-8 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['platform_url'] ?? 'رابط المنصة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="url" class="form-control social-url" name="social[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars((string)($ml['url_placeholder'] ?? 'https://...'), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-11 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['platform_icon_image'] ?? 'أيقونة / صورة المنصة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($link['img'])): ?>
                                            <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($link['img']) : $link['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Social Icon" class="rounded-2" style="width: 40px; height: 40px; object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control social-file" name="social_img_<?php echo $index; ?>" accept="image/*">
                                    </div>
                                </div>
                                <input type="hidden" class="social-old-img" name="social[<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($link['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="col-md-1 text-center pt-3">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_platform'] ?? 'حذف المنصة'), ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addSocialRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_platform'] ?? 'إضافة منصة جديدة'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="socialLinksForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Logo Modal -->
<div class="modal fade custom-modal" id="logoEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $logoModalI18n = [
                'de' => [
                    'title'        => 'Logotext verwalten',
                    'label'        => 'Logotext (DE):',
                    'placeholder'  => 'Beethoven Stadt Dienste',
                    'hint'         => 'Das feste Symbol wird automatisch neben diesem Text angezeigt.',
                    'save'         => 'Änderungen speichern',
                    'cancel'       => 'Abbrechen',
                    'default_logo' => 'Beethoven Stadt Dienste',
                ],
                'en' => [
                    'title'        => 'Manage Logo Text',
                    'label'        => 'Logo Text (EN):',
                    'placeholder'  => 'Beethoven City Services (BCS)',
                    'hint'         => 'The fixed icon will be displayed neatly next to this text automatically.',
                    'save'         => 'Save Changes',
                    'cancel'       => 'Cancel',
                    'default_logo' => 'Beethoven City Services (BCS)',
                ],
                'ar' => [
                    'title'        => 'إدارة نص الشعار',
                    'label'        => 'نص الشعار (AR):',
                    'placeholder'  => 'بيتهوفن سيتي للخدمات (BCS)',
                    'hint'         => 'سيتم عرض الأيقونة الثابتة بجانب هذا النص تلقائياً بتنسيق مرتب.',
                    'save'         => 'حفظ التغييرات',
                    'cancel'       => 'إلغاء',
                    'default_logo' => 'بيتهوفن سيتي للخدمات (BCS)',
                ],
            ];
            $lTxt = $logoModalI18n[$current_lang] ?? $logoModalI18n['de'];

            $logoTextRaw = $settingsPool['site_logo_text'] ?? ($data['site_logo_text'] ?? (function_exists('get_setting') ? get_setting('site_logo_text', '') : ''));
            $decodedLogoText = $decodeAdminJson($logoTextRaw);
            $modalLogoText = !empty($decodedLogoText)
                ? (string)($decodedLogoText[$current_lang] ?? '')
                : (is_string($logoTextRaw) ? trim($logoTextRaw) : '');

            if ($modalLogoText === '') {
                $modalLogoText = $lTxt['default_logo'];
            }

            $modalLogoIcon = (string)($site_logo_icon ?? 'assets/img/icon.png');
            ?>

            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-image text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_site_logo'] ?? $lTxt['title']), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="logoEditForm" class="admin-settings-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_logo">
                    <input type="hidden" name="logo_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="mb-4 text-center">
                        <div class="p-3 shadow-sm d-inline-flex align-items-center gap-2 bg-white rounded-4 border" dir="<?php echo $modal_dir; ?>">
                            <img src="<?php echo htmlspecialchars(get_image_url($modalLogoIcon, 'assets/img/icon.png'), ENT_QUOTES, 'UTF-8'); ?>" width="36" height="36" alt="Icon" style="object-fit: contain;">
                            <div class="<?php echo $modal_align; ?>" style="line-height: 1.15;">
                                <span class="d-block fw-bold text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($modalLogoText, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 <?php echo $modal_align; ?>" dir="<?php echo $modal_dir; ?>">
                        <label class="small fw-bold mb-2 text-secondary d-block">
                            <?php echo htmlspecialchars((string)($ml['logo_text_label'] ?? $lTxt['label']), ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                        <input type="text" class="form-control w-100" name="logo_text" value="<?php echo htmlspecialchars($modalLogoText, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars($lTxt['placeholder'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <div class="form-text small text-muted mt-1">
                            <?php echo htmlspecialchars((string)($ml['logo_text_hint'] ?? $lTxt['hint']), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>

                    <div id="logoAlertBox" class="alert d-none mt-2 rounded-3 border-0"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="logoEditForm" class="btn-premium" id="saveLogoBtn"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? $lTxt['save']), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? $lTxt['cancel']), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-modal[dir="rtl"] select.form-select {
        direction: rtl;
        text-align: right;
        background-position: left 0.95rem center !important;
        padding-left: 2.4rem !important;
        padding-right: 1rem !important;
    }
    .custom-modal[dir="ltr"] select.form-select {
        direction: ltr;
        text-align: left;
        background-position: right 0.95rem center !important;
        padding-right: 2.4rem !important;
        padding-left: 1rem !important;
    }
</style>

<style>
    .custom-modal[dir="rtl"] #adTypeDropdownBtn {
        direction: rtl;
        text-align: right;
        background-position: left 0.95rem center !important;
        padding-left: 2.5rem !important;
        padding-right: 1rem !important;
        border-color: #93c5fd;
        background-color: #eff6ff;
        color: #1e3a8a;
        font-weight: 600;
    }

    .custom-modal[dir="ltr"] #adTypeDropdownBtn {
        direction: ltr;
        text-align: left;
        background-position: right 0.95rem center !important;
        padding-right: 2.5rem !important;
        padding-left: 1rem !important;
        border-color: #93c5fd;
        background-color: #eff6ff;
        color: #1e3a8a;
        font-weight: 600;
    }

    .custom-modal .ad-type-menu {
        background: linear-gradient(180deg, #eff6ff 0%, #dbeafe 100%) !important;
        border: 1px solid #93c5fd !important;
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.16) !important;
        padding: 6px !important;
    }

    .custom-modal .ad-type-menu .dropdown-item {
        border-radius: 10px;
        color: #1e3a8a;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .custom-modal .ad-type-menu .dropdown-item:hover {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }

    .custom-modal .ad-type-menu .dropdown-item.active {
        background-color: #1d4ed8 !important;
        color: #ffffff !important;
    }
</style>

<!-- 3. Announcement Modal -->
<div class="modal fade custom-modal" id="announcementEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-megaphone-fill text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['announcement_settings'] ?? 'إعدادات لوحة الإعلانات'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="announcementEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="update_announcement">
                    <input type="hidden" name="announcement_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <?php 
                    $rawAdSetting = $settingsPool['announcement'] ?? ($data['announcement'] ?? '');
                    $decodedAds = $decodeAdminJson($rawAdSetting);
                    $currentAd = is_array($decodedAds[$current_lang] ?? null)
                        ? $decodedAds[$current_lang]
                        : (isset($decodedAds['status']) ? $decodedAds : []);

                    $sharedStartVal = (string)($decodedAds['shared_start_date'] ?? ($currentAd['start_date'] ?? ''));
                    $sharedEndVal   = (string)($decodedAds['shared_end_date'] ?? ($currentAd['end_date'] ?? ''));

                    $adStatusVal = safe_admin_string($currentAd['status'] ?? 'Draft', $current_lang);
                    $adStatusDraftLabel     = (string)($ml['status_draft'] ?? 'مخفي (مسودة)');
                    $adStatusPublishedLabel = (string)($ml['status_published'] ?? 'نشط (يظهر للزوار)');
                    $activeAdStatusLabel    = ($adStatusVal === 'Published') ? $adStatusPublishedLabel : $adStatusDraftLabel;

                    $adTypeVal = safe_admin_string($currentAd['type'] ?? 'text', $current_lang);
                    $adTypeTextLabel   = (string)($ml['ad_type_text'] ?? 'نص متحرك');
                    $adTypeImageLabel  = (string)($ml['ad_type_image'] ?? 'صورة (بانر دعائي)');
                    $activeAdTypeLabel = ($adTypeVal === 'image') ? $adTypeImageLabel : $adTypeTextLabel;
                    ?>
                    <input type="hidden" name="old_ad_image" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['image_path'] ?? 'assets/img/default-ad.png', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-3 mb-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary <?php echo $modal_align; ?>" style="font-size: 0.95rem;">
                            <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo htmlspecialchars((string)($ml['ad_status_timing'] ?? 'حالة الإعلان والتوقيت'), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['display_status'] ?? 'حالة العرض'), ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="hidden" name="status" id="adStatusInput" value="<?php echo htmlspecialchars($adStatusVal, ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="dropdown w-100" dir="<?php echo $modal_dir; ?>">
                                    <button class="form-select ad-dropdown-btn d-flex align-items-center justify-content-between w-100 <?php echo $modal_align; ?>"
                                            type="button"
                                            id="adStatusDropdownBtn"
                                            dir="<?php echo $modal_dir; ?>"
                                            data-bs-toggle="dropdown"
                                            data-bs-display="static"
                                            aria-expanded="false">
                                        <span id="adStatusDropdownText" class="flex-grow-1 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($activeAdStatusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </button>
                                    <ul class="dropdown-menu ad-type-menu w-100 rounded-3 mt-1 <?php echo $modal_align; ?>" dir="<?php echo $modal_dir; ?>" aria-labelledby="adStatusDropdownBtn">
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item py-2 <?php echo $modal_align; ?> <?php echo ($adStatusVal === 'Draft' ? 'active' : ''); ?>"
                                                    onclick="selectAdStatusOption('Draft', <?php echo htmlspecialchars(json_encode($adStatusDraftLabel, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>, this)">
                                                <?php echo htmlspecialchars($adStatusDraftLabel, ENT_QUOTES, 'UTF-8'); ?>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button"
                                                    class="dropdown-item py-2 <?php echo $modal_align; ?> <?php echo ($adStatusVal === 'Published' ? 'active' : ''); ?>"
                                                    onclick="selectAdStatusOption('Published', <?php echo htmlspecialchars(json_encode($adStatusPublishedLabel, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>, this)">
                                                <?php echo htmlspecialchars($adStatusPublishedLabel, ENT_QUOTES, 'UTF-8'); ?>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['start_date'] ?? 'تاريخ البدء'), ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="datetime-local" class="form-control" name="start_date" lang="en-GB" dir="ltr" value="<?php echo str_replace(' ', 'T', safe_admin_string($sharedStartVal, $current_lang)); ?>">
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['end_date'] ?? 'تاريخ الانتهاء'), ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="datetime-local" class="form-control" name="end_date" lang="en-GB" dir="ltr" value="<?php echo str_replace(' ', 'T', safe_admin_string($sharedEndVal, $current_lang)); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mb-4 shadow-sm <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-3 text-secondary" style="font-size: 0.95rem;">
                            <i class="bi bi-pencil-square text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo htmlspecialchars((string)($ml['announcement_content'] ?? 'محتوى الإعلان'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                        </div>
                        <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['ad_type'] ?? 'نوع الإعلان:'), ENT_QUOTES, 'UTF-8'); ?></label>
                        <input type="hidden" name="type" id="adTypeSelect" value="<?php echo htmlspecialchars($adTypeVal, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="dropdown mb-3 w-100" dir="<?php echo $modal_dir; ?>">
                            <button class="form-select ad-dropdown-btn d-flex align-items-center justify-content-between w-100 <?php echo $modal_align; ?>"
                                    type="button"
                                    id="adTypeDropdownBtn"
                                    dir="<?php echo $modal_dir; ?>"
                                    data-bs-toggle="dropdown"
                                    data-bs-display="static"
                                    aria-expanded="false">
                                <span id="adTypeDropdownText" class="flex-grow-1 <?php echo $modal_align; ?>"><?php echo htmlspecialchars($activeAdTypeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                            </button>
                            <ul class="dropdown-menu ad-type-menu w-100 rounded-3 mt-1 <?php echo $modal_align; ?>" dir="<?php echo $modal_dir; ?>" aria-labelledby="adTypeDropdownBtn">
                                <li>
                                    <button type="button"
                                            class="dropdown-item py-2 <?php echo $modal_align; ?> <?php echo ($adTypeVal === 'text' ? 'active' : ''); ?>"
                                            onclick="selectAdTypeOption('text', <?php echo htmlspecialchars(json_encode($adTypeTextLabel, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>, this)">
                                        <?php echo htmlspecialchars($adTypeTextLabel, ENT_QUOTES, 'UTF-8'); ?>
                                    </button>
                                </li>
                                <li>
                                    <button type="button"
                                            class="dropdown-item py-2 <?php echo $modal_align; ?> <?php echo ($adTypeVal === 'image' ? 'active' : ''); ?>"
                                            onclick="selectAdTypeOption('image', <?php echo htmlspecialchars(json_encode($adTypeImageLabel, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'); ?>, this)">
                                        <?php echo htmlspecialchars($adTypeImageLabel, ENT_QUOTES, 'UTF-8'); ?>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div id="textEditor" class="<?php echo ($adTypeVal === 'text' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['announcement_text_label'] ?? 'نص الإعلان:'), ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control mb-3" name="announcement_text" rows="2" style="height: auto;"><?php echo htmlspecialchars(safe_admin_string($currentAd['announcement_text'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['bg_color'] ?? 'لون الخلفية'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['bg_color'] ?? '#f1f5f9', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['text_color'] ?? 'لون الخط'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['text_color'] ?? '#1e293b', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" style="height: 46px;">
                                </div>
                                <div class="col-4">
                                    <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['font_size'] ?? 'حجم الخط'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="number" class="form-control" name="font_size" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['font_size'] ?? '16', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <div id="imageEditor" class="<?php echo ($adTypeVal === 'image' ? '' : 'd-none'); ?> <?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1"><?php echo htmlspecialchars((string)($ml['upload_ad_image'] ?? 'ارفع صورة الإعلان:'), ENT_QUOTES, 'UTF-8'); ?></label>
                            <?php if (!empty($currentAd['image_path']) && $currentAd['image_path'] !== 'assets/img/default-ad.png'): ?>
                                <div class="mb-2 p-2 bg-light rounded border d-inline-block">
                                    <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($currentAd['image_path']) : $currentAd['image_path'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Ad Preview" class="img-thumbnail border-0 bg-transparent" style="max-height: 80px; object-fit: contain;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="ad_image" style="height: auto; padding: 10px 16px;" accept="image/png, image/jpeg, image/webp">
                        </div>
                    </div>

                    <div class="p-3 shadow-sm <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="section-label fw-bold mb-2 text-secondary" style="font-size: 0.95rem;">
                            <i class="bi bi-link-45deg text-primary <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> 
                            <?php echo htmlspecialchars((string)($ml['redirect_link'] ?? 'رابط التوجيه (اختياري)'), ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <input type="url" class="form-control" name="link" value="<?php echo htmlspecialchars(safe_admin_string($currentAd['link'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="announcementEditForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Menu Edit Modal -->
<div class="modal fade custom-modal" id="menuEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-list-nested text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_main_menu'] ?? 'إدارة القائمة الرئيسية'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="menuLinksForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_menu">
                    <input type="hidden" name="menu_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="menuRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $menuLinksData = $decodeAdminJson($menu_links ?? ($settingsPool['menu_links'] ?? []));
                        foreach ($menuLinksData as $index => $link): 
                            if (!is_array($link)) continue;
                            $title_val = '';
                            if (isset($link[$current_lang]['title'])) {
                                $title_val = $link[$current_lang]['title'];
                            } elseif (isset($link['title'])) {
                                $title_val = is_array($link['title']) ? ($link['title'][$current_lang] ?? reset($link['title'])) : $link['title'];
                            } elseif (isset($link['de']['title'])) {
                                $title_val = $link['de']['title'];
                            }

                            $url_val   = $link['url'] ?? '';
                            $order_val = $link['order'] ?? $index;
                        ?>
                        <div class="p-3 shadow-sm menu-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="menu_row_<?php echo $index; ?>">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['link_title'] ?? 'عنوان الرابط'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                    <input type="text" class="form-control" name="menu[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($title_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars((string)($ml['title'] ?? 'العنوان'), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-5 <?php echo $modal_align; ?>">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['url'] ?? 'الرابط (URL)'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="text" class="form-control" name="menu[<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($url_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="home">
                                </div>
                                <div class="col-md-1 text-center pt-3">
                                    <input type="hidden" name="menu[<?php echo $index; ?>][order]" value="<?php echo (int)$order_val; ?>">
                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('menu_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete'] ?? 'حذف'), ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addMenuRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_link'] ?? 'إضافة رابط جديد'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="menuLinksForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 5. Lang Edit Modal -->
<div class="modal fade custom-modal" id="langEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-translate text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_languages'] ?? 'إدارة اللغات'), ENT_QUOTES, 'UTF-8'); ?>
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form id="langEditForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_languages">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div id="langRowsContainer" class="d-flex flex-column gap-3">
                        <?php 
                        $languagesData = $decodeAdminJson($settingsPool['languages'] ?? ($data['languages'] ?? []));
                        if (!empty($languagesData)): 
                            foreach ($languagesData as $index => $langItem): 
                                if (!is_array($langItem)) continue;
                                $code_val   = $langItem['code'] ?? '';
                                $name_val   = $langItem['name'] ?? '';
                                $active_val = isset($langItem['active']) ? (bool)$langItem['active'] : true;
                        ?>
                                <div class="p-3 shadow-sm lang-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="lang_row_<?php echo $index; ?>">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-4 <?php echo $modal_align; ?>">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['lang_code'] ?? 'رمز اللغة (Code)'), ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control lang-code" name="lang[<?php echo $index; ?>][code]" value="<?php echo htmlspecialchars(safe_admin_string($code_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="fr">
                                        </div>
                                        <div class="col-md-5 <?php echo $modal_align; ?>">
                                            <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['lang_name'] ?? 'اسم اللغة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="text" class="form-control lang-name" name="lang[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars(safe_admin_string($name_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="Français">
                                        </div>
                                        <div class="col-md-2 text-center pt-3">
                                            <label class="small fw-bold mb-1 text-secondary d-block"><?php echo htmlspecialchars((string)($ml['status'] ?? 'الحالة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                            <input type="hidden" class="lang-active-hidden" name="lang[<?php echo $index; ?>][active]" value="0">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input lang-active" type="checkbox" name="lang[<?php echo $index; ?>][active]" value="1" <?php echo $active_val ? 'checked' : ''; ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center pt-3">
                                            <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('lang_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_lang'] ?? 'حذف اللغة'), ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; 
                        endif; 
                        ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addLangRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_language'] ?? 'إضافة لغة جديدة'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="submit" form="langEditForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Hero Edit Modal -->
<div class="modal fade custom-modal" id="heroEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-gear text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['edit_hero_section'] ?? 'تعديل قسم البداية (Hero)'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="heroEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_hero">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="hero_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <?php 
                    $hero_all = $decodeAdminJson($settingsPool['hero'] ?? ($data['hero'] ?? []));
                    $h = is_array($hero_all[$current_lang] ?? null) ? $hero_all[$current_lang] : [];
                    
                    $val_title   = $h['title'] ?? ($hero_all['title'] ?? '');
                    $val_desc    = $h['desc'] ?? ($hero_all['desc'] ?? '');
                    $val_btn_txt = $h['btn_text'] ?? ($hero_all['btn_text'] ?? '');
                    $val_btn_url = $h['btn_url'] ?? ($hero_all['btn_url'] ?? '#');
                    
                    $current_img_val = (string)($h['img'] ?? ($hero_all['img'] ?? 'assets/img/hero-bg.jpg'));
                    ?>
                    
                    <input type="hidden" name="old_hero_img" value="<?php echo htmlspecialchars($current_img_val, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="p-4 shadow-sm" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars((string)($ml['hero_title'] ?? 'عنوان البداية'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($val_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars((string)($ml['hero_desc'] ?? 'النص الوصفي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <textarea class="form-control" name="hero[<?php echo $current_lang; ?>][desc]" rows="3" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($val_desc, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars((string)($ml['hero_btn_text'] ?? 'نص الزر'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars(safe_admin_string($val_btn_txt, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-md-6 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars((string)($ml['hero_btn_url'] ?? 'رابط الزر'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </label>
                                <input type="text" class="form-control" name="hero[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars(safe_admin_string($val_btn_url, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary">
                                    <?php echo htmlspecialchars((string)($ml['hero_bg_image'] ?? 'صورة الخلفية'), ENT_QUOTES, 'UTF-8'); ?>
                                </label>
                                
                                <?php if ($current_img_val !== '' && $current_img_val !== 'assets/img/hero-bg.jpg'): ?>
                                    <div class="mb-3 p-3 bg-light rounded-3 border text-center" style="border-color: #e2e8f0 !important;">
                                        <span class="d-block small text-muted mb-2"><?php echo htmlspecialchars((string)($ml['current_image'] ?? 'الصورة الحالية:'), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($current_img_val) : $current_img_val, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Current Hero Image" 
                                             class="img-thumbnail rounded-3 border-0 bg-transparent" 
                                             style="max-height: 120px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="form-control" name="hero_img" accept="image/*">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="heroEditForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 7. Services Edit Modal -->
<div class="modal fade custom-modal" id="servicesEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-briefcase text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['edit_services_section'] ?? 'تعديل قسم الخدمات'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $services_all = $decodeAdminJson($settingsPool['services'] ?? ($data['services'] ?? []));
                $s = is_array($services_all[$current_lang] ?? null) ? $services_all[$current_lang] : [];
                
                $sec_title     = $s['section_title'] ?? ($s['title'] ?? ($services_all['section_title'] ?? ''));
                $sec_subtitle  = $s['section_subtitle'] ?? ($s['desc'] ?? ($services_all['section_subtitle'] ?? ''));
                $service_items = $s['items'] ?? ($services_all['items'] ?? []);
                if (!is_array($service_items)) $service_items = [];
                ?>

                <form id="servicesEditForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_services">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="services_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['services_section_title'] ?? 'عنوان القسم الرئيسي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <input type="text" class="form-control" name="services[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars(safe_admin_string($sec_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['services_section_desc'] ?? 'العنوان الفرعي / الوصف'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <textarea class="form-control" name="services[<?php echo $current_lang; ?>][section_subtitle]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($sec_subtitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>"><?php echo htmlspecialchars((string)($ml['added_services'] ?? 'الخدمات المضافة'), ENT_QUOTES, 'UTF-8'); ?></h6>
                    <div id="servicesRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($service_items as $index => $item): 
                            if (!is_array($item)) continue;
                        ?>
                            <div class="p-3 shadow-sm service-row-item" id="service_row_<?php echo $index; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['service_title'] ?? 'عنوان الخدمة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control service-title" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($item['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['service_url'] ?? 'رابط الخدمة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control service-url" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($item['url'] ?? '#', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-11 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['image_or_icon'] ?? 'الصورة / الأيقونة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="file" class="form-control service-file" name="service_img_<?php echo $index; ?>" accept="image/*">
                                            <?php if (!empty($item['img'])): ?>
                                                <img src="<?php echo htmlspecialchars(function_exists('get_image_url') ? get_image_url($item['img']) : $item['img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Service Preview" class="rounded border" style="width: 38px; height: 38px; object-fit: cover; flex-shrink: 0;">
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <input type="hidden" class="service-old-img" name="services[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($item['img'] ?? ($item['old_img'] ?? ''), $current_lang), ENT_QUOTES, 'UTF-8'); ?>">

                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('service_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_service'] ?? 'حذف الخدمة'), ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addServiceRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_service'] ?? 'إضافة خدمة جديدة'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="servicesEditForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 8. Choose Edit Modal -->
<div class="modal fade custom-modal" id="chooseEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-star text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_choose'] ?? 'إدارة قسم لماذا نحن / المميزات'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $choose_data_modal = is_array($choose_section ?? null) ? $choose_section : [];
                if (empty($choose_data_modal)) {
                    $decodedChoose = $decodeAdminJson($settingsPool['choose'] ?? ($data['choose'] ?? []));
                    $choose_data_modal = is_array($decodedChoose[$current_lang] ?? null)
                        ? $decodedChoose[$current_lang]
                        : (is_array($decodedChoose['de'] ?? null) ? $decodedChoose['de'] : []);
                }
                
                $choose_sec_title    = $choose_data_modal['section_title'] ?? ($choose_data_modal['title'] ?? '');
                $choose_sec_subtitle = $choose_data_modal['section_subtitle'] ?? ($choose_data_modal['desc'] ?? '');
                $choose_itemsList    = is_array($choose_data_modal['items'] ?? null) ? $choose_data_modal['items'] : [];
                ?>

                <form id="chooseForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_choose">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="choose_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="row g-3">
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['section_main_title'] ?? 'عنوان القسم الرئيسي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <input type="text" class="form-control" name="choose[<?php echo $current_lang; ?>][section_title]" value="<?php echo htmlspecialchars(safe_admin_string($choose_sec_title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12 <?php echo $modal_align; ?>">
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['section_subtitle'] ?? 'الوصف الفرعي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)</label>
                                <textarea class="form-control" name="choose[<?php echo $current_lang; ?>][section_subtitle]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($choose_sec_subtitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>">
                        <?php echo htmlspecialchars((string)($ml['added_items'] ?? 'العناصر المضافة'), ENT_QUOTES, 'UTF-8'); ?>
                    </h6>
                    <div id="chooseRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($choose_itemsList as $index => $item): 
                            if (!is_array($item)) continue;
                            $itTitle = safe_admin_string($item['title'] ?? '', $current_lang);
                            $itDesc  = safe_admin_string($item['desc'] ?? '', $current_lang);
                            $itUrl   = safe_admin_string($item['url'] ?? '#', $current_lang);
                            $itImg   = safe_admin_string($item['img'] ?? '', $current_lang);
                        ?>
                            <div class="p-3 shadow-sm choose-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="choose_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['item_title'] ?? 'عنوان العنصر'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control choose-title" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars($itTitle, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['url'] ?? 'الرابط'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control choose-url" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($itUrl, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-12 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['description'] ?? 'الوصف'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control choose-desc" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars($itDesc, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-11 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['icon_or_image'] ?? 'الأيقونة / الصورة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($itImg !== ''): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 42px; height: 42px;">
                                                    <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($itImg) : $itImg, $current_lang), ENT_QUOTES, 'UTF-8'); ?>" alt="Choose Icon" class="rounded-2 w-100 h-100" style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control choose-file w-100" name="choose_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="choose-old-img" name="choose[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars($itImg, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('choose_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_item'] ?? 'حذف العنصر'), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addChooseRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_item'] ?? 'إضافة عنصر جديد'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="chooseForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 9. Reviews Edit Modal -->
<div class="modal fade custom-modal" id="reviewsEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-play-btn text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_customer_videos'] ?? 'إدارة فيديوهات العملاء'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $decodedReviews = $decodeAdminJson($settingsPool['reviews_items'] ?? ($data['reviews_items'] ?? []));
                $rModalData = is_array($decodedReviews[$current_lang] ?? null) ? $decodedReviews[$current_lang] : $decodedReviews;
                $curTitle   = $rModalData['title'] ?? '';
                $curVideos  = is_array($rModalData['items'] ?? null) ? $rModalData['items'] : [];
                ?>

                <form id="reviewsForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_reviews">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="reviews_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="<?php echo $modal_align; ?>">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars((string)($ml['section_main_title'] ?? 'عنوان القسم الرئيسي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <input type="text" class="form-control" name="reviews[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($curTitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 <?php echo $modal_align; ?>">
                        <?php echo htmlspecialchars((string)($ml['added_videos'] ?? 'الفيديوهات المضافة'), ENT_QUOTES, 'UTF-8'); ?>
                    </h6>
                    <div id="reviewsRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($curVideos as $index => $item): 
                            if (!is_array($item)) continue;
                            $videoUrl = safe_admin_string($item['url'] ?? '', $current_lang);
                        ?>
                            <div class="p-3 shadow-sm review-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="rev_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-11 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['review_video_url'] ?? 'رابط الفيديو (Embed URL)'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control review-url" name="reviews[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars($videoUrl, ENT_QUOTES, 'UTF-8'); ?>" placeholder="https://www.youtube.com/embed/...">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('rev_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_video'] ?? 'حذف الفيديو'), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addReviewRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_video'] ?? 'إضافة فيديو جديد'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reviewsForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 10. Guide Edit Modal -->
<div class="modal fade custom-modal" id="guideEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-book text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_guide'] ?? 'إدارة الدليل الشامل'), ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $guideAllLangs = $decodeAdminJson($settingsPool['guide'] ?? ($data['guide'] ?? []));
                $curLangGuideData = is_array($guideAllLangs[$current_lang] ?? null)
                    ? $guideAllLangs[$current_lang]
                    : (is_array($guideAllLangs['de'] ?? null) ? $guideAllLangs['de'] : []);

                $modalGuideTitle = $curLangGuideData['title'] ?? '';
                $modalGuideDesc  = $curLangGuideData['desc'] ?? '';
                $modalGuideBtn   = $curLangGuideData['read_more_btn'] ?? '';
                $modalGuideItems = is_array($curLangGuideData['items'] ?? null) ? $curLangGuideData['items'] : [];
                ?>
                
                <form id="guideForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_guide">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="guide_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4 <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars((string)($ml['main_section_title'] ?? 'عنوان القسم الرئيسي'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <input type="text" class="form-control" name="guide[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($modalGuideTitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars((string)($ml['section_desc'] ?? 'وصف القسم'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <textarea class="form-control" name="guide[<?php echo $current_lang; ?>][desc]" rows="2" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($modalGuideDesc, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div>
                            <label class="small fw-bold mb-1 text-secondary">
                                <?php echo htmlspecialchars((string)($ml['read_more'] ?? 'نص زر قراءة المزيد'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                            </label>
                            <input type="text" class="form-control" name="guide[<?php echo $current_lang; ?>][read_more_btn]" value="<?php echo htmlspecialchars(safe_admin_string($modalGuideBtn, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>

                    <div id="guideRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalGuideItems as $index => $item): 
                            if (!is_array($item)) continue;
                        ?>
                            <div class="p-3 shadow-sm guide-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="guide_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['article_title'] ?? 'عنوان المقال'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control guide-title" name="guide[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($item['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['page_url'] ?? 'رابط الصفحة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control guide-url" name="guide[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][url]" value="<?php echo htmlspecialchars(safe_admin_string($item['url'] ?? '#', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['description'] ?? 'الوصف'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control guide-desc" name="guide[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][desc]" value="<?php echo htmlspecialchars(safe_admin_string($item['desc'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['image'] ?? 'الصورة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($item['img'])): ?>
                                                <div class="p-1 bg-light rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0; width: 42px; height: 42px;">
                                                    <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($item['img']) : $item['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                         alt="Guide Item Image" 
                                                         class="rounded-2 w-100 h-100" 
                                                         style="object-fit: cover;">
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" class="form-control guide-file w-100" name="guide_img_<?php echo $index; ?>" accept="image/*">
                                        </div>
                                        <input type="hidden" class="guide-old-img" name="guide[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($item['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('guide_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_article'] ?? 'حذف المقال'), ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addGuideRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_article'] ?? 'إضافة مقال جديد'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="guideForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 11. FAQ Edit Modal -->
<div class="modal fade custom-modal" id="faqEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_faq'] ?? 'إدارة الأسئلة الشائعة'), ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $faqAllLangs = $decodeAdminJson($settingsPool['faq'] ?? ($data['faq'] ?? []));
                $curLangFaqData = is_array($faqAllLangs[$current_lang] ?? null)
                    ? $faqAllLangs[$current_lang]
                    : (is_array($faqAllLangs['de'] ?? null) ? $faqAllLangs['de'] : []);

                $modalFaqTitle = $curLangFaqData['title'] ?? ($data['faq_title'] ?? '');
                $modalFaqItems = is_array($curLangFaqData['items'] ?? null) ? $curLangFaqData['items'] : [];
                ?>
                
                <form id="faqForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_faq">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="faq_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-4 <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <label class="small fw-bold mb-1 text-secondary">
                            <?php echo htmlspecialchars((string)($ml['main_section_title'] ?? 'عنوان القسم'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                        </label>
                        <input type="text" class="form-control" name="faq[<?php echo $current_lang; ?>][title]" value="<?php echo htmlspecialchars(safe_admin_string($modalFaqTitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div id="faqRowsContainer" class="d-flex flex-column gap-3">
                        <?php foreach ($modalFaqItems as $index => $item): 
                            if (!is_array($item)) continue;
                        ?>
                            <div class="p-3 shadow-sm faq-row-item" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;" id="faq_row_<?php echo $index; ?>">
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-6 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['faq_question'] ?? 'السؤال'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control faq-question" name="faq[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][question]" value="<?php echo htmlspecialchars(safe_admin_string($item['question'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-5 <?php echo $modal_align; ?>">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['faq_answer'] ?? 'الإجابة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control faq-answer" name="faq[<?php echo $current_lang; ?>][items][<?php echo $index; ?>][answer]" value="<?php echo htmlspecialchars(safe_admin_string($item['answer'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-1 text-center pt-3">
                                        <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('faq_row_<?php echo $index; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_faq'] ?? 'حذف السؤال'), ENT_QUOTES, 'UTF-8'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" class="btn w-100 mt-3 py-3" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addFaqRow()" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                        <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_faq'] ?? 'إضافة سؤال جديد'), ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="faqForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 12. Footer Edit Modal -->
<div class="modal fade custom-modal" id="footerEditModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-layout-wtf text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars((string)($ml['manage_footer'] ?? 'إدارة الفوتر بالكامل'), ENT_QUOTES, 'UTF-8'); ?> 
                    (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <?php 
                $footerAllLangs = $decodeAdminJson($settingsPool['footer'] ?? ($data['footer'] ?? []));
                $curLangFooterData = is_array($footerAllLangs[$current_lang] ?? null)
                    ? $footerAllLangs[$current_lang]
                    : (is_array($footerAllLangs['de'] ?? null) ? $footerAllLangs['de'] : []);

                $modalConsultTitle = $curLangFooterData['consult_title'] ?? ($data['consult_title'] ?? '');
                $modalConsultDesc  = $curLangFooterData['consult_desc'] ?? ($data['consult_desc'] ?? '');
                $modalFooterDesc   = $curLangFooterData['footer_desc'] ?? ($data['footer_desc'] ?? '');
                $modalCol2Title    = $curLangFooterData['col2_title'] ?? ($data['footer_col2_title'] ?? '');
                $modalCol3Title    = $curLangFooterData['col3_title'] ?? ($data['footer_col3_title'] ?? '');
                ?>

                <form id="footerForm" class="admin-settings-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_footer">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="footer_lang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="p-4 shadow-sm <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold">
                                    <i class="bi bi-chat-dots <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['consult_section_title'] ?? 'إعدادات قسم الاستشارة'), ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['consult_title_label'] ?? 'عنوان الاستشارة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="footer[<?php echo $current_lang; ?>][consult_title]" value="<?php echo htmlspecialchars(safe_admin_string($modalConsultTitle, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['consult_desc_label'] ?? 'وصف الاستشارة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                        <input type="text" class="form-control" name="footer[<?php echo $current_lang; ?>][consult_desc]" value="<?php echo htmlspecialchars(safe_admin_string($modalConsultDesc, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100 <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><?php echo htmlspecialchars((string)($ml['column_one'] ?? 'العمود الأول'), ENT_QUOTES, 'UTF-8'); ?></h6>
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['footer_desc_label'] ?? 'وصف الفوتر:'), ENT_QUOTES, 'UTF-8'); ?></label>
                                <textarea class="form-control" name="footer[<?php echo $current_lang; ?>][footer_desc]" rows="6" style="height: auto; padding: 12px 16px;"><?php echo htmlspecialchars(safe_admin_string($modalFooterDesc, $current_lang), ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-4 shadow-sm h-100 <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><?php echo htmlspecialchars((string)($ml['column_two'] ?? 'العمود الثاني'), ENT_QUOTES, 'UTF-8'); ?></h6>
                                <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['column_two_title'] ?? 'عنوان العمود'), ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control mb-3" name="footer[<?php echo $current_lang; ?>][col2_title]" value="<?php echo htmlspecialchars(safe_admin_string($modalCol2Title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="p-3 bg-light rounded-3 text-muted small border" style="border-color: #e2e8f0 !important;">
                                    <i class="bi bi-info-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['menu_auto_notice'] ?? 'يتم جلب الروابط تلقائياً من القائمة الرئيسية (Menu).'), ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="p-4 shadow-sm <?php echo $modal_align; ?>" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <h6 class="text-primary mb-3 fw-bold"><?php echo htmlspecialchars((string)($ml['column_three'] ?? 'العمود الثالث (التواصل)'), ENT_QUOTES, 'UTF-8'); ?></h6>
                                <div class="mb-3" style="max-width: 400px;">
                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['column_three_title'] ?? 'عنوان العمود'), ENT_QUOTES, 'UTF-8'); ?></label>
                                    <input type="text" class="form-control" name="footer[<?php echo $current_lang; ?>][col3_title]" value="<?php echo htmlspecialchars(safe_admin_string($modalCol3Title, $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                
                                <div id="col3LinksContainer" class="d-flex flex-column gap-3">
                                    <?php 
                                    $footerCol3LinksData = is_array($curLangFooterData['col3_items'] ?? null) ? $curLangFooterData['col3_items'] : [];
                                    foreach ($footerCol3LinksData as $i => $link): 
                                        if (!is_array($link)) continue;
                                    ?>
                                        <div class="p-3 shadow-sm footer-col3-item" id="col3_<?php echo $i; ?>" style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;">
                                            <div class="row g-3 align-items-center">
                                                <div class="col-md-4 <?php echo $modal_align; ?>">
                                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['contact_name'] ?? 'اسم الوسيلة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                                    <input type="text" name="col3[<?php echo $i; ?>][title]" class="form-control form-control-sm bg-white footer-col3-title-input" value="<?php echo htmlspecialchars(safe_admin_string($link['title'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars((string)($ml['contact_name_placeholder'] ?? 'اسم الوسيلة'), ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>

                                                <div class="col-md-4 <?php echo $modal_align; ?>">
                                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['contact_url'] ?? 'الرابط'), ENT_QUOTES, 'UTF-8'); ?></label>
                                                    <input type="text" name="col3[<?php echo $i; ?>][url]" class="form-control form-control-sm bg-white footer-col3-url-input" value="<?php echo htmlspecialchars(safe_admin_string($link['url'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo htmlspecialchars((string)($ml['url'] ?? 'الرابط'), ENT_QUOTES, 'UTF-8'); ?>">
                                                </div>

                                                <div class="col-md-3 <?php echo $modal_align; ?>">
                                                    <label class="small fw-bold mb-1 text-secondary"><?php echo htmlspecialchars((string)($ml['contact_icon_image'] ?? 'الأيقونة / الصورة'), ENT_QUOTES, 'UTF-8'); ?></label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if (!empty($link['img'])): ?>
                                                            <div class="p-1 bg-white rounded-3 border d-flex align-items-center justify-content-center" style="flex-shrink: 0;">
                                                                <img src="<?php echo htmlspecialchars(safe_admin_string(function_exists('get_image_url') ? get_image_url($link['img']) : $link['img'], $current_lang), ENT_QUOTES, 'UTF-8'); ?>" 
                                                                     alt="Contact Icon" 
                                                                     class="rounded-2" 
                                                                     style="width: 36px; height: 36px; object-fit: cover;">
                                                            </div>
                                                        <?php endif; ?>
                                                        <input type="file" name="col3_img_<?php echo $i; ?>" class="form-control form-control-sm bg-white footer-col3-file" accept="image/*">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 text-center pt-3">
                                                    <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('col3_<?php echo $i; ?>')" title="<?php echo htmlspecialchars((string)($ml['delete_contact'] ?? 'حذف الوسيلة'), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" class="footer-col3-old-img" name="col3[<?php echo $i; ?>][old_img]" value="<?php echo htmlspecialchars(safe_admin_string($link['img'] ?? '', $current_lang), ENT_QUOTES, 'UTF-8'); ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <button type="button" class="btn w-100 mt-3 py-2" style="background: #ffffff; border: 2px dashed #cbd5e1; color: #2563eb; font-weight: 600; border-radius: 14px; transition: 0.2s;" onclick="addCol3Link()">
                                    <i class="bi bi-plus-circle <?php echo $is_rtl ? 'ms-1' : 'me-1'; ?>"></i> <?php echo htmlspecialchars((string)($ml['add_new_contact'] ?? 'إضافة وسيلة تواصل'), ENT_QUOTES, 'UTF-8'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="footerForm" class="btn-premium"><?php echo htmlspecialchars((string)($ml['save_changes'] ?? 'حفظ التغييرات'), ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars((string)($ml['cancel'] ?? 'إلغاء'), ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const ML = <?php echo json_encode($ml, JSON_UNESCAPED_UNICODE); ?>;
const CURRENT_MODAL_LANG = <?php echo json_encode($current_lang, JSON_UNESCAPED_UNICODE); ?>;
const MODAL_ALIGN = <?php echo json_encode($modal_align, JSON_UNESCAPED_UNICODE); ?>;
const HEADER_MODAL_CSRF = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

const GLOBAL_MODAL_FORM_IDS = new Set([
    'socialLinksForm',
    'logoEditForm',
    'announcementEditForm',
    'menuLinksForm',
    'langEditForm',
    'heroEditForm',
    'servicesEditForm',
    'chooseForm',
    'reviewsForm',
    'guideForm',
    'faqForm',
    'footerForm'
]);

function toggleAdContent(type) {
    const textEditor = document.getElementById('textEditor');
    const imageEditor = document.getElementById('imageEditor');
    if (!textEditor || !imageEditor) return;

    if (type === 'image') {
        textEditor.classList.add('d-none');
        imageEditor.classList.remove('d-none');
    } else {
        imageEditor.classList.add('d-none');
        textEditor.classList.remove('d-none');
    }
}

function selectAdStatusOption(val, label, el) {
    const hiddenInput = document.getElementById('adStatusInput');
    const btnText = document.getElementById('adStatusDropdownText');
    const dropdownBtn = document.getElementById('adStatusDropdownBtn');
    if (hiddenInput) hiddenInput.value = val;
    if (btnText) btnText.textContent = label;

    if (el && el.closest) {
        el.closest('.dropdown-menu')?.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }

    if (dropdownBtn && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        const instance = bootstrap.Dropdown.getInstance(dropdownBtn);
        if (instance) instance.hide();
    }
}

function selectAdTypeOption(val, label, el) {
    const hiddenInput = document.getElementById('adTypeSelect');
    const btnText = document.getElementById('adTypeDropdownText');
    const dropdownBtn = document.getElementById('adTypeDropdownBtn');
    if (hiddenInput) hiddenInput.value = val;
    if (btnText) btnText.textContent = label;

    if (el && el.closest) {
        el.closest('.dropdown-menu')?.querySelectorAll('.dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }

    if (dropdownBtn && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        const instance = bootstrap.Dropdown.getInstance(dropdownBtn);
        if (instance) instance.hide();
    }

    toggleAdContent(val);
}

function removeRow(target) {
    if (typeof target === 'string') {
        const el = document.getElementById(target);
        if (el) el.remove();
    } else if (target && target.closest) {
        const row = target.closest('.social-row-item, .menu-row-item, .lang-row-item, .service-row-item, .choose-row-item, .review-row-item, .guide-row-item, .faq-row-item, .footer-col3-item');
        if (row) row.remove();
    }
}

function showNotification(message, type = 'success') {
    const existingAlert = document.getElementById('customNotificationAlert');
    if (existingAlert) existingAlert.remove();

    let bgClass = 'alert-success';
    let icon = 'bi-check-circle-fill';
    let title = ML.notif_success_title || 'تم بنجاح!';

    if (type === 'danger') {
        bgClass = 'alert-danger';
        icon = 'bi-x-circle-fill';
        title = ML.notif_error_title || 'عذراً، حدث خطأ!';
    } else if (type === 'warning') {
        bgClass = 'alert-warning';
        icon = 'bi-exclamation-triangle-fill';
        title = ML.notif_warning_title || 'تنبيه هام';
    }

    const alertDiv = document.createElement('div');
    alertDiv.id = 'customNotificationAlert';
    alertDiv.className = `alert ${bgClass} alert-dismissible fade show shadow-lg position-fixed`;
    alertDiv.style.cssText = 'top: 30px; left: 50%; transform: translateX(-50%); z-index: 99999; min-width: 340px; border-radius: 12px; border: none;';
    alertDiv.setAttribute('dir', CURRENT_MODAL_LANG === 'ar' ? 'rtl' : 'ltr');
    
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

function addSocialRow() {
    const container = document.getElementById('socialRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.social-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm social-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'social_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-4 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.platform_name || 'اسم المنصة'}</label>
                <input type="text" class="form-control social-name" name="social[${count}][name]" placeholder="${ML.name_placeholder || 'الاسم'}">
            </div>
            <div class="col-md-8 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.platform_url || 'رابط المنصة'}</label>
                <input type="url" class="form-control social-url" name="social[${count}][url]" placeholder="${ML.url_placeholder || 'https://...'}">
            </div>
            <div class="col-md-11 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.platform_icon_image || 'أيقونة / صورة المنصة'}</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="file" class="form-control social-file" name="social_img_${count}" accept="image/*">
                </div>
            </div>
            <input type="hidden" class="social-old-img" name="social[${count}][old_img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_platform || 'حذف المنصة'}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addMenuRow() {
    const container = document.getElementById('menuRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.menu-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm menu-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'menu_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.link_title || 'عنوان الرابط'}</label>
                <input type="text" class="form-control" name="menu[${count}][title]" placeholder="${ML.title || 'العنوان'}">
            </div>
            <div class="col-md-5 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.url || 'الرابط (URL)'}</label>
                <input type="text" class="form-control" name="menu[${count}][url]" placeholder="home">
            </div>
            <div class="col-md-1 text-center pt-3">
                <input type="hidden" name="menu[${count}][order]" value="${count}">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete || 'حذف'}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addLangRow() {
    const container = document.getElementById('langRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.lang-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm lang-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'lang_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-4 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.lang_code || 'رمز اللغة (Code)'}</label>
                <input type="text" class="form-control lang-code" name="lang[${count}][code]" placeholder="fr">
            </div>
            <div class="col-md-5 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.lang_name || 'اسم اللغة'}</label>
                <input type="text" class="form-control lang-name" name="lang[${count}][name]" placeholder="Français">
            </div>
            <div class="col-md-2 text-center pt-3">
                <label class="small fw-bold mb-1 text-secondary d-block">${ML.status || 'الحالة'}</label>
                <input type="hidden" class="lang-active-hidden" name="lang[${count}][active]" value="0">
                <div class="form-check form-switch d-inline-block">
                    <input class="form-check-input lang-active" type="checkbox" name="lang[${count}][active]" value="1" checked>
                </div>
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_lang || 'حذف اللغة'}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addServiceRow() {
    const container = document.getElementById('servicesRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.service-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm service-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'service_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.service_title || 'عنوان الخدمة'}</label>
                <input type="text" class="form-control service-title" name="services[${CURRENT_MODAL_LANG}][items][${count}][title]">
            </div>
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.service_url || 'رابط الخدمة'}</label>
                <input type="text" class="form-control service-url" name="services[${CURRENT_MODAL_LANG}][items][${count}][url]" value="#">
            </div>
            <div class="col-md-11 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.image_or_icon || 'الصورة / الأيقونة'}</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="file" class="form-control service-file" name="service_img_${count}" accept="image/*">
                </div>
            </div>
            <input type="hidden" class="service-old-img" name="services[${CURRENT_MODAL_LANG}][items][${count}][old_img]" value="">
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_service || 'حذف الخدمة'}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addChooseRow() {
    const container = document.getElementById('chooseRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.choose-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm choose-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'choose_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.item_title || 'عنوان العنصر'}</label>
                <input type="text" class="form-control choose-title" name="choose[${CURRENT_MODAL_LANG}][items][${count}][title]">
            </div>
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.url || 'الرابط'}</label>
                <input type="text" class="form-control choose-url" name="choose[${CURRENT_MODAL_LANG}][items][${count}][url]" value="#">
            </div>
            <div class="col-12 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.description || 'الوصف'}</label>
                <input type="text" class="form-control choose-desc" name="choose[${CURRENT_MODAL_LANG}][items][${count}][desc]">
            </div>
            <div class="col-md-11 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.icon_or_image || 'الأيقونة / الصورة'}</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="file" class="form-control choose-file w-100" name="choose_img_${count}" accept="image/*">
                </div>
                <input type="hidden" class="choose-old-img" name="choose[${CURRENT_MODAL_LANG}][items][${count}][old_img]" value="">
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_item || 'حذف العنصر'}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addReviewRow() {
    const container = document.getElementById('reviewsRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.review-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm review-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'rev_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-11 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.review_video_url || 'رابط الفيديو (Embed URL)'}</label>
                <input type="text" class="form-control review-url" name="reviews[${CURRENT_MODAL_LANG}][items][${count}][url]" placeholder="https://www.youtube.com/embed/...">
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_video || 'حذف الفيديو'}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addGuideRow() {
    const container = document.getElementById('guideRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.guide-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm guide-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'guide_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.article_title || 'عنوان المقال'}</label>
                <input type="text" class="form-control guide-title" name="guide[${CURRENT_MODAL_LANG}][items][${count}][title]">
            </div>
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.page_url || 'رابط الصفحة'}</label>
                <input type="text" class="form-control guide-url" name="guide[${CURRENT_MODAL_LANG}][items][${count}][url]">
            </div>
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.description || 'الوصف'}</label>
                <input type="text" class="form-control guide-desc" name="guide[${CURRENT_MODAL_LANG}][items][${count}][desc]">
            </div>
            <div class="col-md-5 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.image || 'الصورة'}</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="file" class="form-control guide-file w-100" name="guide_img_${count}" accept="image/*">
                </div>
                <input type="hidden" class="guide-old-img" name="guide[${CURRENT_MODAL_LANG}][items][${count}][old_img]" value="">
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_article || 'حذف المقال'}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addFaqRow() {
    const container = document.getElementById('faqRowsContainer');
    if (!container) return;
    const count = container.querySelectorAll('.faq-row-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm faq-row-item mb-3';
    div.style.cssText = 'background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0 !important;';
    div.id = 'faq_row_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-6 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.faq_question || 'السؤال'}</label>
                <input type="text" class="form-control faq-question" name="faq[${CURRENT_MODAL_LANG}][items][${count}][question]">
            </div>
            <div class="col-md-5 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.faq_answer || 'الإجابة'}</label>
                <input type="text" class="form-control faq-answer" name="faq[${CURRENT_MODAL_LANG}][items][${count}][answer]">
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" title="${ML.delete_faq || 'حذف السؤال'}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function addCol3Link() {
    const container = document.getElementById('col3LinksContainer');
    if (!container) return;
    const count = container.querySelectorAll('.footer-col3-item').length;
    const div = document.createElement('div');
    div.className = 'p-3 shadow-sm footer-col3-item mb-3';
    div.style.cssText = 'background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0 !important;';
    div.id = 'col3_' + Date.now() + '_' + count;
    div.innerHTML = `
        <div class="row g-3 align-items-center">
            <div class="col-md-4 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.contact_name || 'اسم الوسيلة'}</label>
                <input type="text" name="col3[${count}][title]" class="form-control form-control-sm bg-white footer-col3-title-input" placeholder="${ML.contact_name_placeholder || 'اسم الوسيلة'}">
            </div>
            <div class="col-md-4 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.contact_url || 'الرابط'}</label>
                <input type="text" name="col3[${count}][url]" class="form-control form-control-sm bg-white footer-col3-url-input" placeholder="${ML.url || 'الرابط'}">
            </div>
            <div class="col-md-3 ${MODAL_ALIGN}">
                <label class="small fw-bold mb-1 text-secondary">${ML.contact_icon_image || 'الأيقونة / الصورة'}</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="file" name="col3_img_${count}" class="form-control form-control-sm bg-white footer-col3-file" accept="image/*">
                </div>
            </div>
            <div class="col-md-1 text-center pt-3">
                <button type="button" class="btn-icon-trash mx-auto" onclick="removeRow('${div.id}')" style="border-radius: 8px;" title="${ML.delete_contact || 'حذف الوسيلة'}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <input type="hidden" class="footer-col3-old-img" name="col3[${count}][old_img]" value="">
    `;
    container.appendChild(div);
}

document.addEventListener('DOMContentLoaded', function() {
    const adTypeSelect = document.getElementById('adTypeSelect');
    if (adTypeSelect) {
        toggleAdContent(adTypeSelect.value);
    }

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !GLOBAL_MODAL_FORM_IDS.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        // إعادة ترقيم Social Links
        form.querySelectorAll('.social-row-item').forEach((row, index) => {
            const nameInput = row.querySelector('.social-name');
            const urlInput = row.querySelector('.social-url');
            const fileInput = row.querySelector('.social-file');
            const oldImgInput = row.querySelector('.social-old-img');

            if (nameInput) nameInput.name = `social[${index}][name]`;
            if (urlInput) urlInput.name = `social[${index}][url]`;
            if (fileInput) fileInput.name = `social_img_${index}`;
            if (oldImgInput) oldImgInput.name = `social[${index}][old_img]`;
        });

        // إعادة ترقيم Menu Links
        form.querySelectorAll('.menu-row-item').forEach((row, index) => {
            const titleInput = row.querySelector('input[name*="[title]"]');
            const urlInput = row.querySelector('input[name*="[url]"]');
            const orderInput = row.querySelector('input[name*="[order]"]');

            if (titleInput) titleInput.name = `menu[${index}][title]`;
            if (urlInput) urlInput.name = `menu[${index}][url]`;
            if (orderInput) orderInput.name = `menu[${index}][order]`;
        });

        // إعادة ترقيم Languages
        form.querySelectorAll('.lang-row-item').forEach((row, index) => {
            const codeInput = row.querySelector('.lang-code');
            const nameInput = row.querySelector('.lang-name');
            const activeInput = row.querySelector('.lang-active');
            const activeHidden = row.querySelector('.lang-active-hidden');

            if (codeInput) codeInput.name = `lang[${index}][code]`;
            if (nameInput) nameInput.name = `lang[${index}][name]`;
            if (activeInput) activeInput.name = `lang[${index}][active]`;
            if (activeHidden) {
                activeHidden.name = `lang[${index}][active]`;
                activeHidden.disabled = !!(activeInput && activeInput.checked);
            }
        });

        // إعادة ترقيم Services
        form.querySelectorAll('.service-row-item').forEach((row, index) => {
            const title = row.querySelector('.service-title');
            const url = row.querySelector('.service-url');
            const file = row.querySelector('.service-file');
            const oldImg = row.querySelector('.service-old-img');
            if (title) title.name = `services[${CURRENT_MODAL_LANG}][items][${index}][title]`;
            if (url) url.name = `services[${CURRENT_MODAL_LANG}][items][${index}][url]`;
            if (file) file.name = `service_img_${index}`;
            if (oldImg) oldImg.name = `services[${CURRENT_MODAL_LANG}][items][${index}][old_img]`;
        });

        // إعادة ترقيم Choose Items
        form.querySelectorAll('.choose-row-item').forEach((row, index) => {
            const titleInput = row.querySelector('.choose-title');
            const descInput = row.querySelector('.choose-desc');
            const urlInput = row.querySelector('.choose-url');
            const fileInput = row.querySelector('.choose-file');
            const oldImgInput = row.querySelector('.choose-old-img');

            if (titleInput) titleInput.name = `choose[${CURRENT_MODAL_LANG}][items][${index}][title]`;
            if (descInput) descInput.name = `choose[${CURRENT_MODAL_LANG}][items][${index}][desc]`;
            if (urlInput) urlInput.name = `choose[${CURRENT_MODAL_LANG}][items][${index}][url]`;
            if (fileInput) fileInput.name = `choose_img_${index}`;
            if (oldImgInput) oldImgInput.name = `choose[${CURRENT_MODAL_LANG}][items][${index}][old_img]`;
        });

        // إعادة ترقيم Reviews
        form.querySelectorAll('.review-row-item').forEach((row, index) => {
            const urlInput = row.querySelector('.review-url');
            if (urlInput) {
                urlInput.name = `reviews[${CURRENT_MODAL_LANG}][items][${index}][url]`;
            }
        });

        // إعادة ترقيم Guide
        form.querySelectorAll('.guide-row-item').forEach((row, index) => {
            const title = row.querySelector('.guide-title');
            const url = row.querySelector('.guide-url');
            const desc = row.querySelector('.guide-desc');
            const file = row.querySelector('.guide-file');
            const oldImg = row.querySelector('.guide-old-img');
            
            if (title) title.name = `guide[${CURRENT_MODAL_LANG}][items][${index}][title]`;
            if (url) url.name = `guide[${CURRENT_MODAL_LANG}][items][${index}][url]`;
            if (desc) desc.name = `guide[${CURRENT_MODAL_LANG}][items][${index}][desc]`;
            if (file) file.name = `guide_img_${index}`;
            if (oldImg) oldImg.name = `guide[${CURRENT_MODAL_LANG}][items][${index}][old_img]`;
        });

        // إعادة ترقيم FAQ
        form.querySelectorAll('.faq-row-item').forEach((row, index) => {
            const q = row.querySelector('.faq-question');
            const a = row.querySelector('.faq-answer');
            if (q) q.name = `faq[${CURRENT_MODAL_LANG}][items][${index}][question]`;
            if (a) a.name = `faq[${CURRENT_MODAL_LANG}][items][${index}][answer]`;
        });

        // إعادة ترقيم Footer Col3 Links
        form.querySelectorAll('.footer-col3-item').forEach((row, index) => {
            const titleInput = row.querySelector('.footer-col3-title-input');
            const urlInput = row.querySelector('.footer-col3-url-input');
            const fileInput = row.querySelector('.footer-col3-file');
            const oldImgInput = row.querySelector('.footer-col3-old-img');
            
            if (titleInput) titleInput.name = `col3[${index}][title]`;
            if (urlInput) urlInput.name = `col3[${index}][url]`;
            if (fileInput) fileInput.name = `col3_img_${index}`;
            if (oldImgInput) oldImgInput.name = `col3[${index}][old_img]`;
        });

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || HEADER_MODAL_CSRF;

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
                showNotification((ML.notif_server_error || 'خطأ من السيرفر: ') + rawText, 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                showNotification(ML.notif_saved_reloading || 'تم حفظ التعديلات بنجاح، جاري تحديث الصفحة...', 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showNotification((ML.notif_save_failed || 'عذراً، لم يتم الحفظ: ') + (data.message || ML.notif_check_inputs || 'يرجى التأكد من البيانات المدخلة'), 'danger');
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            showNotification(ML.notif_network_error || 'حدث خطأ في الاتصال بالشبكة، يرجى المحاولة لاحقاً.', 'danger');
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
