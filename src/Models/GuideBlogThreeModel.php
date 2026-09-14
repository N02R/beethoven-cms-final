<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GuideBlogThreeModel {
    /**
     * جلب وتجهيز بيانات المقال الثالث في الدليل
     */
    public static function getGuideData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الخاصة بالمقال الثالث من الإعدادات العامة للموقع
        $data = isset($settings['guide_blog3_page']) ? json_decode($settings['guide_blog3_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View والمودلات
        return is_array($data) ? $data : [];
    }
}
