<?php
declare(strict_types=1);

namespace App\Services;

use Exception;

/**
 * Beethoven CMS - File Uploader Service
 * خدمة آمنة لمعالجة رفع الصور والمستندات وفق المعايير الأمنية
 */
class FileUploader {
    /**
     * معالجة ورفع الملفات بأمان مع فحص الـ MIME Type الحقيقي وتغيير اسم الملف
     */
    public static function upload(
        string $inputName, 
        string $targetDir, 
        array $allowedMimes = [], 
        int $maxSizeBytes = 10485760 // 10 Megabytes
    ): ?string {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$inputName];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('فشل في عملية رفع الملف.');
        }

        // 1. التحقق من الحجم
        if ($file['size'] > $maxSizeBytes) {
            throw new Exception('حجم الملف يتجاوز الحد المسموح به.');
        }

        // 2. فحص نوع الملف الحقيقي عبر الـ MIME Type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $defaultMimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        $allowed = !empty($allowedMimes) ? $allowedMimes : $defaultMimes;

        if (!in_array($mimeType, $allowed)) {
            throw new Exception('نوع الملف غير مسموح به لأسباب أمنية.');
        }

        // 3. توليد اسم عشوائي آمن لمنع هجمات Directory Traversal وتخريب الأسماء
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeFilename = bin2hex(random_bytes(16)) . '.' . strtolower($extension);

        // التأكد من وجود المجلد الهدف
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $destination = rtrim($targetDir, '/') . '/' . $safeFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('فشل نقل الملف المرفوع إلى المجلد المخصص.');
        }

        return $safeFilename;
    }
}