<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class ContactSettingsService
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
        $allowedActions = [
            'update_contact_hero',
            'update_contact_info',
            'update_whatsapp_section',
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $this->filesToDelete = [];
        $targetLang = $this->resolveTargetLang(['targetLang', 'contact_lang', 'lang']);

        // 1. تحديث هيرو صفحة تواصل معنا (Contact Hero)
        if ($action === 'update_contact_hero') {
            $allLangs = $this->fetchSetting($pdo, 'contact_hero', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $dbOldImg = (string)($currentLangData['img'] ?? 'assets/img/contacthero.png');
            $submittedOldImg = (string)($_POST['old_contact_hero_img'] ?? $dbOldImg);
            $heroImg = $this->handleImageField('contact_hero_img', $submittedOldImg, $dbOldImg);
            if ($heroImg === '') {
                $heroImg = 'assets/img/contacthero.png';
            }

            $allLangs[$targetLang] = [
                'img' => $heroImg,
            ];

            $this->saveSetting($pdo, 'contact_hero', $allLangs);
        }

        // 2. تحديث بطاقات معلومات التواصل الديناميكية (Contact Info Items)
        elseif ($action === 'update_contact_info') {
            $allLangs = $this->fetchSetting($pdo, 'contact_info', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $postedInfo = is_array($_POST['contact_info'][$targetLang] ?? null)
                ? $_POST['contact_info'][$targetLang]
                : (is_array($_POST['contact_info'] ?? null) ? $_POST['contact_info'] : $_POST);

            $title = trim((string)($postedInfo['title'] ?? ($currentLangData['title'] ?? '')));
            $desc  = trim((string)($postedInfo['desc'] ?? ($currentLangData['desc'] ?? '')));

            $rawItems   = $postedInfo['items'] ?? [];
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $dbOldIcon = (string)($existingItems[$index]['icon'] ?? '');
                    $oldIcon   = (string)($item['icon'] ?? ($item['old_icon'] ?? $dbOldIcon));
                    $icon      = $this->handleImageField('contact_item_icon_' . $index, $oldIcon, $dbOldIcon);
                    if ($icon === '') {
                        $icon = 'assets/img/Location.svg';
                    }

                    $text  = trim((string)($item['text'] ?? ''));
                    $url   = $this->sanitizeContactUrl((string)($item['url'] ?? ''));
                    $isLtrVal = $item['is_ltr'] ?? false;
                    $isLtr = ($isLtrVal === true || $isLtrVal === '1' || $isLtrVal === 1 || $isLtrVal === 'on');

                    if ($text === '' && $url === '') {
                        continue;
                    }

                    $finalItems[] = [
                        'text'   => $text,
                        'url'    => $url,
                        'icon'   => $icon,
                        'is_ltr' => $isLtr,
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, 'icon');

            $allLangs[$targetLang] = [
                'title' => $title,
                'desc'  => $desc,
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'contact_info', $allLangs);
        }

        // 3. تحديث قسم الواتساب والتواصل المباشر (WhatsApp Section)
        elseif ($action === 'update_whatsapp_section') {
            $allLangs = $this->fetchSetting($pdo, 'whatsapp_section', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedWa = is_array($_POST['whatsapp_section'][$targetLang] ?? null)
                ? $_POST['whatsapp_section'][$targetLang]
                : (is_array($_POST['whatsapp_section'] ?? null) ? $_POST['whatsapp_section'] : $_POST);

            $waText   = trim((string)($postedWa['text'] ?? ($currentLangData['text'] ?? '')));
            $waUrl    = $this->sanitizeContactUrl((string)($postedWa['url'] ?? ($currentLangData['url'] ?? '#')));
            $waBtnTxt = trim((string)($postedWa['btn_text'] ?? ($currentLangData['btn_text'] ?? '')));

            $allLangs[$targetLang] = [
                'text'     => $waText,
                'url'      => $waUrl !== '' ? $waUrl : '#',
                'btn_text' => $waBtnTxt,
            ];

            $this->saveSetting($pdo, 'whatsapp_section', $allLangs);
        }

        $this->flushDeletedFiles();

        return true;
    }

    private function queueOrphanedImagesForDeletion(array $existingItems, array $finalItems, string $imageKey): void
    {
        $retainedImages = [];
        foreach ($finalItems as $item) {
            if (is_array($item) && !empty($item[$imageKey])) {
                $retainedImages[ltrim((string)$item[$imageKey], '/')] = true;
            }
        }

        foreach ($existingItems as $oldItem) {
            if (!is_array($oldItem) || empty($oldItem[$imageKey])) {
                continue;
            }
            $oldPath = ltrim((string)$oldItem[$imageKey], '/');
            if ($oldPath !== '' && str_starts_with($oldPath, 'assets/uploads/') && !isset($retainedImages[$oldPath])) {
                $this->filesToDelete[] = $oldPath;
            }
        }
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

    private function sanitizeContactUrl(string $url): string
    {
        $clean = trim($url);
        if ($clean === '') {
            return '';
        }

        if (preg_match('/^(javascript|data|vbscript|file):/i', $clean)) {
            return '';
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
