<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;
use Exception;

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

        try {
            // 1. تحديد اللغة المستهدفة من المودل بدقة (الافتراضية ar)
            $targetLang = $_POST['footer_lang'] ?? $_POST['targetLang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            // جلب البيانات القديمة للفوتر لتحسس اللغات الثلاث والحفاظ عليها
            $rawFooterSetting = $currentSettings['footer'] ?? '{}';
            $footerAllLangs = json_decode($rawFooterSetting, true);
            if (!is_array($footerAllLangs)) {
                $footerAllLangs = [];
            }

            // جلب البيانات المدخلة من الفورم للمحتوى النصي
            $postedFooter = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];

            $currentLangData = $footerAllLangs[$targetLang] ?? [];

            $consultTitle = trim((string)($postedFooter['consult_title'] ?? ($currentLangData['consult_title'] ?? 'احصل على استشارة مجانية')));
            $consultDesc  = trim((string)($postedFooter['consult_desc'] ?? ($currentLangData['consult_desc'] ?? '')));
            $footerDesc   = trim((string)($postedFooter['footer_desc'] ?? ($currentLangData['footer_desc'] ?? '')));
            $col2Title    = trim((string)($postedFooter['col2_title'] ?? ($currentLangData['col2_title'] ?? 'روابط سريعة')));
            $col3Title    = trim((string)($postedFooter['col3_title'] ?? ($currentLangData['col3_title'] ?? 'تواصل معنا')));

            // تحديث لغة الزائر/المشرف الحالية داخل المصفوفة التجميعية مع الحفاظ على بقية اللغات
            $footerAllLangs[$targetLang] = [
                'consult_title' => $consultTitle,
                'consult_desc'  => $consultDesc,
                'footer_desc'   => $footerDesc,
                'col2_title'    => $col2Title,
                'col3_title'    => $col3Title
            ];

            // ترميز وإنتاج JSON صالح 100% ومختبر ضد قيود القاعدة
            $jsonFooterVal = json_encode($footerAllLangs, JSON_UNESCAPED_UNICODE);
            if ($jsonFooterVal === false || trim($jsonFooterVal) === '') {
                $jsonFooterVal = '{}';
            }
            $stmt->execute(['k' => 'footer', 'v' => $jsonFooterVal, 'v_update' => $jsonFooterVal]);

            // 2. معالجة وحفظ روابط العمود الثالث (footer_col3_links) مع دعم تعدد اللغات لكل عنصر
            $footerCol3Data = $_POST['col3'] ?? [];
            
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
                    if ($newTitleText === '' && empty($item['url'])) continue;

                    $oldImg = $item['old_img'] ?? '';
                    $fileToCheck = $_FILES['col3_img_' . $index] ?? null;

                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
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
                    $elementData['title'] = $newTitleText;

                    $processedCol3[] = $elementData;
                }
            }

            // إنتاج مصفوفة JSON صالحة وموثوقة بنسبة 100%
            $jsonCol3Val = json_encode(array_values($processedCol3), JSON_UNESCAPED_UNICODE);
            if ($jsonCol3Val === false || trim($jsonCol3Val) === '') {
                $jsonCol3Val = '[]';
            }
            $stmt->execute(['k' => 'footer_col3_links', 'v' => $jsonCol3Val, 'v_update' => $jsonCol3Val]);

            return true;

        } catch (Exception $e) {
            error_log("FooterSettingsService Error: " . $e->getMessage());
            return false;
        }
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
