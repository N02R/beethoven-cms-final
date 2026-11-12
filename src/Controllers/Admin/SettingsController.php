<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Core\Security;
use App\Services\ImageUploader;
use App\Services\Settings\AboutSettingsService;
use App\Services\Settings\ContactSettingsService;
use App\Services\Settings\EduSettingsService;
use App\Services\Settings\FooterSettingsService;
use App\Services\Settings\GuideBlog1SettingsService;
use App\Services\Settings\HeaderSettingsService;
use App\Services\Settings\HomeSettingsService;
use App\Services\Settings\JobSettingsService;
use App\Services\Settings\PageContentSettingsService;
use Throwable;

class SettingsController
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];
    private const SESSION_IDLE_TIMEOUT = 1800; // 30 دقيقة

    public function save(): void
    {
        $this->checkAdminAuth();

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.', 'error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // قراءة ترويسة CSRF بغض النظر عن حالة الأحرف (Nginx / Apache)
        $headers = function_exists('getallheaders') ? array_change_key_case(getallheaders(), CASE_LOWER) : [];
        $token   = (string)($headers['x-csrf-token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '');

        if (!Security::verifyCsrfToken($token)) {
            http_response_code(403);
            $msg = 'انتهت صلاحية جلسة الأمان (CSRF Token)، يرجى تحديث الصفحة.';
            echo json_encode(['success' => false, 'message' => $msg, 'error' => $msg], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            $pdo = Database::getConnection();
        } catch (Throwable $e) {
            error_log('Settings DB Connection Error: ' . $e->getMessage());
            http_response_code(500);
            $msg = 'تعذر الاتصال بقاعدة البيانات.';
            echo json_encode(['success' => false, 'message' => $msg, 'error' => $msg], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $rootPath = realpath(__DIR__ . '/../../../');
        $uploadDir = $rootPath . '/public/assets/uploads/';
        $imageUploader = new ImageUploader($uploadDir);

        try {
            $action = trim((string)($_POST['action'] ?? ''));
            if ($action === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing action parameter.', 'error' => 'Missing action parameter.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("
                INSERT INTO global_settings (setting_key, setting_value) 
                VALUES (:k, :v) 
                ON DUPLICATE KEY UPDATE setting_value = :v_update
            ");

            $currentSettingsStmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
            $currentSettings = $currentSettingsStmt->fetchAll(\PDO::FETCH_KEY_PAIR) ?: [];

            // خريطة الخدمات (Service Registry)
            $services = [
                new HeaderSettingsService($rootPath, $imageUploader),
                new PageContentSettingsService($rootPath, $imageUploader),
                new AboutSettingsService($rootPath, $imageUploader),
                new EduSettingsService($rootPath, $imageUploader),
                new JobSettingsService($rootPath, $imageUploader),
                new ContactSettingsService($rootPath, $imageUploader),
                new GuideBlog1SettingsService($rootPath, $imageUploader),
                new HomeSettingsService($rootPath, $imageUploader),
                new FooterSettingsService($rootPath, $imageUploader),
            ];

            $handled = false;
            foreach ($services as $service) {
                if ($service->handleAction($action, $pdo, $currentSettings)) {
                    $handled = true;
                    break;
                }
            }

            if ($handled) {
                $pdo->commit();
                echo json_encode([
                    'success' => true,
                    'message' => 'تم حفظ التغييرات وتحديث البيانات والصور بنجاح.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // معالجة الإجراءات العامة (اللغات والإعلانات)
            if ($action === 'update_languages') {
                $rawLangs = $_POST['lang'] ?? [];
                $cleanLangs = [];

                if (is_array($rawLangs)) {
                    foreach ($rawLangs as $item) {
                        if (!is_array($item)) continue;
                        $code = strtolower(preg_replace('/[^a-zA-Z_-]/', '', trim((string)($item['code'] ?? ''))));
                        $name = trim((string)($item['name'] ?? ''));
                        if ($code !== '' && $name !== '') {
                            $cleanLangs[] = [
                                'code'   => $code,
                                'name'   => $name,
                                'active' => !empty($item['active']) && (string)$item['active'] === '1'
                            ];
                        }
                    }
                }

                $jsonVal = json_encode($cleanLangs, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'languages', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            } elseif ($action === 'update_announcement') {
                $requestedLang = (string)($_POST['announcement_lang'] ?? $_SESSION['site_lang'] ?? 'de');
                $targetLang = in_array($requestedLang, self::SUPPORTED_LANGS, true) ? $requestedLang : 'de';

                $rawAnnouncementSetting = $currentSettings['announcement'] ?? '';
                $announcements = is_string($rawAnnouncementSetting) ? json_decode($rawAnnouncementSetting, true) : [];
                if (!is_array($announcements)) {
                    $announcements = [];
                }

                // ترحيل البيانات القديمة غير المقسمة حسب اللغة إن وجدت
                if (isset($announcements['status']) && !isset($announcements['de']) && !isset($announcements['ar'])) {
                    $legacyData = $announcements;
                    $announcements = [$targetLang => $legacyData];
                }

                $oldAdImage = (string)($announcements[$targetLang]['image_path'] ?? ($_POST['old_ad_image'] ?? 'assets/img/default-ad.png'));
                
                if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
                    if (str_starts_with($oldAdImage, 'assets/uploads/')) {
                        $this->deleteOldImageFile($rootPath, $oldAdImage);
                    }

                    $filename = $imageUploader->processAndUploadFile($_FILES['ad_image']['tmp_name']);
                    $adImage = 'assets/uploads/' . $filename;
                } else {
                    $adImage = $oldAdImage;
                }

                $status   = in_array($_POST['status'] ?? '', ['Draft', 'Published'], true) ? $_POST['status'] : 'Draft';
                $type     = in_array($_POST['type'] ?? '', ['text', 'image'], true) ? $_POST['type'] : 'text';
                $bgColor  = preg_match('/^#[0-9a-fA-F]{6}$/', (string)($_POST['bg_color'] ?? '')) ? $_POST['bg_color'] : '#f1f5f9';
                $txtColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string)($_POST['text_color'] ?? '')) ? $_POST['text_color'] : '#1e293b';
                $fontSize = max(10, min(48, (int)($_POST['font_size'] ?? 16)));

                $rawLink = trim((string)($_POST['link'] ?? ''));
                $safeLink = ($rawLink !== '' && !preg_match('/^(javascript|data|vbscript):/i', $rawLink)) ? $rawLink : '';

                $adData = [
                    'status'            => $status,
                    'start_date'        => trim((string)($_POST['start_date'] ?? '')),
                    'end_date'          => trim((string)($_POST['end_date'] ?? '')),
                    'type'              => $type,
                    'announcement_text' => trim((string)($_POST['announcement_text'] ?? '')),
                    'bg_color'          => $bgColor,
                    'text_color'        => $txtColor,
                    'font_size'         => (string)$fontSize,
                    'link'              => $safeLink,
                    'image_path'        => $adImage
                ];

                $announcements[$targetLang] = $adData;
                $jsonVal = json_encode($announcements, JSON_UNESCAPED_UNICODE);
                $stmt->execute(['k' => 'announcement', 'v' => $jsonVal, 'v_update' => $jsonVal]);
            } else {
                $pdo->rollBack();
                http_response_code(400);
                $msg = 'الإجراء المطلوب غير معروف (Action: ' . htmlspecialchars($action, ENT_QUOTES, 'UTF-8') . ')';
                echo json_encode(['success' => false, 'message' => $msg, 'error' => $msg], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ التغييرات وتحديث الصور بصيغة WebP بنجاح.'
            ], JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Settings Save Error: ' . $e->getMessage());
            http_response_code(500);
            $isProd = (($_ENV['APP_ENV'] ?? 'production') === 'production');
            $msg = $isProd ? 'حدث خطأ داخلي أثناء حفظ الإعدادات.' : ('حدث خطأ في السيرفر: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $msg, 'error' => $msg], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    private function deleteOldImageFile(string $rootPath, string $imagePath): void
    {
        if ($imagePath === '' || !str_starts_with($imagePath, 'assets/uploads/')) {
            return;
        }

        $uploadsBase = realpath($rootPath . '/public/assets/uploads');
        $targetFile  = realpath($rootPath . '/public/' . $imagePath);

        if ($uploadsBase !== false && $targetFile !== false && str_starts_with($targetFile, $uploadsBase . DIRECTORY_SEPARATOR) && is_file($targetFile)) {
            @unlink($targetFile);
        }
    }

    private function checkAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isLoggedIn = !empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
        $role       = $_SESSION['role'] ?? '';
        $hasRole    = in_array($role, ['admin', 'super_admin'], true);

        $currentAgentHash = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '');
        $lastActivity     = (int)($_SESSION['LAST_ACTIVITY'] ?? time());
        $sessionValid     = (empty($_SESSION['user_agent_hash']) || hash_equals($_SESSION['user_agent_hash'], $currentAgentHash))
                            && (time() - $lastActivity <= self::SESSION_IDLE_TIMEOUT);

        if (!$isLoggedIn || !$hasRole || !$sessionValid) {
            if ($this->isJsonRequest()) {
                http_response_code(401);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول أو انتهت الجلسة.', 'error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            header('Location: /admin/login');
            exit;
        }

        $_SESSION['LAST_ACTIVITY'] = time();
    }

    private function isJsonRequest(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
               || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }
}
