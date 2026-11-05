<?php
declare(strict_types=1);

namespace App\Models;

use App\Models\SiteModel;

class AboutModel {
    /**
     * جلب وتجهيز بيانات صفحة "من نحن" والأقسام التابعة لها وفق المعيار المعتمد
     */
    public static function getAboutData(?string $lang = null): array {
        $lang = $lang ?? ($_SESSION['site_lang'] ?? (function_exists('get_current_lang') ? get_current_lang() : 'ar'));
        $settings = SiteModel::getSettings();
        
        // دالة مساعدة داخلية موحدة مستوحاة تماماً من HomeModel.php
        $getLangData = function(string $key, array $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (!is_array($decoded)) {
                return $default;
            }

            // إذا كانت البيانات مهيكلة باللغات (ar, en, de)
            if (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de'])) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $decoded;
        };

        // 1. قسم من نحن الرئيسي (About Section)
        $about_raw = $getLangData('about_section', []);

        // 2. قسم خدمات صفحة من نحن (About Services - المستقل تماماً)
        $about_services_raw = $getLangData('about_services', [
            'section_title'    => 'خدماتنا المميزة',
            'section_subtitle' => '',
            'items'            => []
        ]);

        // 3. قسم فريق العمل (Team) - دعم الهيكل متعدد اللغات والمفاتيح المسطحة كاحتياط
        $team_raw = $getLangData('team', []);
        $team_data = [
            'title' => $team_raw['title'] ?? ($team_raw['team_title'] ?? ($settings['team_title'] ?? 'فريق العمل')),
            'desc'  => $team_raw['desc'] ?? ($team_raw['team_desc'] ?? ($settings['team_desc'] ?? '')),
            'items' => $team_raw['items'] ?? (is_array($team_raw) && !isset($team_raw['title']) && !isset($team_raw['desc']) ? $team_raw : (isset($settings['team_items']) ? json_decode($settings['team_items'], true) : []))
        ];

        // 4. قسم العدادات والإحصائيات (Counts)
        $counts_raw = get_setting('about_counts', []);
        $counts_items = is_string($counts_raw) ? json_decode($counts_raw, true) : (is_array($counts_raw) ? $counts_raw : []);

        // 5. قسم الشركاء (Partners)
        $partners_raw = $getLangData('partners', []);
        $partners_data = [
            'title' => $partners_raw['title'] ?? ($partners_raw['partners_title'] ?? ($settings['partners_title'] ?? 'شركاؤنا داخل وخارج ألمانيا')),
            'items' => $partners_raw['items'] ?? (is_array($partners_raw) && !isset($partners_raw['title']) ? $partners_raw : (isset($settings['partners_items']) ? json_decode($settings['partners_items'], true) : []))
        ];

        return [
            'about_section'    => $about_raw,
            'about_services'   => $about_services_raw, // 🌟 إضافة بيانات الخدمات المستقلة للمودل
            'team_section'     => $team_data,
            'team_title'       => $team_data['title'],
            'team_desc'        => $team_data['desc'],
            'team_items'       => $team_data['items'],
            'about_counts'     => is_array($counts_items) ? $counts_items : [],
            'partners_section' => $partners_data,
            'partners_title'   => $partners_data['title'],
            'partners_items'   => is_array($partners_data['items']) ? $partners_data['items'] : []
        ];
    }
}
