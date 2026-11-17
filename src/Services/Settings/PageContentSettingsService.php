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

            // 2. معالجة تحديثات صفحة الاستقبال (Arrival Page) للغة المستهدفة
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

            // 3. تحديث مصفوفة اللغة وحفظها في جدول pages الجديد
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
