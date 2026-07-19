<?php
// app/Services/ConfigService.php
namespace App\Services;

class ConfigService {
    private string $filePath;

    public function __construct() {
        // المسار آمن: خارج المجلد العام
        $this->filePath = __DIR__ . '/../../storage/announcement_config.json';
    }

    /**
     * جلب الإعدادات مع معالجة الأخطاء (Error Handling)
     */
    public function getData(): array {
        if (!file_exists($this->filePath)) {
            // هنا نستخدم Exception وليس إخراج نص مباشرة
            throw new \Exception("System configuration missing.");
        }
        
        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Configuration format error.");
        }
        
        return $data;
    }
}
