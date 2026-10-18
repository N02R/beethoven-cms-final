<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class EducationModel {
    /**
     * جلب وتجهيز بيانات أقسام صفحة التعليم الخاصة فقط
     */
    public static function getEducationData(): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // تجهيز خطوات الـ Timeline وفرزها تصاعدياً حسب حقل order
        $timeline_steps = isset($settings['edu_timeline_steps']) ? json_decode($settings['edu_timeline_steps'], true) : [];
        if (is_array($timeline_steps)) {
            usort($timeline_steps, function($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });
        }

        // إرجاع مصفوفة منسقة تحتوي على أقسام صفحة التعليم فقط
        return [
            'edu_hero'           => isset($settings['edu_hero']) ? json_decode($settings['edu_hero'], true) : [],
            'edu_why_title'      => $settings['edu_why_title'] ?? '',
            'edu_why_desc'       => $settings['edu_why_desc'] ?? '',
            'edu_why_items'      => isset($settings['edu_why_items']) ? json_decode($settings['edu_why_items'], true) : [],
            'edu_timeline_title' => $settings['edu_timeline_title'] ?? '',
            'edu_timeline_desc'  => $settings['edu_timeline_desc'] ?? '',
            'edu_timeline_steps' => $timeline_steps,
            'edu_services_title' => $settings['edu_services_title'] ?? '',
            'edu_services_desc'  => $settings['edu_services_desc'] ?? '',
            'edu_services_items' => isset($settings['edu_services_items']) ? json_decode($settings['edu_services_items'], true) : [],
        ];
    }
}
