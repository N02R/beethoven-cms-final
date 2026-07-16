<?php
// admin/api/update_social_links.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// التحقق الصحيح بناءً على ما قمتِ بتعريفه في login.php
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    exit(json_encode(['error' => 'Unauthorized']));
}

$config_file = '../../announcement_config.json';
if (!file_exists($config_file)) {
    exit(json_encode(['error' => 'Config file not found']));
}

$data = json_decode(file_get_contents($config_file), true);
$new_social_links = [];

// معالجة البيانات فقط إذا كانت موجودة
if (isset($_POST['social']) && is_array($_POST['social'])) {
    foreach ($_POST['social'] as $index => $item) {
        $img_path = $item['old_img'] ?? '';

        // معالجة رفع الصور
        $file_key = 'social_img_' . $index;
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
            $new_name = 'social_' . time() . '_' . $index . '.' . $ext;
            move_uploaded_file($_FILES[$file_key]['tmp_name'], '../../assets/img/' . $new_name);
            $img_path = 'assets/img/' . $new_name;
        }

        $new_social_links[] = [
            'name' => htmlspecialchars($item['name']),
            'url'  => $item['url'],
            'img'  => $img_path
        ];
    }
}

$data['social_links'] = $new_social_links;
file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true]);
