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
    'services'       => [] // إضافة مفتاح الخدمات
];

// 3. دالة رفع الملفات مع فحص الأمان
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
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
    case 'update_announcement':
        $img_path = handle_upload('ad_image', $upload_path);
        $data['announcement'] = [
            'status'            => $_POST['status'] ?? 'Draft',
            'announcement_text' => $_POST['announcement_text'] ?? '',
            'link'              => $_POST['link'] ?? '',
            'type'              => $_POST['type'] ?? 'text',
            'image_path'        => $img_path ?? ($data['announcement']['image_path'] ?? '')
        ];
        break;

    case 'update_logo':
        $img_path = handle_upload('logo_img', $upload_path);
        if ($img_path) $data['site_logo_path'] = $img_path;
        break;

    case 'update_social':
        $socials = [];
        if (isset($_POST['social']) && is_array($_POST['social'])) {
            foreach ($_POST['social'] as $index => $s) {
                $img_path = handle_upload('social_img_' . $index, $upload_path);
                $socials[] = [
                    'name' => $s['name'] ?? '',
                    'url'  => $s['url'] ?? '',
                    'img'  => $img_path ?? ($s['old_img'] ?? '')
                ];
            }
        }
        $data['social_links'] = $socials;
        break;

    case 'update_menu':
        $new_menu = [];
        if (isset($_POST['menu']) && is_array($_POST['menu'])) {
            foreach ($_POST['menu'] as $item) {
                $new_menu[] = [
                    'title' => $item['title'] ?? 'رابط جديد',
                    'url'   => $item['url'] ?? '#',
                    'order' => (int)($item['order'] ?? 0)
                ];
            }
        }
        usort($new_menu, fn($a, $b) => $a['order'] <=> $b['order']);
        $data['menu_links'] = $new_menu;
        break;

    case 'update_languages':
        $new_langs = [];
        if (isset($_POST['lang']) && is_array($_POST['lang'])) {
            foreach ($_POST['lang'] as $l) {
                if (!empty($l['name'])) $new_langs[] = ['name' => $l['name'], 'url' => $l['url'] ?? '#'];
            }
        }
        $data['languages'] = $new_langs;
        break;

    case 'update_hero':
        $img_path = handle_upload('hero_img', $upload_path);
        $data['hero'] = [
            'title'    => $_POST['hero_title'] ?? '',
            'desc'     => $_POST['hero_desc'] ?? '',
            'btn_text' => $_POST['hero_btn_text'] ?? '',
            'btn_url'  => $_POST['hero_btn_url'] ?? '',
            'img'      => $img_path ?? ($_POST['old_hero_img'] ?? 'assets/img/hero-bg.jpg')
        ];
        break;

        case 'update_services':
        // حفظ العنوان الجديد
        case 'update_services':
        $data['services_section_title'] = $_POST['services_title'] ?? 'خدماتنا المميزة';
        // استقبال الوصف وحفظه
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
    $data['choose_section_desc'] = $_POST['choose_desc'] ?? ''; // حفظ الوصف
    
    $choose_items = [];
    // ... (بقية كود معالجة المصفوفة كما هو)

        if (isset($_POST['choose']) && is_array($_POST['choose'])) {
            foreach ($_POST['choose'] as $index => $c) {
                $img_path = handle_upload('choose_img_' . $index, $upload_path);
                $choose_items[] = [
                    'title' => $c['title'] ?? '',
                    'desc'  => $c['desc'] ?? '',
                    'url'   => $c['url'] ?? '#',
                    'img'   => $img_path ?? ($c['old_img'] ?? 'assets/img/home/Grouphome1.svg')
                ];
            }
        }
        $data['choose_items'] = $choose_items;
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
?>
