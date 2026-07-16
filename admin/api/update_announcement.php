<?php
// 1. بدء الجلسة والتحقق من الصلاحيات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

$is_admin = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'غير مصرح']);
    exit;
}

// 2. قراءة ملف الإعدادات
$config_file = __DIR__ . '/../../announcement_config.json';
if (!file_exists($config_file)) {
    echo json_encode(['success' => false, 'error' => 'ملف الإعدادات غير موجود']);
    exit;
}
$data = json_decode(file_get_contents($config_file), true);

// 3. تحديث بيانات النص
$data['announcement']['type'] = $_POST['type'] ?? 'text';
$data['announcement']['announcement_text'] = $_POST['announcement_text'] ?? '';
$data['announcement']['bg_color'] = $_POST['bg_color'] ?? '#0056b3';
$data['announcement']['text_color'] = $_POST['text_color'] ?? '#ffffff';
$data['announcement']['link'] = $_POST['link'] ?? '';
$data['announcement']['alt_text'] = $_POST['alt_text'] ?? '';

// حل مشكلة الـ Checkbox (إذا لم يتم إرساله، قيمته صفر)
$data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? 1 : 0;

// 4. معالجة الصورة إذا تم رفع صورة جديدة
if ($_POST['type'] == 'image' && isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($_FILES['ad_image']['name'], PATHINFO_EXTENSION));
    
    if (in_array($ext, $allowed)) {
        $img_name = 'ad_' . time() . '.' . $ext;
        $upload_path = __DIR__ . '/../../assets/img/' . $img_name;
        
        if (move_uploaded_file($_FILES['ad_image']['tmp_name'], $upload_path)) {
            $data['announcement']['announcement_image_path'] = 'assets/img/' . $img_name;
        }
    }
}

// 5. حفظ البيانات
if (file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'فشل حفظ التعديلات']);
}
