<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class ServiceCostModel {
    /**
     * جلب وتجهيز بيانات صفحة قائمة أسعار الخدمات
     */
    public static function getServiceCostData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['pricelist_page']) ? json_decode($settings['pricelist_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
