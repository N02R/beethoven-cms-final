<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class HomeModel {
    /**
     * جلب وتجهيز بيانات أقسام الصفحة الرئيسية الخاصة مع دعم متعدد اللغات
     */
    public static function getHomeData(): array {
        // تحديد اللغة الحالية (افتراضياً العربية)
        $lang = $_SESSION['site_lang'] ?? 'ar';

        // جلب كافة الإعدادات باستخدام الموديل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // دالة مساعدة سريعة لاستخراج الهيكل أو النص المناسب حسب اللغة الحالية
        $getLangData = function(string $key, array $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $decoded = json_decode($settings[$key], true);
            
            if (!is_array($decoded)) {
                return $default;
            }

            // استخراج كائن اللغة الحالية، أو العربية كخيار احتياطي (Fallback)
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

        // 3. قسم المميزات (Why Choose Us)
        $choose_raw = $getLangData('choose_items', []);
        $choose_data = [
            'title' => $choose_raw['title'] ?? ($choose_raw['choose_title'] ?? ''),
            'desc'  => $choose_raw['desc'] ?? ($choose_raw['choose_section_desc'] ?? ''),
            'items' => $choose_raw['items'] ?? (is_array($choose_raw) && !isset($choose_raw['title']) ? $choose_raw : [])
        ];

        // 4. قسم الآراء والتقييمات (Reviews)
        $reviews_raw = $getLangData('reviews_items', []);
        $reviews_data = [
            'title' => $reviews_raw['title'] ?? ($reviews_raw['reviews_title'] ?? 'شاهد ماذا يقول عملاؤنا عنا'),
            'desc'  => $reviews_raw['desc'] ?? '',
            'items' => $reviews_raw['items'] ?? (is_array($reviews_raw) && !isset($reviews_raw['title']) ? $reviews_raw : [])
        ];

        // 5. قسم الأسئلة الشائعة (FAQ)
        $faq_raw = $getLangData('faq_items', []);
        $faq_data = [
            'title' => $faq_raw['title'] ?? ($faq_raw['faq_title'] ?? 'الأسئلة الشائعة'),
            'desc'  => $faq_raw['desc'] ?? '',
            'items' => $faq_raw['items'] ?? (is_array($faq_raw) && !isset($faq_raw['title']) ? $faq_raw : [])
        ];

        // 6. قسم الدليل الشامل (Guide)
        $guide_raw = $getLangData('guide_items', []);
        $guide_data = [
            'title' => $guide_raw['title'] ?? ($guide_raw['guide_title'] ?? ''),
            'desc'  => $guide_raw['desc'] ?? ($guide_raw['guide_desc'] ?? ''),
            'items' => $guide_raw['items'] ?? (is_array($guide_raw) && !isset($guide_raw['title']) ? $guide_raw : [])
        ];

        // إرجاع مصفوفة موحدة ومنسقة بدقة للغة الحالية
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
