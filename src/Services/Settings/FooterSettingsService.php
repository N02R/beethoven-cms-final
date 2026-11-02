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

            // 1. معالجة وحفظ نصوص الفوتر (footer) بجلبها مباشرة من القاعدة لضمان الحداثة
            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'footer'");
            $stmtFetch->execute();
            $dbFooterRaw = $stmtFetch->fetchColumn();
            
            $footerAllLangs = [];
            if ($dbFooterRaw && is_string($dbFooterRaw)) {
                $decodedFooter = json_decode($dbFooterRaw, true);
                if (is_array($decodedFooter)) {
                    $footerAllLangs = $decodedFooter;
                }
            }

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
            if ($jsonFooterVal === false || trim($jsonFooterVal) === '') {
                $jsonFooterVal = '{}';
            }
            $stmt->execute(['k' => 'footer', 'v' => $jsonFooterVal, 'v_update' => $jsonFooterVal]);


            // 2. معالجة وحفظ روابط العمود الثالث (footer_col3_links) بجلبها مباشرة من القاعدة لتجنب الضياع
            $stmtCol3Fetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'footer_col3_links'");
            $stmtCol3Fetch->execute();
            $dbCol3Raw = $stmtCol3Fetch->fetchColumn();

            $col3AllLangs = [];
            if ($dbCol3Raw && is_string($dbCol3Raw)) {
                $decodedCol3 = json_decode($dbCol3Raw, true);
                if (is_array($decodedCol3)) {
                    $col3AllLangs = $decodedCol3;
                }
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

            // تحديث عناصر اللغة المستهدفة فقط مع الحفاظ على بقية اللغات (en, de) في المصفوفة
            $col3AllLangs[$targetLang] = [
                'items' => $processedItems
            ];

            $jsonCol3Val = json_encode($col3AllLangs, JSON_UNESCAPED_UNICODE);
            if ($jsonCol3Val === false || trim($jsonCol3Val) === '') {
                $jsonCol3Val = '{}';
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
