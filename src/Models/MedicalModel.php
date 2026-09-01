<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class MedicalModel {
    /**
     * جلب وتجهيز بيانات صفحة التدريب الطبي (باقة التدريب الطبي)
     */
    public static function getMedicalData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['medical_page']) ? json_decode($settings['medical_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
