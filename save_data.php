<?php
// save_data.php
session_start();
header('Content-Type: application/json');

// تأكد من صلاحية الأدمن
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'غير مصرح']));
}

$config_file = 'announcement_config.json';
$data = json_decode(file_get_contents($config_file), true);

// تحديث الإعلان
if ($_POST['action'] === 'update_announcement') {
    $data['announcement']['type'] = $_POST['type'];
    $data['announcement']['announcement_text'] = $_POST['announcement_text'];
    $data['announcement']['bg_color'] = $_POST['bg_color'];
    $data['announcement']['text_color'] = $_POST['text_color'];
    $data['announcement']['link'] = $_POST['link'];
    $data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? '1' : '0';

    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
        $img_name = 'assets/img/ad_' . time() . '.jpg';
        move_uploaded_file($_FILES['ad_image']['tmp_name'], $img_name);
        $data['announcement']['announcement_image_path'] = $img_name;
    }
}

// حفظ الملف
if (file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل الحفظ']);
}
?>
