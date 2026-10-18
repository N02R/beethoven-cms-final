<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GeneralVisaModel {
    /**
     * جلب وتجهيز بيانات صفحة متطلبات التأشيرة
     */
    public static function getVisaData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['general_visa_page']) ? json_decode($settings['general_visa_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
