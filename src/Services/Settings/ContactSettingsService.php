<?php
declare(strict_types=1);

namespace App\Services\Settings;

use App\Services\ImageUploader;
use PDO;

class ContactSettingsService
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    private string $rootPath;
    private ImageUploader $imageUploader;

    /** @var string[] قائمة الملفات القديمة المراد حذفها بعد نجاح الحفظ في قاعدة البيانات */
    private array $filesToDelete = [];

    public function __construct(string $rootPath, ImageUploader $imageUploader)
    {
        $this->rootPath = rtrim($rootPath, '/\\');
        $this->imageUploader = $imageUploader;
    }

    public function handleAction(string $action, PDO $pdo, array $currentSettings): bool
    {
        $allowedActions = [
            'update_contact_hero',
            'update_contact_info',
            'update_whatsapp_section',
        ];

        if (!in_array($action, $allowedActions, true)) {
            return false;
        }

        $this->filesToDelete = [];
        $targetLang = $this->resolveTargetLang(['targetLang', 'contact_lang', 'lang']);

        // 1. تحديث هيرو صفحة تواصل معنا (Contact Hero) متعدد اللغات
        if ($action === 'update_contact_hero') {
            $allLangs = $this->fetchSetting($pdo, 'contact_hero', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $dbOldImg = (string)($currentLangData['img'] ?? 'assets/img/contacthero.png');
            $submittedOldImg = (string)($_POST['old_contact_hero_img'] ?? $dbOldImg);
            $heroImg = $this->handleImageField('contact_hero_img', $submittedOldImg, $dbOldImg);
            if ($heroImg === '') {
                $heroImg = 'assets/img/contacthero.png';
            }

            $allLangs[$targetLang] = [
                'img' => $heroImg,
            ];

            $this->saveSetting($pdo, 'contact_hero', $allLangs);
        }

        // 2. تحديث معلومات وأيقونات التواصل (Contact Info & Icons) متعدد اللغات
        elseif ($action === 'update_contact_info') {
            $allLangs = $this->fetchSetting($pdo, 'contact_info', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedInfo = is_array($_POST['contact_info'][$targetLang] ?? null)
                ? $_POST['contact_info'][$targetLang]
                : (is_array($_POST['contact_info'] ?? null) ? $_POST['contact_info'] : $_POST);

            $title = trim((string)($postedInfo['title'] ?? ($postedInfo['contact_info_title'] ?? ($currentLangData['title'] ?? ''))));
            $desc  = trim((string)($postedInfo['desc'] ?? ($postedInfo['contact_info_desc'] ?? ($currentLangData['desc'] ?? ''))));
            $addr  = trim((string)($postedInfo['address'] ?? ($postedInfo['contact_address'] ?? ($currentLangData['address'] ?? ''))));
            $email = trim((string)($postedInfo['email'] ?? ($postedInfo['contact_email'] ?? ($currentLangData['email'] ?? ''))));
            $phone = trim((string)($postedInfo['phone'] ?? ($postedInfo['contact_phone'] ?? ($currentLangData['phone'] ?? ''))));

            // معالجة أيقونة العنوان (Address Icon)
            $dbOldAddrIcon = (string)($currentLangData['address_icon'] ?? 'assets/img/Location.svg');
            $addrIcon = $this->handleImageField(
                'contact_address_icon',
                (string)($postedInfo['old_address_icon'] ?? $dbOldAddrIcon),
                $dbOldAddrIcon
            );
            if ($addrIcon === '') {
                $addrIcon = 'assets/img/Location.svg';
            }

            // معالجة أيقونة البريد الإلكتروني (Email Icon)
            $dbOldEmailIcon = (string)($currentLangData['email_icon'] ?? 'assets/img/Mail.svg');
            $emailIcon = $this->handleImageField(
                'contact_email_icon',
                (string)($postedInfo['old_email_icon'] ?? $dbOldEmailIcon),
                $dbOldEmailIcon
            );
            if ($emailIcon === '') {
                $emailIcon = 'assets/img/Mail.svg';
            }

            // معالجة أيقونة الهاتف (Phone Icon)
            $dbOldPhoneIcon = (string)($currentLangData['phone_icon'] ?? 'assets/img/Call.svg');
            $phoneIcon = $this->handleImageField(
                'contact_phone_icon',
                (string)($postedInfo['old_phone_icon'] ?? $dbOldPhoneIcon),
                $dbOldPhoneIcon
            );
            if ($phoneIcon === '') {
                $phoneIcon = 'assets/img/Call.svg';
            }

            $allLangs[$targetLang] = [
                'title'        => $title,
                'desc'         => $desc,
                'address'      => $addr,
                'email'        => $email,
                'phone'        => $phone,
                'address_icon' => $addrIcon,
                'email_icon'   => $emailIcon,
                'phone_icon'   => $phoneIcon,
            ];

            $this->saveSetting($pdo, 'contact_info', $allLangs);
        }

        // 3. تحديث قسم الواتساب والتواصل المباشر (WhatsApp Section) متعدد اللغات
        elseif ($action === 'update_whatsapp_section') {
            $allLangs = $this->fetchSetting($pdo, 'whatsapp_section', $currentSettings);
            $currentLangData = is_array($allLangs[$targetLang] ?? null)
                ? $allLangs[$targetLang]
                : (is_array($allLangs['de'] ?? null) ? $allLangs['de'] : []);

            $postedWa = is_array($_POST['whatsapp_section'][$targetLang] ?? null)
                ? $_POST['whatsapp_section'][$targetLang]
                : (is_array($_POST['whatsapp_section'] ?? null) ? $_POST['whatsapp_section'] : $_POST);

            $waText   = trim((string)($postedWa['text'] ?? ($postedWa['whatsapp_text'] ?? ($currentLangData['text'] ?? ''))));
            $waUrl    = $this->sanitizeLinkUrl((string)($postedWa['url'] ?? ($postedWa['whatsapp_url'] ?? ($currentLangData['url'] ?? '#'))));
            $waBtnTxt = trim((string)($postedWa['btn_text'] ?? ($postedWa['whatsapp_btn_txt'] ?? ($currentLangData['btn_text'] ?? ''))));

            $allLangs[$targetLang] = [
                'text'     => $waText,
                'url'      => $waUrl,
                'btn_text' => $waBtnTxt,
            ];

            $this->saveSetting($pdo, 'whatsapp_section', $allLangs);
        }

        // تنفيذ الحذف الآمن للملفات القديمة فقط بعد نجاح التحديث في قاعدة البيانات
        $this->flushDeletedFiles();

        return true;
    }

    private function resolveTargetLang(array $postKeys): string
    {
        foreach ($postKeys as $key) {
            if (!empty($_POST[$key]) && is_string($_POST[$key])) {
                $candidate = strtolower(trim($_POST[$key]));
                if (in_array($candidate, self::SUPPORTED_LANGS, true)) {
                    return $candidate;
                }
            }
        }

        $sessionLang = strtolower(trim((string)($_SESSION['site_lang'] ?? 'de')));
        return in_array($sessionLang, self::SUPPORTED_LANGS, true) ? $sessionLang : 'de';
    }

    private function fetchSetting(PDO $pdo, string $key, array $currentSettings): array
    {
        $stmtFetch = $pdo->prepare('SELECT setting_value FROM global_settings WHERE setting_key = :k LIMIT 1');
        $stmtFetch->execute(['k' => $key]);
        $dbRaw = $stmtFetch->fetchColumn();

        if (is_string($dbRaw) && $dbRaw !== '') {
            $decoded = json_decode($dbRaw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (isset($currentSettings[$key])) {
            if (is_array($currentSettings[$key])) {
                return $currentSettings[$key];
            }
            if (is_string($currentSettings[$key])) {
                $decoded = json_decode($currentSettings[$key], true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        return [];
    }

    private function saveSetting(PDO $pdo, string $key, array $data): void
    {
        $jsonVal = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $stmt = $pdo->prepare(
            'INSERT INTO global_settings (setting_key, setting_value)
             VALUES (:k, :v)
             ON DUPLICATE KEY UPDATE setting_value = :v_update'
        );
        $stmt->execute([
            'k'        => $key,
            'v'        => $jsonVal,
            'v_update' => $jsonVal,
        ]);
    }

    private function handleImageField(string $fileInputKey, string $submittedOldPath, string $dbOldPath): string
    {
        $safeOld = $this->sanitizeImagePath($submittedOldPath !== '' ? $submittedOldPath : $dbOldPath);

        if (isset($_FILES[$fileInputKey]) && is_array($_FILES[$fileInputKey]) && ($_FILES[$fileInputKey]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $filename = $this->imageUploader->processAndUploadFile((string)$_FILES[$fileInputKey]['tmp_name']);
            if ($dbOldPath !== '' && str_starts_with(ltrim($dbOldPath, '/'), 'assets/uploads/')) {
                $this->filesToDelete[] = ltrim($dbOldPath, '/');
            }
            return 'assets/uploads/' . $filename;
        }

        return $safeOld;
    }

    private function sanitizeImagePath(string $path): string
    {
        $clean = trim($path);
        if ($clean === '' || str_contains($clean, '..') || preg_match('/^(javascript|data|vbscript|file):/i', $clean)) {
            return '';
        }

        if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
            return filter_var($clean, FILTER_VALIDATE_URL) ? $clean : '';
        }

        return ltrim($clean, '/');
    }

    private function sanitizeLinkUrl(string $url): string
    {
        $clean = trim($url);
        if ($clean === '' || $clean === '#') {
            return '#';
        }

        if (preg_match('/^(javascript|data|vbscript|file):/i', $clean)) {
            return '#';
        }

        return $clean;
    }

    private function flushDeletedFiles(): void
    {
        foreach (array_unique($this->filesToDelete) as $relativePath) {
            if (!str_starts_with($relativePath, 'assets/uploads/')) {
                continue;
            }
            $this->imageUploader->deleteOldImage(basename($relativePath));
        }
        $this->filesToDelete = [];
    }
}
