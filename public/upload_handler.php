<?php
// public/upload_handler.php
header('Content-Type: application/json; charset=utf-8');

// المسار المطلق الصحيح لمشروعك على الهاتف
$rootPath = '/storage/emulated/0/Documents/beethoven-cms-final';

// التعديل هنا ليتطابق مع مسار ملف الاتصال الحقيقي لديك
require_once $rootPath . '/database/database.php';
require_once $rootPath . '/src/Services/ImageUploader.php';

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

    // المسار الذي سيتم تخزينه وعرضه في الواجهة
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
