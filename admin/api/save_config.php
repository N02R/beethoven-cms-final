<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$file = __DIR__ . '/../../announcement_config.json';
// 1. قراءة البيانات الموجودة حالياً (للحفاظ عليها)
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : ['announcement' => [], 'menu_links' => [], 'social_links' => [], 'site_logo_path' => 'assets/img/logo.png'];

$action = $_POST['action'] ?? '';

// 2. تحديث الجزء المعني فقط بناءً على الـ Action
if ($action === 'update_announcement') {
    $data['announcement'] = [
        'status' => $_POST['announcement']['status'] ?? 'Draft',
        'announcement_text' => $_POST['announcement']['announcement_text'] ?? '',
        'link' => $_POST['announcement']['link'] ?? '',
        'start_date' => $_POST['announcement']['start_date'] ?? '',
        'end_date' => $_POST['announcement']['end_date'] ?? '',
        'type' => $_POST['type'] ?? 'text',
        'bg_color' => $_POST['bg_color'] ?? '#f1f5f9',
        'text_color' => $_POST['text_color'] ?? '#1e293b',
        'font_size' => $_POST['font_size'] ?? '16',
        'image_path' => $data['announcement']['image_path'] ?? '' // الحفاظ على الصورة القديمة
    ];
} 
elseif ($action === 'update_logo') {
    $data['site_logo_path'] = $_POST['site_logo_path'] ?? $data['site_logo_path'];
}
elseif ($action === 'update_social') {
    $data['social_links'] = $_POST['social'] ?? $data['social_links'];
}

// 3. حفظ كامل المصفوفة المحدثة
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم حفظ البيانات بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل الكتابة في الملف']);
}
