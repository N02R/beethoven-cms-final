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

        // جلب كافة الإعدادات باستخدام المودل المركزي SiteModel
        $settings = SiteModel::getSettings();
        
        // دالة مساعدة سريعة لاستخراج النص المناسب حسب اللغة
        $getLangData = function(string $key, $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $decoded = json_decode($settings[$key], true);
            
            // إذا كان الـ JSON مخزناً بشكل يدعم اللغات مثل ['ar' => [...], 'en' => [...]]
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de']))) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $decoded ?? $default;
        };

        $getLangString = function(string $key, string $default = '') use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $decoded = json_decode($settings[$key], true);
            
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de']))) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $settings[$key] ?? $default;
        };

        // جلب بيانات الخدمات وتصفيتها حسب اللغة الحالية
        $services_raw = $getLangData('services', []);
        $services_data = [
            'title' => $services_raw['title'] ?? 'خدماتنا المميزة',
            'desc'  => $services_raw['desc'] ?? '',
            'items' => $services_raw['items'] ?? []
        ];

        // إرجاع مصفوفة منسقة للغة الحالية
        return [
            'hero'                  => $getLangData('hero', []),
            'services_section'      => $services_data, // تم دمج العنوان والوصف والعناصر هنا بشكل أنيق
            'choose_title'          => $getLangString('choose_title', ''),
            'choose_section_desc'   => $getLangString('choose_section_desc', ''),
            'choose_items'          => $getLangData('choose_items', []),
            'reviews_title'         => $getLangString('reviews_title', ''),
            'reviews_items'         => $getLangData('reviews_items', []),
            'faq_title'             => $getLangString('faq_title', ''),
            'faq_items'             => $getLangData('faq_items', []),
        ];
    }
}
