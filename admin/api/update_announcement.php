<?php
session_start();
// التأكد من الصلاحيات
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit(json_encode(['success'=>false, 'error'=>'Unauthorized']));

$config_file = __DIR__ . '/../../announcement_config.json';
$data = json_decode(file_get_contents($config_file), true);

// تحديث النصوص
$data['announcement']['type'] = $_POST['type'];
$data['announcement']['announcement_text'] = $_POST['announcement_text'];
$data['announcement']['bg_color'] = $_POST['bg_color'];
$data['announcement']['text_color'] = $_POST['text_color'];
$data['announcement']['link'] = $_POST['link'];
$data['announcement']['alt_text'] = $_POST['alt_text'];
$data['announcement']['open_new_tab'] = isset($_POST['open_new_tab']) ? 1 : 0;

// معالجة الصورة (تأكدي أن اسم الحقل في الفورم هو ad_image)
if ($_POST['type'] == 'image' && isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] == 0) {
    $img_name = 'ad_' . time() . '.jpg';
    $target = __DIR__ . '/../../assets/img/' . $img_name;
    if (move_uploaded_file($_FILES['ad_image']['tmp_name'], $target)) {
        $data['announcement']['announcement_image_path'] = 'assets/img/' . $img_name;
    }
}

file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
?>
