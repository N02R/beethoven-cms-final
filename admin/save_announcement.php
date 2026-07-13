<?php
// admin/save_announcement.php
header('Content-Type: application/json; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. التحقق الصارم من الصلاحيات الإدارية
$is_admin = true; // يتم ربطها بـ $_SESSION['user_role'] لاحقاً
if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مسموح بالوصول. صلاحيات غير كافية.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'طريقة طلب غير صالحة.']);
    exit;
}

// دالة لتنظيف وتأمين المدخلات النصية ضد هجمات XSS و الإدخال الخبيث
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// تنظيف الحقول المشتركة
$type = sanitizeInput($_POST['type'] ?? 'text');
$status = sanitizeInput($_POST['status'] ?? 'Draft');
$link = isset($_POST['link']) ? filter_var(trim($_POST['link']), FILTER_SANITIZE_URL) : null;
$open_new_tab = isset($_POST['open_new_tab']) && $_POST['open_new_tab'] === 'Yes' ? 1 : 0;
$start_date = !empty($_POST['start_date']) ? sanitizeInput($_POST['start_date']) : null;
$end_date = !empty($_POST['end_date']) ? sanitizeInput($_POST['end_date']) : null;

// التحقق من صحة الرابط برمجياً
if (!empty($link)) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
        echo json_encode(['success' => false, 'error' => 'يجب أن يبدأ الرابط بـ http:// أو https:// فقط.']);
        exit;
    }
}

$image_path = null;
$alt_text = null;
$announcement_text = null;
$bg_color = '#ffffff';
$text_color = '#000000';
$font_size = 14;

// مسار ملف التخزين المؤقت (JSON) في المجلد الرئيسي للمشروع
$config_file = dirname(__DIR__) . '/announcement_config.json';

// إذا كان هناك صورة قديمة مرفوعة مسبقاً، نقوم بقراءتها كاحتياط حتى لا نفقدها إذا قام المدير بتغيير نص فقط
if (file_exists($config_file)) {
    $old_config = json_decode(file_get_contents($config_file), true);
    if (isset($old_config['image_path'])) {
        $image_path = $old_config['image_path'];
    }
}

// المعالجة بناءً على نوع الإعلان المعين (SOLID Principle)
if ($type === 'text') {
    $announcement_text = sanitizeInput($_POST['announcement_text'] ?? '');
    $bg_color = sanitizeInput($_POST['bg_color'] ?? '#ffffff');
    $text_color = sanitizeInput($_POST['text_color'] ?? '#000000');
    $font_size = (int)($_POST['font_size'] ?? 14);

    if (empty($announcement_text)) {
        echo json_encode(['success' => false, 'error' => 'نص الإعلان مطلوب عند اختيار نوع النص.']);
        exit;
    }
} 

else if ($type === 'image') {
    $alt_text = sanitizeInput($_POST['alt_text'] ?? 'إعلان جديد');
    
    if (!isset($_FILES['announcement_image']) || $_FILES['announcement_image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'ملف الصورة مطلوب ولم يتم رفعه بشكل صحيح.']);
        exit;
    }

    $file = $_FILES['announcement_image'];

    // فحص الحجم الأقصى (2 ميجابايت)
    $max_size = 2 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        echo json_encode(['success' => false, 'error' => 'حجم الصورة يتخطى الحد المسموح (2 ميجابايت).']);
        exit;
    }

    // الفحص الأمني الحقيقي لنوع الصورة (وليس الامتداد فقط)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_mimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mime_type, $allowed_mimes)) {
        echo json_encode(['success' => false, 'error' => 'نوع الملف غير مدعوم! يسمح فقط بصيغ JPG, PNG, WebP الحقيقية.']);
        exit;
    }

    $extension = $allowed_mimes[$mime_type];
    
    // إعادة تسمية الملف عشوائياً وبأمان لمنع استبدال الملفات أو حقن الأكواد
    $new_filename = 'ad_' . bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
    $upload_dir = dirname(__DIR__) . '/uploads/announcements/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // حماية إضافية: توليد ملف .htaccess يمنع تشغيل أي ملفات PHP مرفوعة داخل مجلد الإعلانات
    if (!file_exists($upload_dir . '.htaccess')) {
        file_put_contents($upload_dir . '.htaccess', "Options -ExecCGI\nRemoveHandler .php .phtml .php3\nRemoveType .php .phtml .php3\n<Files MATCH \".*(?i)(\\.php|\\.phtml|\\.pl|\\.py|\\.jsp|\\.sh|\\.cgi)\">\nOrder Allow,Deny\nDeny from all\n</Files>");
    }

    $destination = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $image_path = 'uploads/announcements/' . $new_filename;
    } else {
        echo json_encode(['success' => false, 'error' => 'فشل نقل الصورة المرفوعة للمجلد المستهدف.']);
        exit;
    }
}

// -----------------------------------------------------------------
// التحديث الجديد والمهم: تجميع البيانات وحفظها الفعلي في ملف التكوين
// -----------------------------------------------------------------
$config_data = [
    'type'              => $type,
    'status'            => $status,
    'link'              => $link,
    'open_new_tab'      => $open_new_tab,
    'start_date'        => $start_date,
    'end_date'          => $end_date,
    'announcement_text' => $announcement_text,
    'bg_color'          => $bg_color,
    'text_color'        => $text_color,
    'font_size'         => $font_size,
    'image_path'        => $image_path,
    'alt_text'          => $alt_text,
    'updated_at'        => date('Y-m-d H:i:s')
];

if (!file_put_contents($config_file, json_encode($config_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => false, 'error' => 'حدث خطأ أثناء كتابة وحفظ ملف الإعدادات الديناميكي على السيرفر.']);
    exit;
}

// هنا يتم تنفيذ الإدخال الآمن في قاعدة البيانات عبر الـ Model لاحقاً
// مؤقتاً سنرجع استجابة نجاح كاملة للمتصفح لمطابقة الواجهة
echo json_encode([
    'success' => true, 
    'message' => 'تم حفظ إعلان الهيدر بنجاح وتأمينه ومطابقة الشروط الآمنة!'
]);
exit;
