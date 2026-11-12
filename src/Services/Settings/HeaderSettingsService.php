<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class HeaderSettingsService
{
    private string $rootPath;
    private ImageUploader $imageUploader;
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = $rootPath;
        $this->imageUploader = $imageUploader;
    }

    private function resolveTargetLang(string ...$candidates): string
    {
        foreach ($candidates as $lang) {
            if ($lang !== '' && in_array($lang, self::SUPPORTED_LANGS, true)) {
                return $lang;
            }
        }
        return 'de';
    }

    private function sanitizeUrl(mixed $rawUrl, string $default = '#'): string
    {
        $url = trim((string)($rawUrl ?? ''));
        if ($url === '' || $url === '#') {
            return $default;
        }
        if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
            return $default;
        }
        return $url;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        if (!in_array($action, ['update_logo', 'update_social', 'update_menu', 'update_languages'], true)) {
            return false;
        }

        $stmt = $pdo->prepare("
            INSERT INTO global_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        // 1. تحديث الشعار مع دعم اللغات المتعددة (de, en, ar) بصيغة JSON
        if ($action === 'update_logo') {
            if (!isset($_FILES['logo_img']) || $_FILES['logo_img']['error'] !== UPLOAD_ERR_OK) {
                return false;
            }

            $targetLang = $this->resolveTargetLang(
                (string)($_POST['logo_lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $rawLogoSetting = $currentSettings['site_logo_path'] ?? ($currentSettings['site_logo'] ?? '');
            $logos = is_string($rawLogoSetting) ? json_decode($rawLogoSetting, true) : [];
            if (!is_array($logos)) {
                $logos = [];
                if (is_string($rawLogoSetting) && trim($rawLogoSetting) !== '') {
                    $logos['de'] = trim($rawLogoSetting);
                }
            }

            if (!empty($logos[$targetLang]) && str_starts_with((string)$logos[$targetLang], 'assets/uploads/')) {
                $this->deleteOldImageFile((string)$logos[$targetLang]);
            }

            $filename = $this->imageUploader->processAndUploadFile($_FILES['logo_img']['tmp_name']);
            $logoPath = 'assets/uploads/' . $filename;
            $logos[$targetLang] = $logoPath;

            $jsonVal = json_encode($logos, JSON_UNESCAPED_UNICODE);
            $ok1 = $stmt->execute(['k' => 'site_logo_path', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            $ok2 = $stmt->execute(['k' => 'site_logo', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            return $ok1 && $ok2;
        }

        // 2. تحديث منصات التواصل الاجتماعي
        elseif ($action === 'update_social') {
            $incomingSocial = $_POST['social'] ?? [];
            $socialItems = [];

            if (is_array($incomingSocial)) {
                foreach ($incomingSocial as $index => $item) {
                    if (!is_array($item)) continue;
                    $nameVal = trim((string)($item['name'] ?? ''));
                    if ($nameVal === '') continue;

                    $fileToCheck = $_FILES['social_img_' . $index] ?? null;
                    $oldImg = (string)($item['old_img'] ?? '');

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if ($oldImg !== '' && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $imgVal = 'assets/uploads/' . $filename;
                    } else {
                        $imgVal = $oldImg;
                    }

                    $socialItems[] = [
                        'name' => $nameVal,
                        'url'  => $this->sanitizeUrl($item['url'] ?? '#'),
                        'img'  => $imgVal
                    ];
                }
            }

            $jsonVal = json_encode(array_values($socialItems), JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'social_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 3. تحديث القائمة الرئيسية (Menu) حسب اللغة الحالية
        elseif ($action === 'update_menu') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['menu_lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );
            $menuInput = $_POST['menu'] ?? [];

            $oldMenuRaw = $currentSettings['menu_links'] ?? '[]';
            $oldMenuData = is_string($oldMenuRaw) ? json_decode($oldMenuRaw, true) : [];
            if (!is_array($oldMenuData)) {
                $oldMenuData = [];
            }

            $formattedMenu = [];

            if (is_array($menuInput)) {
                foreach ($menuInput as $index => $item) {
                    if (!is_array($item)) continue;
                    $oldItem = $oldMenuData[$index] ?? [];

                    $itemLangs = [
                        'de' => ['title' => trim((string)($oldItem['de']['title'] ?? ''))],
                        'en' => ['title' => trim((string)($oldItem['en']['title'] ?? ''))],
                        'ar' => ['title' => trim((string)($oldItem['ar']['title'] ?? ''))],
                    ];

                    $itemLangs[$targetLang]['title'] = trim((string)($item['title'] ?? ''));

                    $rawUrl = trim((string)($item['url'] ?? ($oldItem['url'] ?? '')));
                    $cleanUrl = preg_replace('/^(ar|en|de)\//i', '', ltrim($rawUrl, '/'));

                    $formattedMenu[] = [
                        'de'    => $itemLangs['de'],
                        'en'    => $itemLangs['en'],
                        'ar'    => $itemLangs['ar'],
                        'url'   => $this->sanitizeUrl($cleanUrl, ''),
                        'order' => (int)($item['order'] ?? $index)
                    ];
                }
            }

            usort($formattedMenu, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

            $jsonVal = json_encode(array_values($formattedMenu), JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'menu_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 4. تحديث إدارة اللغات المتاحة في النظام (Languages Settings)
        elseif ($action === 'update_languages') {
            $incomingLang = $_POST['lang'] ?? [];
            $langItems = [];

            if (is_array($incomingLang)) {
                foreach ($incomingLang as $item) {
                    if (!is_array($item)) continue;
                    $codeVal   = strtolower(preg_replace('/[^a-zA-Z_-]/', '', trim((string)($item['code'] ?? ''))));
                    $nameVal   = trim((string)($item['name'] ?? ''));
                    $activeVal = isset($item['active']) && (string)$item['active'] === '1';

                    if ($codeVal === '' || $nameVal === '') continue;

                    $langItems[] = [
                        'code'   => $codeVal,
                        'name'   => $nameVal,
                        'active' => $activeVal
                    ];
                }
            }

            $jsonVal = json_encode(array_values($langItems), JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'languages', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        return true;
    }

    private function deleteOldImageFile(string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, 'assets/uploads/')) {
            return;
        }

        $uploadsBase = realpath($this->rootPath . '/public/assets/uploads');
        $targetFile  = realpath($this->rootPath . '/public/' . $imagePath);

        if ($uploadsBase !== false && $targetFile !== false && str_starts_with($targetFile, $uploadsBase . DIRECTORY_SEPARATOR) && is_file($targetFile)) {
            @unlink($targetFile);
        }
    }
}
