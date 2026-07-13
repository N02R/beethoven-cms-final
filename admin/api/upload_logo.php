<?php
// 1. بدء الجلسة بأمان للتحقق من الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ترويسة الاستجابة كـ JSON (معيار الـ APIs الاحترافي)
header('Content-Type: application/json; charset=utf-8');

// 3. جدار الحماية الصارم: التحقق من أن المستخدم أدمن فعلياً
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مصرح لك بإجراء هذه العملية.']);
    exit;
}

// 4. التحقق من وصول الملف عبر طلب POST
if ($_SERVER['REQUEST_LIMIT'] ?? $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'طريقة الطلب غير مدعومة.']);
    exit;
}

if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'لم يتم استقبال أي ملف أو حدث خطأ أثناء الرفع.']);
    exit;
}

$file = $_FILES['logo'];

// 5. فحص أمان الملف (الجودة الألمانية ضد الاختراق)
// أ - التحقق من الامتدادات المسموحة فقط لشعار الموقع
$allowed_extensions = ['png', 'jpg', 'jpeg', 'svg', 'webp'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    echo json_encode(['success' => false, 'error' => 'امتداد الملف غير مسموح به. المسموح: PNG, JPG, JPEG, SVG, WEBP']);
    exit;
}

// ب - التحقق من نوع الميم الفعلي للملف (Mime Type) لضمان عدم تزوير الامتداد
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowed_mimes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/webp'];
if (!in_array($mime_type, $allowed_mimes)) {
    echo json_encode(['success' => false, 'error' => 'نوع الملف الفعلي غير صالح كصورة.']);
    exit;
}

// ج - تحديد الحجم الأقصى (مثلاً 2 ميجابايت كحد أقصى للوجو)
$max_size = 2 * 1024 * 1024; 
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'error' => 'حجم الصورة كبير جداً. الحد الأقصى هو 2 ميجابايت.']);
    exit;
}

// 6. نقل الملف إلى مجلد المرفقات وتوليد اسم فريد لمنع تداخل الملفات الكاش
$upload_dir = __DIR__ . '/../../assets/img/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// توليد اسم فريد يعتمد على الوقت لمنع مشاكل الـ Caching في المتصفحات
$new_file_name = 'logo_' . time() . '.' . $file_extension;
$destination = $upload_dir . $new_file_name;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    
    // 7. تحديث ملف الـ JSON بالمسار الجديد للشعار
    $config_file_path = __DIR__ . '/../../announcement_config.json';
    $config_data = [];

    if (file_exists($config_file_path)) {
        $config_data = json_decode(file_get_contents($config_file_path), true);
        if (!is_array($config_data)) {
            $config_data = [];
        }
    }

    // حفظ المسار النسبي الذي سيقرأه الهيدر
    $config_data['site_logo_path'] = 'assets/img/' . $new_file_name;

    // كتابة التعديلات وحفظ الملف بأمان
    if (file_put_contents($config_file_path, json_encode($config_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true, 'message' => 'تم تحديث الشعار بنجاح!']);
    } else {
        echo json_encode(['success' => false, 'error' => 'تم رفع الصورة ولكن فشل تحديث ملف الإعدادات JSON.']);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'فشل نقل الملف المرفوع إلى مجلد الصور بالسيرفر.']);
}
