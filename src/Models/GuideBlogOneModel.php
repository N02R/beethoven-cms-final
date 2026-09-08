<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GuideBlogOneModel {
    /**
     * جلب وتجهيز بيانات المقال الأول في الدليل (لماذا الدراسة في ألمانيا)
     */
    public static function getGuideData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الخاصة بالمقال الأول من الإعدادات العامة للموقع
        $data = isset($settings['guide_blog1_page']) ? json_decode($settings['guide_blog1_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View والمودلات
        return is_array($data) ? $data : [];
    }
}
