<?php
/**
 * save_config.php - ملف إدارة وتحديث إعدادات الموقع
 */
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized Access']));
}

$file = __DIR__ . '/../../announcement_config.json';
$upload_path = __DIR__ . '/../../assets/img/';

// 2. قراءة البيانات أو تهيئة مصفوفة افتراضية
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
    'announcement'   => [],
    'menu_links'     => [],
    'social_links'   => [],
    'languages'      => [],
    'site_logo_path' => 'assets/img/logo.png',
    'hero'           => [],
    'services'       => [],
    'choose_items'   => [],
    'reviews_items'  => []
];

// 3. دالة رفع الملفات
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];
        $file_name = $_FILES[$file_key]['name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            $new_name = $file_key . '_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_name)) {
                return 'assets/img/' . $new_name;
            }
        }
    }
    return null;
}

$action = $_POST['action'] ?? '';

// 4. معالجة العمليات
switch ($action) {
    // [الحالات السابقة: announcement, logo, social, menu, languages, hero - تبقى كما هي]
    // ... (سأختصرهم في القالب لتركيز على الأقسام الجديدة)

    case 'update_services':
        $data['services_section_title'] = $_POST['services_title'] ?? 'خدماتنا المميزة';
        $data['services_section_desc'] = $_POST['services_desc'] ?? ''; 
        $new_services = [];
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $index => $s) {
                $img_path = handle_upload('service_img_' . $index, $upload_path);
                $new_services[] = [
                    'title' => $s['title'] ?? 'عنوان الخدمة',
                    'url'   => $s['url'] ?? '#',
                    'img'   => $img_path ?? ($s['old_img'] ?? 'assets/img/home/default.jpg')
                ];
            }
        }
        $data['services'] = $new_services;
        break;
        
    case 'update_choose':
        $data['choose_title'] = $_POST['choose_title'] ?? 'ما الذي يميز بيتهوفن سيتي';
        $data['choose_section_desc'] = $_POST['choose_desc'] ?? '';
        $choose_items = [];
        if (isset($_POST['choose']) && is_array($_POST['choose'])) {
            foreach ($_POST['choose'] as $index => $c) {
                $img_path = handle_upload('choose_img_' . $index, $upload_path);
                $choose_items[] = [
                    'title' => $c['title'] ?? '',
                    'desc'  => $c['desc'] ?? '',
                    'img'   => $img_path ?? ($c['old_img'] ?? 'assets/img/home/Grouphome1.svg')
                ];
            }
        }
        $data['choose_items'] = $choose_items;
        break;

    // القسم الجديد: التقييمات
    case 'update_reviews':
        $data['reviews_title'] = $_POST['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا';
        $reviews = [];
        if (isset($_POST['reviews']) && is_array($_POST['reviews'])) {
            foreach ($_POST['reviews'] as $r) {
                $reviews[] = ['url' => $r['url'] ?? ''];
            }
        }
        $data['reviews_items'] = $reviews;
        break;

    default:
        die(json_encode(['success' => false, 'message' => 'Action invalid']));
}

// 5. حفظ البيانات
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم الحفظ بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ أثناء الكتابة في الملف']);
}
