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

        // 1. تحديث قسم من نحن الرئيسي (About Section) مع توحيد الصور المشتركة عبر اللغات
        if ($action === 'update_about_section') {
            $targetLang = $this->resolveTargetLang(['about_lang', 'targetLang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_section', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postAbout = is_array($_POST['about'][$targetLang] ?? null)
                ? $_POST['about'][$targetLang]
                : (is_array($_POST['about'] ?? null) ? $_POST['about'] : []);

            $fallbackMain   = (string)($allLangs['shared_main_img'] ?? ($currentLangData['main_img'] ?? ''));
            $fallbackSub    = (string)($allLangs['shared_sub_img'] ?? ($currentLangData['sub_img'] ?? ''));
            $fallbackVision = (string)($allLangs['shared_vision_icon'] ?? ($currentLangData['vision_icon'] ?? ''));
            $fallbackMsg    = (string)($allLangs['shared_message_icon'] ?? ($currentLangData['message_icon'] ?? ''));

            $mainImg = $this->handleImageField(
                'about_main_img',
                (string)($postAbout['old_main_img'] ?? $fallbackMain),
                $fallbackMain
            );

            $subImg = $this->handleImageField(
                'about_sub_img',
                (string)($postAbout['old_sub_img'] ?? $fallbackSub),
                $fallbackSub
            );

            $visionIcon = $this->handleImageField(
                'about_vision_icon',
                (string)($postAbout['old_vision_icon'] ?? $fallbackVision),
                $fallbackVision
            );

            $messageIcon = $this->handleImageField(
                'about_message_icon',
                (string)($postAbout['old_message_icon'] ?? $fallbackMsg),
                $fallbackMsg
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

            $allLangs['shared_main_img']    = $mainImg;
            $allLangs['shared_sub_img']     = $subImg;
            $allLangs['shared_vision_icon'] = $visionIcon;
            $allLangs['shared_message_icon'] = $messageIcon;

            $this->propagateSharedSectionImages($allLangs, [
                'main_img'     => $mainImg,
                'sub_img'      => $subImg,
                'vision_icon'  => $visionIcon,
                'message_icon' => $messageIcon,
            ]);

            $this->saveSetting($pdo, 'about_section', $allLangs);
        }

        // 2. تحديث خدمات صفحة من نحن المستقلة (About Services) مع توحيد صور العناصر
        elseif ($action === 'update_about_services') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'about_services_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_services', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedServices = is_array($_POST['about_services'][$targetLang] ?? null)
                ? $_POST['about_services'][$targetLang]
                : (is_array($_POST['about_services'] ?? null) ? $_POST['about_services'] : []);

            $secTitle      = trim((string)($postedServices['section_title'] ?? ($currentLangData['section_title'] ?? '')));
            $secSub        = trim((string)($postedServices['section_subtitle'] ?? ($currentLangData['section_subtitle'] ?? '')));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $sharedImgs    = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $finalItems    = [];
            $newSharedImgs = [];
            $rawItems      = $postedServices['items'] ?? [];

            if (is_array($rawItems)) {
                $rowPos = 0;
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($sharedImgs[$rowPos] ?? ($sharedImgs[$index] ?? ($existingItems[$index]['img'] ?? '')));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('about_services_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'url'   => $this->sanitizeLinkUrl((string)($item['url'] ?? '#')),
                        'img'   => $img,
                    ];
                    $newSharedImgs[] = $img;
                    $rowPos++;
                }
            }

            $allLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secSub,
                'items'            => $finalItems,
            ];
            $allLangs['shared_items_img'] = $newSharedImgs;
            $this->propagateSharedListImages($allLangs, $newSharedImgs, $targetLang, ['title' => '', 'url' => '#']);

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, $allLangs);
            $this->saveSetting($pdo, 'about_services', $allLangs);
        }

        // 3. تحديث العدادات والإحصائيات (About Counts) مع توحيد الأرقام والصور وإبقاء النص التوضيحي مترجماً
        elseif ($action === 'update_about_counts') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'about_counts_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'about_counts', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null) ? $allLangs[$targetLang] : [];
            $existingItems   = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $sharedImgs      = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];
            $sharedNumbers   = is_array($allLangs['shared_items_number'] ?? null) ? $allLangs['shared_items_number'] : [];

            $postedItems = $_POST['about_counts'][$targetLang]['items']
                ?? ($_POST['about_counts']['items'] ?? []);

            $finalItems       = [];
            $newSharedImgs    = [];
            $newSharedNumbers = [];

            if (is_array($postedItems)) {
                $rowPos = 0;
                foreach ($postedItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($sharedImgs[$rowPos] ?? ($sharedImgs[$index] ?? ($existingItems[$index]['img'] ?? '')));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('count_img_' . $index, $oldImg, $dbOldImg);

                    $fallbackNum = (string)($sharedNumbers[$rowPos] ?? ($sharedNumbers[$index] ?? ($existingItems[$index]['number'] ?? '')));
                    $numVal      = trim((string)($item['number'] ?? $fallbackNum));
                    $titleVal    = trim((string)($item['title'] ?? ''));

                    $finalItems[] = [
                        'number' => $numVal,
                        'title'  => $titleVal,
                        'img'    => $img,
                    ];
                    $newSharedImgs[]    = $img;
                    $newSharedNumbers[] = $numVal;
                    $rowPos++;
                }
            }

            $allLangs[$targetLang] = [
                'items' => $finalItems,
            ];
            $allLangs['shared_items_img']    = $newSharedImgs;
            $allLangs['shared_items_number'] = $newSharedNumbers;

            foreach (self::SUPPORTED_LANGS as $langKey) {
                if ($langKey === $targetLang || !isset($allLangs[$langKey]) || !is_array($allLangs[$langKey])) {
                    continue;
                }
                $otherItems = is_array($allLangs[$langKey]['items'] ?? null) ? $allLangs[$langKey]['items'] : [];
                $maxCount   = max(count($newSharedNumbers), count($newSharedImgs), count($otherItems));

                for ($idx = 0; $idx < $maxCount; $idx++) {
                    $sharedNum = $newSharedNumbers[$idx] ?? ($otherItems[$idx]['number'] ?? '');
                    $sharedImg = $newSharedImgs[$idx] ?? ($otherItems[$idx]['img'] ?? '');
                    if (!isset($otherItems[$idx]) || !is_array($otherItems[$idx])) {
                        $otherItems[$idx] = [
                            'number' => $sharedNum,
                            'title'  => $finalItems[$idx]['title'] ?? '',
                            'img'    => $sharedImg,
                        ];
                    } else {
                        if ($sharedNum !== '') {
                            $otherItems[$idx]['number'] = $sharedNum;
                        }
                        if ($sharedImg !== '') {
                            $otherItems[$idx]['img'] = $sharedImg;
                        }
                    }
                }
                $allLangs[$langKey]['items'] = $otherItems;
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, $allLangs);
            $this->saveSetting($pdo, 'about_counts', $allLangs);
        }

        // 4. تحديث الشركاء (Partners) مع توحيد صور العناصر
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
            $sharedImgs    = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $finalItems    = [];
            $newSharedImgs = [];
            $rawItems      = $postedPartners['items'] ?? ($_POST['partner_items'] ?? []);

            if (is_array($rawItems)) {
                $rowPos = 0;
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($sharedImgs[$rowPos] ?? ($sharedImgs[$index] ?? ($existingItems[$index]['img'] ?? '')));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('partner_img_' . $index, $oldImg, $dbOldImg);

                    if ($img !== '') {
                        $finalItems[]    = ['img' => $img];
                        $newSharedImgs[] = $img;
                        $rowPos++;
                    }
                }
            }

            $allLangs[$targetLang] = [
                'title' => $partnersTitle,
                'items' => $finalItems,
            ];
            $allLangs['shared_items_img'] = $newSharedImgs;
            $this->propagateSharedListImages($allLangs, $newSharedImgs, $targetLang, []);

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, $allLangs);
            $this->saveSetting($pdo, 'partners', $allLangs);
        }

        // 5. تحديث فريق العمل (Team) مع توحيد صور العناصر
        elseif ($action === 'update_about_team' || $action === 'update_team') {
            $targetLang = $this->resolveTargetLang(['targetLang', 'team_lang']);
            $allLangs   = $this->fetchSetting($pdo, 'team', $currentSettings);

            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedTeam = is_array($_POST['team'][$targetLang] ?? null)
                ? $_POST['team'][$targetLang]
                : (is_array($_POST['team'] ?? null) ? $_POST['team'] : []);

            $teamTitle     = trim((string)($postedTeam['title'] ?? ($_POST['team_title'] ?? ($currentLangData['title'] ?? ''))));
            $teamDesc      = trim((string)($postedTeam['desc'] ?? ($_POST['team_desc'] ?? ($currentLangData['desc'] ?? ''))));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $sharedImgs    = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $finalItems    = [];
            $newSharedImgs = [];
            $rawItems      = $postedTeam['items'] ?? [];

            if (is_array($rawItems)) {
                $rowPos = 0;
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($sharedImgs[$rowPos] ?? ($sharedImgs[$index] ?? ($existingItems[$index]['img'] ?? '')));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('team_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'name' => trim((string)($item['name'] ?? '')),
                        'role' => trim((string)($item['role'] ?? '')),
                        'img'  => $img,
                    ];
                    $newSharedImgs[] = $img;
                    $rowPos++;
                }
            }

            $allLangs[$targetLang] = [
                'title' => $teamTitle,
                'desc'  => $teamDesc,
                'items' => $finalItems,
            ];
            $allLangs['shared_items_img'] = $newSharedImgs;
            $this->propagateSharedListImages($allLangs, $newSharedImgs, $targetLang, ['name' => '', 'role' => '']);

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, $allLangs);
            $this->saveSetting($pdo, 'team', $allLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
        $this->flushDeletedFiles();

        return true;
    }

    /**
     * تعميم صور القسم المشتركة على جميع اللغات الموجودة في الإعداد
     */
    private function propagateSharedSectionImages(array &$allLangs, array $imageMap): void
    {
        foreach (self::SUPPORTED_LANGS as $langKey) {
            if (!isset($allLangs[$langKey]) || !is_array($allLangs[$langKey])) {
                continue;
            }
            foreach ($imageMap as $field => $val) {
                if ($val !== '') {
                    $allLangs[$langKey][$field] = $val;
                }
            }
        }
    }

    /**
     * تعميم صور العناصر المشتركة على بقية اللغات الموجودة في الإعداد
     */
    private function propagateSharedListImages(array &$allLangs, array $sharedImgs, string $sourceLang, array $itemDefaults): void
    {
        foreach (self::SUPPORTED_LANGS as $langKey) {
            if ($langKey === $sourceLang || !isset($allLangs[$langKey]) || !is_array($allLangs[$langKey])) {
                continue;
            }
            $otherItems = is_array($allLangs[$langKey]['items'] ?? null) ? $allLangs[$langKey]['items'] : [];
            foreach ($sharedImgs as $idx => $imgPath) {
                if ($imgPath === '') {
                    continue;
                }
                if (!isset($otherItems[$idx]) || !is_array($otherItems[$idx])) {
                    $otherItems[$idx] = array_merge($itemDefaults, ['img' => $imgPath]);
                } else {
                    $otherItems[$idx]['img'] = $imgPath;
                }
            }
            $allLangs[$langKey]['items'] = $otherItems;
        }
    }

    /**
     * جدولة صور العناصر التي تم حذفها من القائمة لحذفها بأمان إذا لم تعد مستخدمة
     */
    private function queueOrphanedImagesForDeletion(array $existingItems, array $finalItems, array $allLangs): void
    {
        $retainedImages = [];
        foreach ($finalItems as $item) {
            if (is_array($item) && !empty($item['img'])) {
                $retainedImages[ltrim((string)$item['img'], '/')] = true;
            }
        }

        if (is_array($allLangs['shared_items_img'] ?? null)) {
            foreach ($allLangs['shared_items_img'] as $sImg) {
                if (is_string($sImg) && $sImg !== '') {
                    $retainedImages[ltrim($sImg, '/')] = true;
                }
            }
        }

        foreach (self::SUPPORTED_LANGS as $langKey) {
            if (!is_array($allLangs[$langKey]['items'] ?? null)) {
                continue;
            }
            foreach ($allLangs[$langKey]['items'] as $langItem) {
                if (is_array($langItem) && !empty($langItem['img'])) {
                    $retainedImages[ltrim((string)$langItem['img'], '/')] = true;
                }
            }
        }

        foreach ($existingItems as $oldItem) {
            if (!is_array($oldItem) || empty($oldItem['img'])) {
                continue;
            }
            $oldPath = ltrim((string)$oldItem['img'], '/');
            if ($oldPath !== '' && str_starts_with($oldPath, 'assets/uploads/') && !isset($retainedImages[$oldPath])) {
                $this->filesToDelete[] = $oldPath;
            }
        }
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
     * حذف الصور القديمة المستبدلة فعلياً من مجلد uploads وسجل قاعدة البيانات بعد نجاح الحفظ
     */
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
