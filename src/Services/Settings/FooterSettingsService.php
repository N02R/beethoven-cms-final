<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class FooterSettingsService
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
        if ($action !== 'update_footer') {
            return false;
        }

        // استخدام جدول global_settings الحقيقي في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. تحديد اللغة المستهدفة من المودل (أو الافتراضية ar)
        $targetLang = $_POST['targetLang'] ?? $_POST['footer_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

        // جلب البيانات القديمة للفوتر لتحسس اللغات الثلاث والتعديل بسلاسة
        $rawFooterSetting = $currentSettings['footer'] ?? '';
        $footerAllLangs = json_decode($rawFooterSetting, true);
        if (!is_array($footerAllLangs)) {
            $footerAllLangs = [];
        }

        // جلب البيانات المدخلة من الفورم
        $postedFooter = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];

        // تجهيز بيانات اللغة المستهدفة مع الحفاظ على اللغات الأخرى
        $currentLangData = $footerAllLangs[$targetLang] ?? [];

        $consultTitle = trim((string)($postedFooter['consult_title'] ?? ($currentLangData['consult_title'] ?? 'احصل على استشارة مجانية')));
        $consultDesc  = trim((string)($postedFooter['consult_desc'] ?? ($currentLangData['consult_desc'] ?? '')));
        $footerDesc   = trim((string)($postedFooter['footer_desc'] ?? ($currentLangData['footer_desc'] ?? '')));
        $col2Title    = trim((string)($postedFooter['col2_title'] ?? ($currentLangData['col2_title'] ?? 'روابط سريعة')));
        $col3Title    = trim((string)($postedFooter['col3_title'] ?? ($currentLangData['col3_title'] ?? 'تواصل معنا')));

        // تحديث أو تعبئة مصفوفة اللغة المستهدفة
        $footerAllLangs[$targetLang] = [
            'consult_title' => $consultTitle,
            'consult_desc'  => $consultDesc,
            'footer_desc'   => $footerDesc,
            'col2_title'    => $col2Title,
            'col3_title'    => $col3Title
        ];

        // التأكد من صحة الـ JSON قبل حفظ النصوص
        $jsonFooterVal = json_encode($footerAllLangs, JSON_UNESCAPED_UNICODE);
        if ($jsonFooterVal === false) {
            $jsonFooterVal = '{}';
        }
        $stmt->execute(['k' => 'footer', 'v' => $jsonFooterVal, 'v_update' => $jsonFooterVal]);

        // 2. معالجة وحفظ روابط العمود الثالث (footer_col3_links) مع دعم تعدد اللغات لكل عنصر
        $footerCol3Data = $_POST['col3'] ?? ($_POST['footer'][$targetLang]['col3'] ?? []);
        
        $oldCol3Raw = $currentSettings['footer_col3_links'] ?? '[]';
        $oldCol3Arr = json_decode($oldCol3Raw, true);
        if (!is_array($oldCol3Arr)) {
            $oldCol3Arr = [];
        }

        $processedCol3 = [];
        if (is_array($footerCol3Data)) {
            foreach ($footerCol3Data as $index => $item) {
                if (!is_array($item)) continue;
                
                $newTitleText = trim($item['title'] ?? '');
                if ($newTitleText === '') continue;

                $oldImg = $item['old_img'] ?? '';
                $fileToCheck = $_FILES['col3_img_' . $index] ?? ($_FILES['col3'][$index]['img'] ?? null);

                if ($fileToCheck && is_array($fileToCheck) && ($fileToCheck['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                    if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                        $this->deleteOldImageFile($oldImg);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $imgVal = 'assets/uploads/' . $filename;
                } else {
                    $imgVal = $oldImg;
                }

                // استرجاع العنصر القديم للحفاظ على ترجمات اللغات الأخرى (ar, en, de)
                $existingItem = $oldCol3Arr[$index] ?? [];
                
                $elementData = [
                    'url' => trim($item['url'] ?? ($existingItem['url'] ?? '#')),
                    'img' => $imgVal
                ];

                // هيكلة الترجمات المتعددة للعنوان لكل لغة
                foreach (['ar', 'en', 'de'] as $langCode) {
                    if ($langCode === $targetLang) {
                        $elementData[$langCode] = ['title' => $newTitleText];
                    } else {
                        $elementData[$langCode] = $existingItem[$langCode] ?? ['title' => $newTitleText];
                    }
                }
                // للتوافقية الكاملة
                $elementData['title'] = $newTitleText;

                $processedCol3[] = $elementData;
            }
        }

        // التأكد من صحة الـ JSON قبل حفظ مصفوفة الروابط
        $jsonCol3Val = json_encode(array_values($processedCol3), JSON_UNESCAPED_UNICODE);
        if ($jsonCol3Val === false) {
            $jsonCol3Val = '[]';
        }
        $stmt->execute(['k' => 'footer_col3_links', 'v' => $jsonCol3Val, 'v_update' => $jsonCol3Val]);

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
