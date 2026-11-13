<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use Throwable;
use RuntimeException;
use InvalidArgumentException;

class ImageUploader
{
    private string $uploadDir;
    private int $maxFileSize;
    private int $maxWidth;
    private int $maxHeight;
    private int $maxSourcePixels;
    private int $webpQuality;
    private array $allowedMimeTypes = [
        'image/jpeg'  => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png'   => 'png',
        'image/webp'  => 'webp',
    ];

    public function __construct(
        ?string $uploadDir = null,
        int $maxFileSize = 5 * 1024 * 1024, // 5 MB
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $webpQuality = 85,
        int $maxSourcePixels = 25000000     // حماية ضد Decompression Bombs (25 MP)
    ) {
        $defaultDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploads';
        $targetDir = $uploadDir ?? $defaultDir;

        $this->uploadDir = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->maxFileSize = $maxFileSize;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->webpQuality = $webpQuality;
        $this->maxSourcePixels = $maxSourcePixels;

        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
                throw new RuntimeException('فشل في إنشاء مجلد رفع الصور.');
            }
        }
    }

    /**
     * رفع ومعالجة ملف مرفوع بالطريقة العادية ($_FILES)
     */
    public function upload(array $file, ?string $oldFilename = null): string
    {
        $this->validateUploadError((int)($file['error'] ?? UPLOAD_ERR_NO_FILE));
        $this->validateFileSize((int)($file['size'] ?? 0));
        $this->validateFileExtension((string)($file['name'] ?? ''));
        
        return $this->processAndUploadFile((string)$file['tmp_name'], $oldFilename);
    }

    /**
     * دالة عامة ومحصّنة لمعالجة أي مسار مؤقت لصورة وتحويلها إلى WebP آمن
     */
    public function processAndUploadFile(string $tmpPath, ?string $oldFilename = null): string
    {
        if ($tmpPath === '' || !is_file($tmpPath)) {
            throw new InvalidArgumentException('الملف المؤقت غير موجود.');
        }

        // فرض فحص الحجم الفعلي على القرص حتى عند الاستدعاء المباشر من الخدمات
        $actualSize = @filesize($tmpPath);
        if ($actualSize === false) {
            throw new InvalidArgumentException('تعذر قراءة حجم الملف المرفوع.');
        }
        $this->validateFileSize($actualSize);

        $detectedMime = $this->detectAndValidateMime($tmpPath);

        $finalFilename = $this->generateSecureFilename();
        $destinationPath = $this->uploadDir . $finalFilename;

        while (file_exists($destinationPath)) {
            $finalFilename = $this->generateSecureFilename();
            $destinationPath = $this->uploadDir . $finalFilename;
        }

        try {
            $this->processAndConvertToWebP($tmpPath, $destinationPath, $detectedMime);
            $this->saveToDatabase($finalFilename);

            if (!empty($oldFilename)) {
                $this->deleteOldImage($oldFilename);
            }
            
            return $finalFilename;
        } catch (Throwable $e) {
            if (file_exists($destinationPath)) {
                @unlink($destinationPath);
            }
            throw new RuntimeException('فشلت معالجة الصورة: ' . $e->getMessage());
        }
    }

    /**
     * حذف الصورة القديمة من السيرفر وقاعدة البيانات بأمان
     */
    public function deleteOldImage(string $filename): void
    {
        $cleanName = basename($filename);
        if ($cleanName === '' || $cleanName === '.' || $cleanName === '..') {
            return;
        }

        $filePath = $this->uploadDir . $cleanName;
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('DELETE FROM uploaded_images WHERE filename = :filename');
            $stmt->execute(['filename' => $cleanName]);
        } catch (Throwable $e) {
            error_log('Failed to delete old image record from database: ' . $e->getMessage());
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
            throw new InvalidArgumentException('حجم الملف غير صالح أو يتجاوز الحد المسموح (5MB).');
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
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!is_string($mimeType) || !array_key_exists($mimeType, $this->allowedMimeTypes)) {
            throw new InvalidArgumentException('نوع الملف غير مدعوم أو تم اكتشاف محتوى غير آمن.');
        }

        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException('الملف المرفوع ليس صورة صالحة أو تالف.');
        }

        [$width, $height] = $imageInfo;
        if ($width <= 0 || $height <= 0 || ($width * $height) > $this->maxSourcePixels) {
            throw new InvalidArgumentException('أبعاد الصورة كبيرة جداً أو غير صالحة للمعالجة.');
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
            if ($image && function_exists('exif_read_data')) {
                $exif = @exif_read_data($sourcePath);
                $orientation = (int)($exif['Orientation'] ?? 1);
                $image = match ($orientation) {
                    3 => imagerotate($image, 180, 0) ?: $image,
                    6 => imagerotate($image, -90, 0) ?: $image,
                    8 => imagerotate($image, 90, 0) ?: $image,
                    default => $image,
                };
                $width = imagesx($image);
                $height = imagesy($image);
            }
        } elseif ($mimeType === 'image/png') {
            $image = @imagecreatefrompng($sourcePath);
        } elseif ($mimeType === 'image/webp') {
            $image = @imagecreatefromwebp($sourcePath);
        }

        if (!$image) {
            $rawContent = @file_get_contents($sourcePath);
            if ($rawContent !== false) {
                $image = @imagecreatefromstring($rawContent);
            }
        }

        if (!$image) {
            throw new RuntimeException('فشل في تحميل مورد الصورة أو أن تنسيق الملف غير مدعوم.');
        }

        // تحويل الصور المفهرسة (Palette PNG-8) إلى TrueColor لمنع فشل imagewebp
        if (!imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $ratio = min($this->maxWidth / $width, $this->maxHeight / $height);
            $newWidth = max(1, (int)round($width * $ratio));
            $newHeight = max(1, (int)round($height * $ratio));

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            if (!$resizedImage) {
                unset($image);
                throw new RuntimeException('فشل في تخصيص الذاكرة لتغيير أبعاد الصورة.');
            }
            
            if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            unset($image);
            $image = $resizedImage;
        }

        $saved = imagewebp($image, $destinationPath, $this->webpQuality);
        unset($image);

        if (!$saved) {
            throw new RuntimeException('فشل في حفظ صورة WebP.');
        }
    }

    private function saveToDatabase(string $filename): void
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare('INSERT INTO uploaded_images (filename, created_at) VALUES (:filename, NOW())');
            $stmt->execute(['filename' => $filename]);
        } catch (Throwable $e) {
            error_log('Failed to log uploaded image to database: ' . $e->getMessage());
        }
    }
}
