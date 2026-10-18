<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class LivingCostModel {
    /**
     * جلب وتجهيز بيانات صفحة تكلفة المعيشة
     */
    public static function getLivingCostData(): array {
        $settings = SiteModel::getSettings();
        
        // جلب البيانات الأساسية من الإعدادات عبر المفتاح المطابق في جدول الإعدادات
        $data = isset($settings['living_cost_page']) ? json_decode($settings['living_cost_page'], true) : [];

        // التأكد من أن المخرجات مصفوفة لتجنب أخطاء الـ View
        return is_array($data) ? $data : [];
    }
}
