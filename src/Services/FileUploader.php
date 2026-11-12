<?php
declare(strict_types=1);

namespace App\Services;

use RuntimeException;
use InvalidArgumentException;

/**
 * Beethoven CMS - Hardened File Uploader Service
 * خدمة محصّنة لرفع المستندات والصور مع اشتقاق الامتداد من الـ MIME الحقيقي ومنع تنفيذ السكربتات
 */
class FileUploader
{
    private const MIME_EXTENSION_MAP = [
        'application/pdf'                                                         => 'pdf',
        'application/msword'                                                      => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'image/jpeg'                                                              => 'jpg',
        'image/pjpeg'                                                             => 'jpg',
        'image/png'                                                               => 'png',
        'image/webp'                                                              => 'webp',
    ];

    /**
     * معالجة ورفع الملفات بأمان مع فحص الـ MIME الحقيقي واشتقاق الامتداد الآمن
     */
    public static function upload(
        string $inputName,
        string $targetDir,
        array $allowedMimes = [],
        int $maxSizeBytes = 10485760 // 10 MB
    ): ?string {
        if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName])) {
            return null;
        }

        $file = $_FILES[$inputName];
        $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException(self::getUploadErrorMessage($error));
        }

        $tmpPath = (string)($file['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new InvalidArgumentException('مصدر الملف المرفوع غير موثوق.');
        }

        // 1. فحص الحجم الفعلي على القرص (منع التلاعب بـ $_FILES['size'])
        $actualSize = @filesize($tmpPath);
        if ($actualSize === false || $actualSize <= 0 || $actualSize > $maxSizeBytes) {
            throw new InvalidArgumentException('حجم الملف غير صالح أو يتجاوز الحد المسموح به.');
        }

        // 2. فحص نوع الملف الحقيقي عبر finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!is_string($mimeType)) {
            throw new InvalidArgumentException('تعذر التعرف على نوع الملف.');
        }

        $defaultMimes = array_keys(self::MIME_EXTENSION_MAP);
        $allowed = !empty($allowedMimes) ? $allowedMimes : $defaultMimes;

        if (!in_array($mimeType, $allowed, true) || !isset(self::MIME_EXTENSION_MAP[$mimeType])) {
            throw new InvalidArgumentException('نوع الملف غير مسموح به لأسباب أمنية.');
        }

        // 3. فحص البنية الداخلية للملف (Deep Content Inspection)
        self::validateFileStructure($tmpPath, $mimeType);

        // 4. اشتقاق الامتداد حصرياً من خريطة MIME الآمنة (إغلاق ثغرة RCE نهائياً)
        $safeExtension = self::MIME_EXTENSION_MAP[$mimeType];

        // 5. تجهيز وتأمين المجلد الهدف
        $normalizedDir = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR;
        if (!is_dir($normalizedDir)) {
            if (!mkdir($normalizedDir, 0755, true) && !is_dir($normalizedDir)) {
                throw new RuntimeException('فشل في إنشاء مجلد رفع الملفات.');
            }
        }
        self::ensureDirectorySecurity($normalizedDir);

        // 6. توليد اسم عشوائي فريد بطول 64 محرفاً
        do {
            $safeFilename = bin2hex(random_bytes(32)) . '.' . $safeExtension;
            $destination  = $normalizedDir . $safeFilename;
        } while (file_exists($destination));

        if (!move_uploaded_file($tmpPath, $destination)) {
            throw new RuntimeException('فشل نقل الملف المرفوع إلى المجلد المخصص.');
        }

        @chmod($destination, 0644);
        return $safeFilename;
    }

    /**
     * التحقق من سلامة محتوى الملف حسب نوعه لمنع الملفات المزورة
     */
    private static function validateFileStructure(string $tmpPath, string $mimeType): void
    {
        if (str_starts_with($mimeType, 'image/')) {
            if (@getimagesize($tmpPath) === false) {
                throw new InvalidArgumentException('ملف الصورة تالف أو غير صالح.');
            }
            return;
        }

        if ($mimeType === 'application/pdf') {
            $handle = @fopen($tmpPath, 'rb');
            $header = $handle ? fread($handle, 5) : '';
            if ($handle) {
                fclose($handle);
            }
            if ($header !== '%PDF-') {
                throw new InvalidArgumentException('ملف PDF غير صالح أو تالف.');
            }
            return;
        }

        if ($mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            if ($zip->open($tmpPath) !== true) {
                throw new InvalidArgumentException('ملف DOCX تالف أو غير صالح.');
            }
            $hasContentTypes = ($zip->locateName('[Content_Types].xml') !== false);
            $hasMacro        = ($zip->locateName('word/vbaProject.bin') !== false);
            $zip->close();

            if (!$hasContentTypes || $hasMacro) {
                throw new InvalidArgumentException('تم رفض مستند DOCX لعدم مطابقته للمعايير الأمنية.');
            }
        }
    }

    /**
     * وضع ملف حماية .htaccess داخل مجلد الرفع لمنع تنفيذ أي سكربتات
     */
    private static function ensureDirectorySecurity(string $dir): void
    {
        $htaccessPath = $dir . '.htaccess';
        if (!file_exists($htaccessPath)) {
            $rules = "Options -Indexes -ExecCGI\n"
                   . "<FilesMatch \"\\.(php|phtml|php3|php4|php5|php7|php8|phar|pl|py|jsp|asp|sh|cgi)$\">\n"
                   . "    Require all denied\n"
                   . "</FilesMatch>\n";
            @file_put_contents($htaccessPath, $rules, LOCK_EX);
        }
    }

    private static function getUploadErrorMessage(int $error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'حجم الملف المرفوع يتجاوز الحد المسموح به.',
            UPLOAD_ERR_PARTIAL                        => 'تم رفع جزء من الملف فقط.',
            UPLOAD_ERR_NO_TMP_DIR                     => 'المجلد المؤقت للسيرفر مفقود.',
            UPLOAD_ERR_CANT_WRITE                     => 'فشل في كتابة الملف على القرص.',
            UPLOAD_ERR_EXTENSION                      => 'تم إيقاف رفع الملف بواسطة إضافة PHP.',
            default                                   => 'فشل في عملية رفع الملف.',
        };
    }
}
