<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class SiteSettings
{
    /**
     * جلب جميع الإعدادات من قاعدة البيانات
     */
    public static function getSettings(): array
    {
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
     * تحديث أو حفظ إعدادات الموقع بنظام المفتاح والقيمة
     */
    public static function updateSettings(array $data): bool
    {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO site_settings (setting_key, setting_value) 
            VALUES (:k, :v) 
            ON DUPLICATE KEY UPDATE setting_value = :v_update
        ");

        $success = true;

        foreach ($data as $key => $value) {
            // استبعاد المتغيرات التي لا تمثل إعدادات مخزنة
            if (in_array($key, ['csrf_token', 'action'])) {
                continue;
            }

            // تنفيذ التحديث أو الإدراج لكل مفتاح وقيمة على حدة
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
