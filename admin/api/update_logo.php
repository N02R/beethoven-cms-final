<?php
// 1. بدء الجلسة بأمان
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ترويسة الاستجابة كـ JSON
header('Content-Type: application/json; charset=utf-8');

// 3. التحقق من صلاحية الأدمن
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مصرح لك بإجراء هذه العملية.']);
    exit;
}

// 4. التحقق من وصول الملف عبر طلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'طريقة الطلب غير مدعومة.']);
    exit;
}

// 5. استقبال الملف (تم التعديل هنا ليتوافق مع اسم الحقل في المودل: logo_img)
if (!isset($_FILES['logo_img']) || $_FILES['logo_img']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'لم يتم استقبال الملف أو حدث خطأ أثناء الرفع.']);
    exit;
}

$file = $_FILES['logo_img'];

// 6. فحص أمان الملف
$allowed_extensions = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    echo json_encode(['success' => false, 'error' => 'امتداد الملف غير مسموح به.']);
    exit;
}

// التحقق من حجم الملف (2 ميجابايت)
if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'حجم الصورة كبير جداً.']);
    exit;
}

// 7. نقل الملف إلى المجلد
$upload_dir = __DIR__ . '/../../assets/img/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$new_file_name = 'logo_' . time() . '.' . $file_extension;
$destination = $upload_dir . $new_file_name;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    
    // 8. تحديث ملف الإعدادات JSON
    $config_file_path = __DIR__ . '/../../announcement_config.json';
    $config_data = file_exists($config_file_path) ? json_decode(file_get_contents($config_file_path), true) : [];

    // حفظ المسار النسبي
    $config_data['site_logo_path'] = 'assets/img/' . $new_file_name;

    if (file_put_contents($config_file_path, json_encode($config_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true, 'message' => 'تم تحديث الشعار بنجاح!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'تم رفع الصورة ولكن فشل تحديث ملف الإعدادات.']);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'فشل نقل الملف إلى السيرفر.']);
}
?>
