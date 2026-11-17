<?php
declare(strict_types=1);

namespace App\Models;

class ArrivalModel {
    private const SUPPORTED_LANGS = ['de', 'en', 'ar'];

    /**
     * جلب وتجهيز بيانات صفحة الاستقبال والوصول مترجمة ومجهزة بالكامل وفق المعيار الهندسي المعتمد
     */
    public static function getArrivalData(?string $lang = null): array {
        $candidateLang = strtolower(trim((string)($lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'de')))));
        $activeLang = in_array($candidateLang, self::SUPPORTED_LANGS, true) ? $candidateLang : 'de';

        $defaultsByLang = [
            'de' => [
                'page_breadcrumb' => 'Flughafentransfer, Transport, Unterkunft & Wohnen',
            ],
            'en' => [
                'page_breadcrumb' => 'Airport Reception, Transportation, Accommodation & Housing',
            ],
            'ar' => [
                'page_breadcrumb' => 'الإستقبال في المطار، المواصلات، الإقامة والسكن',
            ],
        ];
        $fallbackLabels = $defaultsByLang[$activeLang];

        $settings = SiteModel::getSettings();

        // دالة موحدة لاستخراج البيانات باللغة المطلوبة من الكاش المركزي مع تراجع آمن (de -> en -> ar)
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

        // جلب بيانات صفحة الوصول متعددة اللغات
        $arrival_raw = $getLangData('arrival_page', []);

        $page_breadcrumb     = $arrival_raw['page_breadcrumb'] ?? $fallbackLabels['page_breadcrumb'];
        $page_breadcrumb_url = $arrival_raw['page_breadcrumb_url'] ?? '#';
        $hero_img            = $arrival_raw['hero_img'] ?? 'assets/img/education/servicesimg9.png';
        $main_title          = $arrival_raw['main_title'] ?? '';
        $main_desc           = $arrival_raw['main_desc'] ?? '';
        $advice_title        = $arrival_raw['advice_title'] ?? '';
        $advice_desc         = $arrival_raw['advice_desc'] ?? '';
        $tips                = is_array($arrival_raw['tips'] ?? null) ? $arrival_raw['tips'] : [];
        $note_title          = $arrival_raw['note_title'] ?? '';
        $notes               = is_array($arrival_raw['notes'] ?? null) ? $arrival_raw['notes'] : [];

        $arrival_data = [
            'page_breadcrumb'     => $page_breadcrumb,
            'page_breadcrumb_url' => $page_breadcrumb_url,
            'hero_img'            => $hero_img,
            'main_title'          => $main_title,
            'main_desc'           => $main_desc,
            'advice_title'        => $advice_title,
            'advice_desc'         => $advice_desc,
            'tips'                => $tips,
            'note_title'          => $note_title,
            'notes'               => $notes,
        ];

        return [
            'arrival_data' => $arrival_data,
        ];
    }
}
