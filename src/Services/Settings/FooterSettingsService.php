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

            $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'footer'");
            $stmtFetch->execute();
            $dbRaw = $stmtFetch->fetchColumn();

            $footerAllLangs = [];
            if ($dbRaw) {
                if (is_string($dbRaw)) {
                    $decodedDb = json_decode($dbRaw, true);
                    if (is_array($decodedDb)) {
                        $footerAllLangs = $decodedDb;
                    }
                } elseif (is_array($dbRaw)) {
                    $footerAllLangs = $dbRaw;
                }
            }

            $postedFooter = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];
            $currentLangData = $footerAllLangs[$targetLang] ?? [];

            $consultTitle = trim((string)($postedFooter['consult_title'] ?? ($currentLangData['consult_title'] ?? 'احصل على استشارة مجانية')));
            $consultDesc  = trim((string)($postedFooter['consult_desc'] ?? ($currentLangData['consult_desc'] ?? '')));
            $footerDesc   = trim((string)($postedFooter['footer_desc'] ?? ($currentLangData['footer_desc'] ?? '')));
            $col2Title    = trim((string)($postedFooter['col2_title'] ?? ($currentLangData['col2_title'] ?? 'روابط سريعة')));
            $col3Title    = trim((string)($postedFooter['col3_title'] ?? ($currentLangData['col3_title'] ?? 'تواصل معنا')));

            $incomingCol3 = $_POST['col3'] ?? [];
            $processedItems = [];

            if (is_array($incomingCol3) && !empty($incomingCol3)) {
                foreach ($incomingCol3 as $index => $item) {
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
                        filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
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
            } else {
                $processedItems = $currentLangData['col3_items'] ?? [];
            }

            $footerAllLangs[$targetLang] = [
                'consult_title' => $consultTitle,
                'consult_desc'  => $consultDesc,
                'col2_title'    => $col2Title,
                'col3_title'    => $col3Title,
                'footer_desc'   => $footerDesc,
                'col3_items'    => $processedItems
            ];

            $jsonVal = json_encode($footerAllLangs, JSON_UNESCAPED_UNICODE);
            if ($jsonVal === false || trim($jsonVal) === '') {
                $jsonVal = '{}';
            }

            // 🌟 كشف الحقيقة: تسجيل القيم قبل تنفيذ الإدخال لقاعدة البيانات
            error_log("DEBUG FOOTER KEY: footer");
            error_log("DEBUG FOOTER JSON VAL: " . var_export($jsonVal, true));

            $stmt->execute(['k' => 'footer', 'v' => $jsonVal, 'v_update' => $jsonVal]);

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
