<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GuideBlog1Model {
    /**
     * جلب وتجهيز بيانات صفحة دليل الطالب
     */
    public static function getGuideBlog1Data(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['guide_blog_one_page']) ? json_decode($settings['guide_blog_one_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }

    /**
     * ميثود توافقية لتجنب خطأ Call to undefined method
     */
    public static function getGuideData(): array {
        return self::getGuideBlog1Data();
    }
}
