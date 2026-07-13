<?php
// إرجاع النتيجة دائماً كـ JSON ليتعامل معها طلب الـ AJAX
header('Content-Type: application/json; charset=UTF-8');

// بدء الجلسة للتحقق من الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. التحقق من الصلاحيات (Authorization Check)
// ملاحظة: قمنا بمحاكاتها مؤقتاً لتسهيل التجربة، في النظام الحقيقي استبدليها بـ تفقد السيشن
$is_admin = true; 
if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مصرح لك بالوصول لهذا الإجراء!']);
    exit;
}

// التحقق من أن الطلب يحتوي على ملف مرفوع بالفعل
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['new_logo'])) {
    echo json_encode(['success' => false, 'error' => 'طلب غير صالح أو لم يتم رفع ملف.']);
    exit;
}

$file = $_FILES['new_logo'];

// التحقق من عدم وجود أخطاء في الرفع البنيوي من المتصفح
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'حدث خطأ أثناء نقل الملف مؤقتاً على السيرفر.']);
    exit;
}

// 2. التحقق من الحجم (File Size Check) - الحد الأقصى 2 ميجابايت
$max_size = 2 * 1024 * 1024; 
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'error' => 'حجم الملف ضخم جداً! الحد الأقصى المسموح به هو 2 ميجابايت.']);
    exit;
}

// 3. التحقق الصارم جداً من نوع الملف (MIME-Type) باستخدام فحص محتوى البايتات الحقيقي
$allowed_types = [
    'image/png'  => 'png',
    'image/jpeg' => 'jpg',
    'image/jpg'  => 'jpg'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!array_key_exists($mime_type, $allowed_types)) {
    echo json_encode(['success' => false, 'error' => 'نوع الملف غير مدعوم! يسمح فقط برفع صور حقيقية من نوع PNG أو JPG.']);
    exit;
}

// 4. إعادة تسمية الملف بأمان تام وحمايته (Secure Renaming)
$extension = $allowed_types[$mime_type];
$new_filename = 'logo_' . md5(uniqid(rand(), true)) . '.' . $extension;

// 5. نقل وحفظ الملف في مكان آمن (Secure Storage)
$upload_dir = '../assets/img/';
$destination = $upload_dir . $new_filename;

// التأكد من وجود المجلد وصلاحيات الكتابة عليه
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (move_uploaded_file($file['tmp_name'], $destination)) {
    
    // المسار الجديد الذي سيتم اعتماده وحفظه في النظام والظاهر للمتصفح
    $db_saved_path = 'assets/img/' . $new_filename;

    // 6. إنشاء وبيان البيانات في قاعدة البيانات (Database Operations)
    // هنا سنقوم بمحاكاة الاتصال وتحديث جدول الإعدادات
    /*
    require_once '../includes/db_connect.php';
    $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'site_logo'");
    $stmt->execute([$db_saved_path]);
    */

    // 7. تسجيل العملية بالكامل في سجلات الأمان (Activity Log)
    $user_id = 1; // رقم المدير الحالي
    $action = "تم تحديث شعار الموقع بنجاح إلى: " . $new_filename;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    /*
    $log_stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $log_stmt->execute([$user_id, $action, $ip_address, $user_agent]);
    */

    // إرجاع رد النجاح النهائي للـ AJAX لتهيئة المتصفح لإعادة التحميل
    echo json_encode(['success' => true, 'path' => $db_saved_path]);
    exit;

} else {
    echo json_encode(['success' => false, 'error' => 'فشل نقل الملف إلى المجلد المستهدف، يرجى التحقق من صلاحيات السيرفر (Permissions).']);
    exit;
}
