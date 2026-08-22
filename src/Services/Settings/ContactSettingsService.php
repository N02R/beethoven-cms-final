<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class ContactSettingsService
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
            'update_contact_hero',
            'update_contact_info',
            'update_whatsapp_section'
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث صورة الهيرو لصفحة تواصل معنا (Contact Hero)
        if ($action === 'update_contact_hero') {
            $oldHeroImg = $_POST['old_contact_hero_img'] ?? ($currentSettings['contact_hero_img'] ?? '');
            $heroImg = $oldHeroImg;

            if (isset($_FILES['contact_hero_img']) && $_FILES['contact_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldHeroImg)) {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $imageUploader->processAndUploadFile($_FILES['contact_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }

            $stmt->execute(['k' => 'contact_hero_img', 'v' => $heroImg, 'v_update' => $heroImg]);
        } 
        // 2. تحديث معلومات وأيقونات التواصل (Contact Info & Icons)
        elseif ($action === 'update_contact_info') {
            $title = $_POST['contact_info_title'] ?? 'معلومات التواصل';
            $desc  = $_POST['contact_info_desc'] ?? '';
            $addr  = $_POST['contact_address'] ?? '';
            $email = $_POST['contact_email'] ?? '';
            $phone = $_POST['contact_phone'] ?? '';

            $stmt->execute(['k' => 'contact_info_title', 'v' => $title, 'v_update' => $title]);
            $stmt->execute(['k' => 'contact_info_desc', 'v' => $desc, 'v_update' => $desc]);
            $stmt->execute(['k' => 'contact_address', 'v' => $addr, 'v_update' => $addr]);
            $stmt->execute(['k' => 'contact_email', 'v' => $email, 'v_update' => $email]);
            $stmt->execute(['k' => 'contact_phone', 'v' => $phone, 'v_update' => $phone]);

            // أيقونة العنوان
            $oldAddrIcon = $_POST['old_contact_address_icon'] ?? ($currentSettings['contact_address_icon'] ?? '');
            $addrIcon = $oldAddrIcon;
            if (isset($_FILES['contact_address_icon']) && $_FILES['contact_address_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAddrIcon)) {
                    $this->deleteOldImageFile($oldAddrIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_address_icon']['tmp_name']);
                $addrIcon = 'assets/uploads/' . $filename;
            }
            $stmt->execute(['k' => 'contact_address_icon', 'v' => $addrIcon, 'v_update' => $addrIcon]);

            // أيقونة البريد الإلكتروني
            $oldEmailIcon = $_POST['old_contact_email_icon'] ?? ($currentSettings['contact_email_icon'] ?? '');
            $emailIcon = $oldEmailIcon;
            if (isset($_FILES['contact_email_icon']) && $_FILES['contact_email_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldEmailIcon)) {
                    $this->deleteOldImageFile($oldEmailIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_email_icon']['tmp_name']);
                $emailIcon = 'assets/uploads/' . $filename;
            }
            $stmt->execute(['k' => 'contact_email_icon', 'v' => $emailIcon, 'v_update' => $emailIcon]);

            // أيقونة الهاتف
            $oldPhoneIcon = $_POST['old_contact_phone_icon'] ?? ($currentSettings['contact_phone_icon'] ?? '');
            $phoneIcon = $oldPhoneIcon;
            if (isset($_FILES['contact_phone_icon']) && $_FILES['contact_phone_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldPhoneIcon)) {
                    $this->deleteOldImageFile($oldPhoneIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_phone_icon']['tmp_name']);
                $phoneIcon = 'assets/uploads/' . $filename;
            }
            $stmt->execute(['k' => 'contact_phone_icon', 'v' => $phoneIcon, 'v_update' => $phoneIcon]);
        } 
        // 3. تحديث قسم الواتساب والتواصل المباشر (WhatsApp Section)
        elseif ($action === 'update_whatsapp_section') {
            $waText   = $_POST['whatsapp_text'] ?? '';
            $waUrl    = $_POST['whatsapp_url'] ?? '';
            $waBtnTxt = $_POST['whatsapp_btn_txt'] ?? 'تواصل عبر الواتساب';

            $stmt->execute(['k' => 'whatsapp_text', 'v' => $waText, 'v_update' => $waText]);
            $stmt->execute(['k' => 'whatsapp_url', 'v' => $waUrl, 'v_update' => $waUrl]);
            $stmt->execute(['k' => 'whatsapp_btn_txt', 'v' => $waBtnTxt, 'v_update' => $waBtnTxt]);
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
