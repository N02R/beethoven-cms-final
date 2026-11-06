<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class EducationModel {
    /**
     * جلب وتجهيز بيانات أقسام صفحة التعليم مترجمة ومجهزة بالكامل حسب لغة الزائر الحالية
     */
    public static function getEducationData(): array {
        $lang = $_SESSION['site_lang'] ?? 'ar';
        $settings = SiteModel::getSettings();
        
        // دالة استخراج وتوطين البيانات حسب لغة الزائر بالمعيار الذهبي الموحد
        $getLangData = function(string $key, array $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (!is_array($decoded)) {
                return $default;
            }

            if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? ($decoded['de'] ?? $default));
            }
            
            return $decoded;
        };

        // 1. قسم الهيرو (Edu Hero)
        $edu_hero = $getLangData('edu_hero', []);

        // 2. قسم لماذا الدراسة (Edu Why)
        $edu_why = $getLangData('edu_why', []);
        $why_items = $edu_why['items'] ?? [];
        if (!is_array($why_items)) {
            $why_items = [];
        }

        // 3. قسم الخطوات الزمنية (Edu Timeline)
        $edu_timeline = $getLangData('edu_timeline', []);
        $timeline_steps = $edu_timeline['steps'] ?? ($edu_timeline['items'] ?? []);
        if (!is_array($timeline_steps)) {
            $timeline_steps = [];
        }

        // 4. قسم خدمات التعليم (Edu Services)
        $edu_services = $getLangData('edu_services', []);
        $services_items = $edu_services['items'] ?? [];
        if (!is_array($services_items)) {
            $services_items = [];
        }

        // إرجاع مصفوفة منسقة وشاملة تغطي كافة المتغيرات بمرونة تامة
        return [
            'edu_hero'             => $edu_hero,
            'edu_why_section'      => $edu_why,
            'edu_why_title'        => $edu_why['title'] ?? 'لماذا الدراسة في ألمانيا؟',
            'edu_why_desc'         => $edu_why['desc'] ?? '',
            'edu_why_items'        => $why_items,
            'edu_timeline_section' => $edu_timeline,
            'edu_timeline_title'   => $edu_timeline['title'] ?? 'خطوات الدراسة في ألمانيا',
            'edu_timeline_desc'    => $edu_timeline['desc'] ?? '',
            'edu_timeline_steps'   => $timeline_steps,
            'edu_services_section' => $edu_services,
            'edu_services_title'   => $edu_services['title'] ?? ($edu_services['section_title'] ?? 'خدمات التعليم العالي والجامعي'),
            'edu_services_desc'    => $edu_services['desc'] ?? ($edu_services['section_subtitle'] ?? ''),
            'edu_services_items'   => $services_items,
        ];
    }
}
