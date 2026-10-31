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

        $stmt = $pdo->prepare("INSERT INTO global_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v_update");

        // 1. معالجة وحفظ إعدادات نصوص الفوتر الموحدة (الهيكل الجديد تحت المفتاح 'footer')
        $targetLang = $_POST['targetLang'] ?? $_POST['footer_lang'] ?? $_POST['lang'] ?? $_SESSION['site_lang'] ?? 'ar';

        $stmtFetch = $pdo->prepare("SELECT setting_value FROM global_settings WHERE setting_key = 'footer'");
        $stmtFetch->execute();
        $dbRaw = $stmtFetch->fetchColumn();

        $footerAllLangs = [];
        if ($dbRaw && is_string($dbRaw)) {
            $decodedDb = json_decode($dbRaw, true);
            if (is_array($decodedDb)) {
                $footerAllLangs = $decodedDb;
            }
        }

        $currentLangData = $footerAllLangs[$targetLang] ?? [];
        $existingConsultTitle = $currentLangData['consult_title'] ?? 'احصل على استشارة مجانية';
        $existingConsultDesc  = $currentLangData['consult_desc'] ?? '';
        $existingFooterDesc   = $currentLangData['footer_desc'] ?? '';
        $existingCol2Title    = $currentLangData['col2_title'] ?? 'روابط سريعة';
        $existingCol3Title    = $currentLangData['col3_title'] ?? 'تواصل معنا';

        $postedFooter = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];

        $consultTitle = trim((string)($postedFooter['consult_title'] ?? $_POST['consult_title'] ?? $existingConsultTitle));
        if ($consultTitle === '') $consultTitle = $existingConsultTitle;

        $consultDesc  = trim((string)($postedFooter['consult_desc'] ?? $_POST['consult_desc'] ?? $existingConsultDesc));
        $footerDesc   = trim((string)($postedFooter['footer_desc'] ?? $_POST['footer_desc'] ?? $existingFooterDesc));
        $col2Title    = trim((string)($postedFooter['col2_title'] ?? $_POST['footer_col2_title'] ?? $existingCol2Title));
        $col3Title    = trim((string)($postedFooter['col3_title'] ?? $_POST['footer_col3_title'] ?? $existingCol3Title));

        $footerAllLangs[$targetLang] = [
            'consult_title' => $consultTitle,
            'consult_desc'  => $consultDesc,
            'footer_desc'   => $footerDesc,
            'col2_title'    => $col2Title,
            'col3_title'    => $col3Title
        ];

        $jsonFooterVal = json_encode($footerAllLangs, JSON_UNESCAPED_UNICODE);
        $stmt->execute(['k' => 'footer', 'v' => $jsonFooterVal, 'v_update' => $jsonFooterVal]);

        // 2. معالجة وحفظ روابط العمود الثالث (footer_col3_links) مع رفع الصور وتأمينها
        $footerCol3Data = $_POST['col3'] ?? [];
        if (is_array($footerCol3Data)) {
            foreach ($footerCol3Data as $index => $item) {
                $fileToCheck = $_FILES['col3_img_' . $index] ?? ($_FILES['col3'][$index]['img'] ?? null);
                if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                    if (!empty($item['old_img'])) {
                        $this->deleteOldImageFile($item['old_img']);
                    }
                    $filename = $this->imageUploader->processAndUploadFile($fileToCheck['tmp_name']);
                    $footerCol3Data[$index]['img'] = 'assets/uploads/' . $filename;
                } else {
                    $footerCol3Data[$index]['img'] = $item['old_img'] ?? '';
                }
                unset($footerCol3Data[$index]['old_img']);
            }
            $jsonCol3Val = json_encode(array_values($footerCol3Data), JSON_UNESCAPED_UNICODE);
            $stmt->execute(['k' => 'footer_col3_links', 'v' => $jsonCol3Val, 'v_update' => $jsonCol3Val]);
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
