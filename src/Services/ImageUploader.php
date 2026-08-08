<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use Exception;
use PDO;
use RuntimeException;
use InvalidArgumentException;

class ImageUploader
{
    private string $uploadDir;
    private int $maxFileSize;
    private int $maxWidth;
    private int $maxHeight;
    private int $webpQuality;
    private array $allowedMimeTypes = [
        'image/jpeg'  => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png'   => 'png',
        'image/webp'  => 'webp',
    ];

    public function __construct(
        ?string $uploadDir = null,
        int $maxFileSize = 5 * 1024 * 1024, // الحد الأقصى لحجم الملف: 5 ميجابايت
        int $maxWidth = 1920,                // الحد الأقصى لعرض الصورة
        int $maxHeight = 1920,               // الحد الأقصى لارتفاع الصورة
        int $webpQuality = 85                // درجة جودة ضغط الـ WebP (من 0 إلى 100)
    ) {
        // الاعتماد على مجلد public/uploads الخاص بالنظام
        $defaultDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads';
        $targetDir = $uploadDir ?? $defaultDir;

        $this->uploadDir = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->maxFileSize = $maxFileSize;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->webpQuality = $webpQuality;

        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
                throw new RuntimeException('فشل في إنشاء مجلد رفع الصور.');
            }
        }
    }

    /**
     * رفع ومعالجة الصورة وتخزينها في النظام
     */
    public function upload(array $file): string
    {
        $this->validateUploadError($file['error'] ?? UPLOAD_ERR_NO_FILE);
        $this->validateFileSize($file['size'] ?? 0);
        $this->validateFileExtension($file['name'] ?? '');
        
        // جلب الـ Mime Type الحقيقي والأمن للملف
        $detectedMime = $this->detectAndValidateMime($file['tmp_name'] ?? '');

        $finalFilename = $this->generateSecureFilename();
        $destinationPath = $this->uploadDir . $finalFilename;

        if (file_exists($destinationPath)) {
            $finalFilename = $this->generateSecureFilename();
            $destinationPath = $this->uploadDir . $finalFilename;
        }

        try {
            // المعالجة والتحويل إلى WebP باستخدام الـ MIME الحقيقي المفحوص
            $this->processAndConvertToWebP($file['tmp_name'], $destinationPath, $detectedMime);
            
            // التخزين عبر كلاس الاتصال الموحد للنظام
            $this->saveToDatabase($finalFilename);
            
            if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                @unlink($file['tmp_name']);
            }

            return $finalFilename;
        } catch (Exception $e) {
            if (file_exists($destinationPath)) {
                @unlink($destinationPath);
            }
            throw new RuntimeException('فشلت معالجة الصورة: ' . $e->getMessage());
        }
    }

    private function validateUploadError(int $error): void
    {
        if ($error === UPLOAD_ERR_OK) {
            return;
        }

        $message = match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'حجم الملف المرفوع يتجاوز الحد المسموح به.',
            UPLOAD_ERR_PARTIAL => 'تم رفع جزء من الملف فقط.',
            UPLOAD_ERR_NO_FILE => 'لم يتم إرفاق أي ملف.',
            UPLOAD_ERR_NO_TMP_DIR => 'المجلد المؤقت للسيرفر مفقود.',
            UPLOAD_ERR_CANT_WRITE => 'فشل في كتابة الملف على القرص.',
            UPLOAD_ERR_EXTENSION => 'تم إيقاف رفع الملف بواسطة إضافة PHP.',
            default => 'حدث خطأ غير معروف أثناء رفع الملف.',
        };

        throw new InvalidArgumentException($message);
    }

    private function validateFileSize(int $size): void
    {
        if ($size <= 0 || $size > $this->maxFileSize) {
            throw new InvalidArgumentException('حجم الملف غير صالح أو يتجاوز الحد المسموح.');
        }
    }

    private function validateFileExtension(string $filename): void
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            throw new InvalidArgumentException('امتداد الملف غير مسموح به.');
        }
    }

    private function detectAndValidateMime(string $tmpPath): string
    {
        if (empty($tmpPath) || !file_exists($tmpPath)) {
            throw new InvalidArgumentException('الملف المؤقت غير موجود.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!array_key_exists($mimeType, $this->allowedMimeTypes)) {
            throw new InvalidArgumentException('نوع الملف غير مدعوم أو تم اكتشاف محتوى غير آمن.');
        }

        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException('الملف المرفوع ليس صورة صالحة أو تالف.');
        }

        return $mimeType;
    }

    private function generateSecureFilename(): string
    {
        return bin2hex(random_bytes(32)) . '.webp';
    }

    private function processAndConvertToWebP(string $sourcePath, string $destinationPath, string $mimeType): void
    {
        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException('الملف المرفوع ليس صورة صالحة أو تالف.');
        }
        
        [$width, $height] = $imageInfo;

        $image = null;
        if ($mimeType === 'image/jpeg' || $mimeType === 'image/pjpeg') {
            $image = @imagecreatefromjpeg($sourcePath);
        } elseif ($mimeType === 'image/png') {
            $image = @imagecreatefrompng($sourcePath);
        } elseif ($mimeType === 'image/webp') {
            $image = @imagecreatefromwebp($sourcePath);
        }

        // خطة بديلة لقراءة الصور التي تحتوي على تدرجات ألوان أو خصائص معقدة
        if (!$image) {
            $image = @imagecreatefromstring(file_get_contents($sourcePath));
        }

        if (!$image) {
            throw new RuntimeException('فشل في تحميل مورد الصورة أو أن تنسيق الملف غير مدعوم في بيئة السيرفر.');
        }

        if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        // إعادة تحجيم الصورة مع الحفاظ على الأبعاد النسبية إذا تجاوزت الحد الأقصى
        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $ratio = min($this->maxWidth / $width, $this->maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        if (!imagewebp($image, $destinationPath, $this->webpQuality)) {
            imagedestroy($image);
            throw new RuntimeException('فشل في حفظ صورة WebP.');
        }

        imagedestroy($image);
    }

    private function saveToDatabase(string $filename): void
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('INSERT INTO uploaded_images (filename, created_at) VALUES (:filename, NOW())');
            $stmt->execute(['filename' => $filename]);
        } catch (Exception $e) {
            error_log('Failed to log uploaded image to database: ' . $e->getMessage());
        }
    }
}
