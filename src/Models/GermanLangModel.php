<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GermanLangModel {
    /**
     * جلب وتجهيز بيانات صفحة دورات اللغة الألمانية
     */
    public static function getGermanLangData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية الخاصة باللغة الألمانية من الإعدادات
        $data = isset($settings['germanlang_page']) ? json_decode($settings['germanlang_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
