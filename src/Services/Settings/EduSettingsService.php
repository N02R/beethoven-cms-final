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

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث هيرو التعليم العالي (Edu Hero)
        if ($action === 'update_edu_hero') {
            $oldEduHeroData = json_decode($currentSettings['edu_hero'] ?? '', true) ?: [];

            $heroImg = $_POST['old_edu_hero_img'] ?? ($oldEduHeroData['img'] ?? 'assets/img/education/hero.jpg');
            if (isset($_FILES['edu_hero_img']) && $_FILES['edu_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldEduHeroData['img'])) {
                    $this->deleteOldImageFile($oldEduHeroData['img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['edu_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }

            $eduHeroData = [
                'title'    => $_POST['edu_hero_title'] ?? '',
                'desc'     => $_POST['edu_hero_desc'] ?? '',
                'btn_text' => $_POST['edu_hero_btn_text'] ?? 'ابدأ الآن',
                'btn_url'  => $_POST['edu_hero_btn_url'] ?? '#',
                'img'      => $heroImg
            ];

            $jsonVal = json_encode($eduHeroData, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 2. تحديث لماذا الدراسة في ألمانيا (Edu Why)
        elseif ($action === 'update_edu_why') {
            $eduWhyTitle = $_POST['edu_why_title'] ?? '';
            $eduWhyDesc  = $_POST['edu_why_desc'] ?? '';
            
            $stmt->execute(['k' => 'edu_why_title', 'v' => $eduWhyTitle, 'v_update' => $eduWhyTitle]);
            $stmt->execute(['k' => 'edu_why_desc', 'v' => $eduWhyDesc, 'v_update' => $eduWhyDesc]);

            $eduWhyData = $_POST['edu_why'] ?? [];
            foreach ($eduWhyData as $index => $item) {
                $fileToCheck = $_FILES['edu_why_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $eduWhyData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $eduWhyData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($eduWhyData[$index]['old_img']);
            }
            
            $jsonVal = json_encode(array_values($eduWhyData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_why_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 3. تحديث خطوات الرحلة والخط الزمني (Edu Timeline)
        elseif ($action === 'update_edu_timeline') {
            $eduTimelineTitle = $_POST['edu_timeline_title'] ?? '';
            $eduTimelineDesc  = $_POST['edu_timeline_desc'] ?? '';
            
            $stmt->execute(['k' => 'edu_timeline_title', 'v' => $eduTimelineTitle, 'v_update' => $eduTimelineTitle]);
            $stmt->execute(['k' => 'edu_timeline_desc', 'v' => $eduTimelineDesc, 'v_update' => $eduTimelineDesc]);

            $eduTimelineData = $_POST['edu_timeline'] ?? [];
            usort($eduTimelineData, function($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });

            foreach ($eduTimelineData as $index => $item) {
                $fileToCheck = $_FILES['edu_timeline_icon_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_icon'])) {
                        $this->deleteOldImageFile($item['old_icon']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $eduTimelineData[$index]['icon'] = 'assets/uploads/' . $filename;
                } else {
                    $eduTimelineData[$index]['icon'] = $item['old_icon'] ?? '';
                }
                unset($eduTimelineData[$index]['old_icon']);
            }
            
            $jsonVal = json_encode(array_values($eduTimelineData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_timeline_steps', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 4. تحديث خدمات التعليم (Edu Services)
        elseif ($action === 'update_edu_services') {
            $eduServicesTitle = $_POST['edu_services_title'] ?? '';
            $eduServicesDesc  = $_POST['edu_services_desc'] ?? '';
            
            $stmt->execute(['k' => 'edu_services_title', 'v' => $eduServicesTitle, 'v_update' => $eduServicesTitle]);
            $stmt->execute(['k' => 'edu_services_desc', 'v' => $eduServicesDesc, 'v_update' => $eduServicesDesc]);

            $eduServicesData = $_POST['edu_services'] ?? [];
            foreach ($eduServicesData as $index => $item) {
                $fileToCheck = $_FILES['edu_service_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $eduServicesData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $eduServicesData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($eduServicesData[$index]['old_img']);
            }
            
            $jsonVal = json_encode(array_values($eduServicesData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'edu_services_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
