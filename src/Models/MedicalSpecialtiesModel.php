<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class MedicalSpecialtiesModel {
    /**
     * جلب وتجهيز بيانات صفحة التخصصات الطبية
     */
    public static function getMedicalSpecialtiesData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['medical_specialties_page']) ? json_decode($settings['medical_specialties_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
