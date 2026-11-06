<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class JobSettingsService
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
            'update_job_hero',
            'update_job_why',
            'update_job_programs',
            'update_job_timeline',
            'update_job_services'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");
        $targetLang = $_POST['targetLang'] ?? $_POST['job_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

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

        // 1. تحديث هيرو الوظائف والمهن (Job Hero) متعدد اللغات
        if ($action === 'update_job_hero') {
            $allLangs = $getSettingAllLangs('job_hero');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $oldHeroImg = $_POST['old_job_hero_img'] ?? ($currentLangData['img'] ?? 'assets/img/job/jobhero.png');
            
            if (isset($_FILES['job_hero_img']) && $_FILES['job_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldHeroImg) && !str_contains($oldHeroImg, 'default') && $oldHeroImg !== 'assets/img/job/jobhero.png') {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['job_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            } else {
                $heroImg = $oldHeroImg;
            }

            $postedHero = $_POST['job_hero'][$targetLang] ?? $_POST;

            $allLangs[$targetLang] = [
                'title'    => trim((string)($postedHero['title'] ?? ($postedHero['job_hero_title'] ?? ($currentLangData['title'] ?? '')))),
                'desc'     => trim((string)($postedHero['desc'] ?? ($postedHero['job_hero_desc'] ?? ($currentLangData['desc'] ?? '')))),
                'btn_text' => trim((string)($postedHero['btn_text'] ?? ($postedHero['job_hero_btn_text'] ?? ($currentLangData['btn_text'] ?? 'تواصل معنا')))),
                'btn_url'  => trim((string)($postedHero['btn_url'] ?? ($postedHero['job_hero_btn_url'] ?? ($currentLangData['btn_url'] ?? 'contact')))),
                'img'      => $heroImg
            ];

            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 2. تحديث لماذا التدريب والتوظيف (Job Why) متعدد اللغات مع الحماية الكاملة
        elseif ($action === 'update_job_why') {
            $allLangs = $getSettingAllLangs('job_why');
            $currentLangData = $allLangs[$targetLang] ?? [];
            
            $existingTitle = $currentLangData['title'] ?? 'لماذا التدريب والتوظيف في ألمانيا؟';
            $existingDesc  = $currentLangData['desc'] ?? '';
            $existingItems = $currentLangData['items'] ?? [];

            $postedWhy = $_POST['job_why'][$targetLang] ?? $_POST;

            $jobWhyTitle = trim((string)($postedWhy['title'] ?? ($postedWhy['job_why_title'] ?? '')));
            if ($jobWhyTitle === '') $jobWhyTitle = $existingTitle;

            $jobWhyDesc = isset($postedWhy['desc']) ? trim((string)$postedWhy['desc']) : ($postedWhy['job_why_desc'] ?? $existingDesc);
            
            $rawItems = $postedWhy['items'] ?? $_POST['job_why'] ?? null;
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) continue;
                    
                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    $fileToCheck = $_FILES['job_why_img_' . $index] ?? null;
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
                'title' => $jobWhyTitle,
                'desc'  => $jobWhyDesc,
                'items' => $finalItems
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_why', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 3. تحديث برامج وأنواع التدريب المهني (Job Programs) متعدد اللغات
        elseif ($action === 'update_job_programs') {
            $allLangs = $getSettingAllLangs('job_programs');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $existingTitle = $currentLangData['title'] ?? 'برامج التدريب المهني والتوظيف';
            $existingDesc  = $currentLangData['desc'] ?? '';
            $existingItems = $currentLangData['items'] ?? [];

            $postedPrograms = $_POST['job_programs'][$targetLang] ?? $_POST;

            $jobProgTitle = trim((string)($postedPrograms['title'] ?? ($postedPrograms['job_program_title'] ?? '')));
            if ($jobProgTitle === '') $jobProgTitle = $existingTitle;

            $jobProgDesc = isset($postedPrograms['desc']) ? trim((string)$postedPrograms['desc']) : ($postedPrograms['job_program_desc'] ?? $existingDesc);
            
            $rawPrograms = $postedPrograms['items'] ?? ($postedPrograms['programs'] ?? $_POST['job_programs'] ?? null);
            $finalPrograms = [];

            if (is_array($rawPrograms)) {
                foreach ($rawPrograms as $index => $item) {
                    if (!is_array($item)) continue;

                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    $fileToCheck = $_FILES['prog_img_' . $index] ?? null;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $img = 'assets/uploads/' . $filename;
                    }

                    $finalPrograms[] = [
                        'title'    => trim($item['title'] ?? ''),
                        'desc'     => trim($item['desc'] ?? ''),
                        'btn_text' => trim($item['btn_text'] ?? 'اطلب الآن'),
                        'btn_url'  => trim($item['btn_url'] ?? '#'),
                        'is_dark'  => isset($item['is_dark']) ? true : false,
                        'img'      => $img
                    ];
                }
            } else {
                $finalPrograms = $existingItems;
            }

            $allLangs[$targetLang] = [
                'title' => $jobProgTitle,
                'desc'  => $jobProgDesc,
                'items' => $finalPrograms
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_programs', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 4. تحديث خطوات ومسار التدريب والتوظيف (Job Timeline) متعدد اللغات
        elseif ($action === 'update_job_timeline') {
            $allLangs = $getSettingAllLangs('job_timeline');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $existingTitle = $currentLangData['title'] ?? 'خطوات التوظيف والتدريب في ألمانيا';
            $existingDesc  = $currentLangData['desc'] ?? '';
            $existingItems = $currentLangData['steps'] ?? ($currentLangData['items'] ?? []);

            $postedTimeline = $_POST['job_timeline'][$targetLang] ?? $_POST;

            $jobTimelineTitle = trim((string)($postedTimeline['title'] ?? ($postedTimeline['job_timeline_title'] ?? '')));
            if ($jobTimelineTitle === '') $jobTimelineTitle = $existingTitle;

            $jobTimelineDesc = isset($postedTimeline['desc']) ? trim((string)$postedTimeline['desc']) : ($postedTimeline['job_timeline_desc'] ?? $existingDesc);
            
            $rawSteps = $postedTimeline['steps'] ?? ($postedTimeline['items'] ?? $_POST['job_timeline'] ?? null);
            $finalSteps = [];

            if (is_array($rawSteps)) {
                usort($rawSteps, function($a, $b) {
                    return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                });

                foreach ($rawSteps as $index => $item) {
                    if (!is_array($item)) continue;

                    $oldIcon = $item['old_icon'] ?? ($existingItems[$index]['icon'] ?? '');
                    $icon = $oldIcon;

                    $fileToCheck = $_FILES['steps_icon_' . $index] ?? null;
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
                'title' => $jobTimelineTitle,
                'desc'  => $jobTimelineDesc,
                'steps' => $finalSteps
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_timeline', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 

        // 5. تحديث كروت الخدمات المهتمة (Job Services) متعدد اللغات
        elseif ($action === 'update_job_services') {
            $allLangs = $getSettingAllLangs('job_services');
            $currentLangData = $allLangs[$targetLang] ?? [];

            $existingTitle = $currentLangData['title'] ?? ($currentLangData['section_title'] ?? 'خدمات التوظيف');
            $existingDesc  = $currentLangData['desc'] ?? ($currentLangData['section_subtitle'] ?? '');
            $existingItems = $currentLangData['items'] ?? [];

            $postedServices = $_POST['job_services'][$targetLang] ?? $_POST;

            $jobServicesTitle = trim((string)($postedServices['title'] ?? ($postedServices['section_title'] ?? ($postedServices['job_services_title'] ?? ''))));
            if ($jobServicesTitle === '') $jobServicesTitle = $existingTitle;

            $jobServicesDesc = isset($postedServices['desc']) ? trim((string)$postedServices['desc']) : (isset($postedServices['section_subtitle']) ? trim((string)$postedServices['section_subtitle']) : ($postedServices['job_services_desc'] ?? $existingDesc));
            
            $rawItems = $postedServices['items'] ?? $_POST['job_services'] ?? null;
            $finalItems = [];

            if (is_array($rawItems)) {
                foreach ($rawItems as $index => $item) {
                    if (!is_array($item)) continue;

                    $oldImg = $item['old_img'] ?? ($existingItems[$index]['img'] ?? '');
                    $img = $oldImg;

                    $fileToCheck = $_FILES['srv_img_' . $index] ?? null;
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
                'title'            => $jobServicesTitle,
                'desc'             => $jobServicesDesc,
                'section_title'    => $jobServicesTitle,
                'section_subtitle' => $jobServicesDesc,
                'items'            => $finalItems
            ];
            
            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_services', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
