<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class AboutSettingsService
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
            'update_about_section',
            'update_about_counts',
            'update_about_partners',
            'update_about_team',
            'update_team'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث قسم من نحن الرئيسي (About Section)
        if ($action === 'update_about_section') {
            $oldAboutData = json_decode($currentSettings['about_section'] ?? '', true) ?: [];

            $mainImg = $_POST['old_about_main_img'] ?? ($oldAboutData['main_img'] ?? '');
            if (isset($_FILES['about_main_img']) && $_FILES['about_main_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAboutData['main_img'])) {
                    $this->deleteOldImageFile($oldAboutData['main_img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_main_img']['tmp_name']);
                $mainImg = 'assets/uploads/' . $filename;
            }

            $subImg = $_POST['old_about_sub_img'] ?? ($oldAboutData['sub_img'] ?? '');
            if (isset($_FILES['about_sub_img']) && $_FILES['about_sub_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAboutData['sub_img'])) {
                    $this->deleteOldImageFile($oldAboutData['sub_img']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_sub_img']['tmp_name']);
                $subImg = 'assets/uploads/' . $filename;
            }

            $visionIcon = $_POST['old_vision_icon'] ?? ($oldAboutData['vision_icon'] ?? '');
            if (isset($_FILES['about_vision_icon']) && $_FILES['about_vision_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAboutData['vision_icon'])) {
                    $this->deleteOldImageFile($oldAboutData['vision_icon']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_vision_icon']['tmp_name']);
                $visionIcon = 'assets/uploads/' . $filename;
            }

            $messageIcon = $_POST['old_message_icon'] ?? ($oldAboutData['message_icon'] ?? '');
            if (isset($_FILES['about_message_icon']) && $_FILES['about_message_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAboutData['message_icon'])) {
                    $this->deleteOldImageFile($oldAboutData['message_icon']);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['about_message_icon']['tmp_name']);
                $messageIcon = 'assets/uploads/' . $filename;
            }

            $aboutData = [
                'title'         => $_POST['about_title'] ?? 'من نحن',
                'desc'          => $_POST['about_desc'] ?? '',
                'btn_text'      => $_POST['about_btn_text'] ?? 'قراءة المزيد',
                'btn_url'       => $_POST['about_btn_url'] ?? '#',
                'main_img'      => $mainImg,
                'sub_img'       => $subImg,
                'vision_title'  => $_POST['vision_title'] ?? 'رؤية الشركة',
                'vision_desc'   => $_POST['vision_desc'] ?? '',
                'vision_icon'   => $visionIcon,
                'message_title' => $_POST['message_title'] ?? 'رسالة الشركة',
                'message_desc'  => $_POST['message_desc'] ?? '',
                'message_icon'  => $messageIcon
            ];

            $jsonVal = json_encode($aboutData, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'about_section', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 2. تحديث العدادات والإحصائيات
        elseif ($action === 'update_about_counts') {
            $countsData = $_POST['counts'] ?? [];
            foreach ($countsData as $index => $item) {
                $fileToCheck = $_FILES['count_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $countsData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $countsData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($countsData[$index]['old_img']);
            }
            
            $jsonVal = json_encode(array_values($countsData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'about_counts', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 3. تحديث الشركاء
        elseif ($action === 'update_about_partners') {
            $partnersTitle = $_POST['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا';
            $stmt->execute(['k' => 'partners_title', 'v' => $partnersTitle, 'v_update' => $partnersTitle]);

            $partnersData = $_POST['partners'] ?? [];
            foreach ($partnersData as $index => $item) {
                $fileToCheck = $_FILES['partner_img_' . $index] ?? null;
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $partnersData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $partnersData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($partnersData[$index]['old_img']);
            }
            
            $jsonVal = json_encode(array_values($partnersData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'partners_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 4. تحديث فريق العمل (About Team / Team)
        elseif ($action === 'update_about_team' || $action === 'update_team') {
            $teamTitle = $_POST['team_title'] ?? 'فريق العمل';
            $teamDesc  = $_POST['team_desc'] ?? '';
            
            $stmt->execute(['k' => 'team_title', 'v' => $teamTitle, 'v_update' => $teamTitle]);
            $stmt->execute(['k' => 'team_desc', 'v' => $teamDesc, 'v_update' => $teamDesc]);

            $teamData = $_POST['team'] ?? [];
            foreach ($teamData as $index => $item) {
                $fileToCheck = $_FILES['team_img_' . $index] ?? ($_FILES['team'][$index]['img'] ?? null);
                
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $teamData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $teamData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($teamData[$index]['old_img']);
            }
            
            $jsonVal = json_encode(array_values($teamData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'team_items', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
