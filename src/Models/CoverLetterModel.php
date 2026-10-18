<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class CoverLetterModel {
    /**
     * جلب وتجهيز بيانات صفحة خطاب الطلب
     */
    public static function getCoverLetterData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات
        $data = isset($settings['coverletter_page']) ? json_decode($settings['coverletter_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
