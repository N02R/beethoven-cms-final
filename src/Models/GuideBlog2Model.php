<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class GuideBlog2Model {
    /**
     * جلب وتجهيز بيانات صفحة المقال/الدليل الثاني
     */
    public static function getGuideBlog2Data(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات بناءً على مفتاح التخزين الخاص بالمقال الثاني
        $data = isset($settings['guide_blog_two_page']) ? json_decode($settings['guide_blog_two_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }

    /**
     * ميثود توافقية لتجنب خطأ Call to undefined method ولتوافق استدعاءات الـ Controller
     */
    public static function getGuideData(): array {
        return self::getGuideBlog2Data();
    }
}
