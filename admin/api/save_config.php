<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$file = __DIR__ . '/../../announcement_config.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
    'announcement' => [], 'menu_links' => [], 'social_links' => [], 'languages' => [], 'site_logo_path' => 'assets/img/logo.png'
];

$action = $_POST['action'] ?? '';
$upload_path = __DIR__ . '/../../assets/img/';

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

// معالجة العمليات
if ($action === 'update_announcement') {
    $img_path = handle_upload('ad_image', $upload_path);
    $data['announcement'] = [
        'status' => $_POST['status'] ?? 'Draft',
        'announcement_text' => $_POST['announcement_text'] ?? '',
        'link' => $_POST['link'] ?? '',
        'type' => $_POST['type'] ?? 'text',
        'image_path' => $img_path ?? ($data['announcement']['image_path'] ?? '')
    ];
} 
elseif ($action === 'update_logo') {
    $img_path = handle_upload('logo_img', $upload_path);
    if ($img_path) $data['site_logo_path'] = $img_path;
}
elseif ($action === 'update_social') {
    $socials = [];
    if (isset($_POST['social']) && is_array($_POST['social'])) {
        foreach ($_POST['social'] as $index => $s) {
            $img_path = handle_upload('social_img_' . $index, $upload_path);
            $socials[] = [
                'name' => $s['name'] ?? '',
                'url' => $s['url'] ?? '',
                'img' => $img_path ?? ($s['old_img'] ?? '')
            ];
        }
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
                'order' => (int)($item['order'] ?? 0)
            ];
        }
    }
    usort($new_menu, function($a, $b) { return $a['order'] <=> $b['order']; });
    $data['menu_links'] = $new_menu;
}
elseif ($action === 'update_languages') {
    $new_langs = [];
    if (isset($_POST['lang']) && is_array($_POST['lang'])) {
        foreach ($_POST['lang'] as $l) {
            if (!empty($l['name'])) $new_langs[] = ['name' => $l['name'], 'url' => $l['url'] ?? '#'];
        }
    }
    $data['languages'] = $new_langs;
}

if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'message' => 'تم الحفظ بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في الحفظ']);
}
?>
