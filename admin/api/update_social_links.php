<?php
// admin/api/update_social_links.php
session_start();
// تضمين ملف التحقق لضمان الأمان
require_once('../../includes/check_auth.php');

header('Content-Type: application/json');

// استخدام الدالة التي عرفناها في check_auth.php
if (!isAdmin()) {
    exit(json_encode(['error' => 'Unauthorized']));
}

// توحيد مسار الملف
$config_path = '../../announcement_config.json';

if (!file_exists($config_path)) {
    exit(json_encode(['error' => 'Config file missing']));
}

$data = json_decode(file_get_contents($config_path), true);
$new_social_links = [];

// معالجة البيانات
if (isset($_POST['social'])) {
    foreach ($_POST['social'] as $index => $item) {
        $img_path = $item['old_img'] ?? '';

        if (isset($_FILES['social_img_' . $index]) && $_FILES['social_img_' . $index]['error'] == 0) {
            $ext = pathinfo($_FILES['social_img_' . $index]['name'], PATHINFO_EXTENSION);
            $new_name = 'social_' . time() . '_' . $index . '.' . $ext;
            move_uploaded_file($_FILES['social_img_' . $index]['tmp_name'], '../../assets/img/' . $new_name);
            $img_path = 'assets/img/' . $new_name;
        }

        $new_social_links[] = [
            'name' => $item['name'],
            'url'  => $item['url'],
            'img'  => $img_path
        ];
    }
    $data['social_links'] = $new_social_links;
    file_put_contents($config_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'No data received']);
}
