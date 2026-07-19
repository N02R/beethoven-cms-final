<?php
namespace App\Controllers;

use App\Services\UploadService;
use App\Services\ConfigService;

class ConfigController {
    public function updateLogo($file) {
        try {
            // 1. رفع الصورة
            $uploader = new UploadService();
            $newFileName = $uploader->upload($file, __DIR__ . '/../../public/uploads');

            // 2. تحديث ملف الإعدادات
            $configService = new ConfigService();
            $data = $configService->getData();
            
            // تحديث المسار
            $data['site_logo_path'] = 'uploads/' . $newFileName;

            // 3. حفظ الـ JSON الجديد
            file_put_contents(
                __DIR__ . '/../../storage/announcement_config.json', 
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            return "تم تحديث الشعار بنجاح!";
        } catch (\Exception $e) {
            return "خطأ: " . $e->getMessage();
        }
    }
}
