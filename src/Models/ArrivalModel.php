<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class ArrivalModel {
    /**
     * جلب وتجهيز بيانات صفحة الاستقبال والوصول
     */
    public static function getArrivalData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['arrival_page']) ? json_decode($settings['arrival_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
