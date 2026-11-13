<?php
declare(strict_types=1);

namespace App\Models;

class JobModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات أقسام صفحة التدريب والتوظيف مترجمة ومجهزة بالكامل وفق المعيار الهندسي المعتمد
     */
    public static function getJobData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'why_title'      => 'Warum Ausbildung und Arbeit in Deutschland?',
                'prog_title'     => 'Ausbildungs- und Arbeitsprogramme',
                'timeline_title' => 'Schritte zur Ausbildung und Arbeit in Deutschland',
                'services_title' => 'Arbeits- & Ausbildungsservices',
            ],
            'en' => [
                'why_title'      => 'Why Training & Employment in Germany?',
                'prog_title'     => 'Vocational Training & Employment Programs',
                'timeline_title' => 'Steps to Employment & Training in Germany',
                'services_title' => 'Employment Services',
            ],
            'ar' => [
                'why_title'      => 'لماذا التدريب والتوظيف في ألمانيا؟',
                'prog_title'     => 'برامج التدريب المهني والتوظيف',
                'timeline_title' => 'خطوات التوظيف والتدريب في ألمانيا',
                'services_title' => 'خدمات التوظيف',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $settings = SiteModel::getSettings();

        // دالة موحدة لاستخراج القسم باللغة المطلوبة من الكاش المركزي مع تراجع آمن (de -> en -> ar)
        $getLangData = static function (string $key, array $default = []) use ($settings, $activeLang): array {
            if (!isset($settings[$key])) {
                return $default;
            }

            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

            if (!is_array($decoded)) {
                return $default;
            }

            if (isset($decoded[$activeLang]) && is_array($decoded[$activeLang])) {
                return $decoded[$activeLang];
            }
            if (isset($decoded['de']) && is_array($decoded['de'])) {
                return $decoded['de'];
            }
            if (isset($decoded['en']) && is_array($decoded['en'])) {
                return $decoded['en'];
            }
            if (isset($decoded['ar']) && is_array($decoded['ar'])) {
                return $decoded['ar'];
            }

            return $decoded;
        };

        // 1. قسم الهيرو (Job Hero)
        $job_hero = $getLangData('job_hero', []);

        // 2. قسم لماذا التدريب والتوظيف (Job Why)
        $job_why_raw = $getLangData('job_why', []);
        $why_items = is_array($job_why_raw['items'] ?? null) ? $job_why_raw['items'] : [];
        $job_why = [
            'title' => $job_why_raw['title'] ?? $fallbackLabels['why_title'],
            'desc'  => $job_why_raw['desc'] ?? '',
            'items' => $why_items,
        ];

        // 3. قسم البرامج وأنواع التدريب (Job Programs)
        $job_programs_raw = $getLangData('job_programs', []);
        $program_items = is_array($job_programs_raw['items'] ?? null) ? $job_programs_raw['items'] : [];
        $job_programs = [
            'title' => $job_programs_raw['title'] ?? $fallbackLabels['prog_title'],
            'desc'  => $job_programs_raw['desc'] ?? '',
            'items' => $program_items,
        ];

        // 4. قسم خطوات التوظيف (Job Timeline)
        $job_timeline_raw = $getLangData('job_timeline', []);
        $timeline_steps = is_array($job_timeline_raw['steps'] ?? null)
            ? $job_timeline_raw['steps']
            : (is_array($job_timeline_raw['items'] ?? null) ? $job_timeline_raw['items'] : []);

        usort($timeline_steps, static function (mixed $a, mixed $b): int {
            $orderA = is_array($a) ? (int)($a['order'] ?? 0) : 0;
            $orderB = is_array($b) ? (int)($b['order'] ?? 0) : 0;
            return $orderA <=> $orderB;
        });

        $job_timeline = [
            'title' => $job_timeline_raw['title'] ?? $fallbackLabels['timeline_title'],
            'desc'  => $job_timeline_raw['desc'] ?? '',
            'steps' => $timeline_steps,
            'items' => $timeline_steps,
        ];

        // 5. قسم خدمات التوظيف (Job Services)
        $job_services_raw = $getLangData('job_services', []);
        $services_items = is_array($job_services_raw['items'] ?? null) ? $job_services_raw['items'] : [];
        $job_services = [
            'title'            => $job_services_raw['title'] ?? ($job_services_raw['section_title'] ?? $fallbackLabels['services_title']),
            'section_title'    => $job_services_raw['section_title'] ?? ($job_services_raw['title'] ?? $fallbackLabels['services_title']),
            'desc'             => $job_services_raw['desc'] ?? ($job_services_raw['section_subtitle'] ?? ''),
            'section_subtitle' => $job_services_raw['section_subtitle'] ?? ($job_services_raw['desc'] ?? ''),
            'items'            => $services_items,
        ];

        return [
            'job_hero'             => $job_hero,
            'job_why'              => $job_why,
            'job_why_section'      => $job_why,
            'job_why_title'        => $job_why['title'],
            'job_why_desc'         => $job_why['desc'],
            'job_why_items'        => $job_why['items'],
            'job_programs'         => $job_programs,
            'job_programs_section' => $job_programs,
            'job_program_title'    => $job_programs['title'],
            'job_program_desc'     => $job_programs['desc'],
            'job_program_types'    => $job_programs['items'],
            'job_timeline'         => $job_timeline,
            'job_timeline_section' => $job_timeline,
            'job_timeline_title'   => $job_timeline['title'],
            'job_timeline_desc'    => $job_timeline['desc'],
            'job_timeline_steps'   => $job_timeline['steps'],
            'job_services'         => $job_services,
            'job_services_section' => $job_services,
            'job_services_title'   => $job_services['title'],
            'job_services_desc'    => $job_services['desc'],
            'job_services_items'   => $job_services['items'],
        ];
    }
}
