<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class JobModel {
    /**
     * جلب وتجهيز بيانات أقسام صفحة التدريب والتوظيف الخاصة فقط
     */
    public static function getJobData(): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // تجهيز خطوات الـ Timeline وفرزها تصاعدياً حسب حقل order
        $timeline_steps = isset($settings['job_timeline_steps']) ? json_decode($settings['job_timeline_steps'], true) : [];
        if (is_array($timeline_steps)) {
            usort($timeline_steps, function($a, $b) {
                return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
            });
        }

        // إرجاع مصفوفة منسقة تحتوي على أقسام صفحة التدريب والتوظيف فقط
        return [
            'job_hero'           => isset($settings['job_hero']) ? json_decode($settings['job_hero'], true) : [],
            'job_why_title'      => $settings['job_why_title'] ?? '',
            'job_why_desc'       => $settings['job_why_desc'] ?? '',
            'job_why_items'      => isset($settings['job_why_items']) ? json_decode($settings['job_why_items'], true) : [],
            'job_program_title'  => $settings['job_program_title'] ?? '',
            'job_program_desc'   => $settings['job_program_desc'] ?? '',
            'job_program_types'  => isset($settings['job_program_types']) ? json_decode($settings['job_program_types'], true) : [],
            'job_timeline_title' => $settings['job_timeline_title'] ?? '',
            'job_timeline_desc'  => $settings['job_timeline_desc'] ?? '',
            'job_timeline_steps' => $timeline_steps,
            'job_services_title' => $settings['job_services_title'] ?? '',
            'job_services_desc'  => $settings['job_services_desc'] ?? '',
            'job_services_items' => isset($settings['job_services_items']) ? json_decode($settings['job_services_items'], true) : [],
        ];
    }
}
