<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class AboutModel {
    /**
     * جلب وتجهيز بيانات صفحة "من نحن" والأقسام التابعة لها بمعيار موحد ومتعدد اللغات
     */
    public static function getAboutData(?string $lang = null): array {
        // جلب كافة الإعدادات باستخدام المودل المركزي
        $settings = SiteModel::getSettings();
        
        // تحديد اللغة النشطة (إن لم تُمرر، نجلبها من الدالة العامة أو نعتمد العربية)
        $currentLang = $lang ?? (function_exists('get_current_lang') ? get_current_lang() : 'ar');

        // دالة مساعدة لفك تشفير الـ JSON واستخراج بيانات اللغة النشطة أو التراجع للغة ar
        $getLocalized = function(string $key, $default = []) use ($settings, $currentLang) {
            $raw = $settings[$key] ?? null;
            if (empty($raw)) return $default;
            
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (!is_array($decoded)) return $default;

            // إذا كانت البيانات مخزنة بهيكل اللغات (ar, en, de)
            if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                return $decoded[$currentLang] ?? ($decoded['ar'] ?? $default);
            }

            // إذا كانت مصفوفة عادية وليست متعددة اللغات
            return $decoded;
        };

        // فك تشفير البيانات العامة مع دعم اللغات
        $aboutAllLangs = isset($settings['about_section']) ? (is_string($settings['about_section']) ? json_decode($settings['about_section'], true) : $settings['about_section']) : [];
        $aboutSection = is_array($aboutAllLangs) ? ($aboutAllLangs[$currentLang] ?? ($aboutAllLangs['ar'] ?? [])) : [];

        $teamAllLangs = isset($settings['team']) ? (is_string($settings['team']) ? json_decode($settings['team'], true) : $settings['team']) : [];
        $teamData = is_array($teamAllLangs) ? ($teamAllLangs[$currentLang] ?? ($teamAllLangs['ar'] ?? [])) : [];

        $partnersAllLangs = isset($settings['partners']) ? (is_string($settings['partners']) ? json_decode($settings['partners'], true) : $settings['partners']) : [];
        $partnersData = is_array($partnersAllLangs) ? ($partnersAllLangs[$currentLang] ?? ($partnersAllLangs['ar'] ?? [])) : [];

        return [
            'about_section'  => $aboutSection,
            'team_title'     => $teamData['title'] ?? ($settings['team_title'] ?? 'فريق العمل'),
            'team_desc'      => $teamData['desc'] ?? ($settings['team_desc'] ?? ''),
            'team_items'     => $teamData['items'] ?? (isset($settings['team_items']) ? json_decode($settings['team_items'], true) : []),
            'about_counts'   => isset($settings['about_counts']) ? json_decode($settings['about_counts'], true) : [],
            'partners_title' => $partnersData['title'] ?? ($settings['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا'),
            'partners_items' => $partnersData['items'] ?? (isset($settings['partners_items']) ? json_decode($settings['partners_items'], true) : []),
        ];
    }
}
