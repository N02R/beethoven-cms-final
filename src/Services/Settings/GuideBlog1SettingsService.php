<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class GuideBlog1SettingsService
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
        // التحقق مما إذا كان الإكشن يخص المقال الأول أو المقال الثاني
        $isBlog1 = str_starts_with($action, 'update_guide_blog1_');
        $isBlog2 = str_starts_with($action, 'update_guide_blog2_');

        if (!$isBlog1 && !$isBlog2) {
            return false;
        }

        // تحديد المفتاح وقاعدة البيانات المناسبة بناءً على نوع المقال
        $settingKey = $isBlog1 ? 'guide_blog1_page' : 'guide_blog2_page';

        // جلب البيانات الحالية المخزنة كـ JSON وفكها
        $guideData = isset($currentSettings[$settingKey]) ? json_decode($currentSettings[$settingKey], true) : [];
        if (!is_array($guideData)) {
            $guideData = [];
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // --- معالجة إكشنات المقال الأول (Blog 1) ---
        if ($action === 'update_guide_blog1_breadcrumb') {
            $guideData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
            $guideData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';
        } 
        elseif ($action === 'update_guide_blog1_hero') {
            $heroImg = $_POST['old_guide_hero_img'] ?? '';
            if (isset($_FILES['guide_hero_img']) && $_FILES['guide_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($heroImg)) {
                    $this->deleteOldImageFile($heroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['guide_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }
            $guideData['hero_img'] = $heroImg;
        }
        elseif ($action === 'update_guide_blog1_main') {
            $guideData['main_title'] = $_POST['main_title'] ?? '';
            $guideData['main_desc'] = $_POST['main_desc'] ?? '';
        }
        elseif ($action === 'update_guide_blog1_notes') {
            $guideData['notes_title'] = $_POST['notes_title'] ?? '';
            $notesInput = $_POST['notes'] ?? [];
            $guideData['notes_items'] = array_values($notesInput);
        }
        elseif ($action === 'update_guide_blog1_why') {
            $guideData['why_title'] = $_POST['why_title'] ?? '';
            $guideData['why_desc'] = $_POST['why_desc'] ?? '';
            
            $whyCards = $_POST['why_cards'] ?? [];
            foreach ($whyCards as $index => $card) {
                $fileKey = 'why_img_' . $index;
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    if (!empty($card['old_img'])) {
                        $this->deleteOldImageFile($card['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($_FILES[$fileKey]['tmp_name']);
                    $whyCards[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $whyCards[$index]['img'] = $card['old_img'] ?? '';
                }
                unset($whyCards[$index]['old_img']);
            }
            $guideData['why_cards'] = array_values($whyCards);
        }
        elseif ($action === 'update_guide_blog1_timeline') {
            $guideData['timeline_title'] = $_POST['timeline_title'] ?? '';
            $guideData['timeline_desc'] = $_POST['timeline_desc'] ?? '';

            $timelineSteps = $_POST['timeline'] ?? [];
            foreach ($timelineSteps as $index => $step) {
                $fileKey = 'timeline_icon_' . $index;
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    if (!empty($step['old_icon'])) {
                        $this->deleteOldImageFile($step['old_icon']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($_FILES[$fileKey]['tmp_name']);
                    $timelineSteps[$index]['icon'] = 'assets/uploads/' . $filename;
                } else {
                    $timelineSteps[$index]['icon'] = $step['old_icon'] ?? '';
                }
                unset($timelineSteps[$index]['old_icon']);
            }
            $guideData['timeline_steps'] = array_values($timelineSteps);
        }

        // --- معالجة إكشنات المقال الثاني (Blog 2) ---
        elseif ($action === 'update_guide_blog2_breadcrumb') {
            $guideData['page_breadcrumb'] = $_POST['page_breadcrumb'] ?? '';
            $guideData['page_breadcrumb_url'] = $_POST['page_breadcrumb_url'] ?? '#';
        }
        elseif ($action === 'update_guide_blog2_hero') {
            $heroImg = $_POST['old_guide_hero_img'] ?? '';
            if (isset($_FILES['guide_hero_img']) && $_FILES['guide_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($heroImg)) {
                    $this->deleteOldImageFile($heroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['guide_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }
            $guideData['hero_img'] = $heroImg;
        }
        elseif ($action === 'update_guide_blog2_main') {
            $guideData['main_title'] = $_POST['main_title'] ?? '';
            $guideData['main_desc'] = $_POST['main_desc'] ?? '';
        }
        elseif ($action === 'update_guide_blog2_services') { // قسم الخدمات الخاص بـ Blog 2
            $guideData['services_advice_title'] = $_POST['services_advice_title'] ?? '';
            $servicesInput = $_POST['services_advice_points'] ?? [];
            $guideData['services_advice_points'] = array_values($servicesInput);
        }
        elseif ($action === 'update_guide_blog2_why') {
            $guideData['why_title'] = $_POST['why_title'] ?? '';
            $guideData['why_desc'] = $_POST['why_desc'] ?? '';
            
            $whyCards = $_POST['why_cards'] ?? [];
            foreach ($whyCards as $index => $card) {
                $fileKey = 'why_img_' . $index;
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    if (!empty($card['old_img'])) {
                        $this->deleteOldImageFile($card['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($_FILES[$fileKey]['tmp_name']);
                    $whyCards[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $whyCards[$index]['img'] = $card['old_img'] ?? '';
                }
                unset($whyCards[$index]['old_img']);
            }
            $guideData['why_cards'] = array_values($whyCards);
        }
        elseif ($action === 'update_guide_blog2_timeline') {
            $guideData['timeline_title'] = $_POST['timeline_title'] ?? '';
            $guideData['timeline_desc'] = $_POST['timeline_desc'] ?? '';

            $timelineSteps = $_POST['timeline'] ?? [];
            foreach ($timelineSteps as $index => $step) {
                $fileKey = 'timeline_icon_' . $index;
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                    if (!empty($step['old_icon'])) {
                        $this->deleteOldImageFile($step['old_icon']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($_FILES[$fileKey]['tmp_name']);
                    $timelineSteps[$index]['icon'] = 'assets/uploads/' . $filename;
                } else {
                    $timelineSteps[$index]['icon'] = $step['old_icon'] ?? '';
                }
                unset($timelineSteps[$index]['old_icon']);
            }
            $guideData['timeline_steps'] = array_values($timelineSteps);
        } else {
            return false;
        }

        $jsonVal = json_encode($guideData, JSON_UNESCAPED_UNICODE);
        $stmt->execute(['key' => $settingKey, 'v' => $jsonVal, 'v_update' => $jsonVal]);

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
