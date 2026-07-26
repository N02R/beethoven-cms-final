<?php
declare(strict_types=1);

define('ALLOWED_ACCESS', true);
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/ImageUploader.php';

use App\Services\ImageUploader;

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
        exit;
    }

    verify_csrf_token($_POST['csrf_token'] ?? '');

    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_general_settings':
            $siteTitle = trim($_POST['site_title'] ?? '');
            $siteEmail = trim($_POST['site_email'] ?? '');

            // 1. معالجة رفع شعار الموقع الجديد إذا وجد
            $logoFilename = null;
            if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';
                $uploader = new ImageUploader($uploadDir);
                // الدالة upload تقوم بالتحقق، التحويل إلى WebP، الحفظ في جدول uploaded_images وإرجاع اسم الملف
                $logoFilename = $uploader->upload($_FILES['site_logo'], $pdo);
            }

            // 2. تحديث قاعدة البيانات (بدلاً من ملفات JSON)
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v");
            
            $stmt->execute(['k' => 'site_title', 'v' => $siteTitle]);
            $stmt->execute(['k' => 'site_email', 'v' => $siteEmail]);

            if ($logoFilename) {
                // إذا تم رفع شعار جديد، نقوم بتخزين اسمه في الإعدادات
                $stmt->execute(['k' => 'site_logo', 'v' => $logoFilename]);
            }

            $pdo->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Settings updated successfully to database.',
                'data' => ['logo' => $logoFilename]
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid action specified.']);
            break;
    }

} catch (InvalidArgumentException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (\Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("Config Save Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Internal server error.']);
}
