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

        // استخدام جدول global_settings الحقيقي في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديث الشعار مع دعم اللغات المتعددة (ar, en, de) بصيغة JSON
        if ($action === 'update_logo') {
            if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                // استهداف اللغة المحددة من المودل (أو الافتراضية ar)
                $targetLang = $_POST['logo_lang'] ?? 'ar';

                // جلب الشعارات القديمة المخزنة كـ JSON أو تحويل النص القديم لمصفوفة
                $rawLogoSetting = $currentSettings['site_logo_path'] ?? ($currentSettings['site_logo'] ?? '');
                $logos = json_decode($rawLogoSetting, true);
                if (!is_array($logos)) {
                    $logos = [];
                    // إذا كان القديم نصاً عادياً وليس JSON، نعتبره شعاراً للغة العربية مبدئياً
                    if (!empty($rawLogoSetting)) {
                        $logos['ar'] = $rawLogoSetting;
                    }
                }

                // حذف الشعار القديم الخاص بهذه اللغة فقط إن وجد
                if (!empty($logos[$targetLang])) {
                    $this->deleteOldImageFile($logos[$targetLang]);
                }

                // رفع الشعار الجديد وتوليد مساره
                $filename = $this->imageUploader->processAndUploadFile($_FILES['logo_img']['tmp_name']);
                $logoPath = 'assets/uploads/' . $filename;
                
                // تحديث مصفوفة الشعار للغة المستهدفة
                $logos[$targetLang] = $logoPath;

                $jsonVal = json_encode($logos, JSON_UNESCAPED_UNICODE);

                // حفظه في الحقلين للتوافقية الكاملة مع بقية النظام
                $stmt->execute(['k' => 'site_logo_path', 'v' => $jsonVal, 'v_update' => $jsonVal]);
                $stmt->execute(['k' => 'site_logo', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            }
        }

        // 2. تحديث منصات التواصل الاجتماعي (يدعم تعدد اللغات)
        elseif ($action === 'update_social') {
            $targetLang = $_POST['social_lang'] ?? ($current_lang ?? 'ar');

            $rawSocialSetting = $currentSettings['social_links'] ?? '';
            $allSocial = json_decode($rawSocialSetting, true);
            if (!is_array($allSocial)) {
                $allSocial = [];
                if (!empty($rawSocialSetting)) {
                    $legacyData = json_decode($rawSocialSetting, true);
                    if (is_array($legacyData)) {
                        $allSocial['ar'] = $legacyData; // Fallback للبيانات القديمة
                    }
                }
            }

            // استقبال البيانات المرسلة وتجاوز هيكل الـ items واللغة
            $incomingSocial = $_POST['social'] ?? [];
            $langInput = $incomingSocial[$targetLang] ?? $incomingSocial;
            $items = $langInput['items'] ?? (is_array($langInput) && isset($langInput[0]) ? $langInput : []);

            $socialItems = [];
            foreach ($items as $index => $item) {
                $nameVal = trim($item['name'] ?? '');
                if ($nameVal === '') continue; // تخطي العناصر الفارغة

                // فحص الملف المرفوع للصورة/الأيقونة
                $fileToCheck = $_FILES['social_img_' . $index] ?? ($_FILES['social'][$targetLang]['items'][$index]['img'] ?? null);
                
                $oldImg = $item['old_img'] ?? '';
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                        $this->deleteOldImageFile($rootPath, $oldImg);
                    }
                    $filename = $imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $imgVal = 'assets/uploads/' . $filename;
                } else {
                    $imgVal = $oldImg;
                }

                $socialItems[] = [
                    'name' => $nameVal,
                    'url'  => $item['url'] ?? '#',
                    'img'  => $imgVal
                ];
            }

            $allSocial[$targetLang] = $socialItems;
            $jsonVal = json_encode($allSocial, JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'social_links', 'v' => $jsonVal, 'v_update' => $jsonVal]);
        }

        // 3. تحديث القائمة الرئيسية (Menu) حسب اللغة الحالية
        elseif ($action === 'update_menu') {
            $targetLang = $_POST['menu_lang'] ?? 'ar';
            $menuInput  = $_POST['menu'] ?? [];

            // جلب البيانات القديمة لتحديث اللغة المستهدفة فقط
            $oldMenuRaw = $currentSettings['menu_links'] ?? '[]';
            $oldMenuData = json_decode($oldMenuRaw, true);
            if (!is_array($oldMenuData)) {
                $oldMenuData = [];
            }

            $formattedMenu = [];

            foreach ($menuInput as $index => $item) {
                $oldItem = $oldMenuData[$index] ?? [];

                // إعداد مصفوفة اللغات مع الحفاظ على البيانات السابقة
                $itemLangs = [
                    'ar' => ['title' => trim($oldItem['ar']['title'] ?? '')],
                    'en' => ['title' => trim($oldItem['en']['title'] ?? '')],
                    'de' => ['title' => trim($oldItem['de']['title'] ?? '')],
                ];

                // تحديث عنوان اللغة الحالية بالمدخل الجديد
                $itemLangs[$targetLang]['title'] = trim($item['title'] ?? '');

                $formattedMenu[] = [
                    'ar'    => $itemLangs['ar'],
                    'en'    => $itemLangs['en'],
                    'de'    => $itemLangs['de'],
                    'url'   => trim($item['url'] ?? ($oldItem['url'] ?? '')),
                    'order' => (int)($item['order'] ?? $index)
                ];
            }

            // ترتيب عناصر القائمة
            usort($formattedMenu, function ($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });

            $jsonVal = json_encode(array_values($formattedMenu), JSON_UNESCAPED_UNICODE);
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
