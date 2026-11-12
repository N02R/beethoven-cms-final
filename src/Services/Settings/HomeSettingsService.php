<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class HomeSettingsService
{
    private string $rootPath;
    private ImageUploader $imageUploader;
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = $rootPath;
        $this->imageUploader = $imageUploader;
    }

    /**
     * تحديد اللغة المستهدفة بأمان وفق القائمة البيضاء المعتمدة
     */
    private function resolveTargetLang(string ...$candidates): string
    {
        foreach ($candidates as $lang) {
            if ($lang !== '' && in_array($lang, self::SUPPORTED_LANGS, true)) {
                return $lang;
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
        if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
            return $default;
        }
        return $url;
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
            'update_guide'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("
            INSERT INTO global_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        // 1. تحديث قسم الهيرو (Hero)
        if ($action === 'update_hero') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['hero_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $rawHeroSetting = $currentSettings['hero'] ?? '';
            $heroAllLangs = is_string($rawHeroSetting) ? json_decode($rawHeroSetting, true) : (is_array($rawHeroSetting) ? $rawHeroSetting : []);
            if (!is_array($heroAllLangs)) {
                $heroAllLangs = [];
            }

            if (isset($heroAllLangs['title']) && !isset($heroAllLangs['de']) && !isset($heroAllLangs['ar'])) {
                $oldData = $heroAllLangs;
                $heroAllLangs = [$targetLang => $oldData];
            }

            $oldHeroImg = (string)($_POST['old_hero_img'] 
                        ?? $heroAllLangs[$targetLang]['img'] 
                        ?? $heroAllLangs['img'] 
                        ?? 'assets/img/hero-bg.jpg');
            
            if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                if (str_starts_with($oldHeroImg, 'assets/uploads/')) {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $oldHeroImg;
            }

            $postedHero = $_POST['hero'][$targetLang] ?? $_POST['hero'] ?? [];

            $heroAllLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($_POST['hero_title'] ?? ''))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($_POST['hero_desc'] ?? ''))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($_POST['hero_btn_text'] ?? ''))),
                'btn_url'  => $this->sanitizeUrl($postedHero['btn_url'] ?? ($_POST['hero_btn_url'] ?? '#')),
                'img'      => $heroImg
            ];

            $heroAllLangs['img'] = $heroImg;

            $jsonVal = json_encode($heroAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 2. تحديث قسم الخدمات (Services)
        elseif ($action === 'update_services') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['services_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $rawServicesSetting = $currentSettings['services'] ?? '';
            $servicesAllLangs = is_string($rawServicesSetting) ? json_decode($rawServicesSetting, true) : (is_array($rawServicesSetting) ? $rawServicesSetting : []);
            if (!is_array($servicesAllLangs)) {
                $servicesAllLangs = [];
            }

            $postedServices = $_POST['services'][$targetLang] ?? $_POST['services'] ?? [];

            $secTitle = trim((string)($postedServices['section_title'] ?? ($postedServices['title'] ?? ($_POST['services_section_title'] ?? ''))));
            $secDesc  = trim((string)($postedServices['section_subtitle'] ?? ($postedServices['desc'] ?? ($_POST['services_section_subtitle'] ?? ''))));

            $servicesAllLangs[$targetLang] = [
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'items'            => []
            ];

            $items = $postedServices['items'] ?? $_POST['service_items'] ?? $_POST['services_items'] ?? [];
            if (is_array($items)) {
                foreach ($items as $index => $item) {
                    if (!is_array($item)) continue;
                    $oldImg = (string)($item['old_img'] ?? ($servicesAllLangs[$targetLang]['items'][$index]['img'] ?? ''));
                    $img = $oldImg;

                    $fileTmp = null;
                    $fileErr = UPLOAD_ERR_NO_FILE;

                    if (isset($_FILES['service_items']['tmp_name'][$index]['img']) && $_FILES['service_items']['error'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_items']['tmp_name'][$index]['img'];
                        $fileErr = $_FILES['service_items']['error'][$index]['img'];
                    } elseif (isset($_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img']) && $_FILES['services']['error'][$targetLang]['items'][$index]['img'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['services']['tmp_name'][$targetLang]['items'][$index]['img'];
                        $fileErr = $_FILES['services']['error'][$targetLang]['items'][$index]['img'];
                    } elseif (isset($_FILES['service_img_' . $index]) && $_FILES['service_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        $fileTmp = $_FILES['service_img_' . $index]['tmp_name'];
                        $fileErr = $_FILES['service_img_' . $index]['error'];
                    }

                    if ($fileTmp && $fileErr === UPLOAD_ERR_OK) {
                        if (str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileTmp);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $title = trim((string)($item['title'] ?? ''));
                    if ($title === '' && $img === '') {
                        continue;
                    }

                    $servicesAllLangs[$targetLang]['items'][] = [
                        'title' => $title,
                        'desc'  => trim((string)($item['desc'] ?? '')),
                        'icon'  => trim((string)($item['icon'] ?? 'bi bi-concierge-bell')),
                        'img'   => $img,
                        'url'   => $this->sanitizeUrl($item['url'] ?? '#')
                    ];
                }
            }

            $jsonVal = json_encode($servicesAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 3. تحديث الأسئلة الشائعة (FAQ)
        elseif ($action === 'update_faq') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['faq_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'faq'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $faqAllLangs = ($dbRaw && is_string($dbRaw)) ? (json_decode($dbRaw, true) ?? []) : [];
            if (!is_array($faqAllLangs)) {
                $faqAllLangs = [];
            }

            $currentLangData = $faqAllLangs[$targetLang] ?? [];
            $existingTitle   = $currentLangData['title'] ?? '';
            $existingDesc    = $currentLangData['desc'] ?? '';

            $postedFaq = $_POST['faq'][$targetLang] ?? [];

            $faqTitle = trim((string)($postedFaq['title'] ?? ($_POST['faq_title'][$targetLang] ?? ($_POST['faq_title'] ?? ''))));
            if ($faqTitle === '') {
                $faqTitle = $existingTitle;
            }

            $faqDesc = isset($postedFaq['desc']) ? trim((string)$postedFaq['desc']) : $existingDesc;

            $rawItems = $postedFaq['items'] ?? ($_POST['faq_items'] ?? []);
            $processedItems = [];
            if (is_array($rawItems)) {
                foreach ($rawItems as $item) {
                    if (!is_array($item)) continue;
                    $q = trim((string)($item['question'] ?? ($item['title'] ?? '')));
                    $a = trim((string)($item['answer'] ?? ($item['desc'] ?? '')));
                    
                    if ($q !== '' || $a !== '') {
                        $processedItems[] = [
                            'question' => $q,
                            'answer'   => $a
                        ];
                    }
                }
            }

            $faqAllLangs[$targetLang] = [
                'title' => $faqTitle,
                'desc'  => $faqDesc,
                'items' => $processedItems
            ];

            $jsonVal = json_encode($faqAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'faq', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 4. تحديث التقييمات وفيديوهات العملاء (Reviews - DSGVO No-Cookie)
        elseif ($action === 'update_reviews') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['reviews_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $rawReviewsSetting = $currentSettings['reviews_items'] ?? '';
            $reviewsAllLangs = is_string($rawReviewsSetting) ? json_decode($rawReviewsSetting, true) : (is_array($rawReviewsSetting) ? $rawReviewsSetting : []);
            if (!is_array($reviewsAllLangs)) {
                $reviewsAllLangs = [];
            }

            $currentLangData = $reviewsAllLangs[$targetLang] ?? [];
            $postedReviews   = $_POST['reviews'][$targetLang] ?? $_POST['reviews'] ?? [];

            $reviewsTitle = trim((string)($postedReviews['title'] ?? ($_POST['reviews_title'] ?? ($currentLangData['title'] ?? ''))));

            $finalItems = [];
            if (isset($postedReviews['items']) && is_array($postedReviews['items'])) {
                foreach ($postedReviews['items'] as $item) {
                    if (!is_array($item)) continue;
                    $rawUrl   = trim((string)($item['url'] ?? ''));
                    $embedUrl = $this->convertToSafeYoutubeEmbed($rawUrl);
                    
                    if ($embedUrl !== '') {
                        $finalItems[] = ['url' => $embedUrl];
                    }
                }
            }

            $reviewsAllLangs[$targetLang] = [
                'title' => $reviewsTitle,
                'items' => $finalItems
            ];

            $jsonVal = json_encode($reviewsAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'reviews_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 5. تحديث قسم المميزات (Choose Us)
        elseif ($action === 'update_choose') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['choose_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $rawChooseSetting = $currentSettings['choose'] ?? '';
            $chooseAllLangs = is_string($rawChooseSetting) ? json_decode($rawChooseSetting, true) : (is_array($rawChooseSetting) ? $rawChooseSetting : []);
            if (!is_array($chooseAllLangs)) {
                $chooseAllLangs = [];
            }

            $currentLangData = $chooseAllLangs[$targetLang] ?? [];
            $postedChoose    = $_POST['choose'][$targetLang] ?? $_POST['choose'] ?? [];

            $secTitle = trim((string)($postedChoose['section_title'] 
                     ?? ($_POST['choose_section_title'] 
                     ?? ($currentLangData['section_title'] ?? ($currentLangData['title'] ?? '')))));
                     
            $secDesc  = trim((string)($postedChoose['section_subtitle'] 
                     ?? ($_POST['choose_section_subtitle'] 
                     ?? ($currentLangData['section_subtitle'] ?? ($currentLangData['desc'] ?? '')))));

            $existingItems = $currentLangData['items'] ?? [];
            $finalItems = [];

            if (isset($postedChoose['items']) && is_array($postedChoose['items'])) {
                foreach ($postedChoose['items'] as $index => $item) {
                    if (!is_array($item)) continue;
                    $oldImg = (string)($item['old_img'] ?? ($existingItems[$index]['img'] ?? ''));
                    $img = $oldImg;

                    if (isset($_FILES['choose_img_' . $index]) && $_FILES['choose_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['choose_img_' . $index]['tmp_name']);
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
                        'url'   => $this->sanitizeUrl($item['url'] ?? '#')
                    ];
                }
            }

            $chooseAllLangs[$targetLang] = [
                'section_title'    => $secTitle,
                'section_subtitle' => $secDesc,
                'title'            => $secTitle,
                'desc'             => $secDesc,
                'items'            => $finalItems
            ];

            $jsonVal = json_encode($chooseAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'choose', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 6. تحديث الدليل الشامل (Guide)
        elseif ($action === 'update_guide') {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['guide_lang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'guide'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $guideAllLangs = ($dbRaw && is_string($dbRaw)) ? (json_decode($dbRaw, true) ?? []) : [];
            if (!is_array($guideAllLangs)) {
                $guideAllLangs = [];
            }

            $currentLangData = $guideAllLangs[$targetLang] ?? [];
            $existingTitle   = $currentLangData['title'] ?? '';
            $existingDesc    = $currentLangData['desc'] ?? '';
            $existingBtn     = $currentLangData['read_more_btn'] ?? '';
            $existingItems   = $currentLangData['items'] ?? [];

            $postedGuide = $_POST['guide'][$targetLang] ?? [];

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
                    if (!is_array($item)) continue;

                    $oldImg   = (string)($item['old_img'] ?? ($existingItems[$index]['img'] ?? ''));
                    $finalImg = $oldImg;

                    if (isset($_FILES['guide_img_' . $index]) && $_FILES['guide_img_' . $index]['error'] === UPLOAD_ERR_OK) {
                        if (str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($_FILES['guide_img_' . $index]['tmp_name']);
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
                        'img'   => $finalImg
                    ];
                }
            }

            $guideAllLangs[$targetLang] = [
                'title'         => $guideTitle,
                'desc'          => $guideDesc,
                'read_more_btn' => $readMoreBtn,
                'items'         => $processedItems
            ];

            $jsonVal = json_encode($guideAllLangs, JSON_UNESCAPED_UNICODE);
            return $stmt->execute(['k' => 'guide', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        return true;
    }

    /**
     * حذف آمن للملفات المرفوعة داخل مجلد uploads فقط (مع منع Path Traversal)
     */
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
