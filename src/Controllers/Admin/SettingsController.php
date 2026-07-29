<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Services\ImageUploader;
use Exception;
use InvalidArgumentException;
use PDO;

class SettingsController
{
    /**
     * عرض صفحة إعدادات لوحة التحكم
     */
    public function index(): void
    {
        $this->checkAdminAuth();

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $settings = [
            'site_title' => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email' => $rawSettings['site_email'] ?? '',
            'site_logo'  => $rawSettings['site_logo'] ?? '',
        ];

        // توليد رمز CSRF لحماية نموذج الإعدادات
        if (empty($_SESSION['settings_csrf'])) {
            $_SESSION['settings_csrf'] = bin2hex(random_bytes(32));
        }

        $csrf_token = $_SESSION['settings_csrf'];

        $root_path = realpath(__DIR__ . '/../../../');
        $view_file = $root_path . '/src/Views/admin/settings.php';

        if (file_exists($view_file)) {
            require_once $view_file;
        } else {
            echo "Settings View file not found.";
        }
    }

    /**
     * حفظ وتحديث الإعدادات (معالجة AJAX / Form POST)
     */
    public function save(): void
    {
        $this->checkAdminAuth();

        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
            exit;
        }

        // 1. التحقق من CSRF Token
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['settings_csrf'] ?? '', $token)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'رمز الحماية غير صالح (CSRF Invalid)']);
            exit;
        }

        $pdo = Database::getConnection();

        try {
            $siteTitle = trim($_POST['site_title'] ?? '');
            $siteEmail = trim($_POST['site_email'] ?? '');

            // 2. معالجة رفع الشعار باستخدام ImageUploader الموحد
            $logoFilename = null;
            if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploader = new ImageUploader();
                $logoFilename = $uploader->upload($_FILES['site_logo']);
            }

            // 3. حفظ الإعدادات في قاعدة البيانات
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v");

            $stmt->execute(['k' => 'site_title', 'v' => $siteTitle]);
            $stmt->execute(['k' => 'site_email', 'v' => $siteEmail]);

            if ($logoFilename) {
                $stmt->execute(['k' => 'site_logo', 'v' => $logoFilename]);
            }

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ الإعدادات بنجاح.',
                'data'    => ['logo' => $logoFilename]
            ]);
            exit;

        } catch (InvalidArgumentException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Settings Save Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'حدث خطأ في السيرفر أثناء حفظ الإعدادات.']);
            exit;
        }
    }

    /**
     * التحقق من جلسة الأدمن
     */
    private function checkAdminAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            if ($this->isJsonRequest()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'غير مصرح بالوصول']);
                exit;
            }
            header("Location: index.php?url=admin/login");
            exit;
        }
    }

    private function isJsonRequest(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }
}
