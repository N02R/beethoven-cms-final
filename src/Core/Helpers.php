<?php
declare(strict_types=1);

use App\Config\Database;

if (!function_exists('get_setting')) {
    /**
     * جلب قيمة إعداد معين من قاعدة البيانات (تدعم النصوص والمصفوفات)
     */
    function get_setting(string $key, mixed $default = ''): mixed {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ? LIMIT 1");
            $stmt->execute([$key]);
            $result = $stmt->fetch();

            if ($result && isset($result['setting_value'])) {
                $value = $result['setting_value'];
                
                // محاولة فك الـ JSON إذا كانت القيمة مخزنة كمصفوفة
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
                
                return $value;
            }
        } catch (\Exception $e) {
            // في حال حدوث أي خطأ
        }
        
        return $default;
    }
}
