<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class MotivationModel {
    /**
     * جلب وتجهيز بيانات صفحة خطاب الدافع
     */
    public static function getMotivationData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['motivation_page']) ? json_decode($settings['motivation_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
