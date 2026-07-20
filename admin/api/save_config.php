<?php
// /admin/api/save_config.php

// 1. تحميل الـ Autoloader الخاص بالمشروع
require_once __DIR__ . '/../../vendor/autoload.php';

// 2. استخدام ملف الحماية المركزي الموحد (الذي يتحقق من جلسة المدير ويرفض الطلب بـ JSON عند الحاجة)
require_once __DIR__ . '/../admin_init.php';

use App\Controllers\ConfigController;
use App\Services\AuditLoggerService;

header('Content-Type: application/json; charset=UTF-8');

$logger = new AuditLoggerService();

try {
    $action = $_POST['action'] ?? '';
    $controller = new ConfigController();

    // 3. توجيه الطلب وتنفيذ العملية عبر الـ Controller
    switch ($action) {
        case 'update_logo':
            if (!isset($_FILES['logo_img'])) {
                throw new Exception("لم يتم العثور على ملف الصورة المرفوع");
            }
            // استدعاء دالة المعالجة من الـ Controller
            $message = $controller->updateLogo($_FILES['logo_img']);
            break;

        // يمكنكِ إضافة باقي الحالات هنا (update_social, update_menu, إلخ...) بنفس النمط المستقبلي
        
        default:
            throw new Exception("الإجراء غير صالح أو مفقود");
    }

    // 4. التوثيق في سجل العمليات (Audit Log)
    $logger->log($action, "تم تنفيذ عملية: " . $action . " بنجاح بواسطة المسؤول");
    
    // 5. إرجاع الاستجابة الناجحة بصيغة JSON
    echo json_encode(['success' => true, 'message' => $message]);

} catch (\Exception $e) {
    // التقاط أي استثناء وإرجاعه كرسالة خطأ بصيغة JSON
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
