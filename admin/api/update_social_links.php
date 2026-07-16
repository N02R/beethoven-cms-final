<?php
// admin/api/update_social_links.php
session_start();
// المسار: نحن في admin/api نحتاج للخروج مرتين للوصول لـ includes
require_once('../../includes/check_auth.php'); 

header('Content-Type: application/json');

if (!isAdmin()) {
    exit(json_encode(['error' => 'Unauthorized']));
}

// المسار: الملف في المجلد الرئيسي (مستوى واحد فوق admin)
$config_path = '../../announcement_config.json';

$data = json_decode(file_get_contents($config_path), true);
$new_social_links = [];

if (isset($_POST['social'])) {
    foreach ($_POST['social'] as $index => $item) {
        $img_path = $item['old_img'] ?? '';

        if (isset($_FILES['social_img_' . $index]) && $_FILES['social_img_' . $index]['error'] == 0) {
            $ext = pathinfo($_FILES['social_img_' . $index]['name'], PATHINFO_EXTENSION);
            $new_name = 'social_' . time() . '_' . $index . '.' . $ext;
            // حفظ الصورة في assets/img
            move_uploaded_file($_FILES['social_img_' . $index]['tmp_name'], '../../assets/img/' . $new_name);
            $img_path = 'assets/img/' . $new_name;
        }

        $new_social_links[] = [
            'name' => htmlspecialchars($item['name']),
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
