<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class AboutSettingsService
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
            'update_about_section',
            'update_about_counts',
            'update_about_partners',
            'update_partners',
            'update_about_team',
            'update_team',
            'update_about_services',
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $this->filesToDelete = [];

        // 1. تحديث قسم من نحن الرئيسي (About Section)
        if ($action === 'update_about_section') {
            $targetLang = $this->resolveTargetLang(['about_lang', 'targetLang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_section', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postAbout = is_array($_POST['about'][$targetLang] ?? null)
                ? $_POST['about'][$targetLang]
                : (is_array($_POST['about'] ?? null) ? $_POST['about'] : []);

            $mainImg = $this->handleImageField(
                'about_main_img',
                (string)($postAbout['old_main_img'] ?? ($currentLangData['main_img'] ?? '')),
                (string)($currentLangData['main_img'] ?? '')
            );

            $subImg = $this->handleImageField(
                'about_sub_img',
                (string)($postAbout['old_sub_img'] ?? ($currentLangData['sub_img'] ?? '')),
                (string)($currentLangData['sub_img'] ?? '')
            );

            $visionIcon = $this->handleImageField(
                'about_vision_icon',
                (string)($postAbout['old_vision_icon'] ?? ($currentLangData['vision_icon'] ?? '')),
                (string)($currentLangData['vision_icon'] ?? '')
            );

            $messageIcon = $this->handleImageField(
                'about_message_icon',
                (string)($postAbout['old_message_icon'] ?? ($currentLangData['message_icon'] ?? '')),
                (string)($currentLangData['message_icon'] ?? '')
            );

            $allLangs[$targetLang] = [
                'title'         => trim((string)($postAbout['title'] ?? ($currentLangData['title'] ?? ''))),
                'desc'          => trim((string)($postAbout['desc'] ?? ($currentLangData['desc'] ?? ''))),
                'btn_text'      => trim((string)($postAbout['btn_text'] ?? ($currentLangData['btn_text'] ?? ''))),
                'btn_url'       => $this->sanitizeLinkUrl((string)($postAbout['btn_url'] ?? ($currentLangData['btn_url'] ?? '#'))),
                'main_img'      => $mainImg,
                'sub_img'       => $subImg,
                'vision_title'  => trim((string)($postAbout['vision_title'] ?? ($currentLangData['vision_title'] ?? ''))),
                'vision_desc'   => trim((string)($postAbout['vision_desc'] ?? ($currentLangData['vision_desc'] ?? ''))),
                'vision_icon'   => $visionIcon,
                'message_title' => trim((string)($postAbout['message_title'] ?? ($currentLangData['message_title'] ?? ''))),
                'message_desc'  => trim((string)($postAbout['message_desc'] ?? ($currentLangData['message_desc'] ?? ''))),
                'message_icon'  => $messageIcon,
            ];

            $this->saveSetting($pdo, 'about_section', $allLangs);
        }

        // 2. تحديث خدمات صفحة من نحن المستقلة (About Services)
        elseif ($action === 'update_about_services') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'about_services_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_services', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedServices = is_array($_POST['about_services'][$targetLang] ?? null)
                ? $_POST['about_services'][$targetLang]
                : (is_array($_POST['about_services'] ?? null) ? $_POST['about_services'] : []);

            $secTitle = trim((string)($postedServices['section_title'] ?? ($currentLangData['section_title'] ?? '')));
            $secSub   = trim((string)($postedServices['section_subtitle'] ?? ($currentLangData['section_subtitle'] ?? '')));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $finalItems = [];
            $rawItems = $postedServices['items'] ?? null;

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('about_services_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'url'   => $this->sanitizeLinkUrl((string)($item['url'] ?? '#')),
                        'img'   => $img,
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $allLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secSub,
                'items'            => $finalItems,
            ];

            $this->saveSetting($pdo, 'about_services', $allLangs);
        }

        // 3. تحديث العدادات والإحصائيات (About Counts)
        elseif ($action === 'update_about_counts') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'about_counts_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_counts', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null) ? $allLangs[$targetLang] : [];
            $existingItems   = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $postedItems = $_POST['about_counts'][$targetLang]['items']
                ?? ($_POST['about_counts']['items'] ?? null);

            $finalItems = [];
            if (is_array($postedItems)) {
                foreach ($postedItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('count_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'number' => trim((string)($item['number'] ?? '')),
                        'title'  => trim((string)($item['title'] ?? '')),
                        'img'    => $img,
                    ];
                }
            }

            $allLangs[$targetLang] = [
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'about_counts', $allLangs);
        }

        // 4. تحديث الشركاء (Partners)
        elseif ($action === 'update_partners' || $action === 'update_about_partners') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'partners_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'partners', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedPartners = is_array($_POST['partners'][$targetLang] ?? null)
                ? $_POST['partners'][$targetLang]
                : (is_array($_POST['partners'] ?? null) ? $_POST['partners'] : []);

            $partnersTitle = trim((string)($postedPartners['title'] ?? ($_POST['partners_title'] ?? ($currentLangData['title'] ?? ''))));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $finalItems = [];
            $rawItems = $postedPartners['items'] ?? ($_POST['partner_items'] ?? null);

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('partner_img_' . $index, $oldImg, $dbOldImg);

                    if ($img !== '') {
                        $finalItems[] = ['img' => $img];
                    }
                }
            } else {
                $finalItems = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title' => $partnersTitle,
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'partners', $allLangs);
        }

        // 5. تحديث فريق العمل (Team)
        elseif ($action === 'update_about_team' || $action === 'update_team') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'team_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'team', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedTeam = is_array($_POST['team'][$targetLang] ?? null)
                ? $_POST['team'][$targetLang]
                : (is_array($_POST['team'] ?? null) ? $_POST['team'] : []);

            $teamTitle = trim((string)($postedTeam['title'] ?? ($_POST['team_title'] ?? ($currentLangData['title'] ?? ''))));
            $teamDesc  = trim((string)($postedTeam['desc'] ?? ($_POST['team_desc'] ?? ($currentLangData['desc'] ?? ''))));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $finalItems = [];
            $rawItems = $postedTeam['items'] ?? null;

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('team_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'name' => trim((string)($item['name'] ?? '')),
                        'role' => trim((string)($item['role'] ?? '')),
                        'img'  => $img,
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title' => $teamTitle,
                'desc'  => $teamDesc,
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'team', $allLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
        $this->flushDeletedFiles();

        return true;
    }

    /**
     * تحديد اللغة المستهدفة وفق القائمة البيضاء المعتمدة (de | en | ar)
     */
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

    /**
     * جلب الإعداد متعدد اللغات من قاعدة البيانات أو المصفوفة الحالية
     */
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

    /**
     * حفظ الإعداد في قاعدة البيانات بترميز UTF-8 آمن
     */
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

    /**
     * معالجة رفع الصورة الجديدة أو تنقية المسار القديم وجدولة حذفه بأمان
     */
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

    /**
     * تنقية مسار الصورة لمنع Path Traversal والبروتوكولات الخطرة
     */
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

    /**
     * تنقية الروابط لمنع حقن javascript:
     */
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

    /**
     * حذف الصور القديمة المستبدلة فعلياً من مجلد uploads بعد نجاح الحفظ
     */
    private function flushDeletedFiles(): void
    {
        $uploadsRoot = realpath($this->rootPath . '/public/assets/uploads');
        if ($uploadsRoot === false) {
            return;
        }

        foreach (array_unique($this->filesToDelete) as $relativePath) {
            if (!str_starts_with($relativePath, 'assets/uploads/')) {
                continue;
            }
            $targetFile = realpath($this->rootPath . '/public/' . $relativePath);
            if ($targetFile !== false && str_starts_with($targetFile, $uploadsRoot . DIRECTORY_SEPARATOR) && is_file($targetFile)) {
                @unlink($targetFile);
            }
        }
        $this->filesToDelete = [];
    }
}
