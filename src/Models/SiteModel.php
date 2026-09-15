<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class SiteModel {
    /**
     * جلب جميع الإعدادات من قاعدة البيانات
     */
    public static function getSettings(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
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

        // دوال مساعدة لاستخراج البيانات والنصوص حسب اللغة الحالية
        $getLangData = function(string $key, $default = []) use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $decoded = json_decode($settings[$key], true);
            
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']))) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $decoded ?? $default;
        };

        $getLangString = function(string $key, string $default = '') use ($settings, $lang) {
            if (!isset($settings[$key])) {
                return $default;
            }
            $decoded = json_decode($settings[$key], true);
            
            if (is_array($decoded) && (isset($decoded['ar']) || isset($decoded['en']))) {
                return $decoded[$lang] ?? ($decoded['ar'] ?? $default);
            }
            
            return $settings[$key] ?? $default;
        };
        
        return [
            'site_title'        => $getLangString('site_title', 'Beethoven Services'),
            'site_email'        => $settings['site_email'] ?? '',
            'site_logo_path'    => $settings['site_logo_path'] ?? '',
            'social_links'      => isset($settings['social_links']) ? json_decode($settings['social_links'], true) : [],
            'menu_links'        => $getLangData('menu_links', []),
            'languages'         => isset($settings['languages']) ? json_decode($settings['languages'], true) : [],
            'announcement'      => $getLangData('announcement', []),
            
            // بيانات قسم الخدمات المشترك بين الصفحات (مدعومة باللغات)
            'services_section_title' => $getLangString('services_section_title', 'خدماتنا المميزة'),
            'services_section_desc'  => $getLangString('services_section_desc', ''),
            'services'               => $getLangData('services', []),

            // بيانات قسم الاستشارة في الفوتر
            'consult_title'     => $getLangString('consult_title', 'احصل على استشارة مجانية'),
            'consult_desc'      => $getLangString('consult_desc', ''),
            
            // بيانات أعمدة الفوتر
            'footer_desc'       => $getLangString('footer_desc', ''),
            'footer_col2_title' => $getLangString('footer_col2_title', 'روابط سريعة'),
            'footer_col3_title' => $getLangString('footer_col3_title', 'تواصل معنا'),
            
            // روابط تواصل معنا
            'footer_col3_links' => isset($settings['footer_col3_links']) ? json_decode($settings['footer_col3_links'], true) : [],
            
            // بيانات صفحة الدليل الشامل
            'guide_title'       => $getLangString('guide_title', 'دليل بيتهوفن الشامل'),
            'guide_desc'        => $getLangString('guide_desc', ''),
            'guide_items'       => $getLangData('guide_items', []),
        ];
    }

    /**
     * تحديث أو حفظ إعدادات الموقع بنظام المفتاح والقيمة
     */
    public static function updateSettings(array $data): bool {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO site_settings (setting_key, setting_value) 
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
