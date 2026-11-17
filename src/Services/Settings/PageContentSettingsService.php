<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;
use Throwable;

class PageContentSettingsService
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    private string $rootPath;
    private ImageUploader $imageUploader;

    /** @var string[] قائمة الملفات القديمة المراد حذفها بعد نجاح الحفظ في قاعدة البيانات */
    private array $filesToDelete = [];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = rtrim($rootPath, '/\\');
        $this->imageUploader = $imageUploader;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $dbKey = $this->resolveDbKey($action);
        if (!$dbKey) {
            return false;
        }

        $this->filesToDelete = [];
        $targetLang = $this->resolveTargetLang(['targetLang', 'page_lang', 'lang']);

        try {
            $allLangs = $this->fetchSetting($pdo, $dbKey, $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $pageData = $currentLangData;

            // معالجة خاصة لصفحة الاستقبال والوصول (Arrival Page) متوافقة مع تعدد اللغات
            if ($dbKey === 'arrival_page') {
                $postedArrival = is_array($_POST['arrival_data'][$targetLang] ?? null)
                    ? $_POST['arrival_data'][$targetLang]
                    : (is_array($_POST['arrival_data'] ?? null) ? $_POST['arrival_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedArrival['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedArrival['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg9.png');
                    $submittedOldImg = (string)($postedArrival['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('arrival_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg9.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedArrival['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedArrival['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_tips')) {
                    $pageData['advice_title'] = trim((string)($postedArrival['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $pageData['advice_desc'] = trim((string)($postedArrival['advice_desc'] ?? ($pageData['advice_desc'] ?? '')));
                    
                    $tipsRaw = $postedArrival['tips_raw'] ?? ($postedArrival['tips'] ?? ($_POST['tips_raw'] ?? ($_POST['tips'] ?? [])));
                    if (is_string($tipsRaw)) {
                        $pageData['tips'] = array_values(array_filter(array_map('trim', explode("\n", $tipsRaw)), fn($val) => $val !== ''));
                    } elseif (is_array($tipsRaw)) {
                        $pageData['tips'] = array_values(array_filter(array_map('trim', $tipsRaw), fn($val) => $val !== ''));
                    } else {
                        $pageData['tips'] = [];
                    }
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedArrival['note_title'] ?? ($pageData['note_title'] ?? '')));
                    
                    $notesRaw = $postedArrival['notes_raw'] ?? ($postedArrival['notes'] ?? ($_POST['notes_raw'] ?? ($_POST['notes'] ?? [])));
                    if (is_string($notesRaw)) {
                        $pageData['notes'] = array_values(array_filter(array_map('trim', explode("\n", $notesRaw)), fn($val) => $val !== ''));
                    } elseif (is_array($notesRaw)) {
                        $pageData['notes'] = array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== ''));
                    } else {
                        $pageData['notes'] = [];
                    }
                }
            }
            // معالجة صفحة السيرة الذاتية (CV Page)
            elseif ($dbKey === 'cv_page') {
                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($_POST['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($_POST['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? '');
                    $submittedOldImg = (string)($_POST['old_img'] ?? $dbOldImg);
                    $heroImg = $this->handleImageField('hero_img', $submittedOldImg, $dbOldImg);
                    $pageData['hero_img'] = $heroImg;
                } elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($_POST['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($_POST['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } elseif (str_contains($action, '_advice')) {
                    $pageData['advice_title'] = trim((string)($_POST['advice_title'] ?? ($pageData['advice_title'] ?? '')));
                    $advicePointsRaw = $_POST['advice_points'] ?? [];
                    $pageData['advice_points'] = is_array($advicePointsRaw) ? array_values(array_filter(array_map('trim', $advicePointsRaw), fn($val) => $val !== '')) : [];
                } elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($_POST['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $_POST['notes'] ?? [];
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } elseif (str_contains($action, '_downloads')) {
                    $types = $_POST['download_types'] ?? [];
                    $titles = $_POST['download_titles'] ?? [];
                    $subs = $_POST['download_subs'] ?? [];
                    $files = $_POST['download_files'] ?? [];

                    $downloadItems = [];
                    for ($i = 0; $i < count($titles); $i++) {
                        $titleVal = trim($titles[$i] ?? '');
                        if ($titleVal === '') continue;

                        $downloadItems[] = [
                            'type'  => $types[$i] ?? 'pdf',
                            'title' => $titleVal,
                            'sub'   => $subs[$i] ?? 'Example',
                            'file'  => $files[$i] ?? '#'
                        ];
                    }
                    $pageData['download_items'] = $downloadItems;
                }
            }
            // بقية الصفحات الأخرى وفق المعيار الهندسي الموحد
            else {
                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($_POST['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($_POST['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? '');
                    $submittedOldImg = (string)($_POST['old_img'] ?? $dbOldImg);
                    $heroImg = $this->handleImageField('hero_img', $submittedOldImg, $dbOldImg);
                    $pageData['hero_img'] = $heroImg;
                    $pageData['hero_position'] = trim((string)($_POST['hero_position'] ?? ($pageData['hero_position'] ?? 'center center')));
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($_POST['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($_POST['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                    
                    if (isset($_POST['importance_title'])) {
                        $pageData['importance_title'] = trim((string)$_POST['importance_title']);
                    }
                    if (isset($_POST['importance_desc'])) {
                        $pageData['importance_desc'] = trim((string)$_POST['importance_desc']);
                    }
                }
                elseif (str_contains($action, '_advice') || str_contains($action, '_tips') || str_contains($action, '_goals')) {
                    $adviceTitle = trim((string)($_POST['advice_title'] ?? ($_POST['tips_title'] ?? ($_POST['goals_title'] ?? ''))));
                    $pageData['advice_title'] = $adviceTitle;
                    
                    $pointsRaw = $_POST['advice_points'] ?? ($_POST['tips_items'] ?? ($_POST['goals_items'] ?? ($_POST['tips'] ?? [])));
                    if (is_string($pointsRaw)) {
                        $pageData['advice_points'] = array_values(array_filter(array_map('trim', explode("\n", $pointsRaw)), fn($val) => $val !== ''));
                    } elseif (is_array($pointsRaw)) {
                        $pageData['advice_points'] = array_values(array_filter(array_map('trim', $pointsRaw), fn($val) => $val !== ''));
                    } else {
                        $pageData['advice_points'] = [];
                    }
                }
                elseif (str_contains($action, '_notes') || str_contains($action, '_note')) {
                    $pageData['note_title'] = trim((string)($_POST['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $_POST['notes'] ?? ($_POST['notes_list'] ?? ($_POST['note_texts'] ?? []));
                    if (is_string($notesRaw)) {
                        $pageData['notes'] = array_values(array_filter(array_map('trim', explode("\n", $notesRaw)), fn($val) => $val !== ''));
                    } elseif (is_array($notesRaw)) {
                        $pageData['notes'] = array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== ''));
                    } else {
                        $pageData['notes'] = [];
                    }
                }
            }

            $allLangs[$targetLang] = $pageData;
            $this->saveSetting($pdo, $dbKey, $allLangs);

            // تنفيذ الحذف الآمن للملفات القديمة بعد نجاح الحفظ في قاعدة البيانات
            $this->flushDeletedFiles();

            return true;

        } catch (Throwable $e) {
            error_log('PageContentSettingsService Error: ' . $e->getMessage());
            return false;
        }
    }

    private function resolveDbKey(string $action): ?string
    {
        if (str_starts_with($action, 'update_arrival_')) return 'arrival_page';
        if (str_starts_with($action, 'update_job_agreements_')) return 'job_agreements_page';
        if (str_starts_with($action, 'update_medical_packages_')) return 'medical_packages_page';
        if (str_starts_with($action, 'update_pricelist_')) return 'pricelist_page';
        if (str_starts_with($action, 'update_medical_specialties_')) return 'medical_specialties_page';
        if (str_starts_with($action, 'update_vocational_')) return 'vocational_page';
        if (str_starts_with($action, 'update_visa_')) return 'general_visa_page';
        if (str_starts_with($action, 'update_check_')) return 'check_page';
        if (str_starts_with($action, 'update_cover_')) return 'coverletter_page';
        if (str_starts_with($action, 'update_cv_')) return 'cv_page';
        if (str_starts_with($action, 'update_motivation_')) return 'motivation_page';
        if (str_starts_with($action, 'update_german_')) return 'germanlang_page';
        if (str_starts_with($action, 'update_english_')) return 'englishlang_page';
        if (str_starts_with($action, 'update_offers_')) return 'offers_page';
        if (str_starts_with($action, 'update_health_')) return 'health_page';
        if (str_starts_with($action, 'update_financial_')) return 'financial_page';
        if (str_starts_with($action, 'update_living_')) return 'living_cost_page';
        if (str_starts_with($action, 'update_foundation_') || str_starts_with($action, 'update_stk_')) return 'foundation_page';
        if (str_starts_with($action, 'update_courses_') || str_starts_with($action, 'update_course_') || str_starts_with($action, 'update_lang_')) return 'courses_page';
        return null;
    }

    private function resolveTargetLang(array $postKeys): string
    {
        foreach ($postKeys as $key) {
            if (!empty($_POST[$key]) && is_string($_POST[$key])) {
                $candidate = strtolower(trim($_POST[$key]));
                if (in_array($candidate, self::SUPPORTED_LANGS, true)) {
                    return $candidate;
                }
            }
        }

        $sessionLang = strtolower(trim((string)($_SESSION['site_lang'] ?? 'de')));
        return in_array($sessionLang, self::SUPPORTED_LANGS, true) ? $sessionLang : 'de';
    }

    private function fetchSetting(PDO $pdo, string $key, array $currentSettings): array
    {
        $stmtFetch = $pdo->prepare('SELECT setting_value FROM global_settings WHERE setting_key = :k LIMIT 1');
        $stmtFetch->execute(['k' => $key]);
        $dbRaw = $stmtFetch->fetchColumn();

        if (is_string($dbRaw) && $dbRaw !== '') {
            $decoded = json_decode($dbRaw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (isset($currentSettings[$key])) {
            if (is_array($currentSettings[$key])) {
                return $currentSettings[$key];
            }
            if (is_string($currentSettings[$key])) {
                $decoded = json_decode($currentSettings[$key], true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        return [];
    }

    private function saveSetting(PDO $pdo, string $key, array $data): void
    {
        $jsonVal = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $stmt = $pdo->prepare(
            'INSERT INTO global_settings (setting_key, setting_value)
             VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = :v_update'
        );
        $stmt->execute([
            'k'        => $key,
            'v'        => $jsonVal,
            'v_update' => $jsonVal,
        ]);
    }

    private function handleImageField(string $fileInputKey, string $submittedOldPath, string $dbOldPath): string
    {
        $safeOld = $this->sanitizeImagePath($submittedOldPath !== '' ? $submittedOldPath : $dbOldPath);

        if (isset($_FILES[$fileInputKey]) && is_array($_FILES[$fileInputKey]) && ($_FILES[$fileInputKey]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $filename = $this->imageUploader->processAndUploadFile((string)$_FILES[$fileInputKey]['tmp_name']);
            if ($dbOldPath !== '' && str_starts_with(ltrim($dbOldPath, '/'), 'assets/uploads/')) {
                $this->filesToDelete[] = ltrim($dbOldPath, '/');
            }
            return 'assets/uploads/' . $filename;
        }

        return $safeOld;
    }

    private function sanitizeImagePath(string $path): string
    {
        $clean = trim($path);
        if ($clean === '' || str_contains($clean, '..') || preg_match('/^(javascript|data|vbscript|file):/i', $clean)) {
            return '';
        }

        if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
            return filter_var($clean, FILTER_VALIDATE_URL) ? $clean : '';
        }

        return ltrim($clean, '/');
    }

    private function sanitizeLinkUrl(string $url): string
    {
        $clean = trim($url);
        if ($clean === '' || $clean === '#') {
            return '#';
        }

        if (preg_match('/^(javascript|data|vbscript|file):/i', $clean)) {
            return '#';
        }

        return $clean;
    }

    private function flushDeletedFiles(): void
    {
        foreach (array_unique($this->filesToDelete) as $relativePath) {
            if (!str_starts_with($relativePath, 'assets/uploads/')) {
                continue;
            }
            $this->imageUploader->deleteOldImage(basename($relativePath));
        }
        $this->filesToDelete = [];
    }
}
