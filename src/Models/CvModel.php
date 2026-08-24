<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CvModel {
    /**
     * جلب وتجهيز بيانات صفحة السيرة الذاتية (CV)
     */
    public static function getCvData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية الخاصة بالـ CV من الإعدادات
        $data = isset($settings['cv_page']) ? json_decode($settings['cv_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
