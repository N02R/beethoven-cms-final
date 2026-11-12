<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;
use Throwable;

class FooterSettingsService
{
    private string $rootPath;
    private ImageUploader $imageUploader;
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = $rootPath;
        $this->imageUploader = $imageUploader;
    }

    private function resolveTargetLang(string ...$candidates): string
    {
        foreach ($candidates as $lang) {
            if ($lang !== '' && in_array($lang, self::SUPPORTED_LANGS, true)) {
                return $lang;
            }
        }
        return 'de';
    }

    private function sanitizeUrl(mixed $rawUrl, string $default = '#'): string
    {
        $url = trim((string)($rawUrl ?? ''));
        if ($url === '' || $url === '#') {
            return $default;
        }
        if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
            return $default;
        }
        return $url;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        if ($action !== 'update_footer') {
            return false;
        }

        $stmt = $pdo->prepare("
            INSERT INTO global_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        try {
            $targetLang = $this->resolveTargetLang(
                (string)($_POST['footer_lang'] ?? ''),
                (string)($_POST['targetLang'] ?? ''),
                (string)($_POST['lang'] ?? ''),
                (string)($_SESSION['site_lang'] ?? 'de')
            );

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

            $postedFooter    = $_POST['footer'][$targetLang] ?? $_POST['footer'] ?? [];
            $currentLangData = $footerAllLangs[$targetLang] ?? [];

            $consultTitle = trim((string)($postedFooter['consult_title'] ?? ($currentLangData['consult_title'] ?? '')));
            $consultDesc  = trim((string)($postedFooter['consult_desc'] ?? ($currentLangData['consult_desc'] ?? '')));
            $footerDesc   = trim((string)($postedFooter['footer_desc'] ?? ($currentLangData['footer_desc'] ?? '')));
            $col2Title    = trim((string)($postedFooter['col2_title'] ?? ($currentLangData['col2_title'] ?? '')));
            $col3Title    = trim((string)($postedFooter['col3_title'] ?? ($currentLangData['col3_title'] ?? '')));

            $incomingCol3   = $_POST['col3'] ?? [];
            $processedItems = [];

            if (is_array($incomingCol3)) {
                foreach ($incomingCol3 as $index => $item) {
                    if (!is_array($item)) continue;

                    $title  = trim((string)($item['title'] ?? ''));
                    $url    = $this->sanitizeUrl($item['url'] ?? '#');
                    $oldImg = (string)($item['old_img'] ?? '');
                    
                    $fileToCheck = $_FILES['col3_img_' . $index] ?? null;

                    $imgVal = $oldImg;
                    if ($fileToCheck && is_array($fileToCheck) && $fileToCheck['error'] === UPLOAD_ERR_OK) {
                        if ($oldImg !== '' && str_starts_with($oldImg, 'assets/uploads/')) {
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

            return $stmt->execute(['k' => 'footer', 'v' => $jsonVal, 'v_update' => $jsonVal]);

        } catch (Throwable $e) {
            error_log('FooterSettingsService Error: ' . $e->getMessage());
            return false;
        }
    }

    private function deleteOldImageFile(string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, 'assets/uploads/')) {
            return;
        }

        $uploadsBase = realpath($this->rootPath . '/public/assets/uploads');
        $targetFile  = realpath($this->rootPath . '/public/' . $imagePath);

        if ($uploadsBase !== false && $targetFile !== false && str_starts_with($targetFile, $uploadsBase . DIRECTORY_SEPARATOR) && is_file($targetFile)) {
            @unlink($targetFile);
        }
    }
}
