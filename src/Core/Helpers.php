<?php
declare(strict_types=1);

use App\Config\Database;

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين من قاعدة البيانات
     */
    function get_setting(string $key, string $default = ''): string {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$key]);
            $result = $stmt->fetch();

            return $result ? (string)$result['setting_value'] : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
