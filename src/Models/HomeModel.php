<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class HomeModel {
    public static function getHomeData(): array {
        $lang = $_SESSION['site_lang'] ?? 'ar';
        $settings = SiteModel::getSettings();
        $globalData = SiteModel::getGlobalData();
        
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
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $decoded;
        };

        // 1. قسم الهيرو (Hero)
        $hero_raw = $getLangData('hero', []);

        // 2. قسم الخدمات (Services)
        $services_raw = $getLangData('services', []);
        $services_data = [
            'title' => $services_raw['title'] ?? ($services_raw['section_title'] ?? 'خدماتنا المميزة'),
            'desc'  => $services_raw['desc'] ?? ($services_raw['section_subtitle'] ?? ''),
            'items' => $services_raw['items'] ?? []
        ];

        // 3. قسم المميزات (Choose Us)
        $choose_raw = $getLangData('choose', []);
        $choose_data = [
            'title' => $choose_raw['section_title'] ?? ($choose_raw['title'] ?? 'ما الذي يميز بيتهوفن سيتي'),
            'desc'  => $choose_raw['section_subtitle'] ?? ($choose_raw['desc'] ?? ''),
            'items' => $choose_raw['items'] ?? []
        ];

        // 4. قسم الآراء والتقييمات (Reviews Videos)
        $reviews_raw = $getLangData('reviews_items', []);
        $reviews_data = [
            'title' => is_string($reviews_raw['title'] ?? null) ? $reviews_raw['title'] : 'شاهد ماذا يقول عملاؤنا عنا',
            'items' => is_array($reviews_raw['items'] ?? null) ? $reviews_raw['items'] : []
        ];

        // 5. قسم الأسئلة الشائعة (FAQ)
        $faq_raw = $getLangData('faq_items', []);
        $faq_data = [
            'title' => $faq_raw['title'] ?? ($faq_raw['faq_title'] ?? 'الأسئلة الشائعة'),
            'desc'  => $faq_raw['desc'] ?? '',
            'items' => $faq_raw['items'] ?? (is_array($faq_raw) && !isset($faq_raw['title']) ? $faq_raw : [])
        ];

        // 6. قسم الدليل الشامل (Guide) - موحد ومستمد مباشرة من الـ SiteModel المركزي لتجنب أي تكرار
        $guide_data = [
            'title' => $globalData['guide_title'],
            'desc'  => $globalData['guide_desc'],
            'items' => $globalData['guide_items']
        ];

        return [
            'hero'                  => $hero_raw,
            'services_section'      => $services_data,
            'choose_section'        => $choose_data,
            'choose_title'          => $choose_data['title'],
            'choose_section_desc'   => $choose_data['desc'],
            'choose_items'          => $choose_data['items'],
            'reviews_section'       => $reviews_data,
            'reviews_title'         => $reviews_data['title'],
            'reviews_items'         => $reviews_data['items'],
            'faq_section'           => $faq_data,
            'faq_title'             => $faq_data['title'],
            'faq_items'             => $faq_data['items'],
            'guide_section'         => $guide_data,
            'guide_title'           => $guide_data['title'],
            'guide_items'           => $guide_data['items']
        ];
    }
}
