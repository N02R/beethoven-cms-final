<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class OffersModel {
    /**
     * جلب وتجهيز بيانات صفحة العروض والاتفاقيات
     */
    public static function getOffersData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['offers_page']) ? json_decode($settings['offers_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
