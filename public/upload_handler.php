<?php
// public/upload_handler.php
header('Content-Type: application/json; charset=utf-8');

// الصعود مستوى للأعلى (..) للخروج من public ثم الدخول إلى src
require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../src/Services/ImageUploader.php';

use App\Services\ImageUploader;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة']);
    exit;
}

try {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('لم يتم اختيار أي صورة.');
    }

    $uploader = new ImageUploader();
    $filename = $uploader->upload($_FILES['image']);

    // بما أننا داخل مجلد public، فالصورة ستخزن في مجلد uploads بداخله
    $imagePath = 'uploads/' . $filename;

    echo json_encode([
        'success' => true,
        'message' => 'تم رفع الصورة بنجاح',
        'url' => $imagePath,
        'path' => $imagePath
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
