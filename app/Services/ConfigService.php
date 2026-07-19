<?php
namespace App\Services;

class ConfigService {
    private string $filePath;

    public function __construct() {
        // تحديد المسار الآمن (خارج مجلد public)
        $this->filePath = __DIR__ . '/../../storage/announcement_config.json';
    }

    /**
     * جلب البيانات بأمان
     */
    public function getData(): array {
        if (!file_exists($this->filePath)) {
            throw new \Exception("System configuration missing.");
        }
        
        $json = file_get_contents($this->filePath);
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Configuration format error.");
        }
        
        return $data;
    }

    /**
     * حفظ البيانات بأمان مع منع التداخل (Concurrency Lock)
     */
    public function saveData(array $data): bool {
        // تحويل المصفوفة إلى JSON مدعوم باللغة العربية ومنسق
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        if ($json === false) {
            throw new \Exception("فشل في تشفير البيانات إلى JSON.");
        }

        // استخدام LOCK_EX لمنع كتابة ملفين في نفس اللحظة (Thread Safety)
        $result = file_put_contents($this->filePath, $json, LOCK_EX);
        
        if ($result === false) {
            // رسالة خطأ واضحة في حال كانت تصاريح المجلد (Permissions) غير صحيحة
            throw new \Exception("فشل في الكتابة على الملف. يرجى التحقق من تصاريح مجلد storage.");
        }

        return true;
    }
}
