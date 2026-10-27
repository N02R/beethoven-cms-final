<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class SiteModel {
    /**
     * جلب جميع الإعدادات العامة والهيدر والفوتر من الجدول الجديد global_settings
     */
    public static function getSettings(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM global_settings");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach ($results as $row) {
            if (isset($row['setting_key'])) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }

    /**
     * دالة موحدة لجلب وتجهيز بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع مع دعم اللغات
     */
    public static function getGlobalData(): array {
        $settings = self::getSettings();
        $lang = $_SESSION['site_lang'] ?? 'ar';

        // دوال مساعدة لاستخراج البيانات والنصوص حسب اللغة الحالية بشكل آمن
        $getLangData = function(string $key, $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de']))) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return is_array($decoded) ? $decoded : $default;
        };

        $getLangString = function(string $key, string $default = '') use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $raw = $settings[$key];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']) || isset($decoded['de']))){
                $langBlock = $decoded[$lang] ?? ($decoded['ar'] ?? []);
                if (is_array($langBlock)) {
                    return $langBlock['title'] ?? ($langBlock['guide_title'] ?? ($langBlock['desc'] ?? $default));
                }
                return is_string($langBlock) ? $langBlock : $default;
            }
            
            return is_string($raw) ? $raw : $default;
        };
        
        // استخراج الشعار مع دعم اللغات
        $rawLogo = $settings['site_logo_path'] ?? ($settings['site_logo'] ?? '');
        $decodedLogo = is_string($rawLogo) ? json_decode($rawLogo, true) : $rawLogo;
        if (is_array($decodedLogo) && (isset($decodedLogo['ar']) || isset($decodedLogo['en']) || isset($decodedLogo['de']))) {
            $site_logo_path = $decodedLogo[$lang] ?? ($decodedLogo['ar'] ?? '');
        } else {
            $site_logo_path = is_string($rawLogo) ? $rawLogo : '';
        }

        // معالجة بيانات الدليل الشامل (الهيكل المدمج الحديث)
        $guideRaw = $settings['guide_items'] ?? '[]';
        $guideDecoded = is_string($guideRaw) ? json_decode($guideRaw, true) : $guideRaw;
        
        $getMultiLang = function($settingKey, $default) use ($settings, $lang) {
            if (!isset($settings[$settingKey])) return $default;
            $raw = $settings[$settingKey];
            $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
            if (is_array($decoded)) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? ($decoded['de'] ?? $default));
            }
            return is_string($raw) ? $raw : $default;
        };

        return [
            'site_title'        => $getMultiLang('site_title', 'Beethoven Services'),
            'site_email'        => $settings['site_email'] ?? '',
            'site_logo_path'    => $site_logo_path,
            'site_logo'         => $site_logo_path,
            'social_links'      => isset($settings['social_links']) && is_string($settings['social_links']) ? json_decode($settings['social_links'], true) : [],
            'menu_links'        => $getLangData('menu_links', []),
            'languages'         => isset($settings['languages']) && is_string($settings['languages']) ? json_decode($settings['languages'], true) : [],
            'announcement'      => $getLangData('announcement', []),
            
            'services_section_title' => $getMultiLang('services_section_title', 'خدماتنا المميزة'),
            'services_section_desc'  => $getMultiLang('services_section_desc', ''),
            'services'               => $getLangData('services', []),

            'consult_title'     => $getMultiLang('consult_title', 'احصل على استشارة مجانية'),
            'consult_desc'      => $getMultiLang('consult_desc', ''),
            
            'footer_desc'       => $getMultiLang('footer_desc', ''),
            'footer_col2_title' => $getMultiLang('footer_col2_title', 'روابط سريعة'),
            'footer_col3_title' => $getMultiLang('footer_col3_title', 'تواصل معنا'),
            
            'footer_col3_links' => isset($settings['footer_col3_links']) && is_string($settings['footer_col3_links']) ? json_decode($settings['footer_col3_links'], true) : [],
            
            // بيانات الدليل الشامل المدمجة والجاهزة للعرض
            'guide_title'       => $getMultiLang('guide_title', 'دليل بيتهوفن الشامل'),
            'guide_desc'        => $getMultiLang('guide_desc', ''),
            'read_more_btn'     => $getMultiLang('read_more_btn', 'قراءة المزيد'),
            'guide_items'       => is_array($guideDecoded) ? $guideDecoded : [],
        ];
    }

    public static function updateSettings(array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO global_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        $success = true;
        foreach ($data as $key => $value) {
            if (in_array($key, ['csrf_token', 'action'])) {
                continue;
            }
            $res = $stmt->execute([
                'k' => $key,
                'v' => $value,
                'v_update' => $value
            ]);
            if (!$res) {
                $success = false;
            }
        }
        return $success;
    }
}
