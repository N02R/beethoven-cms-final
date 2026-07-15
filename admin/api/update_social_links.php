<?php
// admin/api/update_social_links.php
header('Content-Type: application/json');
if (!isset($is_admin) || $is_admin !== true) exit(json_encode(['error' => 'Unauthorized']));

$data = json_decode(file_get_contents('../../config/announcement_config.json'), true);
$new_social_links = [];

// معالجة البيانات القادمة من الفورم
foreach ($_POST['social'] as $index => $item) {
    $img_path = $item['old_img'];

    // إذا تم رفع صورة جديدة
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
file_put_contents('../../config/announcement_config.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true]);
