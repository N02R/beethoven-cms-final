<?php
// admin/api/update_social_links.php
session_start();
// 1. تضمين ملف التحقق لضمان الأمان
require_once('../../includes/check_auth.php');

header('Content-Type: application/json');

// 2. استخدام دالة التحقق بدلاً من متغير غير معرف
if (!isAdmin()) {
    exit(json_encode(['error' => 'Unauthorized']));
}

// 3. تأكدي من مسار ملف الـ JSON الصحيح
$config_file = '../../announcement_config.json'; 

// 4. التحقق من وجود البيانات
if (!isset($_POST['social'])) {
    // في حال حذف المستخدم كل الروابط، نقوم بتحديث الملف بمصفوفة فارغة
    $data = json_decode(file_get_contents($config_file), true);
    $data['social_links'] = [];
    file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    exit(json_encode(['success' => true]));
}

$data = json_decode(file_get_contents($config_file), true);
$new_social_links = [];

// معالجة البيانات
foreach ($_POST['social'] as $index => $item) {
    $img_path = $item['old_img'] ?? '';

    // معالجة رفع الصورة
    $file_key = 'social_img_' . $index;
    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
        $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
        $new_name = 'social_' . time() . '_' . $index . '.' . $ext;
        // تأكدي أن مجلد assets/img موجود وقابل للكتابة
        move_uploaded_file($_FILES[$file_key]['tmp_name'], '../../assets/img/' . $new_name);
        $img_path = 'assets/img/' . $new_name;
    }

    $new_social_links[] = [
        'name' => htmlspecialchars($item['name']),
        'url'  => filter_var($item['url'], FILTER_SANITIZE_URL),
        'img'  => $img_path
    ];
}

$data['social_links'] = $new_social_links;
// أضيفي هذا السطر قبل سطر الـ file_put_contents
if (!is_writable('../../announcement_config.json')) {
    exit(json_encode(['error' => 'File is not writable - Check Permissions!']));
}

file_put_contents($config_file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true]);
