<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class EduSettingsService
{
    private string $rootPath;
    private ImageUploader $imageUploader;

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = $rootPath;
        $this->imageUploader = $imageUploader;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $allowedActions = [
            'update_edu_hero',
            'update_edu_why',
            'update_edu_timeline',
            'update_edu_services'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");
        $targetLang = $_POST['targetLang'] ?? $_POST['edu_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

        // دالة مساعدة ذكية لجلب وتحليل بيانات الـ JSON لضمان تعدد اللغات من قاعدة البيانات مباشرة
        $getSettingAllLangs = function(string $key) use ($pdo): array {
            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = :k");
            $stmtFetch->execute(['k' => $key]);
            $dbRaw = $stmtFetch->fetchColumn();
            if ($dbRaw && is_string($dbRaw)) {
                $decoded = json_decode($dbRaw, true);
                if (is_array($decoded)) return $decoded;
            }
            return [];
        };

        // 1. تحديث هيرو التعليم العالي (Edu Hero) متعدد اللغات
        if ($action === 'update_edu_hero') {
            $allLangs = $getSettingAllLangs('edu_hero');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $oldHeroImg = $_POST['old_edu_hero_img'] ?? ($currentLangData['img'] ?? 'assets/img/education/hero.jpg');
            
            if (isset($_FILES['edu_hero_img']) && $_FILES['edu_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldHeroImg) && !str_contains($oldHeroImg, 'default') && $oldHeroImg !== 'assets/img/education/hero.jpg') {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['edu_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $oldHeroImg;
            }

            $postedHero = $_POST['edu_hero'][$targetLang] ?? $_POST;

            $allLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($postedHero['edu_hero_title'] ?? ($currentLangData['title'] ?? '')))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($postedHero['edu_hero_desc'] ?? ($currentLangData['desc'] ?? '')))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($postedHero['edu_hero_btn_text'] ?? ($currentLangData['btn_text'] ?? 'ابدأ الآن')))),
                'btn_url'  => trim((string)($postedHero['btn_url'] ?? ($postedHero['edu_hero_btn_url'] ?? ($currentLangData['btn_url'] ?? '#')))),
                'img'      => $heroImg
            ];

            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 2. تحديث لماذا الدراسة في ألمانيا (Edu Why) متعدد اللغات مع الحماية الكاملة
        elseif ($action === 'update_edu_why') {
            $allLangs = $getSettingAllLangs('edu_why');
            $currentLangData = $allLangs[$targetLang] ?? [];
            
            $existingTitle = $currentLangData['title'] ?? 'لماذا الدراسة في ألمانيا؟';
            $existingDesc  = $currentLangData['desc'] ?? '';
            $existingItems = $currentLangData['items'] ?? [];

            $postedWhy = $_POST['edu_why'][$targetLang] ?? $_POST;

            $eduWhyTitle = trim((string)($postedWhy['title'] ?? ($postedWhy['edu_why_title'] ?? '')));
            if ($eduWhyTitle === '') $eduWhyTitle = $existingTitle;

            $eduWhyDesc = isset($postedWhy['desc']) ? trim((string)$postedWhy['desc']) : ($postedWhy['edu_why_desc'] ?? $existingDesc);
            
            $rawItems = $postedWhy['items'] ?? $_POST['edu_why'] ?? null;
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) continue;
                    
                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    $fileToCheck = $_FILES['edu_why_img_' . $index] ?? null;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'title' => trim($item['title'] ?? ''),
                        'desc'  => trim($item['desc'] ?? ''),
                        'img'   => $img
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title' => $eduWhyTitle,
                'desc'  => $eduWhyDesc,
                'items' => $finalItems
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_why', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 3. تحديث خطوات الرحلة والخط الزمني (Edu Timeline) متعدد اللغات
        elseif ($action === 'update_edu_timeline') {
            $allLangs = $getSettingAllLangs('edu_timeline');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $existingTitle = $currentLangData['title'] ?? 'خطوات الدراسة في ألمانيا';
            $existingDesc  = $currentLangData['desc'] ?? '';
            $existingItems = $currentLangData['steps'] ?? ($currentLangData['items'] ?? []);

            $postedTimeline = $_POST['edu_timeline'][$targetLang] ?? $_POST;

            $eduTimelineTitle = trim((string)($postedTimeline['title'] ?? ($postedTimeline['edu_timeline_title'] ?? '')));
            if ($eduTimelineTitle === '') $eduTimelineTitle = $existingTitle;

            $eduTimelineDesc = isset($postedTimeline['desc']) ? trim((string)$postedTimeline['desc']) : ($postedTimeline['edu_timeline_desc'] ?? $existingDesc);
            
            $rawSteps = $postedTimeline['steps'] ?? ($postedTimeline['items'] ?? $_POST['edu_timeline'] ?? null);
            $finalSteps = [];

            if (is_array($rawSteps)) {
                foreach ($rawSteps as $index => $item) {
                    if (!is_array($item)) continue;

                    $oldIcon = $item['old_icon'] ?? ($existingItems[$index]['icon'] ?? '');
                    $icon = $oldIcon;

                    $fileToCheck = $_FILES['edu_timeline_icon_' . $index] ?? null;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldIcon) && str_starts_with($oldIcon, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldIcon);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $icon = 'assets/uploads/' . $filename;
                    }

                    $finalSteps[] = [
                        'title'    => trim($item['title'] ?? ''),
                        'subtitle' => trim($item['subtitle'] ?? ''),
                        'desc'     => trim($item['desc'] ?? ''),
                        'icon'     => $icon
                    ];
                }
            } else {
                $finalSteps = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title' => $eduTimelineTitle,
                'desc'  => $eduTimelineDesc,
                'steps' => $finalSteps
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_timeline', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 4. تحديث خدمات التعليم (Edu Services) متعدد اللغات
        elseif ($action === 'update_edu_services') {
            $allLangs = $getSettingAllLangs('edu_services');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $existingTitle = $currentLangData['title'] ?? ($currentLangData['section_title'] ?? 'خدمات التعليم');
            $existingDesc  = $currentLangData['desc'] ?? ($currentLangData['section_subtitle'] ?? '');
            $existingItems = $currentLangData['items'] ?? [];

            $postedServices = $_POST['edu_services'][$targetLang] ?? $_POST;

            $eduServicesTitle = trim((string)($postedServices['title'] ?? ($postedServices['section_title'] ?? ($postedServices['edu_services_title'] ?? ''))));
            if ($eduServicesTitle === '') $eduServicesTitle = $existingTitle;

            $eduServicesDesc = isset($postedServices['desc']) ? trim((string)$postedServices['desc']) : (isset($postedServices['section_subtitle']) ? trim((string)$postedServices['section_subtitle']) : ($postedServices['edu_services_desc'] ?? $existingDesc));
            
            $rawItems = $postedServices['items'] ?? $_POST['edu_services'] ?? null;
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) continue;

                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    $fileToCheck = $_FILES['edu_service_img_' . $index] ?? null;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalItems[] = [
                        'title' => trim($item['title'] ?? ''),
                        'url'   => trim($item['url'] ?? '#'),
                        'img'   => $img
                    ];
                }
            } else {
                $finalItems = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title'            => $eduServicesTitle,
                'desc'             => $eduServicesDesc,
                'section_title'    => $eduServicesTitle,
                'section_subtitle' => $eduServicesDesc,
                'items'            => $finalItems
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        return true;
    }

    private function deleteOldImageFile(string $imagePath): void
    {
        if (!empty($imagePath) && str_starts_with($imagePath, 'assets/uploads/')) {
            $fullPath = $this->rootPath . '/public/' . $imagePath;
            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
