<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class JobSettingsService
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
            'update_job_hero',
            'update_job_why',
            'update_job_programs',
            'update_job_timeline',
            'update_job_services',
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $this->filesToDelete = [];
        $targetLang = $this->resolveTargetLang(['targetLang', 'job_lang', 'lang']);

        // 1. تحديث هيرو الوظائف والمهن (Job Hero) متعدد اللغات مع توحيد shared_img
        if ($action === 'update_job_hero') {
            $allLangs = $this->fetchSetting($pdo, 'job_hero', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedHero = is_array($_POST['job_hero'][$targetLang] ?? null)
                ? $_POST['job_hero'][$targetLang]
                : (is_array($_POST['job_hero'] ?? null) ? $_POST['job_hero'] : $_POST);

            $existingSharedImg = $this->sanitizeImagePath((string)($allLangs['shared_img'] ?? ''));
            $dbOldImg = $existingSharedImg !== ''
                ? $existingSharedImg
                : (string)($currentLangData['img'] ?? 'assets/img/job/jobhero.png');

            $submittedOldImg = (string)($postedHero['old_img'] ?? ($_POST['old_job_hero_img'] ?? $dbOldImg));
            $heroImg = $this->handleImageField('job_hero_img', $submittedOldImg, $dbOldImg);
            if ($heroImg === '') {
                $heroImg = 'assets/img/job/jobhero.png';
            }

            $allLangs['shared_img'] = $heroImg;
            $allLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($postedHero['job_hero_title'] ?? ($currentLangData['title'] ?? '')))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($postedHero['job_hero_desc'] ?? ($currentLangData['desc'] ?? '')))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($postedHero['job_hero_btn_text'] ?? ($currentLangData['btn_text'] ?? '')))),
                'btn_url'  => $this->sanitizeLinkUrl((string)($postedHero['btn_url'] ?? ($postedHero['job_hero_btn_url'] ?? ($currentLangData['btn_url'] ?? 'contact')))),
                'img'      => $heroImg,
            ];

            foreach (self::SUPPORTED_LANGS as $langCode) {
                if ($langCode !== $targetLang && isset($allLangs[$langCode]) && is_array($allLangs[$langCode])) {
                    if (empty($allLangs[$langCode]['img'])) {
                        $allLangs[$langCode]['img'] = $heroImg;
                    }
                }
            }

            $this->queueOrphanedSingleImageForDeletion($allLangs, 'img');
            $this->saveSetting($pdo, 'job_hero', $allLangs);
        }

        // 2. تحديث لماذا التدريب والتوظيف (Job Why) متعدد اللغات مع توحيد shared_items_img
        elseif ($action === 'update_job_why') {
            $allLangs = $this->fetchSetting($pdo, 'job_why', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? '');
            $existingDesc  = (string)($currentLangData['desc'] ?? '');
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $existingSharedImgs = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $postedWhy = is_array($_POST['job_why'][$targetLang] ?? null)
                ? $_POST['job_why'][$targetLang]
                : (is_array($_POST['job_why'] ?? null) ? $_POST['job_why'] : $_POST);

            $jobWhyTitle = trim((string)($postedWhy['title'] ?? ($postedWhy['job_why_title'] ?? $existingTitle)));
            $jobWhyDesc  = isset($postedWhy['desc'])
                ? trim((string)$postedWhy['desc'])
                : trim((string)($postedWhy['job_why_desc'] ?? $existingDesc));

            $rawItems = $postedWhy['items'] ?? [];
            $finalItems = [];
            $sharedItemsImg = [];

            if (is_array($rawItems)) {
                $idxCounter = 0;
                foreach ($rawItems as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = $this->sanitizeImagePath((string)(
                        $existingSharedImgs[$idxCounter]
                        ?? ($existingItems[$idxCounter]['img'] ?? '')
                    ));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('job_why_img_' . $idxCounter, $oldImg, $dbOldImg);

                    $sharedItemsImg[$idxCounter] = $img;
                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'desc'  => trim((string)($item['desc'] ?? '')),
                        'img'   => $img,
                    ];
                    $idxCounter++;
                }
            }

            $allLangs['shared_items_img'] = $sharedItemsImg;
            $allLangs[$targetLang] = [
                'title' => $jobWhyTitle,
                'desc'  => $jobWhyDesc,
                'items' => $finalItems,
            ];

            $this->syncSharedArrayToOtherLangs($allLangs, $targetLang, 'items', 'img', $sharedItemsImg);
            $this->queueOrphanedListImagesForDeletion($allLangs, 'items', 'img', $sharedItemsImg);
            $this->saveSetting($pdo, 'job_why', $allLangs);
        }

        // 3. تحديث برامج وأنواع التدريب المهني (Job Programs) متعدد اللغات مع توحيد shared_items_img
        elseif ($action === 'update_job_programs') {
            $allLangs = $this->fetchSetting($pdo, 'job_programs', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? '');
            $existingDesc  = (string)($currentLangData['desc'] ?? '');
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $existingSharedImgs = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $postedPrograms = is_array($_POST['job_programs'][$targetLang] ?? null)
                ? $_POST['job_programs'][$targetLang]
                : (is_array($_POST['job_programs'] ?? null) ? $_POST['job_programs'] : $_POST);

            $jobProgTitle = trim((string)($postedPrograms['title'] ?? ($postedPrograms['job_program_title'] ?? $existingTitle)));
            $jobProgDesc  = isset($postedPrograms['desc'])
                ? trim((string)$postedPrograms['desc'])
                : trim((string)($postedPrograms['job_program_desc'] ?? $existingDesc));

            $rawPrograms = $postedPrograms['items'] ?? ($postedPrograms['programs'] ?? []);
            $finalPrograms = [];
            $sharedItemsImg = [];

            if (is_array($rawPrograms)) {
                $idxCounter = 0;
                foreach ($rawPrograms as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = $this->sanitizeImagePath((string)(
                        $existingSharedImgs[$idxCounter]
                        ?? ($existingItems[$idxCounter]['img'] ?? '')
                    ));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('prog_img_' . $idxCounter, $oldImg, $dbOldImg);

                    $isDarkVal = $item['is_dark'] ?? false;
                    $isDark = ($isDarkVal === true || $isDarkVal === '1' || $isDarkVal === 1 || $isDarkVal === 'on');

                    $sharedItemsImg[$idxCounter] = $img;
                    $finalPrograms[] = [
                        'title'    => trim((string)($item['title'] ?? '')),
                        'desc'     => trim((string)($item['desc'] ?? '')),
                        'btn_text' => trim((string)($item['btn_text'] ?? '')),
                        'btn_url'  => $this->sanitizeLinkUrl((string)($item['btn_url'] ?? '#')),
                        'is_dark'  => $isDark,
                        'img'      => $img,
                    ];
                    $idxCounter++;
                }
            }

            $allLangs['shared_items_img'] = $sharedItemsImg;
            $allLangs[$targetLang] = [
                'title' => $jobProgTitle,
                'desc'  => $jobProgDesc,
                'items' => $finalPrograms,
            ];

            $this->syncSharedArrayToOtherLangs($allLangs, $targetLang, 'items', 'img', $sharedItemsImg);
            $this->queueOrphanedListImagesForDeletion($allLangs, 'items', 'img', $sharedItemsImg);
            $this->saveSetting($pdo, 'job_programs', $allLangs);
        }

        // 4. تحديث خطوات ومسار التدريب والتوظيف (Job Timeline) متعدد اللغات مع توحيد shared_steps_img
        elseif ($action === 'update_job_timeline') {
            $allLangs = $this->fetchSetting($pdo, 'job_timeline', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? '');
            $existingDesc  = (string)($currentLangData['desc'] ?? '');
            $existingItems = is_array($currentLangData['steps'] ?? null)
                ? $currentLangData['steps']
                : (is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : []);
            $existingSharedImgs = is_array($allLangs['shared_steps_img'] ?? null) ? $allLangs['shared_steps_img'] : [];

            $postedTimeline = is_array($_POST['job_timeline'][$targetLang] ?? null)
                ? $_POST['job_timeline'][$targetLang]
                : (is_array($_POST['job_timeline'] ?? null) ? $_POST['job_timeline'] : $_POST);

            $jobTimelineTitle = trim((string)($postedTimeline['title'] ?? ($postedTimeline['job_timeline_title'] ?? $existingTitle)));
            $jobTimelineDesc  = isset($postedTimeline['desc'])
                ? trim((string)$postedTimeline['desc'])
                : trim((string)($postedTimeline['job_timeline_desc'] ?? $existingDesc));

            $rawSteps = $postedTimeline['steps'] ?? ($postedTimeline['items'] ?? []);
            $finalSteps = [];

            if (is_array($rawSteps)) {
                $idxCounter = 0;
                foreach ($rawSteps as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldIcon = $this->sanitizeImagePath((string)(
                        $existingSharedImgs[$idxCounter]
                        ?? ($existingItems[$idxCounter]['icon'] ?? '')
                    ));
                    $oldIcon   = (string)($item['icon'] ?? ($item['old_icon'] ?? $dbOldIcon));
                    $icon      = $this->handleImageField('steps_icon_' . $idxCounter, $oldIcon, $dbOldIcon);

                    $finalSteps[] = [
                        'title'    => trim((string)($item['title'] ?? '')),
                        'subtitle' => trim((string)($item['subtitle'] ?? '')),
                        'desc'     => trim((string)($item['desc'] ?? '')),
                        'order'    => (int)($item['order'] ?? $idxCounter),
                        'icon'     => $icon,
                    ];
                    $idxCounter++;
                }

                // فرز الخطوات تصاعدياً بعد ربط الملفات المرفوعة بفهارسها الصحيحة
                usort($finalSteps, static fn(array $a, array $b): int => $a['order'] <=> $b['order']);
            }

            $sharedStepsImg = [];
            foreach ($finalSteps as $sIdx => $stepRow) {
                $sharedStepsImg[$sIdx] = (string)($stepRow['icon'] ?? '');
            }

            $allLangs['shared_steps_img'] = $sharedStepsImg;
            $allLangs[$targetLang] = [
                'title' => $jobTimelineTitle,
                'desc'  => $jobTimelineDesc,
                'steps' => $finalSteps,
            ];

            $this->syncSharedArrayToOtherLangs($allLangs, $targetLang, 'steps', 'icon', $sharedStepsImg);
            $this->queueOrphanedListImagesForDeletion($allLangs, 'steps', 'icon', $sharedStepsImg);
            $this->saveSetting($pdo, 'job_timeline', $allLangs);
        }

        // 5. تحديث كروت الخدمات المهتمة (Job Services) متعدد اللغات مع توحيد shared_items_img
        elseif ($action === 'update_job_services') {
            $allLangs = $this->fetchSetting($pdo, 'job_services', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? ($currentLangData['section_title'] ?? ''));
            $existingDesc  = (string)($currentLangData['desc'] ?? ($currentLangData['section_subtitle'] ?? ''));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $existingSharedImgs = is_array($allLangs['shared_items_img'] ?? null) ? $allLangs['shared_items_img'] : [];

            $postedServices = is_array($_POST['job_services'][$targetLang] ?? null)
                ? $_POST['job_services'][$targetLang]
                : (is_array($_POST['job_services'] ?? null) ? $_POST['job_services'] : $_POST);

            $jobServicesTitle = trim((string)($postedServices['title'] ?? ($postedServices['section_title'] ?? ($postedServices['job_services_title'] ?? $existingTitle))));
            $jobServicesDesc  = isset($postedServices['desc'])
                ? trim((string)$postedServices['desc'])
                : (isset($postedServices['section_subtitle'])
                    ? trim((string)$postedServices['section_subtitle'])
                    : trim((string)($postedServices['job_services_desc'] ?? $existingDesc)));

            $rawItems = $postedServices['items'] ?? [];
            $finalItems = [];
            $sharedItemsImg = [];

            if (is_array($rawItems)) {
                $idxCounter = 0;
                foreach ($rawItems as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = $this->sanitizeImagePath((string)(
                        $existingSharedImgs[$idxCounter]
                        ?? ($existingItems[$idxCounter]['img'] ?? '')
                    ));
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('srv_img_' . $idxCounter, $oldImg, $dbOldImg);

                    $sharedItemsImg[$idxCounter] = $img;
                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'url'   => $this->sanitizeLinkUrl((string)($item['url'] ?? '#')),
                        'img'   => $img,
                    ];
                    $idxCounter++;
                }
            }

            $allLangs['shared_items_img'] = $sharedItemsImg;
            $allLangs[$targetLang] = [
                'title'            => $jobServicesTitle,
                'desc'             => $jobServicesDesc,
                'section_title'    => $jobServicesTitle,
                'section_subtitle' => $jobServicesDesc,
                'items'            => $finalItems,
            ];

            $this->syncSharedArrayToOtherLangs($allLangs, $targetLang, 'items', 'img', $sharedItemsImg);
            $this->queueOrphanedListImagesForDeletion($allLangs, 'items', 'img', $sharedItemsImg);
            $this->saveSetting($pdo, 'job_services', $allLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
        $this->flushDeletedFiles();

        return true;
    }

    /**
     * عكس الصور المشتركة على بقية اللغات في العناصر الموجودة بنفس الفهرس
     */
    private function syncSharedArrayToOtherLangs(
        array &$allLangs,
        string $targetLang,
        string $listKey,
        string $imgField,
        array $sharedImgs
    ): void {
        foreach (self::SUPPORTED_LANGS as $langCode) {
            if ($langCode === $targetLang || !isset($allLangs[$langCode]) || !is_array($allLangs[$langCode])) {
                continue;
            }
            if (!isset($allLangs[$langCode][$listKey]) || !is_array($allLangs[$langCode][$listKey])) {
                continue;
            }
            foreach ($allLangs[$langCode][$listKey] as $idx => &$row) {
                if (!is_array($row)) {
                    continue;
                }
                if (array_key_exists($idx, $sharedImgs) && $sharedImgs[$idx] !== '') {
                    $row[$imgField] = $sharedImgs[$idx];
                }
            }
            unset($row);
        }
    }

    /**
     * جدولة حذف الصور الفردية غير المستخدمة في أي لغة أو في shared_img
     */
    private function queueOrphanedSingleImageForDeletion(array $allLangs, string $imgField): void
    {
        $retained = [];
        $sharedVal = ltrim((string)($allLangs['shared_img'] ?? ''), '/');
        if ($sharedVal !== '') {
            $retained[$sharedVal] = true;
        }
        foreach (self::SUPPORTED_LANGS as $langCode) {
            $val = ltrim((string)($allLangs[$langCode][$imgField] ?? ''), '/');
            if ($val !== '') {
                $retained[$val] = true;
            }
        }

        $this->filesToDelete = array_values(array_filter(
            array_unique($this->filesToDelete),
            static fn(string $path): bool => !isset($retained[ltrim($path, '/')])
        ));
    }

    /**
     * جدولة حذف صور القوائم التي لم تعد مستخدمة في أي لغة أو في المصفوفة المشتركة
     */
    private function queueOrphanedListImagesForDeletion(
        array $allLangs,
        string $listKey,
        string $imgField,
        array $sharedImgs
    ): void {
        $retained = [];
        foreach ($sharedImgs as $imgPath) {
            $clean = ltrim((string)$imgPath, '/');
            if ($clean !== '') {
                $retained[$clean] = true;
            }
        }

        foreach (self::SUPPORTED_LANGS as $langCode) {
            $list = $allLangs[$langCode][$listKey] ?? [];
            if (!is_array($list)) {
                continue;
            }
            foreach ($list as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $clean = ltrim((string)($row[$imgField] ?? ''), '/');
                if ($clean !== '') {
                    $retained[$clean] = true;
                }
            }
        }

        $this->filesToDelete = array_values(array_filter(
            array_unique($this->filesToDelete),
            static fn(string $path): bool => !isset($retained[ltrim($path, '/')])
        ));
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
