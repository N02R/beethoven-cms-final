<?php
// استدعاء ملف الاتصال بقاعدة البيانات والـ CMS
require_once '../includes/config.php'; // أو المسار الصحيح لديك
require_once '../includes/CMS.php';

header('Content-Type: application/json');

// استقبال المتغيرات من الـ JS
$page = $_GET['page'] ?? '';
$section = $_GET['section'] ?? '';
$field = $_GET['field'] ?? '';

if (empty($page) || empty($section) || empty($field)) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

// حالة خاصة لروابط السوشيال ميديا لأنها مصفوفة (Array)
if ($field === 'social_links') {
    // نفترض أن دالة الـ CMS تعيد مصفوفة بالروابط
    $data = \App\Core\CMS::get($page, $section, $field, false, false);
    echo json_encode($data);
} 
// حالة عامة (نص أو مسار صورة)
else {
    $value = \App\Core\CMS::get($page, $section, $field, false, false);
    echo json_encode(['value' => $value]);
}
?>
