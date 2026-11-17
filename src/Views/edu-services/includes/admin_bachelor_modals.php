<?php
declare(strict_types=1);

/**
 * Beethoven CMS - Bachelor Package Admin Modals (Gold Standard Security, Multilingual & AJAX)
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
        'save_success'   => 'Änderungen erfolgreich gespeichert...',
        'save_error'     => 'Speichern fehlgeschlagen: ',
        'invalid_resp'   => 'Unerwartete Serverantwort.',
        'network_error'  => 'Netzwerkfehler.',
        'success_title'  => 'Erfolgreich!',
        'error_title'    => 'Fehler!',
        'warning_title'  => 'Hinweis',
        'bc_title'       => 'Breadcrumb bearbeiten',
        'bc_name_lbl'    => 'Seitenname im Pfad',
        'bc_url_lbl'     => 'Seiten-URL',
        'content_title'  => 'Inhalt & Passwort bearbeiten',
        'title_lbl'      => 'Haupttitel',
        'desc_lbl'       => 'Beschreibung / HTML',
        'pass_lbl'       => 'Passwort-Label',
        'btn_lbl'        => 'Button-Text',
        'btn_url_lbl'    => 'Button-URL',
    ],
    'en' => [
        'save_success'   => 'Changes saved successfully...',
        'save_error'     => 'Failed to save: ',
        'invalid_resp'   => 'Unexpected server response.',
        'network_error'  => 'Network error.',
        'success_title'  => 'Success!',
        'error_title'    => 'Error!',
        'warning_title'  => 'Notice',
        'bc_title'       => 'Edit Breadcrumb',
        'bc_name_lbl'    => 'Page Name in Path',
        'bc_url_lbl'     => 'Page URL',
        'content_title'  => 'Edit Content & Password',
        'title_lbl'      => 'Main Title',
        'desc_lbl'       => 'Description / HTML',
        'pass_lbl'       => 'Password Label',
        'btn_lbl'        => 'Button Text',
        'btn_url_lbl'    => 'Button URL',
    ],
    'ar' => [
        'save_success'   => 'تم حفظ التعديلات بنجاح...',
        'save_error'     => 'عذراً، لم يتم الحفظ: ',
        'invalid_resp'   => 'استجابة غير متوقعة من الخادم.',
        'network_error'  => 'حدث خطأ في الاتصال.',
        'success_title'  => 'تم بنجاح!',
        'error_title'    => 'عذراً، حدث خطأ!',
        'warning_title'  => 'تنبيه هام',
        'bc_title'       => 'تعديل مسار التنقل (Breadcrumb)',
        'bc_name_lbl'    => 'اسم الصفحة في المسار',
        'bc_url_lbl'     => 'رابط الصفحة (URL)',
        'content_title'  => 'تعديل محتوى الصفحة وكلمة المرور',
        'title_lbl'      => 'العنوان الرئيسي',
        'desc_lbl'       => 'الوصف / محتوى HTML',
        'pass_lbl'       => 'تسمية كلمة المرور',
        'btn_lbl'        => 'نص الزر',
        'btn_url_lbl'    => 'رابط الزر',
    ],
];
$mTxt = $modalI18n[$current_lang];

// جلب البيانات من جدول pages
$fetchRawBachelorSetting = static function (string $pageKey) use ($current_lang): array {
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

$curBachelorData = $fetchRawBachelorSetting('bachelor_package_page');

$modalBcUrl      = (string)($curBachelorData['page_breadcrumb_url'] ?? '#');
$modalBcText     = (string)($curBachelorData['page_breadcrumb'] ?? 'BCS Bachelor Package');
$modalMainTitle  = (string)($curBachelorData['main_title'] ?? '');
$modalMainDesc   = (string)($curBachelorData['main_desc'] ?? '');
$modalPassLabel  = (string)($curBachelorData['password_label'] ?? 'كلمة المرور:');
$modalBtnText    = (string)($curBachelorData['btn_text'] ?? 'ادخال');
$modalBtnUrl     = (string)($curBachelorData['btn_url'] ?? '#');
?>

<!-- 1. Breadcrumb Edit Modal -->
<div class="modal fade custom-modal" id="bachelorBreadcrumbModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
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
                <form id="bachelorBreadcrumbForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_bachelor_breadcrumb">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_name_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][page_breadcrumb]" value="<?php echo htmlspecialchars($modalBcText, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-0 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['bc_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][page_breadcrumb_url]" value="<?php echo htmlspecialchars($modalBcUrl, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="bachelorBreadcrumbForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Content & Password Edit Modal -->
<div class="modal fade custom-modal" id="bachelorContentModal" tabindex="-1" aria-hidden="true" dir="<?php echo $modal_dir; ?>">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="bi bi-shield-lock text-primary <?php echo $is_rtl ? 'ms-2' : 'me-2'; ?>"></i> 
                    <?php echo htmlspecialchars($mTxt['content_title'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo strtoupper($current_lang); ?>)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="bachelorContentForm" class="admin-settings-form">
                    <input type="hidden" name="action" value="update_bachelor_content">
                    <input type="hidden" name="targetLang" value="<?php echo htmlspecialchars($current_lang, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($activeCsrfToken, ENT_QUOTES, 'UTF-8'); ?>">
                    
                    <div class="p-4 shadow-sm mb-0" style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0;">
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['title_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][main_title]" value="<?php echo htmlspecialchars($modalMainTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="mb-3 <?php echo $modal_align; ?>">
                            <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['desc_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                            <textarea class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][main_desc]" rows="4" style="height: auto; padding: 12px 16px;" required><?php echo htmlspecialchars($modalMainDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['pass_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][password_label]" value="<?php echo htmlspecialchars($modalPassLabel, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['btn_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][btn_text]" value="<?php echo htmlspecialchars($modalBtnText, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-4 <?php echo $modal_align; ?>">
                                <label class="form-label fw-semibold small text-secondary"><?php echo htmlspecialchars($mTxt['btn_url_lbl'], ENT_QUOTES, 'UTF-8'); ?></label>
                                <input type="text" class="form-control" name="bachelor_data[<?php echo $current_lang; ?>][btn_url]" value="<?php echo htmlspecialchars($modalBtnUrl, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="bachelorContentForm" class="btn-premium"><?php echo htmlspecialchars($lang['save_changes'] ?? 'Save Changes', ENT_QUOTES, 'UTF-8'); ?></button>
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"><?php echo htmlspecialchars($lang['cancel'] ?? 'Cancel', ENT_QUOTES, 'UTF-8'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
const BACHELOR_MODAL_TXT = <?php echo json_encode($mTxt, JSON_UNESCAPED_UNICODE); ?>;
const BACHELOR_CSRF_TOKEN = <?php echo json_encode($activeCsrfToken, JSON_UNESCAPED_UNICODE); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const bachelorFormIds = new Set(['bachelorBreadcrumbForm', 'bachelorContentForm']);

    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement) || !bachelorFormIds.has(form.id)) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (form.dataset.submitting === 'true') return;
        form.dataset.submitting = 'true';

        const submitBtn = document.querySelector(`button[form="${form.id}"][type="submit"]`)
            || form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || formData.get('csrf_token')
            || BACHELOR_CSRF_TOKEN;

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
                alert(BACHELOR_MODAL_TXT.invalid_resp);
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            if (data.success || data.status === 'success') {
                alert(BACHELOR_MODAL_TXT.save_success);
                setTimeout(() => window.location.reload(), 800);
            } else {
                alert(BACHELOR_MODAL_TXT.save_error + (data.message || ''));
                form.dataset.submitting = 'false';
                if (submitBtn) submitBtn.disabled = false;
            }
        } catch (err) {
            console.error('Fetch Error:', err);
            alert(BACHELOR_MODAL_TXT.network_error);
            form.dataset.submitting = 'false';
            if (submitBtn) submitBtn.disabled = false;
        }
    }, true);
});
</script>
