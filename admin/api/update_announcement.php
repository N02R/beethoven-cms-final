<?php
// 1. بدء الجلسة بأمان للتحقق من الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. ترويسة الاستجابة كـ JSON
header('Content-Type: application/json; charset=utf-8');

// 3. جدار الحماية الصارم: التحقق من أن المستخدم أدمن فعلياً
$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مصرح لك بإجراء هذه العملية.']);
    exit;
}

// 4. التحقق من طريقة الطلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'طريقة الطلب غير مدعومة.']);
    exit;
}

// 5. قراءة ملف الـ JSON الحالي للحفاظ على البيانات الأخرى (مثل مسار اللوجو وروابط القائمة)
$config_file_path = __DIR__ . '/../../announcement_config.json';
$config_data = [];

if (file_exists($config_file_path)) {
    $config_data = json_decode(file_get_contents($config_file_path), true);
    if (!is_array($config_data)) {
        $config_data = [];
    }
}

// 6. استلام وتنظيف البيانات النصية المدخلة من الفورم (Sanitization)
$config_data['status']            = isset($_POST['status']) ? htmlspecialchars(strip_tags($_POST['status'])) : 'Draft';
$config_data['type']              = isset($_POST['type']) ? htmlspecialchars(strip_tags($_POST['type'])) : 'text';
$config_data['announcement_text'] = isset($_POST['announcement_text']) ? htmlspecialchars(strip_tags($_POST['announcement_text'])) : '';
$config_data['bg_color']          = isset($_POST['bg_color']) ? htmlspecialchars(strip_tags($_POST['bg_color'])) : '#000000';
$config_data['text_color']        = isset($_POST['text_color']) ? htmlspecialchars(strip_tags($_POST['text_color'])) : '#ffffff';
$config_data['font_size']         = isset($_POST['font_size']) ? (int)$_POST['font_size'] : 16;
$config_data['link']              = isset($_POST['link']) ? filter_var($_POST['link'], FILTER_SANITIZE_URL) : '';
$config_data['open_new_tab']      = isset($_POST['open_new_tab']) ? (int)$_POST['open_new_tab'] : 0;
$config_data['start_date']        = isset($_POST['start_date']) ? htmlspecialchars(strip_tags($_POST['start_date'])) : '';
$config_data['end_date']          = isset($_POST['end_date']) ? htmlspecialchars(strip_tags($_POST['end_date'])) : '';
$config_data['alt_text']          = isset($_POST['alt_text']) ? htmlspecialchars(strip_tags($_POST['alt_text'])) : 'إعلان';

// 7. معالجة رفع صورة الإعلان في حال كان النوع "image" وتم اختيار ملف
if ($config_data['type'] === 'image' && isset($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] === UPLOAD_ERR_OK) {
    
    $file = $_FILES['announcement_image'];
    $allowed_extensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // فحص الامتداد
    if (in_array($file_extension, $allowed_extensions)) {
        // فحص الـ Mime Type الحقيقي للحماية
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mimes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'];
        if (in_array($mime_type, $allowed_mimes)) {
            
            $upload_dir = __DIR__ . '/../../assets/img/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // توليد اسم فريد للبنر الإعلاني
            $new_banner_name = 'ad_banner_' . time() . '.' . $file_extension;
            $destination = $upload_dir . $new_banner_name;
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $config_data['image_path'] = 'assets/img/' . $new_banner_name;
            }
        }
    }
}

// 8. كتابة كافة البيانات المحدثة داخل ملف الـ JSON بأمان
if (file_put_contents($config_file_path, json_encode($config_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم تحديث إعدادات الإعلان بنجاح!']);
} else {
    echo json_encode(['success' => false, 'error' => 'فشل حفظ التعديلات داخل ملف الـ JSON.']);
}
