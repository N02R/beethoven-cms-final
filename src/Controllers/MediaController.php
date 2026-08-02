<?php

declare(strict_types=1);

namespace App\Controllers;

class MediaController
{
    public function serve(): void
    {
        // استقبال اسم الملف من الـ Query String (مثال: ?file=xxx.webp)
        $filename = $_GET['file'] ?? '';

        // تنظيف الاسم حمايةً من ثغرات Directory Traversal (مثل ../../)
        $filename = basename($filename);

        if (empty($filename)) {
            http_response_code(400);
            exit('Invalid file request.');
        }

        $filePath = realpath(__DIR__ . '/../../storage/uploads/') . '/' . $filename;

        // التحقق من وجود الملف وأنه يقع فعلياً داخل مجلد التخزين الآمن
        if (!$filePath || !file_exists($filePath) || !is_file($filePath)) {
            http_response_code(404);
            exit('Image not found.');
        }

        // تحديد نوع الملف (Mime Type) بدقة
        $mimeType = mime_content_type($filePath);
        
        // ترويسات الأمان والتخزين المؤقت (Caching) لتقليل ضغط السيرفر وتسريع الموقع
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=31536000, immutable'); // تخزين مؤقت لمدة سنة
        
        // قراءة وإرسال محتوى الملف للمتصفح
        readfile($filePath);
        exit;
    }
}
