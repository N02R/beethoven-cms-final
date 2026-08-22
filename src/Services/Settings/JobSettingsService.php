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
            'update_job_program',
            'update_job_timeline',
            'update_job_services'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث هيرو فرص العمل والتوظيف (Job Hero)
        if ($action === 'update_job_hero') {
            $oldJobHeroData = json_decode($currentSettings['job_hero'] ?? '', true) ?: [];

            $heroImg = $_POST['old_img'] ?? ($oldJobHeroData['img'] ?? 'assets/img/job/hero.jpg');
            if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldJobHeroData['img'])) {
                    $this->deleteOldImageFile($oldJobHeroData['img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }

            $jobHeroData = [
                'title'    => $_POST['title'] ?? '',
                'desc'     => $_POST['desc'] ?? '',
                'btn_text' => $_POST['btn_text'] ?? 'ابدأ الآن',
                'btn_url'  => $_POST['btn_url'] ?? '#',
                'img'      => $heroImg
            ];
            $jsonVal = json_encode($jobHeroData, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 2. تحديث قسم لماذا التدريب معنا (Job Why)
        elseif ($action === 'update_job_why') {
            $jobWhyTitle = $_POST['why_title'] ?? '';
            $jobWhyDesc  = $_POST['why_desc'] ?? '';
            $stmt->execute(['k' => 'job_why_title', 'v' => $jobWhyTitle, 'v_update' => $jobWhyTitle]);
            $stmt->execute(['k' => 'job_why_desc', 'v' => $jobWhyDesc, 'v_update' => $jobWhyDesc]);

            $jobWhyData = $_POST['items'] ?? [];
            foreach ($jobWhyData as $index => $item) {
                $fileToCheck = $_FILES['why_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $jobWhyData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $jobWhyData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($jobWhyData[$index]['old_img']);
            }
            $jsonVal = json_encode(array_values($jobWhyData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_why_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 3. تحديث برامج وأنواع التدريب المهني (Job Program Types)
        elseif ($action === 'update_job_program') {
            $jobProgramTitle = $_POST['program_title'] ?? '';
            $jobProgramDesc  = $_POST['program_desc'] ?? '';
            $stmt->execute(['k' => 'job_program_title', 'v' => $jobProgramTitle, 'v_update' => $jobProgramTitle]);
            $stmt->execute(['k' => 'job_program_desc', 'v' => $jobProgramDesc, 'v_update' => $jobProgramDesc]);

            $jobProgramData = $_POST['programs'] ?? [];
            foreach ($jobProgramData as $index => $item) {
                $fileToCheck = $_FILES['prog_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $jobProgramData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $jobProgramData[$index]['img'] = $item['old_img'] ?? '';
                }
                
                $jobProgramData[$index]['is_dark'] = isset($item['is_dark']) ? 1 : 0;
                unset($jobProgramData[$index]['old_img']);
            }
            $jsonVal = json_encode(array_values($jobProgramData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_program_types', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 4. تحديث خطوات ومسار التدريب والتوظيف (Job Timeline)
        elseif ($action === 'update_job_timeline') {
            $jobTimelineTitle = $_POST['timeline_title'] ?? '';
            $jobTimelineDesc  = $_POST['timeline_desc'] ?? '';
            $stmt->execute(['k' => 'job_timeline_title', 'v' => $jobTimelineTitle, 'v_update' => $jobTimelineTitle]);
            $stmt->execute(['k' => 'job_timeline_desc', 'v' => $jobTimelineDesc, 'v_update' => $jobTimelineDesc]);

            $jobTimelineData = $_POST['steps'] ?? [];
            usort($jobTimelineData, function($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });

            foreach ($jobTimelineData as $index => $item) {
                $fileToCheck = $_FILES['steps_icon_' . $index] ?? null;

                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_icon'])) {
                        $this->deleteOldImageFile($item['old_icon']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $jobTimelineData[$index]['icon'] = 'assets/uploads/' . $filename;
                } else {
                    $jobTimelineData[$index]['icon'] = $item['old_icon'] ?? '';
                }
                unset($jobTimelineData[$index]['old_icon']);
            }
            
            $jsonVal = json_encode(array_values($jobTimelineData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_timeline_steps', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 5. تحديث كروت الخدمات المهتمة (Job Services)
        elseif ($action === 'update_job_services') {
            $jobServicesTitle = $_POST['services_title'] ?? '';
            $jobServicesDesc  = $_POST['services_desc'] ?? '';
            $stmt->execute(['k' => 'job_services_title', 'v' => $jobServicesTitle, 'v_update' => $jobServicesTitle]);
            $stmt->execute(['k' => 'job_services_desc', 'v' => $jobServicesDesc, 'v_update' => $jobServicesDesc]);

            $jobServicesData = $_POST['services'] ?? [];
            foreach ($jobServicesData as $index => $item) {
                $fileToCheck = $_FILES['srv_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $jobServicesData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $jobServicesData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($jobServicesData[$index]['old_img']);
            }
            $jsonVal = json_encode(array_values($jobServicesData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'job_services_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
