<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CoursesModel {
    /**
     * جلب وتجهيز بيانات صفحة الدورات واللغة
     */
    public static function getCoursesData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['courses_page']) ? json_decode($settings['courses_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
