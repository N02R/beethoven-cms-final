<?php

namespace App\Services;

class ConfigManager
{
    private string $filePath;
    private string $uploadDir;

    public function __construct(string $filePath, string $uploadDir)
    {
        $this->filePath = $filePath;
        $this->uploadDir = $uploadDir;
    }

    /**
     * قراءة البيانات من ملف JSON
     */
    public function load(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        
        $content = file_get_contents($this->filePath);
        return json_decode($content, true) ?: [];
    }

    /**
     * حفظ البيانات المحدثة داخل ملف JSON
     */
    public function save(array $data): bool
    {
        $jsonFlags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $encoded = json_encode($data, $jsonFlags);
        
        if ($encoded === false) {
            return false;
        }

        return file_put_contents($this->filePath, $encoded, LOCK_EX) !== false;
    }

    /**
     * معالجة رفع المستندات والملفات
     */
    public function handleFileUpload(string $fieldName, string $oldFilePath = '', array $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'webp']): string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return $oldFilePath;
        }

        $file = $_FILES[$fieldName];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            return $oldFilePath;
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $filename = uniqid('file_', true) . '.' . $ext;
        $destination = rtrim($this->uploadDir, '/') . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'assets/files/' . $filename;
        }

        return $oldFilePath;
    }
}
