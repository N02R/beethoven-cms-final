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

        // 1. تحديث هيرو الوظائف والمهن (Job Hero) متعدد اللغات
        if ($action === 'update_job_hero') {
            $allLangs = $this->fetchSetting($pdo, 'job_hero', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedHero = is_array($_POST['job_hero'][$targetLang] ?? null)
                ? $_POST['job_hero'][$targetLang]
                : (is_array($_POST['job_hero'] ?? null) ? $_POST['job_hero'] : $_POST);

            $dbOldImg = (string)($currentLangData['img'] ?? 'assets/img/job/jobhero.png');
            $submittedOldImg = (string)($postedHero['old_img'] ?? ($_POST['old_job_hero_img'] ?? $dbOldImg));
            $heroImg = $this->handleImageField('job_hero_img', $submittedOldImg, $dbOldImg);
            if ($heroImg === '') {
                $heroImg = 'assets/img/job/jobhero.png';
            }

            $allLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($postedHero['job_hero_title'] ?? ($currentLangData['title'] ?? '')))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($postedHero['job_hero_desc'] ?? ($currentLangData['desc'] ?? '')))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($postedHero['job_hero_btn_text'] ?? ($currentLangData['btn_text'] ?? '')))),
                'btn_url'  => $this->sanitizeLinkUrl((string)($postedHero['btn_url'] ?? ($postedHero['job_hero_btn_url'] ?? ($currentLangData['btn_url'] ?? 'contact')))),
                'img'      => $heroImg,
            ];

            $this->saveSetting($pdo, 'job_hero', $allLangs);
        }

        // 2. تحديث لماذا التدريب والتوظيف (Job Why) متعدد اللغات
        elseif ($action === 'update_job_why') {
            $allLangs = $this->fetchSetting($pdo, 'job_why', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? '');
            $existingDesc  = (string)($currentLangData['desc'] ?? '');
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $postedWhy = is_array($_POST['job_why'][$targetLang] ?? null)
                ? $_POST['job_why'][$targetLang]
                : (is_array($_POST['job_why'] ?? null) ? $_POST['job_why'] : $_POST);

            $jobWhyTitle = trim((string)($postedWhy['title'] ?? ($postedWhy['job_why_title'] ?? $existingTitle)));
            $jobWhyDesc  = isset($postedWhy['desc'])
                ? trim((string)$postedWhy['desc'])
                : trim((string)($postedWhy['job_why_desc'] ?? $existingDesc));

            $rawItems = $postedWhy['items'] ?? [];
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('job_why_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'desc'  => trim((string)($item['desc'] ?? '')),
                        'img'   => $img,
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, 'img');

            $allLangs[$targetLang] = [
                'title' => $jobWhyTitle,
                'desc'  => $jobWhyDesc,
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'job_why', $allLangs);
        }

        // 3. تحديث برامج وأنواع التدريب المهني (Job Programs) متعدد اللغات
        elseif ($action === 'update_job_programs') {
            $allLangs = $this->fetchSetting($pdo, 'job_programs', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? '');
            $existingDesc  = (string)($currentLangData['desc'] ?? '');
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $postedPrograms = is_array($_POST['job_programs'][$targetLang] ?? null)
                ? $_POST['job_programs'][$targetLang]
                : (is_array($_POST['job_programs'] ?? null) ? $_POST['job_programs'] : $_POST);

            $jobProgTitle = trim((string)($postedPrograms['title'] ?? ($postedPrograms['job_program_title'] ?? $existingTitle)));
            $jobProgDesc  = isset($postedPrograms['desc'])
                ? trim((string)$postedPrograms['desc'])
                : trim((string)($postedPrograms['job_program_desc'] ?? $existingDesc));

            $rawPrograms = $postedPrograms['items'] ?? ($postedPrograms['programs'] ?? []);
            $finalPrograms = [];

            if (is_array($rawPrograms)) {
                foreach ($rawPrograms as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('prog_img_' . $index, $oldImg, $dbOldImg);

                    $isDarkVal = $item['is_dark'] ?? false;
                    $isDark = ($isDarkVal === true || $isDarkVal === '1' || $isDarkVal === 1 || $isDarkVal === 'on');

                    $finalPrograms[] = [
                        'title'    => trim((string)($item['title'] ?? '')),
                        'desc'     => trim((string)($item['desc'] ?? '')),
                        'btn_text' => trim((string)($item['btn_text'] ?? '')),
                        'btn_url'  => $this->sanitizeLinkUrl((string)($item['btn_url'] ?? '#')),
                        'is_dark'  => $isDark,
                        'img'      => $img,
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalPrograms, 'img');

            $allLangs[$targetLang] = [
                'title' => $jobProgTitle,
                'desc'  => $jobProgDesc,
                'items' => $finalPrograms,
            ];

            $this->saveSetting($pdo, 'job_programs', $allLangs);
        }

        // 4. تحديث خطوات ومسار التدريب والتوظيف (Job Timeline) متعدد اللغات
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
                foreach ($rawSteps as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldIcon = (string)($existingItems[$index]['icon'] ?? '');
                    $oldIcon   = (string)($item['icon'] ?? ($item['old_icon'] ?? $dbOldIcon));
                    $icon      = $this->handleImageField('steps_icon_' . $index, $oldIcon, $dbOldIcon);

                    $finalSteps[] = [
                        'title'    => trim((string)($item['title'] ?? '')),
                        'subtitle' => trim((string)($item['subtitle'] ?? '')),
                        'desc'     => trim((string)($item['desc'] ?? '')),
                        'order'    => (int)($item['order'] ?? $index),
                        'icon'     => $icon,
                    ];
                }

                // فرز الخطوات تصاعدياً بعد ربط الملفات المرفوعة بفهارسها الصحيحة
                usort($finalSteps, static fn(array $a, array $b): int => $a['order'] <=> $b['order']);
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalSteps, 'icon');

            $allLangs[$targetLang] = [
                'title' => $jobTimelineTitle,
                'desc'  => $jobTimelineDesc,
                'steps' => $finalSteps,
            ];

            $this->saveSetting($pdo, 'job_timeline', $allLangs);
        }

        // 5. تحديث كروت الخدمات المهتمة (Job Services) متعدد اللغات
        elseif ($action === 'update_job_services') {
            $allLangs = $this->fetchSetting($pdo, 'job_services', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $existingTitle = (string)($currentLangData['title'] ?? ($currentLangData['section_title'] ?? ''));
            $existingDesc  = (string)($currentLangData['desc'] ?? ($currentLangData['section_subtitle'] ?? ''));
            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

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

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $oldImg   = (string)($item['img'] ?? ($item['old_img'] ?? $dbOldImg));
                    $img      = $this->handleImageField('srv_img_' . $index, $oldImg, $dbOldImg);

                    $finalItems[] = [
                        'title' => trim((string)($item['title'] ?? '')),
                        'url'   => $this->sanitizeLinkUrl((string)($item['url'] ?? '#')),
                        'img'   => $img,
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, 'img');

            $allLangs[$targetLang] = [
                'title'            => $jobServicesTitle,
                'desc'             => $jobServicesDesc,
                'section_title'    => $jobServicesTitle,
                'section_subtitle' => $jobServicesDesc,
                'items'            => $finalItems,
            ];

            $this->saveSetting($pdo, 'job_services', $allLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
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
