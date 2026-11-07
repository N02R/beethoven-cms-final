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

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");
        $targetLang = $_POST['targetLang'] ?? $_POST['contact_lang'] ?? $_SESSION['site_lang'] ?? 'ar';

        // دالة مساعدة لجلب وتحليل بيانات الـ JSON لضمان تعدد اللغات من قاعدة البيانات
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

        // 1. تحديث هيرو صفحة تواصل معنا (Contact Hero) متعدد اللغات
        if ($action === 'update_contact_hero') {
            $allLangs = $getSettingAllLangs('contact_hero');
            $currentLangData = $allLangs[$targetLang] ?? ($allLangs['ar'] ?? []);

            $oldHeroImg = $_POST['old_contact_hero_img'] ?? ($currentLangData['img'] ?? 'assets/img/contacthero.png');
            $heroImg = $oldHeroImg;

            if (isset($_FILES['contact_hero_img']) && $_FILES['contact_hero_img']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldHeroImg) && str_starts_with($oldHeroImg, 'assets/uploads/')) {
                    $this->deleteOldImageFile($oldHeroImg);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_hero_img']['tmp_name']);
                $heroImg = 'assets/uploads/' . $filename;
            }

            $allLangs[$targetLang] = [
                'img' => $heroImg
            ];

            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'contact_hero', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 2. تحديث معلومات وأيقونات التواصل (Contact Info & Icons) متعدد اللغات
        elseif ($action === 'update_contact_info') {
            $allLangs = $getSettingAllLangs('contact_info');
            $currentLangData = $allLangs[$targetLang] ?? ($allLangs['ar'] ?? []);
            $postedInfo = $_POST['contact_info'][$targetLang] ?? $_POST;

            $title = trim((string)($postedInfo['title'] ?? ($postedInfo['contact_info_title'] ?? ($currentLangData['title'] ?? 'معلومات التواصل'))));
            $desc  = trim((string)($postedInfo['desc'] ?? ($postedInfo['contact_info_desc'] ?? ($currentLangData['desc'] ?? ''))));
            $addr  = trim((string)($postedInfo['address'] ?? ($postedInfo['contact_address'] ?? ($currentLangData['address'] ?? ''))));
            $email = trim((string)($postedInfo['email'] ?? ($postedInfo['contact_email'] ?? ($currentLangData['email'] ?? ''))));
            $phone = trim((string)($postedInfo['phone'] ?? ($postedInfo['contact_phone'] ?? ($currentLangData['phone'] ?? ''))));

            // معالجة أيقونة العنوان (Address Icon)
            $oldAddrIcon = $postedInfo['old_address_icon'] ?? ($currentLangData['address_icon'] ?? 'assets/img/Location.svg');
            $addrIcon = $oldAddrIcon;
            if (isset($_FILES['contact_address_icon']) && $_FILES['contact_address_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldAddrIcon) && str_starts_with($oldAddrIcon, 'assets/uploads/')) {
                    $this->deleteOldImageFile($oldAddrIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_address_icon']['tmp_name']);
                $addrIcon = 'assets/uploads/' . $filename;
            }

            // معالجة أيقونة البريد الإلكتروني (Email Icon)
            $oldEmailIcon = $postedInfo['old_email_icon'] ?? ($currentLangData['email_icon'] ?? 'assets/img/Mail.svg');
            $emailIcon = $oldEmailIcon;
            if (isset($_FILES['contact_email_icon']) && $_FILES['contact_email_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldEmailIcon) && str_starts_with($oldEmailIcon, 'assets/uploads/')) {
                    $this->deleteOldImageFile($oldEmailIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_email_icon']['tmp_name']);
                $emailIcon = 'assets/uploads/' . $filename;
            }

            // معالجة أيقونة الهاتف (Phone Icon)
            $oldPhoneIcon = $postedInfo['old_phone_icon'] ?? ($currentLangData['phone_icon'] ?? 'assets/img/Call.svg');
            $phoneIcon = $oldPhoneIcon;
            if (isset($_FILES['contact_phone_icon']) && $_FILES['contact_phone_icon']['error'] === UPLOAD_ERR_OK) {
                if (!empty($oldPhoneIcon) && str_starts_with($oldPhoneIcon, 'assets/uploads/')) {
                    $this->deleteOldImageFile($oldPhoneIcon);
                }
                $filename = $this->imageUploader->processAndUploadFile($_FILES['contact_phone_icon']['tmp_name']);
                $phoneIcon = 'assets/uploads/' . $filename;
            }

            $allLangs[$targetLang] = [
                'title'        => $title,
                'desc'         => $desc,
                'address'      => $addr,
                'email'        => $email,
                'phone'        => $phone,
                'address_icon' => $addrIcon,
                'email_icon'   => $emailIcon,
                'phone_icon'   => $phoneIcon
            ];

            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'contact_info', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        } 
        // 3. تحديث قسم الواتساب والتواصل المباشر (WhatsApp Section) متعدد اللغات
        elseif ($action === 'update_whatsapp_section') {
            $allLangs = $getSettingAllLangs('whatsapp_section');
            $currentLangData = $allLangs[$targetLang] ?? ($allLangs['ar'] ?? []);
            $postedWa = $_POST['whatsapp_section'][$targetLang] ?? $_POST;

            $waText   = trim((string)($postedWa['text'] ?? ($postedWa['whatsapp_text'] ?? ($currentLangData['text'] ?? ''))));
            $waUrl    = trim((string)($postedWa['url'] ?? ($postedWa['whatsapp_url'] ?? ($currentLangData['url'] ?? '#'))));
            $waBtnTxt = trim((string)($postedWa['btn_text'] ?? ($postedWa['whatsapp_btn_txt'] ?? ($currentLangData['btn_text'] ?? 'تواصل عبر الواتساب'))));

            $allLangs[$targetLang] = [
                'text'     => $waText,
                'url'      => $waUrl,
                'btn_text' => $waBtnTxt
            ];

            $jsonVal = json_encode($allLangs, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'whatsapp_section', 'v' => $jsonVal, 'v_update' => $jsonVal]);
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
