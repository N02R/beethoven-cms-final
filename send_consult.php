<?php
declare(strict_types=1);

// بدء الجلسة وتضمين ملفات الإعدادات وقاعدة البيانات والـ Security
require_once __DIR__ . '/src/Core/helpers.php';
require_once __DIR__ . '/src/Config/Database.php';
require_once __DIR__ . '/src/Core/Security.php';

// ضبط رأس الصفحة ليعيد بيانات JSON حصراً
header('Content-Type: application/json; charset=UTF-8');

// التأكد من أن الطلب تم عبر طريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// استلام البريد الإلكتروني وتطهيره
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$privacy_consent =$_POST['privacy_consent'] ?? '';

// 1. التحقق من صحة البريد الإلكتروني
if (empty($email) \vert{}\vert{} !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني المدخل غير صالح.']);
    exit;
}

// 2. التحقق من موافقة المستخدم على سياسة الخصوصية (DSGVO Requirement)
if ($privacy_consent !== 'on' && $privacy_consent !== '1' &&$privacy_consent !== true) {
    echo json_encode(['success' => false, 'message' => 'يجب الموافقة على شروط سياسة الخصوصية للمتابعة.']);
    exit;
}

try {
    // 3. الاتصال بقاعدة البيانات وحفظ الطلب بأمان تام باستخدام PDO Prepared Statements
    $db = \App\Config\Database::getInstance();
    
    $stmt =$db->prepare("INSERT INTO `consultations` (`email`, `privacy_consent`, `status`, `created_at`) VALUES (?, 1, 'Pending', NOW())");
    $stmt->execute([$email]);

    // (اختياري احترافي): يمكنك هنا أيضاً إضافة دالة إرسال بريد إلكتروني لإشعار إدارة الشركة (mail() أو PHPMailer)

    echo json_encode([
        'success' => true, 
        'message' => 'تم إرسال طلبك بنجاح. سيقوم فريقنا بالتواصل معك قريباً!'
    ]);

} catch (\Exception $e) {
    // تسجيل الخطأ داخلياً وعدم إظهار تفاصيل القاعدة الحساسة للمستخدم (أمان إنتاجي)
    error_log("Consultation Error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false, 
        'message' => 'حدث خطأ في النظام أثناء حفظ البيانات. يرجى المحاولة لاحقاً.'
    ]);
}
