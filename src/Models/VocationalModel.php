<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class VocationalModel {
    /**
     * جلب وتجهيز بيانات صفحة التدريب المهني (Ausbildung)
     */
    public static function getVocationalData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['vocational_page']) ? json_decode($settings['vocational_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
