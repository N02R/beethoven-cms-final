<?php

declare(strict_types=1);

namespace App\Services;

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
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    public function __construct(
        string $uploadDir,
        int $maxFileSize = 5 * 1024 * 1024, // 5 ميجابايت كحد أقصى
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $webpQuality = 85
    ) {
        $this->uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->maxFileSize = $maxFileSize;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
        $this->webpQuality = $webpQuality;

        if (!is_dir($this->uploadDir)) {
            if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
                throw new RuntimeException('Failed to create upload directory.');
            }
        }
    }

    public function upload(array $file, PDO $pdo): string
    {
        $this->validateUploadError($file['error']);
        $this->validateFileSize($file['size']);
        $this->validateFileExtension($file['name']);
        $this->validateMimeAndContent($file['tmp_name']);

        $finalFilename = $this->generateSecureFilename();
        $destinationPath = $this->uploadDir . $finalFilename;

        // منع الكتابة الفوقية للملفات
        if (file_exists($destinationPath)) {
            $finalFilename = $this->generateSecureFilename();
            $destinationPath = $this->uploadDir . $finalFilename;
        }

        try {
            $this->processAndConvertToWebP($file['tmp_name'], $destinationPath, $file['type']);
            $this->saveToDatabase($pdo, $finalFilename);
            
            if (is_uploaded_file($file['tmp_name'])) {
                @unlink($file['tmp_name']);
            }

            return $finalFilename;
        } catch (Exception $e) {
            if (file_exists($destinationPath)) {
                @unlink($destinationPath);
            }
            throw new RuntimeException('Image processing failed: ' . $e->getMessage());
        }
    }

    private function validateUploadError(int $error): void
    {
        if ($error === UPLOAD_ERR_OK) {
            return;
        }

        $message = match ($error) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum allowed size.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            default => 'Unknown upload error.',
        };

        throw new InvalidArgumentException($message);
    }

    private function validateFileSize(int $size): void
    {
        if ($size <= 0 || $size > $this->maxFileSize) {
            throw new InvalidArgumentException('File size is invalid or exceeds the allowed limit.');
        }
    }

    private function validateFileExtension(string $filename): void
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            throw new InvalidArgumentException('Invalid file extension.');
        }
    }

    private function validateMimeAndContent(string $tmpPath): void
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpPath);

        if (!array_key_exists($mimeType, $this->allowedMimeTypes)) {
            throw new InvalidArgumentException('Invalid file type or malicious content detected.');
        }

        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException('The uploaded file is not a valid image or is corrupted.');
        }
    }

    private function generateSecureFilename(): string
    {
        return bin2hex(random_bytes(32)) . '.webp';
    }

    private function processAndConvertToWebP(string $sourcePath, string $destinationPath, string $mimeType): void
    {
        [$width, $height] = getimagesize($sourcePath);

        $image = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($sourcePath),
            'image/png'  => imagecreatefrompng($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default      => throw new InvalidArgumentException('Unsupported image type for conversion.'),
        };

        if (!$image) {
            throw new RuntimeException('Failed to load image resource.');
        }

        if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

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
            throw new RuntimeException('Failed to save WebP image.');
        }

        imagedestroy($image);
    }

    private function saveToDatabase(PDO $pdo, string $filename): void
    {
        $stmt = $pdo->prepare('INSERT INTO uploaded_images (filename, created_at) VALUES (:filename, NOW())');
        $stmt->execute(['filename' => $filename]);
    }
}
