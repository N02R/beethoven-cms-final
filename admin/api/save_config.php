<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit(json_encode(['success' => false]));

$config_file = 'announcement_config.json';
$data = json_decode(file_get_contents($config_file), true);

$action = $_POST['action'] ?? '';

if ($action === 'update_announcement') {
    $data['announcement'] = [
        'status' => $_POST['status'], 'type' => $_POST['type'],
        'announcement_text' => htmlspecialchars($_POST['announcement_text']),
        'bg_color' => $_POST['bg_color'], 'text_color' => $_POST['text_color'],
        'font_size' => $_POST['font_size'], 'link' => $_POST['link'],
        'open_new_tab' => isset($_POST['open_new_tab']) ? '1' : '0',
        'start_date' => $_POST['start_date'], 'end_date' => $_POST['end_date']
    ];
    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] == 0) {
        move_uploaded_file($_FILES['ad_image']['tmp_name'], 'assets/img/ad_banner.jpg');
        $data['announcement']['announcement_image_path'] = 'assets/img/ad_banner.jpg';
    }
} elseif ($action === 'update_social') {
    $new_social = [];
    foreach ($_POST['social'] as $index => $item) {
        $img = $item['old_img'];
        if (isset($_FILES['social_img_'.$index]) && $_FILES['social_img_'.$index]['error'] == 0) {
            $path = 'assets/img/soc_' . $index . '.png';
            move_uploaded_file($_FILES['social_img_'.$index]['tmp_name'], $path);
            $img = $path;
        }
        $new_social[] = ['name' => $item['name'], 'url' => $item['url'], 'img' => $img];
    }
    $data['social_links'] = $new_social;
} elseif ($action === 'update_logo') {
    if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] == 0) {
        move_uploaded_file($_FILES['logo_img']['tmp_name'], 'assets/img/logo.png');
        $data['site_logo_path'] = 'assets/img/logo.png';
    }
}

file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
