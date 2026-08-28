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
        
        // التحقق من المفتاح الموحد الجديد foundation_page مع دعم التوافق مع المفتاح القديم إن وجد
        $rawJson = $settings['foundation_page'] ?? ($settings['studienkolleg_page'] ?? null);
        
        $data = $rawJson ? json_decode($rawJson, true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
