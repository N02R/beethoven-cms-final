<?php
declare(strict_types=1);

namespace App\Models;

class EducationModel
{
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات أقسام صفحة التعليم مترجمة ومجهزة بالكامل وفق المعيار الهندسي المعتمد
     */
    public static function getEducationData(?string $lang = null): array
    {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'why_title'      => 'Warum in Deutschland studieren?',
                'timeline_title' => 'Schritte zum Studium in Deutschland',
                'services_title' => 'Bildungsdienstleistungen',
            ],
            'en' => [
                'why_title'      => 'Why Study in Germany?',
                'timeline_title' => 'Steps to Study in Germany',
                'services_title' => 'Education Services',
            ],
            'ar' => [
                'why_title'      => 'لماذا الدراسة في ألمانيا؟',
                'timeline_title' => 'خطوات الدراسة في ألمانيا',
                'services_title' => 'خدمات التعليم',
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

        // 1. قسم الهيرو (Edu Hero)
        $edu_hero = $getLangData('edu_hero', []);

        // 2. قسم لماذا الدراسة (Edu Why)
        $edu_why_raw = $getLangData('edu_why', []);
        $why_items = is_array($edu_why_raw['items'] ?? null) ? $edu_why_raw['items'] : [];
        $edu_why = [
            'title' => $edu_why_raw['title'] ?? $fallbackLabels['why_title'],
            'desc'  => $edu_why_raw['desc'] ?? '',
            'items' => $why_items,
        ];

        // 3. قسم الخطوات الزمنية (Edu Timeline)
        $edu_timeline_raw = $getLangData('edu_timeline', []);
        $timeline_steps = is_array($edu_timeline_raw['steps'] ?? null)
            ? $edu_timeline_raw['steps']
            : (is_array($edu_timeline_raw['items'] ?? null) ? $edu_timeline_raw['items'] : []);
        $edu_timeline = [
            'title' => $edu_timeline_raw['title'] ?? $fallbackLabels['timeline_title'],
            'desc'  => $edu_timeline_raw['desc'] ?? '',
            'steps' => $timeline_steps,
            'items' => $timeline_steps,
        ];

        // 4. قسم خدمات التعليم (Edu Services)
        $edu_services_raw = $getLangData('edu_services', []);
        $services_items = is_array($edu_services_raw['items'] ?? null) ? $edu_services_raw['items'] : [];
        $edu_services = [
            'title'            => $edu_services_raw['title'] ?? ($edu_services_raw['section_title'] ?? $fallbackLabels['services_title']),
            'section_title'    => $edu_services_raw['section_title'] ?? ($edu_services_raw['title'] ?? $fallbackLabels['services_title']),
            'desc'             => $edu_services_raw['desc'] ?? ($edu_services_raw['section_subtitle'] ?? ''),
            'section_subtitle' => $edu_services_raw['section_subtitle'] ?? ($edu_services_raw['desc'] ?? ''),
            'items'            => $services_items,
        ];

        return [
            'edu_hero'             => $edu_hero,
            'edu_why'              => $edu_why,
            'edu_why_section'      => $edu_why,
            'edu_why_title'        => $edu_why['title'],
            'edu_why_desc'         => $edu_why['desc'],
            'edu_why_items'        => $edu_why['items'],
            'edu_timeline'         => $edu_timeline,
            'edu_timeline_section' => $edu_timeline,
            'edu_timeline_title'   => $edu_timeline['title'],
            'edu_timeline_desc'    => $edu_timeline['desc'],
            'edu_timeline_steps'   => $edu_timeline['steps'],
            'edu_services'         => $edu_services,
            'edu_services_section' => $edu_services,
            'edu_services_title'   => $edu_services['title'],
            'edu_services_desc'    => $edu_services['desc'],
            'edu_services_items'   => $edu_services['items'],
        ];
    }
}
