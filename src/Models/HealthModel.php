<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class HealthModel {
    /**
     * جلب وتجهيز بيانات صفحة التأمين الصحي
     */
    public static function getHealthData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات تحت مفتاح health_page
        $data = isset($settings['health_page']) ? json_decode($settings['health_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
