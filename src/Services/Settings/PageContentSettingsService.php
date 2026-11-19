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
            // 1. جلب البيانات الحالية للصفحة من جدول pages الجديد
            $allLangs = $this->fetchPageData($pdo, $dbKey);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $pageData = $currentLangData;

            // 2. معالجة تحديثات صفحة الاستقبال (Arrival Page)
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
                    $tipsRaw = $postedArrival['tips'] ?? ($_POST['tips'] ?? []);
                    $pageData['tips'] = is_array($tipsRaw) ? array_values(array_filter(array_map('trim', $tipsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedArrival['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedArrival['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                }
            }

            // 3. معالجة خاصة لصفحة BCS Bachelor Package
            elseif ($dbKey === 'bachelor_package_page') {
                $postedBachelor = is_array($_POST['bachelor_data'][$targetLang] ?? null)
                    ? $_POST['bachelor_data'][$targetLang]
                    : (is_array($_POST['bachelor_data'] ?? null) ? $_POST['bachelor_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedBachelor['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedBachelor['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_content')) {
                    $pageData['main_title'] = trim((string)($postedBachelor['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedBachelor['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                    $pageData['password_label'] = trim((string)($postedBachelor['password_label'] ?? ($pageData['password_label'] ?? '')));
                    $pageData['btn_text'] = trim((string)($postedBachelor['btn_text'] ?? ($pageData['btn_text'] ?? '')));
                    $pageData['btn_url'] = $this->sanitizeLinkUrl((string)($postedBachelor['btn_url'] ?? ($pageData['btn_url'] ?? '#')));
                }
            }

            // 4. معالجة خاصة لصفحة تحقق من شهاداتك التعليمية (Check Certificate Page)
            elseif ($dbKey === 'check_page') {
                $postedCheck = is_array($_POST['check_data'][$targetLang] ?? null)
                    ? $_POST['check_data'][$targetLang]
                    : (is_array($_POST['check_data'] ?? null) ? $_POST['check_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCheck['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCheck['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg13.png');
                    $submittedOldImg = (string)($postedCheck['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('check_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg13.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCheck['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCheck['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_notes')) {
                    $pageData['note_title'] = trim((string)($postedCheck['note_title'] ?? ($pageData['note_title'] ?? '')));
                    $notesRaw = $postedCheck['notes'] ?? ($_POST['notes'] ?? []);
                    $pageData['notes'] = is_array($notesRaw) ? array_values(array_filter(array_map('trim', $notesRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_links')) {
                    $pageData['links_intro']       = trim((string)($postedCheck['links_intro'] ?? ($pageData['links_intro'] ?? '')));
                    $pageData['anabin_url']        = $this->sanitizeLinkUrl((string)($postedCheck['anabin_url'] ?? ($pageData['anabin_url'] ?? '')));
                    $pageData['uniassist_url']     = $this->sanitizeLinkUrl((string)($postedCheck['uniassist_url'] ?? ($pageData['uniassist_url'] ?? '')));
                    $pageData['uni_contact_intro'] = trim((string)($postedCheck['uni_contact_intro'] ?? ($pageData['uni_contact_intro'] ?? '')));
                    $pageData['condition_1']       = trim((string)($postedCheck['condition_1'] ?? ($pageData['condition_1'] ?? '')));
                    $pageData['condition_2']       = trim((string)($postedCheck['condition_2'] ?? ($pageData['condition_2'] ?? '')));
                    $pageData['conclusion_text']   = trim((string)($postedCheck['conclusion_text'] ?? ($pageData['conclusion_text'] ?? '')));
                }
            }

            // 5. معالجة خاصة لصفحة الدورة التحضيرية لشهادات اللغة الألمانية (Courses Page)
            elseif ($dbKey === 'courses_page') {
                $postedCourses = is_array($_POST['courses_data'][$targetLang] ?? null)
                    ? $_POST['courses_data'][$targetLang]
                    : (is_array($_POST['courses_data'] ?? null) ? $_POST['courses_data'] : $_POST);

                if (str_contains($action, '_breadcrumb')) {
                    $pageData['page_breadcrumb'] = trim((string)($postedCourses['page_breadcrumb'] ?? ($pageData['page_breadcrumb'] ?? '')));
                    $pageData['page_breadcrumb_url'] = $this->sanitizeLinkUrl((string)($postedCourses['page_breadcrumb_url'] ?? ($pageData['page_breadcrumb_url'] ?? '#')));
                } 
                elseif (str_contains($action, '_hero')) {
                    $dbOldImg = (string)($pageData['hero_img'] ?? 'assets/img/education/servicesimg12.png');
                    $submittedOldImg = (string)($postedCourses['old_hero_img'] ?? ($_POST['old_img'] ?? $dbOldImg));
                    $heroImg = $this->handleImageField('courses_hero_img', $submittedOldImg, $dbOldImg);
                    if ($heroImg === '') {
                        $heroImg = 'assets/img/education/servicesimg12.png';
                    }
                    $pageData['hero_img'] = $heroImg;
                } 
                elseif (str_contains($action, '_main')) {
                    $pageData['main_title'] = trim((string)($postedCourses['main_title'] ?? ($pageData['main_title'] ?? '')));
                    $pageData['main_desc'] = trim((string)($postedCourses['main_desc'] ?? ($pageData['main_desc'] ?? '')));
                } 
                elseif (str_contains($action, '_goals')) {
                    $pageData['goals_title'] = trim((string)($postedCourses['goals_title'] ?? ($pageData['goals_title'] ?? '')));
                    $goalsRaw = $postedCourses['goals'] ?? ($_POST['goals'] ?? []);
                    $pageData['goals'] = is_array($goalsRaw) ? array_values(array_filter(array_map('trim', $goalsRaw), fn($val) => $val !== '')) : [];
                } 
                elseif (str_contains($action, '_warning')) {
                    $pageData['warning_text'] = trim((string)($postedCourses['warning_text'] ?? ($pageData['warning_text'] ?? '')));
                } 
                elseif (str_contains($action, '_cost')) {
                    $pageData['cost_title'] = trim((string)($postedCourses['cost_title'] ?? ($pageData['cost_title'] ?? '')));
                    $costItemsRaw = $postedCourses['cost_items'] ?? [];
                    $cleanCostItems = [];
                    if (is_array($costItemsRaw)) {
                        foreach ($costItemsRaw as $item) {
                            if (!is_array($item)) continue;
                            $titleVal = trim((string)($item['title'] ?? ''));
                            $descVal  = trim((string)($item['desc'] ?? ''));
                            if ($titleVal !== '' || $descVal !== '') {
                                $cleanCostItems[] = [
                                    'title' => $titleVal,
                                    'desc'  => $descVal,
                                ];
                            }
                        }
                    }
                    $pageData['cost_items'] = $cleanCostItems;
                }
            }

            // 6. تحديث مصفوفة اللغة وحفظها في جدول pages الجديد
            $allLangs[$targetLang] = $pageData;
            $this->savePageData($pdo, $dbKey, $allLangs);

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
        if (str_starts_with($action, 'update_bachelor_')) return 'bachelor_package_page';
        if (str_starts_with($action, 'update_check_')) return 'check_page';
        if (str_starts_with($action, 'update_courses_') || str_starts_with($action, 'update_lang_')) return 'courses_page';
        if (str_starts_with($action, 'update_cv_')) return 'cv_page';
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

    private function fetchPageData(PDO $pdo, string $pageKey): array
    {
        $stmtFetch = $pdo->prepare('SELECT page_data FROM pages WHERE page_key = :k LIMIT 1');
        $stmtFetch->execute(['k' => $pageKey]);
        $dbRaw = $stmtFetch->fetchColumn();

        if (is_string($dbRaw) && $dbRaw !== '') {
            $decoded = json_decode($dbRaw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    private function savePageData(PDO $pdo, string $pageKey, array $data): void
    {
        $jsonVal = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $stmt = $pdo->prepare(
            'INSERT INTO pages (page_key, page_data)
             VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE page_data = :v_update'
        );
        $stmt->execute([
            'k'        => $pageKey,
            'v'        => $jsonVal,
            'v_update' => $jsonVal,
        ]);
    }

    private function handleImageField(string $fileInputKey, string $submittedOldPath, string $dbOldPath): string
    {
        $safeOld = trim($submittedOldPath !== '' ? $submittedOldPath : $dbOldPath);
        if (isset($_FILES[$fileInputKey]) && is_array($_FILES[$fileInputKey]) && ($_FILES[$fileInputKey]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $filename = $this->imageUploader->processAndUploadFile((string)$_FILES[$fileInputKey]['tmp_name']);
            if ($dbOldPath !== '' && str_starts_with(ltrim($dbOldPath, '/'), 'assets/uploads/')) {
                $this->filesToDelete[] = ltrim($dbOldPath, '/');
            }
            return 'assets/uploads/' . $filename;
        }
        return $safeOld;
    }

    private function sanitizeLinkUrl(string $url): string
    {
        $clean = trim($url);
        return ($clean === '' || preg_match('/^(javascript|data|vbscript|file):/i', $clean)) ? '#' : $clean;
    }

    private function flushDeletedFiles(): void
    {
        foreach (array_unique($this->filesToDelete) as $relativePath) {
            if (str_starts_with($relativePath, 'assets/uploads/')) {
                $this->imageUploader->deleteOldImage(basename($relativePath));
            }
        }
        $this->filesToDelete = [];
    }
}
