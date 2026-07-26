<?php

declare(strict_types=1);

// استدعاء ملفات التهيئة وجلسات الأمان المتوفرة لديك في المجلد
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/secure_session.php';
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/ImageUploader.php';

use App\Services\ImageUploader;

header('Content-Type: application/json; charset=utf-8');

try {
    // التأكد من أن الطلب POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
        exit;
    }

    if (!isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No image file provided.']);
        exit;
    }

    // تحديد مسار التخزين (خارج المجلد العام للأمان، أو ضمن مجلد آمن بالتخزين)
    $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';

    $uploader = new ImageUploader($uploadDir);
    // نفترض أن $pdo معرف مسبقاً في ملف db_connect.php الخاص بك
    $filename = $uploader->upload($_FILES['image'], $pdo);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded and converted successfully.',
        'data' => [
            'filename' => $filename
        ]
    ]);

} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'An internal server error occurred.']);
}
