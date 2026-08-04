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
     * دالة موحدة لجلب وتجهيز بيانات الهيدر والفوتر والإعدادات العامة لكل الموقع
     */
    public static function getGlobalData(): array {
        $settings = self::getSettings();
        
        return [
            'site_title'        => $settings['site_title'] ?? 'Beethoven Services',
            'site_email'        => $settings['site_email'] ?? '',
            'site_logo_path'    => $settings['site_logo_path'] ?? '',
            'social_links'      => isset($settings['social_links']) ? json_decode($settings['social_links'], true) : [],
            'menu_links'        => isset($settings['menu_links']) ? json_decode($settings['menu_links'], true) : [],
            'languages'         => isset($settings['languages']) ? json_decode($settings['languages'], true) : [],
            'announcement'      => isset($settings['announcement']) ? json_decode($settings['announcement'], true) : [],
            
            // بيانات قسم الاستشارة في الفوتر
            'consult_title'     => $settings['consult_title'] ?? 'احصل على استشارة مجانية',
            'consult_desc'      => $settings['consult_desc'] ?? '',
            
            // بيانات أعمدة الفوتر
            'footer_desc'       => $settings['footer_desc'] ?? '',
            'footer_col2_title' => $settings['footer_col2_title'] ?? 'روابط سريعة',
            'footer_col3_title' => $settings['footer_col3_title'] ?? 'تواصل معنا',
            
            // روابط تواصل معنا
            'footer_col3_links' => isset($settings['footer_col3_links']) ? json_decode($settings['footer_col3_links'], true) : [],
            
            // بيانات صفحة الدليل الشامل (محدثة لتعرض التعديلات فوراً)
            'guide_title'       => $settings['guide_title'] ?? 'دليل بيتهوفن الشامل',
            'guide_desc'        => $settings['guide_desc'] ?? '',
            'guide_items'       => isset($settings['guide_items']) ? (is_string($settings['guide_items']) ? json_decode($settings['guide_items'], true) : $settings['guide_items']) : [],
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
