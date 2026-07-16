<?php
session_start();
header('Content-Type: application/json');

// 1. حماية موحدة
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// استخدام __DIR__ لضمان الوصول للملف دائماً مهما كان مكان ملف الـ API
$file = __DIR__ . '/../../announcement_config.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$action = $_POST['action'] ?? '';

// 2. معالجة الإعلان
if ($action === 'update_announcement') {
    $data['announcement'] = [
        'status' => $_POST['status'],
        'type' => $_POST['type'],
        'announcement_text' => htmlspecialchars($_POST['announcement_text']),
        'bg_color' => $_POST['bg_color'],
        'text_color' => $_POST['text_color'],
        'font_size' => $_POST['font_size'],
        'link' => $_POST['link'],
        'open_new_tab' => isset($_POST['open_new_tab']) ? '1' : '0',
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date']
    ];
} 
// 3. معالجة السوشيال ميديا
elseif ($action === 'update_social') {
    $new_social = [];
    foreach ($_POST['social'] as $index => $item) {
        $img = $item['old_img'] ?? '';
        $file_key = 'social_img_' . $index;
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $new_name = 'social_' . time() . '_' . $index . '.png';
            move_uploaded_file($_FILES[$file_key]['tmp_name'], __DIR__ . '/../../assets/img/' . $new_name);
            $img = 'assets/img/' . $new_name;
        }
        $new_social[] = ['name' => htmlspecialchars($item['name']), 'url' => $item['url'], 'img' => $img];
    }
    $data['social_links'] = $new_social;
} 
// 4. معالجة اللوجو
elseif ($action === 'update_logo') {
    if (isset($_FILES['logo_img']) && $_FILES['logo_img']['error'] == 0) {
        $new_name = 'logo_' . time() . '.png';
        move_uploaded_file($_FILES['logo_img']['tmp_name'], __DIR__ . '/../../assets/img/' . $new_name);
        $data['site_logo_path'] = 'assets/img/' . $new_name;
    }
}

// 5. الحفظ النهائي
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل الحفظ']);
}
