<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CheckModel {
    /**
     * جلب وتجهيز بيانات صفحة الفحص والمراجعة
     */
    public static function getCheckData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات الخاصة بصفحة الفحص
        $data = isset($settings['check_page']) ? json_decode($settings['check_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
