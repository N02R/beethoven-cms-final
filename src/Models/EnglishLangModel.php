<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class EnglishLangModel {
    /**
     * جلب وتجهيز بيانات صفحة البرامج الدراسية باللغة الإنجليزية
     */
    public static function getEnglishLangData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية الخاصة بالبرامج الإنجليزية من الإعدادات
        $data = isset($settings['englishlang_page']) ? json_decode($settings['englishlang_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
