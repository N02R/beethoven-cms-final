<?php
// admin/api/save_config.php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Services\ConfigService;
use App\Services\UploadService;
use App\Services\AuditLoggerService; // التحديث الأخير: إضافة سجل العمليات

// استخدام الـ SessionManager الذي أنشأناه بدلاً من session_start() التقليدي
use App\Core\SessionManager;
SessionManager::startSecureSession();

header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

$configService = new ConfigService();
$uploader = new UploadService();
$logger = new AuditLoggerService(); // التحديث الأخير: تهيئة خدمة السجل

try {
    $data = $configService->getData();
    $action = $_POST['action'] ?? '';

    // 2. معالجة العمليات (تكملة الـ switch)
    switch ($action) {
        case 'update_logo':
            $fileName = $uploader->upload($_FILES['logo_img'], __DIR__ . '/../../public/uploads');
            $data['site_logo_path'] = 'uploads/' . $fileName;
            break;

        // يمكنكِ إضافة باقي الحالات هنا (update_social, update_menu, إلخ...)
        // بنفس نمط الـ Uploads الذي اعتمدناه
        
        default:
            throw new Exception("الإجراء غير صالح");
    }

    // 3. الحفظ باستخدام الخدمة (التي تحتوي على LOCK_EX)
    $configService->saveData($data);
    
    // 4. التوثيق في سجل العمليات (التحديث الأخير)
    $logger->log($action, "تم تنفيذ عملية: " . $action . " بنجاح بواسطة المسؤول");
    
    echo json_encode(['success' => true, 'message' => 'تم الحفظ وتوثيق العملية بنجاح']);

} catch (\Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
