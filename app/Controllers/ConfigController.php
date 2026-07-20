<?php
// /app/Controllers/ConfigController.php

namespace App\Controllers;

use App\Services\UploadService;
use App\Services\ConfigService;
use \Exception;

class ConfigController {
    
    public function updateLogo($file) {
        try {
            // 1. رفع الصورة باستخدام خدمة الرفع الآمن
            $uploader = new UploadService();
            $newFileName = $uploader->upload($file, __DIR__ . '/../../public/uploads');

            // 2. جلب وتحديث الإعدادات باستخدام ConfigService (التي تضمن القفل الآمن LOCK_EX)
            $configService = new ConfigService();
            $data = $configService->getData();
            
            // تحديث مسار الشعار الجديد
            $data['site_logo_path'] = 'uploads/' . $newFileName;

            // 3. الحفظ الآمن للبيانات عبر الـ ConfigService لضمان تطابق مسارات وحماية ملف الـ JSON
            $configService->saveData($data);

            return "تم تحديث الشعار وحفظ الإعدادات بنجاح!";
            
        } catch (Exception $e) {
            // إعادة رمي الخطأ ليتم التقاطه ومعالجته بشكل موحد في الـ API
            throw new Exception("خطأ في المعالجة: " . $e->getMessage());
        }
    }
}
