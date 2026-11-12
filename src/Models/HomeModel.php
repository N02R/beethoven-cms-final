<?php
declare(strict_types=1);

namespace App\Models;

class HomeModel {
    public static function getHomeData(?string $langOverride = null): array {
        $lang       = SiteModel::resolveLang($langOverride);
        $settings   = SiteModel::getSettings();
        $globalData = SiteModel::getGlobalData($lang);
        
        $getLangData = function(string $key, array $default = []) use ($settings, $lang): array {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (!is_array($decoded)) {
                return $default;
            }

            if (isset($decoded['de']) || isset($decoded['en']) || isset($decoded['ar'])) {
                $langBlock = $decoded[$lang] ?? ($decoded['de'] ?? ($decoded['en'] ?? ($decoded['ar'] ?? $default)));
                return is_array($langBlock) ? $langBlock : $default;
            }
            
            return $decoded;
        };

        // 1. قسم الهيرو (Hero)
        $hero_data = $getLangData('hero', []);

        // 2. قسم الخدمات (Services)
        $services_raw  = $getLangData('services', []);
        $services_data = [
            'section_title'    => $services_raw['section_title'] ?? ($services_raw['title'] ?? ''),
            'section_subtitle' => $services_raw['section_subtitle'] ?? ($services_raw['desc'] ?? ''),
            'items'            => is_array($services_raw['items'] ?? null) ? $services_raw['items'] : []
        ];

        // 3. قسم المميزات (Choose Us)
        $choose_raw  = $getLangData('choose', []);
        $choose_data = [
            'section_title'    => $choose_raw['section_title'] ?? ($choose_raw['title'] ?? ''),
            'section_subtitle' => $choose_raw['section_subtitle'] ?? ($choose_raw['desc'] ?? ''),
            'items'            => is_array($choose_raw['items'] ?? null) ? $choose_raw['items'] : []
        ];

        // 4. قسم الآراء والتقييمات (Reviews Videos)
        $reviews_raw  = $getLangData('reviews_items', []);
        $reviews_data = [
            'title' => is_string($reviews_raw['title'] ?? null) ? $reviews_raw['title'] : '',
            'items' => is_array($reviews_raw['items'] ?? null) ? $reviews_raw['items'] : []
        ];

        // 5. قسم الأسئلة الشائعة (FAQ) - يقرأ من المفتاح الموحد 'faq' مع تراجع آمن إلى 'faq_items'
        $faq_raw = $getLangData('faq', []);
        if (empty($faq_raw)) {
            $faq_raw = $getLangData('faq_items', []);
        }
        $faq_data = [
            'title' => $faq_raw['title'] ?? ($faq_raw['faq_title'] ?? $globalData['faq_title']),
            'desc'  => $faq_raw['desc'] ?? '',
            'items' => is_array($faq_raw['items'] ?? null)
                ? $faq_raw['items']
                : (!empty($globalData['faq_items']) ? $globalData['faq_items'] : [])
        ];

        // 6. قسم الدليل الشامل (Guide) - مستمد مباشرة من SiteModel المركزي
        $guide_data = [
            'title'         => $globalData['guide_title'],
            'desc'          => $globalData['guide_desc'],
            'read_more_btn' => $globalData['read_more_btn'],
            'items'         => $globalData['guide_items']
        ];

        return [
            'hero'                => $hero_data,
            'services'            => $services_data,
            'services_section'    => $services_data,
            'choose'              => $choose_data,
            'choose_section'      => $choose_data,
            'choose_title'        => $choose_data['section_title'],
            'choose_section_desc' => $choose_data['section_subtitle'],
            'choose_items'        => $choose_data['items'],
            'reviews_items'       => $reviews_data,
            'reviews_section'     => $reviews_data,
            'reviews_title'       => $reviews_data['title'],
            'faq_section'         => $faq_data,
            'faq_title'           => $faq_data['title'],
            'faq_items'           => $faq_data['items'],
            'guide_section'       => $guide_data,
            'guide_title'         => $guide_data['title'],
            'guide_desc'          => $guide_data['desc'],
            'guide_items'         => $guide_data['items']
        ];
    }
}
