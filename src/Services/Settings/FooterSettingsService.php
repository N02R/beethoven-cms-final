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

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        try {
            $targetLang = $_POST['footer_lang'] ?? $_POST['targetLang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

            // 1. معالجة وحفظ نصوص الفوتر (footer)
            $rawFooterSetting = $currentSettings['footer'] ?? '{}';
            $footerAllLangs = json_decode($rawFooterSetting, true) ?: [];
            $postedFooter = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];
            $currentLangData = $footerAllLangs[$targetLang] ?? [];

            $footerAllLangs[$targetLang] = [
                'consult_title' => trim((string)($postedFooter['consult_title'] ?? ($currentLangData['consult_title'] ?? 'احصل على استشارة مجانية'))),
                'consult_desc'  => trim((string)($postedFooter['consult_desc'] ?? ($currentLangData['consult_desc'] ?? ''))),
                'footer_desc'   => trim((string)($postedFooter['footer_desc'] ?? ($currentLangData['footer_desc'] ?? ''))),
                'col2_title'    => trim((string)($postedFooter['col2_title'] ?? ($currentLangData['col2_title'] ?? 'روابط سريعة'))),
                'col3_title'    => trim((string)($postedFooter['col3_title'] ?? ($currentLangData['col3_title'] ?? 'تواصل معنا')))
            ];

            $jsonFooterVal = json_encode($footerAllLangs, JSON_UNESCAPED_UNICODE);
            if ($jsonFooterVal === false) $jsonFooterVal = '{}';
            $stmt->execute(['k' => 'footer', 'v' => $jsonFooterVal, 'v_update' => $jsonFooterVal]);


            // 2. معالجة وحفظ روابط العمود الثالث (footer_col3_links) وفق النمط القياسي للغات
            $rawCol3Setting = $currentSettings['footer_col3_links'] ?? '{}';
            $col3AllLangs = json_decode($rawCol3Setting, true);
            if (!is_array($col3AllLangs)) {
                $col3AllLangs = []; // تحويل الهيكل القديم إن وجد إلى فارغ لتجنب التضارب
            }

            // استقبال العناصر المرسلة للغة الحالية عبر الفورم
            $postedCol3Items = $_POST['col3'] ?? [];
            $processedItems = [];

            if (is_array($postedCol3Items)) {
                foreach ($postedCol3Items as $index => $item) {
                    if (!is_array($item)) continue;

                    $title = trim($item['title'] ?? '');
                    $url   = trim($item['url'] ?? '#');
                    $oldImg = $item['old_img'] ?? '';
                    $fileToCheck = $_FILES['col3_img_' . $index] ?? null;

                    $imgVal = $oldImg;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if (!empty($oldImg) && str_starts_with($oldImg, 'assets/uploads/')) {
                            $this->deleteOldImageFile($oldImg);
                        }
                        $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                        $imgVal = 'assets/uploads/' . $filename;
                    }

                    if ($title !== '' || $url !== '#') {
                        $processedItems[] = [
                            'title' => $title,
                            'url'   => $url,
                            'img'   => $imgVal
                        ];
                    }
                }
            }

            // حفظ العناصر تحت مصفوفة items للغة المستهدفة مع الحفاظ على اللغات الأخرى
            $col3AllLangs[$targetLang] = [
                'items' => $processedItems
            ];

            $jsonCol3Val = json_encode($col3AllLangs, JSON_UNESCAPED_UNICODE);
            if ($jsonCol3Val === false) $jsonCol3Val = '{}';
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
