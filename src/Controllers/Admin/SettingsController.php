<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use PDO;
use Exception;

class SettingsController {

    private ?PDO $pdo = null;

    public function __construct() {
        try {
            $this->pdo = Database::getConnection();
        } catch (Exception $e) {
            $this->handleError("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage(), 500);
        }
    }

    /**
     * التحقق من صلاحيات المدير وإرجاع استجابة JSON إذا كان الطلب AJAX
     */
    private function checkAdminAuth(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAdmin = isset($_SESSION['is_logged_in']) && 
                   $_SESSION['is_logged_in'] === true && 
                   isset($_SESSION['role']) && 
                   ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin');

        if (!$isAdmin) {
            if (ob_get_length()) { ob_clean(); }

            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'message' => 'انتهت الجلسة أو ليس لديك الصلاحية، يرجى إعادة تسجيل الدخول.'
                ]);
                exit;
            } else {
                header("Location: /admin/login?error=" . urlencode('يرجى تسجيل الدخول للوصول إلى لوحة التحكم.'));
                exit;
            }
        }
    }

    /**
     * عرض صفحة الإعدادات وجلب البيانات من قاعدة البيانات
     */
    public function index(): void {
        $this->checkAdminAuth();

        if (!defined('ALLOWED_ACCESS')) {
            define('ALLOWED_ACCESS', true);
        }

        try {
            $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $settingsData = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            $csrf_token = $_SESSION['csrf_token'];

            $root_path = realpath(__DIR__ . '/../../../');
            $view_file = $root_path . '/src/Views/admin/settings.php';

            if (file_exists($view_file)) {
                require_once $view_file;
            } else {
                http_response_code(404);
                echo "Settings View file not found.";
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo "حدث خطأ أثناء تحميل صفحة الإعدادات: " . $e->getMessage();
        }
    }

    /**
     * معالجة حفظ وتحديث الإعدادات بناءً على الـ Action المرسل
     */
    public function save(): void {
        $this->checkAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->handleError('طريقة الطلب غير مسموح بها.', 405);
        }

        $csrfToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (empty($csrfToken) || !hash_equals($_SESSION['csrf_token'] ?? '', $csrfToken)) {
            $this->handleError('رمز الحماية (CSRF Token) غير صالح.', 403);
        }

        $action = $_POST['action'] ?? 'general';

        try {
            $this->pdo->beginTransaction();

            $root_path = realpath(__DIR__ . '/../../../');
            $uploadDir = $root_path . '/public/assets/uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $stmtAll = $this->pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $currentSettings = $stmtAll->fetchAll(PDO::FETCH_KEY_PAIR);

            $stmt = $this->pdo->prepare("
                INSERT INTO site_settings (setting_key, setting_value) 
                VALUES (:k, :v) 
                ON DUPLICATE KEY UPDATE setting_value = :v_update
            ");

            if ($action === 'update_logo') {
                if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] === UPLOAD_ERR_OK) {
                    $oldLogo = $currentSettings['site_logo_path'] ?? '';
                    $this->deleteOldImageFile($root_path, $oldLogo);

                    $filename = 'logo_' . time() . '.webp';
                    $destination = $uploadDir . $filename;
                    
                    if ($this->convertToWebpAndSave($_FILES['logo_img']['tmp_name'], $destination)) {
                        $logoPath = 'assets/uploads/' . $filename;
                        $stmt->execute(['k' => 'site_logo_path', 'v' => $logoPath, 'v_update' => $logoPath]);
                    } else {
                        throw new Exception('فشل معالجة وتحويل صورة الشعار.');
                    }
                } else {
                    throw new Exception('يرجى اختيار صورة صحيحة للشعار.');
                }
            } elseif ($action === 'general' || isset($_POST['settings'])) {
                if (isset($_POST['settings']) && is_array($_POST['settings'])) {
                    foreach ($_POST['settings'] as $key => $value) {
                        $trimmedKey = trim($key);
                        $trimmedVal = trim((string)$value);
                        $stmt->execute([
                            'k' => $trimmedKey, 
                            'v' => $trimmedVal, 
                            'v_update' => $trimmedVal
                        ]);
                    }
                }
            }

            $this->pdo->commit();

            if (ob_get_length()) {
                ob_clean();
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'message' => 'تم حفظ التحديثات بنجاح!'
            ]);
            exit;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $this->handleError('حدث خطأ أثناء الحفظ: ' . $e->getMessage(), 500);
        }
    }

    private function convertToWebpAndSave(string $tmpPath, string $destination): bool {
        $fileType = mime_content_type($tmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($fileType, $allowedTypes)) {
            return false;
        }

        $image = match ($fileType) {
            'image/jpeg' => imagecreatefromjpeg($tmpPath),
            'image/png'  => imagecreatefrompng($tmpPath),
            'image/webp' => imagecreatefromwebp($tmpPath),
            default      => null,
        };

        if (!$image) {
            return false;
        }

        if ($fileType === 'image/png' || $fileType === 'image/webp') {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        $success = imagewebp($image, $destination, 80);
        imagedestroy($image);

        return $success;
    }

    private function deleteOldImageFile(string $rootPath, string $oldPath): void {
        if (!empty($oldPath)) {
            $fullPath = $rootPath . '/public/' . ltrim($oldPath, '/');
            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    private function handleError(string $message, int $statusCode = 400): void {
        if (ob_get_length()) {
            ob_clean();
        }
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
        exit;
    }
}
