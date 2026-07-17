<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

// 1. التحقق من الصلاحيات
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$file = __DIR__ . '/../../announcement_config.json';
// قراءة الملف وتجهيز المصفوفة الافتراضية إذا لم يوجد
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
    'announcement' => [], 
    'menu_links' => [], 
    'social_links' => [], 
    'languages' => [
        ['name' => 'العربية', 'url' => 'index.php'],
        ['name' => 'English', 'url' => 'index-en.php']
    ],
    'site_logo_path' => 'assets/img/logo.png'
];

$action = $_POST['action'] ?? '';
$upload_path = __DIR__ . '/../../assets/img/';

// 2. دالة مساعدة لنقل الصور
function handle_upload($file_key, $upload_dir) {
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
        $new_name = $file_key . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_name)) {
            return 'assets/img/' . $new_name;
        }
    }
    return null;
}

// 3. معالجة العمليات
if ($action === 'update_announcement') {
    $img_path = handle_upload('ad_image', $upload_path);
    $data['announcement'] = [
        'status' => $_POST['status'] ?? 'Draft',
        'announcement_text' => $_POST['announcement_text'] ?? '',
        'link' => $_POST['link'] ?? '',
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'type' => $_POST['type'] ?? 'text',
        'bg_color' => $_POST['bg_color'] ?? '#f1f5f9',
        'text_color' => $_POST['text_color'] ?? '#1e293b',
        'font_size' => $_POST['font_size'] ?? '16',
        'open_new_tab' => $_POST['open_new_tab'] ?? 0,
        'image_path' => $img_path ?? ($data['announcement']['image_path'] ?? '')
    ];
} 
elseif ($action === 'update_logo') {
    $img_path = handle_upload('logo_img', $upload_path);
    if ($img_path) {
        $data['site_logo_path'] = $img_path;
    }
}
elseif ($action === 'update_social') {
    $socials = $_POST['social'] ?? [];
    foreach ($socials as $index => $s) {
        $img_path = handle_upload('social_img_' . $index, $upload_path);
        $socials[$index]['img'] = $img_path ?? ($s['old_img'] ?? '');
        $socials[$index]['name'] = $s['name'] ?? '';
        $socials[$index]['url'] = $s['url'] ?? '';
        unset($socials[$index]['old_img']);
    }
    $data['social_links'] = $socials;
}
elseif ($action === 'update_menu') {
    $new_menu = [];
    if (isset($_POST['menu']) && is_array($_POST['menu'])) {
        foreach ($_POST['menu'] as $item) {
            $new_menu[] = [
                'title' => $item['title'] ?? 'رابط جديد',
                'url'   => $item['url'] ?? '#',
                'order' => (int)($item['order'] ?? 0),
                'active' => false
            ];
        }
    }
    usort($new_menu, function($a, $b) { return $a['order'] <=> $b['order']; });
    $data['menu_links'] = $new_menu;
}
elseif ($action === 'update_languages') {
    // تحديث قائمة اللغات
    $new_langs = [];
    if (isset($_POST['lang']) && is_array($_POST['lang'])) {
        foreach ($_POST['lang'] as $l) {
            if (!empty($l['name'])) {
                $new_langs[] = [
                    'name' => $l['name'],
                    'url'  => $l['url'] ?? '#'
                ];
            }
        }
    }
    $data['languages'] = $new_langs;
}

// 4. حفظ البيانات في ملف JSON
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم حفظ التغييرات بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في حفظ الملف']);
}
?>
