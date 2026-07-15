<?php
// admin/api/update_config.php
session_start();
header('Content-Type: application/json');

// 1. جدار حماية
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die(json_encode(['error' => 'Unauthorized Access']));
}

$config_file = '../../config/announcement_config.json';

// التأكد من وجود الملف
if (!file_exists($config_file)) {
    die(json_encode(['error' => 'Configuration file not found']));
}

$data = json_decode(file_get_contents($config_file), true);

// 2. تحديث الإعلان العلوي
if (isset($_POST['status'])) {
    $data['announcement'] = [
        'status'            => $_POST['status'],
        'type'              => $_POST['type'],
        'announcement_text' => htmlspecialchars($_POST['announcement_text']),
        'bg_color'          => $_POST['bg_color'],
        'text_color'        => $_POST['text_color'],
        'font_size'         => $_POST['font_size'],
        'link'              => $_POST['link'],
        'open_new_tab'      => isset($_POST['open_new_tab']) ? '1' : '0',
        'start_date'        => $_POST['start_date'],
        'end_date'          => $_POST['end_date']
    ];
}

// 3. تحديث السوشيال ميديا
if (isset($_POST['social'])) {
    $new_social = [];
    foreach ($_POST['social'] as $index => $item) {
        $img = $item['old_img'] ?? '';

        // معالجة رفع صورة جديدة لكل منصة
        $file_key = 'social_img_' . $index;
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../assets/img/';
            $file_name = 'social_' . time() . '_' . $index . '_' . basename($_FILES[$file_key]['name']);
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $file_name)) {
                $img = 'assets/img/' . $file_name;
            }
        }

        $new_social[] = [
            'name' => htmlspecialchars($item['name']),
            'url'  => $item['url'],
            'img'  => $img
        ];
    }
    $data['social_links'] = $new_social;
}

// 4. حفظ البيانات في الملف
if (file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to save changes']);
}
