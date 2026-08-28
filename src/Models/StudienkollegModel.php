<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class StudienkollegModel {
    /**
     * جلب وتجهيز بيانات صفحة الدورة التأسيسية / السنة التحضيرية
     */
    public static function getStudienkollegData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['studienkolleg_page']) ? json_decode($settings['studienkolleg_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
