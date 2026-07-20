<?php
// save_data.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');

// 1. تأكيد صلاحية الأدمن باستخدام دالة متسقة أو فحص مباشر آمن
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'غير مصرح لك بالقيام بهذا الإجراء']);
    exit;
}

// 2. توحيد المسار الصحيح لملف الإعدادات (بناءً على مجلد الـ storage الذي اعتمدناه)
$config_file = __DIR__ . '/storage/announcement_config.json';

if (!file_exists($config_file)) {
    echo json_encode(['success' => false, 'message' => 'ملف الإعدادات غير موجود']);
    exit;
}

$jsonData = file_get_contents($config_file);
$data = json_decode($jsonData, true);

if (!is_array($data)) {
    $data = [];
}

$action = $_POST['action'] ?? '';
$success = false;

// 3. معالجة تحديث الإعلان
if ($action === 'update_announcement') {
    // التأكد من وجود مصفوفة الإعلان
    if (!isset($data['announcement']) || !is_array($data['announcement'])) {
        $data['announcement'] = [];
    }

    $data['announcement']['type'] = $_POST['type'] ?? 'text';
    $data['announcement']['status'] = $_POST['status'] ?? 'Draft';
    $data['announcement']['announcement_text'] = $_POST['announcement_text'] ?? '';
    $data['announcement']['bg_color'] = $_POST['bg_color'] ?? '#f1f5f9';
    $data['announcement']['text_color'] = $_POST['text_color'] ?? '#1e293b';
    $data['announcement']['font_size'] = $_POST['font_size'] ?? '16';
    $data['announcement']['link'] = $_POST['link'] ?? '';
    $data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? 1 : 0;
    $data['announcement']['start_date'] = $_POST['start_date'] ?? null;
    $data['announcement']['end_date'] = $_POST['end_date'] ?? null;

    // معالجة رفع صورة الإعلان وتوحيد المفتاح ليتطابق مع الـ header.php (image_path)
    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/public/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_extension = pathinfo($_FILES['ad_image']['name'], PATHINFO_EXTENSION);
        $safe_name = 'ad_' . time() . '.' . $file_extension;
        $destination = $upload_dir . $safe_name;
        
        if (move_uploaded_file($_FILES['ad_image']['tmp_name'], $destination)) {
            // توحيد اسم المفتاح ليصبح image_path تماماً كما يقرأه الهيدر
            $data['announcement']['image_path'] = 'uploads/' . $safe_name;
        }
    }

    $success = true;
}

// 4. حفظ البيانات المحدثة في ملف الـ JSON
if ($success) {
    if (file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true, 'message' => 'تم الحفظ بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'فشل في كتابة وحفظ البيانات على الخادم']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'الإجراء المطلوب غير معروف أو غير صالح']);
}
