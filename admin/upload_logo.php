<?php
// backend/upload_logo.php
header('Content-Type: application/json; charset=utf-8');

// تفعيل الجلسة للتحقق من الأمان لاحقاً
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// مصفوفة الرد الافتراضية
$response = ['success' => false, 'error' => 'حدث خطأ غير متوقع.'];

// تأكدي من أن طلب الرفع جاء عبر طريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'طريقة طلب غير صالحة.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// التحقق من وجود الملف المرفوع وبدون أخطاء رفع
if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
    $response['error'] = 'ولم يتم استلام أي ملف أو حدث خطأ أثناء الرفع.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$file = $_FILES['logo'];

// 1. فحص الحجم المسموح (مثلاً بحد أقصى 2 ميجابايت)
$max_size = 2 * 1024 * 1024;
if ($file['size'] > $max_size) {
    $response['error'] = 'حجم الملف كبير جداً، الحد الأقصى المسموح به هو 2 ميجابايت.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. فحص نوع الملف (MIME Type) والامتداد للأمان الحقيقي
$allowed_types = [
    'image/png'  => 'png',
    'image/jpeg' => 'jpg',
    'image/jpg'  => 'jpg',
    'image/gif'  => 'gif',
    'image/svg+xml' => 'svg'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!array_key_exists($mime_type, $allowed_types)) {
    $response['error'] = 'نوع الملف غير مدعوم. يسمح فقط بصور من نوع PNG, JPG, GIF, SVG.';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. توليد اسم عشوائي فريد لمنع تكرار الأسماء وتخريب الملفات القديمة
$extension = $allowed_types[$mime_type];
$new_filename = 'logo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

// تحديد المجلد الفعلي لحفظ الصور (تأكدي من وجود مجلد assets/img وصلاحيات الكتابة له)
$upload_dir = dirname(__DIR__) . '/assets/img/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$destination = $upload_dir . $new_filename;

// 4. نقل الملف إلى المجلد الفعلي وتحديث ملف الإعدادات
if (move_uploaded_file($file['tmp_name'], $destination)) {
    // المسار النسبي الذي سيخزن لتستخدمه المتصفحات
    $db_saved_path = 'assets/img/' . $new_filename;

    // تحديد مسار ملف التكوين JSON في جذر المشروع
    $config_file = dirname(__DIR__) . '/announcement_config.json';
    
    $config_data = [];
    if (file_exists($config_file)) {
        $config_data = json_decode(file_get_contents($config_file), true);
        if (!is_array($config_data)) {
            $config_data = [];
        }
    }
    
    // إضافة أو تحديث مسار اللوجو مع بقاء بيانات الإعلان الحالية كاملة
    $config_data['site_logo_path'] = $db_saved_path;
    
    // حفظ التحديثات في ملف الـ JSON بشكل منسق وعربي سليم
    file_put_contents($config_file, json_encode($config_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    $response = [
        'success' => true,
        'path' => $db_saved_path
    ];
} else {
    $response['error'] = 'فشل نقل الملف المرفوع إلى المجلد المخصص.';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
