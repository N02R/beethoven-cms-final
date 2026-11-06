<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class JobModel {
    /**
     * جلب وتجهيز بيانات أقسام صفحة التدريب والتوظيف مترجمة ومجهزة بالكامل حسب لغة الزائر الحالية
     */
    public static function getJobData(): array {
        $lang = $_SESSION['site_lang'] ?? 'de';
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
                return $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['ar'] ?? $default));
            }
            
            return $decoded;
        };

        // 1. قسم الهيرو (Job Hero)
        $job_hero = $getLangData('job_hero', []);

        // 2. قسم لماذا التدريب والتوظيف (Job Why)
        $job_why = $getLangData('job_why', []);
        $why_items = $job_why['items'] ?? [];
        if (!is_array($why_items)) {
            $why_items = [];
        }

        // 3. قسم البرامج وأنواع التدريب (Job Programs)
        $job_programs = $getLangData('job_programs', []);
        $program_items = $job_programs['items'] ?? [];
        if (!is_array($program_items)) {
            $program_items = [];
        }

        // 4. قسم خطوات التوظيف (Job Timeline)
        $job_timeline = $getLangData('job_timeline', []);
        $timeline_steps = $job_timeline['steps'] ?? ($job_timeline['items'] ?? []);
        if (!is_array($timeline_steps)) {
            $timeline_steps = [];
        }

        // فرز خطوات الـ Timeline تصاعدياً حسب حقل order إن وجد
        usort($timeline_steps, function($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        // 5. قسم خدمات التوظيف (Job Services)
        $job_services = $getLangData('job_services', []);
        $services_items = $job_services['items'] ?? [];
        if (!is_array($services_items)) {
            $services_items = [];
        }

        // إرجاع مصفوفة منسقة وشاملة تغطي كافة المتغيرات بمرونة تامة
        return [
            'job_hero'             => $job_hero,
            'job_why_section'      => $job_why,
            'job_why_title'        => $job_why['title'] ?? 'لماذا التدريب والتوظيف في ألمانيا؟',
            'job_why_desc'         => $job_why['desc'] ?? '',
            'job_why_items'        => $why_items,
            'job_programs_section' => $job_programs,
            'job_program_title'    => $job_programs['title'] ?? 'برامج التدريب المهني والتوظيف',
            'job_program_desc'     => $job_programs['desc'] ?? '',
            'job_program_types'    => $program_items,
            'job_timeline_section' => $job_timeline,
            'job_timeline_title'   => $job_timeline['title'] ?? 'خطوات التوظيف والتدريب في ألمانيا',
            'job_timeline_desc'    => $job_timeline['desc'] ?? '',
            'job_timeline_steps'   => $timeline_steps,
            'job_services_section' => $job_services,
            'job_services_title'   => $job_services['title'] ?? ($job_services['section_title'] ?? 'خدمات التوظيف المهني'),
            'job_services_desc'    => $job_services['desc'] ?? ($job_services['section_subtitle'] ?? ''),
            'job_services_items'   => $services_items,
        ];
    }
}
