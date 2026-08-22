<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class HeaderSettingsService
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
        if (!in_array($action, ['update_logo', 'update_social', 'update_menu'], true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث الشعار
        if ($action === 'update_logo') {
            if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                $oldLogo = $currentSettings['site_logo_path'] ?? '';
                $this->deleteOldImageFile($oldLogo);

                $filename = $this->imageUploader->processAndUploadFile($_FILES['logo_img']['tmp_name']);
                $logoPath = 'assets/uploads/' . $filename;
                
                $stmt->execute(['k' => 'site_logo_path', 'v' => $logoPath, 'v_update' => $logoPath]);
            }
        }

        // 2. تحديث منصات التواصل الاجتماعي
        elseif ($action === 'update_social') {
            $socialData = $_POST['social'] ?? [];
            foreach ($socialData as $index => $item) {
                $fileToCheck = $_FILES['social_img_' . $index] ?? ($_FILES['social'][$index]['img'] ?? null);
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $socialData[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $socialData[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($socialData[$index]['old_img']);
            }
            $jsonVal = json_encode(array_values($socialData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'social_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 3. تحديث القائمة الرئيسية (Menu)
        elseif ($action === 'update_menu') {
            $menuData = $_POST['menu'] ?? [];
            usort($menuData, function($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });
            $jsonVal = json_encode(array_values($menuData), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'menu_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
