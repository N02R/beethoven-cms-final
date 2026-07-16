<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit(json_encode(['success'=>false]));

$config_file = __DIR__ . '/../../announcement_config.json';
$data = json_decode(file_get_contents($config_file), true);

$data['announcement']['type'] = $_POST['type'];
$data['announcement']['announcement_text'] = $_POST['announcement_text'];
$data['announcement']['bg_color'] = $_POST['bg_color'];
$data['announcement']['text_color'] = $_POST['text_color'];
$data['announcement']['link'] = $_POST['link'];
$data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? 1 : 0;

if ($_POST['type'] == 'image' && isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] == 0) {
    $img_name = 'ad_current.jpg'; // اسم ثابت لضمان التحديث
    move_uploaded_file($_FILES['ad_image']['tmp_name'], __DIR__ . '/../../assets/img/' . $img_name);
    $data['announcement']['announcement_image_path'] = 'assets/img/' . $img_name;
}

file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
?>
