<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class FoundationModel {
    /**
     * جلب وتجهيز بيانات صفحة الدورة التأسيسية
     */
    public static function getFoundationData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['foundation_page']) ? json_decode($settings['foundation_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
