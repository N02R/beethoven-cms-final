<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GuideBlogTwoModel {
    /**
     * جلب وتجهيز بيانات المقال الثاني في الدليل
     */
    public static function getGuideData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الخاصة بالمقال الثاني من الإعدادات العامة للموقع
        $data = isset($settings['guide_blog2_page']) ? json_decode($settings['guide_blog2_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View والمودلات
        return is_array($data) ? $data : [];
    }
}
