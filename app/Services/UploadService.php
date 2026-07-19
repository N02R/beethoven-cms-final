<?php
namespace App\Services;

class UploadService {
    private array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    private int $maxFileSize = 2 * 1024 * 1024; // 2MB

    public function upload(array $file, string $targetDir): string {
        // 1. التحقق من وجود أخطاء في الرفع
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("خطأ في رفع الملف.");
        }

        // 2. التحقق من حجم الملف
        if ($file['size'] > $this->maxFileSize) {
            throw new \Exception("حجم الملف كبير جداً (الحد الأقصى 2MB).");
        }

        // 3. التحقق من نوع الملف (Security: MIME type validation)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \Exception("نوع ملف غير مسموح به.");
        }

        // 4. توليد اسم ملف عشوائي (Security: Prevent Overwriting & Directory Traversal)
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetPath = $targetDir . '/' . $newName;

        // 5. نقل الملف
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $newName;
        }

        throw new \Exception("فشل حفظ الملف.");
    }
}
