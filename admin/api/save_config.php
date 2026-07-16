<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$file = __DIR__ . '/../../announcement_config.json';
// قراءة البيانات الحالية للحفاظ عليها
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$action = $_POST['action'] ?? '';

// --- معالجة الإعلان (دمج منطقك الآمن هنا) ---
if ($action === 'update_announcement') {
    $type = htmlspecialchars($_POST['type'] ?? 'text');
    
    $new_ad = [
        'type' => $type,
        'status' => htmlspecialchars($_POST['status'] ?? 'Draft'),
        'link' => filter_var($_POST['link'] ?? '', FILTER_SANITIZE_URL),
        'open_new_tab' => isset($_POST['open_new_tab']) ? 1 : 0,
        'announcement_text' => htmlspecialchars($_POST['announcement_text'] ?? ''),
        'bg_color' => htmlspecialchars($_POST['bg_color'] ?? '#ffffff'),
        'text_color' => htmlspecialchars($_POST['text_color'] ?? '#000000'),
        'font_size' => (int)($_POST['font_size'] ?? 14),
        'image_path' => $data['announcement']['image_path'] ?? null // الاحتفاظ بالصورة القديمة
    ];

    // معالجة رفع الصورة إذا كان النوع صورة
    if ($type === 'image' && isset($_FILES['announcement_image']) && $_FILES['announcement_image']['error'] == 0) {
        $upload_dir = __DIR__ . '/../../uploads/announcements/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        
        $filename = 'ad_' . time() . '.jpg';
        if (move_uploaded_file($_FILES['announcement_image']['tmp_name'], $upload_dir . $filename)) {
            $new_ad['image_path'] = 'uploads/announcements/' . $filename;
        }
    }
    
    $data['announcement'] = $new_ad;
}

// ... (نفس منطق السوشيال واللوجو السابق)

// الحفظ النهائي
if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'فشل الحفظ']);
}
