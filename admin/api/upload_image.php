<?php

declare(strict_types=1);

// السماح بالوصول لملفات الحماية عبر ملف التهيئة المركزي
define('ALLOWED_ACCESS', true);

// استدعاء ملف التهيئة المركزي الذي يقوم بجلب الجلسة، الاتصال بقاعدة البيانات، ومكتبة الحماية تلقائياً
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/ImageUploader.php';

use App\Services\ImageUploader;

// ضبط ترويسة الاستجابة لتكون بصيغة JSON حصراً
header('Content-Type: application/json; charset=utf-8');

try {
    // التأكد من أن الطلب تم عبر طريقة POST فقط
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
        exit;
    }

    // التحقق من رمز حماية الـ CSRF باستخدام الدالة الجاهزة في form_protection.php (التي تم استدعاؤها عبر init.php)
    $csrfToken = $_POST['csrf_token'] ?? '';
    verify_csrf_token($csrfToken);

    // التأكد من وجود ملف مرفق ضمن الطلب
    if (!isset($_FILES['image'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No image file provided.']);
        exit;
    }

    // تحديد مسار التخزين الآمن خارج المجلد العام أو في مسار التخزين بالمشروع
    $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/';

    // تهيئة كلاس الرفع والمعالجة وتنفيذ العملية باستخدام الاتصال $pdo الجاهز من init.php
    $uploader = new ImageUploader($uploadDir);
    $filename = $uploader->upload($_FILES['image'], $pdo);

    // إرجاع استجابة نجاح مهيكلة
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded, validated, and converted to WebP successfully.',
        'data' => [
            'filename' => $filename
        ]
    ]);

} catch (InvalidArgumentException $e) {
    // أخطاء التحقق المدخلة أو فشل شروط الأمان
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (\Exception $e) {
    // تسجيل الأخطاء النظامية داخلياً وإخفاء التفاصيل عن المستخدم لأسباب أمنية
    error_log("Image Upload Exception: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'An internal server error occurred.']);
}
