<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class HomeSettingsService
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

    /**
     * تحديد اللغة المستهدفة بأمان وفق القائمة البيضاء المعتمدة
     */
    private function resolveTargetLang(string ...$candidates): string
    {
        foreach ($candidates as $lang) {
            $clean = strtolower(trim($lang));
            if ($clean !== '' && in_array($clean, self::SUPPORTED_LANGS, true)) {
                return $clean;
            }
        }
        return 'de';
    }

    /**
     * تنقية الروابط لمنع ثغرات XSS (javascript: / data:)
     */
    private function sanitizeUrl(mixed $rawUrl, string $default = '#'): string
    {
        $url = trim((string)($rawUrl ?? ''));
        if ($url === '' || $url === '#') {
            return $default;
        }
        if (preg_match('/^(javascript|data|vbscript|file):/i', $url)) {
            return $default;
        }
        return $url;
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
     * تحويل رابط يوتيوب إلى صيغة Embed المحمية للخصوصية الألمانية (DSGVO / youtube-nocookie)
     */
    private function convertToSafeYoutubeEmbed(string $url): string
    {
        $url = trim($url);
        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }

        $parsed = parse_url($url);
        $host   = strtolower($parsed['host'] ?? '');
        $allowedHosts = ['www.youtube.com', 'youtube.com', 'youtu.be', 'www.youtube-nocookie.com', 'youtube-nocookie.com'];

        if (!in_array($host, $allowedHosts, true)) {
            return '';
        }

        if (preg_match('/(?:embed\/|v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1];
        }

        return '';
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $allowedActions = [
            'update_hero',
            'update_services',
            'update_faq',
            'update_reviews',
            'update_choose',
            'update_guide',
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $this->filesToDelete = [];

        // 1. تحديث قسم الهيرو (Hero)
        if ($action === 'update_hero') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['hero_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $heroAllLangs = $this->fetchSetting($pdo, 'hero', $currentSettings);

            if (isset($heroAllLangs['title']) && !isset($heroAllLangs['de']) && !isset($heroAllLangs['ar'])) {
                $oldData = $heroAllLangs;
                $heroAllLangs = [$targetLang => $oldData];
            }

            $dbOldImg = (string)($heroAllLangs[$targetLang]['img'] ?? ($heroAllLangs['img'] ?? 'assets/img/hero-bg.jpg'));
            $submittedOldImg = (string)($_POST['old_hero_img'] ?? $dbOldImg);
            $safeOldImg = $this->sanitizeImagePath($submittedOldImg !== '' ? $submittedOldImg : $dbOldImg);

            if (isset($_FILES['hero_img']) && is_array($_FILES['hero_img']) && ($_FILES['hero_img']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $filename = $this->imageUploader->processAndUploadFile((string)$_FILES['hero_img']['tmp_name']);
                if ($dbOldImg !== '' && str_starts_with(ltrim($dbOldImg, '/'), 'assets/uploads/')) {
                    $this->filesToDelete[] = ltrim($dbOldImg, '/');
                }
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $safeOldImg !== '' ? $safeOldImg : 'assets/img/hero-bg.jpg';
            }

            $postedHero = is_array($_POST['hero'][$targetLang] ?? null)
                ? $_POST['hero'][$targetLang]
                : (is_array($_POST['hero'] ?? null) ? $_POST['hero'] : []);

            $heroAllLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($_POST['hero_title'] ?? ''))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($_POST['hero_desc'] ?? ''))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($_POST['hero_btn_text'] ?? ''))),
                'btn_url'  => $this->sanitizeUrl($postedHero['btn_url'] ?? ($_POST['hero_btn_url'] ?? '#')),
                'img'      => $heroImg,
            ];

            $heroAllLangs['img'] = $heroImg;

            $this->saveSetting($pdo, 'hero', $heroAllLangs);
        }

        // 2. تحديث قسم الخدمات (Services)
        elseif ($action === 'update_services') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['services_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $servicesAllLangs = $this->fetchSetting($pdo, 'services', $currentSettings);
            $existingItems = is_array($servicesAllLangs[$targetLang]['items'] ?? null)
                ? $servicesAllLangs[$targetLang]['items']
                : [];

            $postedServices = is_array($_POST['services'][$targetLang] ?? null)
                ? $_POST['services'][$targetLang]
                : (is_array($_POST['services'] ?? null) ? $_POST['services'] : []);

            $secTitle = trim((string)($postedServices['section_title'] ?? ($postedServices['title'] ?? ($_POST['services_section_title'] ?? ''))));
            $secDesc  = trim((string)($postedServices['section_subtitle'] ?? ($postedServices['desc'] ?? ($_POST['services_section_subtitle'] ?? ''))));

            $finalItems = [];
            $items = $postedServices['items'] ?? ($_POST['service_items'] ?? ($_POST['services_items'] ?? []));

            if (is_array($items)) {
                foreach ($items as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $submittedOldImg = (string)($item['old_img'] ?? ($item['img'] ?? $dbOldImg));
                    $safeOldImg = $this->sanitizeImagePath($submittedOldImg !== '' ? $submittedOldImg : $dbOldImg);
                    $img = $safeOldImg;

                    $fileTmp = null;
                    $fileErr = UPLOAD_ERR_NO_FILE;

                    if (isset($_FILES['service_items']['tmp_name'][$index]['img']) && ($_FILES['service_items']['error'][$index]['img'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_items']['tmp_name'][$index]['img'];
                        $fileErr = $_FILES['service_items']['error'][$index]['img'];
                    } elseif (isset($_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img']) && ($_FILES['services']['error'][$targetLang]['items'][$index]['img'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img'];
                        $fileErr = $_FILES['services']['error'][$targetLang]['items'][$index]['img'];
                    } elseif (isset($_FILES['service_img_' . $index]) && is_array($_FILES['service_img_' . $index]) && ($_FILES['service_img_' . $index]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_img_' . $index]['tmp_name'];
                        $fileErr = $_FILES['service_img_' . $index]['error'];
                    }

                    if ($fileTmp !== null && $fileErr === UPLOAD_ERR_OK) {
                        $filename = $this->imageUploader->processAndUploadFile((string)$fileTmp);
                        if ($dbOldImg !== '' && str_starts_with(ltrim($dbOldImg, '/'), 'assets/uploads/')) {
                            $this->filesToDelete[] = ltrim($dbOldImg, '/');
                        }
                        $img = 'assets/uploads/' . $filename;
                    }

                    $title = trim((string)($item['title'] ?? ''));
                    if ($title === '' && $img === '') {
                        continue;
                    }

                    $finalItems[] = [
                        'title' => $title,
                        'desc'  => trim((string)($item['desc'] ?? '')),
                        'icon'  => trim((string)($item['icon'] ?? 'bi bi-concierge-bell')),
                        'img'   => $img,
                        'url'   => $this->sanitizeUrl($item['url'] ?? '#'),
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, 'img');

            $servicesAllLangs[$targetLang] = [
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'items'            => $finalItems,
            ];

            $this->saveSetting($pdo, 'services', $servicesAllLangs);
        }

        // 3. تحديث الأسئلة الشائعة (FAQ)
        elseif ($action === 'update_faq') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['faq_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $faqAllLangs = $this->fetchSetting($pdo, 'faq', $currentSettings);

            $currentLangData = is_array($faqAllLangs[$targetLang] ?? null) ? $faqAllLangs[$targetLang] : [];
            $existingTitle   = (string)($currentLangData['title'] ?? '');
            $existingDesc    = (string)($currentLangData['desc'] ?? '');

            $postedFaq = is_array($_POST['faq'][$targetLang] ?? null) ? $_POST['faq'][$targetLang] : [];

            $faqTitle = trim((string)($postedFaq['title'] ?? ($_POST['faq_title'][$targetLang] ?? ($_POST['faq_title'] ?? ''))));
            if ($faqTitle === '') {
                $faqTitle = $existingTitle;
            }

            $faqDesc = isset($postedFaq['desc']) ? trim((string)$postedFaq['desc']) : $existingDesc;

            $rawItems = $postedFaq['items'] ?? ($_POST['faq_items'] ?? []);
            $processedItems = [];
            if (is_array($rawItems)) {
                foreach ($rawItems as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $q = trim((string)($item['question'] ?? ($item['title'] ?? '')));
                    $a = trim((string)($item['answer'] ?? ($item['desc'] ?? '')));

                    if ($q !== '' || $a !== '') {
                        $processedItems[] = [
                            'question' => $q,
                            'answer'   => $a,
                        ];
                    }
                }
            }

            $faqAllLangs[$targetLang] = [
                'title' => $faqTitle,
                'desc'  => $faqDesc,
                'items' => $processedItems,
            ];

            $this->saveSetting($pdo, 'faq', $faqAllLangs);
        }

        // 4. تحديث التقييمات وفيديوهات العملاء (Reviews - DSGVO No-Cookie)
        elseif ($action === 'update_reviews') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['reviews_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $reviewsAllLangs = $this->fetchSetting($pdo, 'reviews_items', $currentSettings);

            $currentLangData = is_array($reviewsAllLangs[$targetLang] ?? null) ? $reviewsAllLangs[$targetLang] : [];
            $postedReviews   = is_array($_POST['reviews'][$targetLang] ?? null)
                ? $_POST['reviews'][$targetLang]
                : (is_array($_POST['reviews'] ?? null) ? $_POST['reviews'] : []);

            $reviewsTitle = trim((string)($postedReviews['title'] ?? ($_POST['reviews_title'] ?? ($currentLangData['title'] ?? ''))));

            $finalItems = [];
            if (isset($postedReviews['items']) && is_array($postedReviews['items'])) {
                foreach ($postedReviews['items'] as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $rawUrl   = trim((string)($item['url'] ?? ''));
                    $embedUrl = $this->convertToSafeYoutubeEmbed($rawUrl);

                    if ($embedUrl !== '') {
                        $finalItems[] = ['url' => $embedUrl];
                    }
                }
            }

            $reviewsAllLangs[$targetLang] = [
                'title' => $reviewsTitle,
                'items' => $finalItems,
            ];

            $this->saveSetting($pdo, 'reviews_items', $reviewsAllLangs);
        }

        // 5. تحديث قسم المميزات (Choose Us)
        elseif ($action === 'update_choose') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['choose_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $chooseAllLangs = $this->fetchSetting($pdo, 'choose', $currentSettings);

            $currentLangData = is_array($chooseAllLangs[$targetLang] ?? null) ? $chooseAllLangs[$targetLang] : [];
            $postedChoose    = is_array($_POST['choose'][$targetLang] ?? null)
                ? $_POST['choose'][$targetLang]
                : (is_array($_POST['choose'] ?? null) ? $_POST['choose'] : []);

            $secTitle = trim((string)($postedChoose['section_title']
                ?? ($_POST['choose_section_title']
                ?? ($currentLangData['section_title'] ?? ($currentLangData['title'] ?? '')))));

            $secDesc = trim((string)($postedChoose['section_subtitle']
                ?? ($_POST['choose_section_subtitle']
                ?? ($currentLangData['section_subtitle'] ?? ($currentLangData['desc'] ?? '')))));

            $existingItems = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];
            $finalItems = [];

            if (isset($postedChoose['items']) && is_array($postedChoose['items'])) {
                foreach ($postedChoose['items'] as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $submittedOldImg = (string)($item['old_img'] ?? ($item['img'] ?? $dbOldImg));
                    $safeOldImg = $this->sanitizeImagePath($submittedOldImg !== '' ? $submittedOldImg : $dbOldImg);
                    $img = $safeOldImg;

                    if (isset($_FILES['choose_img_' . $index]) && is_array($_FILES['choose_img_' . $index]) && ($_FILES['choose_img_' . $index]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $filename = $this->imageUploader->processAndUploadFile((string)$_FILES['choose_img_' . $index]['tmp_name']);
                        if ($dbOldImg !== '' && str_starts_with(ltrim($dbOldImg, '/'), 'assets/uploads/')) {
                            $this->filesToDelete[] = ltrim($dbOldImg, '/');
                        }
                        $img = 'assets/uploads/' . $filename;
                    }

                    $title = trim((string)($item['title'] ?? ''));
                    $desc  = trim((string)($item['desc'] ?? ''));
                    if ($title === '' && $desc === '') {
                        continue;
                    }

                    $finalItems[] = [
                        'title' => $title,
                        'desc'  => $desc,
                        'img'   => $img,
                        'url'   => $this->sanitizeUrl($item['url'] ?? '#'),
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $finalItems, 'img');

            $chooseAllLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'items'            => $finalItems,
            ];

            $this->saveSetting($pdo, 'choose', $chooseAllLangs);
        }

        // 6. تحديث الدليل الشامل (Guide)
        elseif ($action === 'update_guide') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['guide_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $guideAllLangs = $this->fetchSetting($pdo, 'guide', $currentSettings);

            $currentLangData = is_array($guideAllLangs[$targetLang] ?? null) ? $guideAllLangs[$targetLang] : [];
            $existingTitle   = (string)($currentLangData['title'] ?? '');
            $existingDesc    = (string)($currentLangData['desc'] ?? '');
            $existingBtn     = (string)($currentLangData['read_more_btn'] ?? '');
            $existingItems   = is_array($currentLangData['items'] ?? null) ? $currentLangData['items'] : [];

            $postedGuide = is_array($_POST['guide'][$targetLang] ?? null) ? $_POST['guide'][$targetLang] : [];

            $guideTitle = trim((string)($postedGuide['title'] ?? ''));
            if ($guideTitle === '') {
                $guideTitle = $existingTitle;
            }

            $guideDesc = isset($postedGuide['desc']) ? trim((string)$postedGuide['desc']) : $existingDesc;

            $readMoreBtn = trim((string)($postedGuide['read_more_btn'] ?? ($postedGuide['read_more'] ?? '')));
            if ($readMoreBtn === '') {
                $readMoreBtn = $existingBtn;
            }

            $processedItems = [];
            if (isset($postedGuide['items']) && is_array($postedGuide['items'])) {
                foreach ($postedGuide['items'] as $index => $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $dbOldImg = (string)($existingItems[$index]['img'] ?? '');
                    $submittedOldImg = (string)($item['old_img'] ?? ($item['img'] ?? $dbOldImg));
                    $safeOldImg = $this->sanitizeImagePath($submittedOldImg !== '' ? $submittedOldImg : $dbOldImg);
                    $finalImg = $safeOldImg;

                    if (isset($_FILES['guide_img_' . $index]) && is_array($_FILES['guide_img_' . $index]) && ($_FILES['guide_img_' . $index]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $filename = $this->imageUploader->processAndUploadFile((string)$_FILES['guide_img_' . $index]['tmp_name']);
                        if ($dbOldImg !== '' && str_starts_with(ltrim($dbOldImg, '/'), 'assets/uploads/')) {
                            $this->filesToDelete[] = ltrim($dbOldImg, '/');
                        }
                        $finalImg = 'assets/uploads/' . $filename;
                    }

                    $title = trim((string)($item['title'] ?? ''));
                    if ($title === '' && $finalImg === '') {
                        continue;
                    }

                    $processedItems[] = [
                        'title' => $title,
                        'desc'  => trim((string)($item['desc'] ?? '')),
                        'url'   => $this->sanitizeUrl($item['url'] ?? '#'),
                        'img'   => $finalImg,
                    ];
                }
            }

            $this->queueOrphanedImagesForDeletion($existingItems, $processedItems, 'img');

            $guideAllLangs[$targetLang] = [
                'title'         => $guideTitle,
                'desc'          => $guideDesc,
                'read_more_btn' => $readMoreBtn,
                'items'         => $processedItems,
            ];

            $this->saveSetting($pdo, 'guide', $guideAllLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
        $this->flushDeletedFiles();

        return true;
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
