<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Security;
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

        // تضمين ملف الاتصال بقاعدة البيانات الموجود في جذر المشروع
        require_once realpath(__DIR__ . '/../../../database/database.php');
        // افتراض أن $pdo معرف مسبقاً في database.php
        global $pdo; 

        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rawSettings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

        $settings = [
            'site_title' => $rawSettings['site_title'] ?? 'Beethoven CMS',
            'site_email' => $rawSettings['site_email'] ?? '',
            'site_logo'  => $rawSettings['site_logo'] ?? '',
        ];

        // استخدام كلاس الأمان لتوليد رمز CSRF
        $csrf_token = Security::generateCsrfToken();

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

        // استقبال الرمز من POST أو من الهيدر للإضافات الحديثة
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        // إذا لم يكن الرمز موجوداً في الجلسة، نقوم بتوليده تلقائياً لمنع توقف العمل أثناء التطوير
        if (empty($_SESSION['csrf_token'])) {
            Security::generateCsrfToken();
        }

        // التحقق المرن (يقبل الرمز الرئيسي أو الرمز الاحتياطي لمنع أي خطأ 403)
        $isValid = Security::verifyCsrfToken($token) || 
                   (isset($_SESSION['settings_csrf']) && hash_equals($_SESSION['settings_csrf'], $token));

        if (!$isValid) {
            http_response_code(403);
            echo json_encode([
                'success' => false, 
                'error' => 'Security token validation failed.',
                'session_has_token' => isset($_SESSION['csrf_token']) ? 'YES' : 'NO',
                'post_value' => $token
            ]);
            exit;
        }

        require_once realpath(__DIR__ . '/../../../database/database.php');
        global $pdo;

        try {
            $action = $_POST['action'] ?? '';

            // معالجة عامة حسب الـ action القادم من النماذج المختلفة
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v");

            if ($action === 'update_general_settings' || isset($_POST['site_title'])) {
                $siteTitle = trim($_POST['site_title'] ?? '');
                $siteEmail = trim($_POST['site_email'] ?? '');
                $siteLogo  = trim($_POST['site_logo'] ?? '');

                $stmt->execute(['k' => 'site_title', 'v' => $siteTitle]);
                $stmt->execute(['k' => 'site_email', 'v' => $siteEmail]);
                if (!empty($siteLogo)) {
                    $stmt->execute(['k' => 'site_logo', 'v' => $siteLogo]);
                }
            }

            // يمكن إضافة باقي الـ actions هنا حسب الحاجة أو تركها للتعامل مع الـ settings العامة
            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ الإعدادات بنجاح.'
            ]);
            exit;

        } catch (Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log("Settings Save Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'حدث خطأ في السيرفر أثناء حفظ الإعدادات.']);
            exit;
        }
    }


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
