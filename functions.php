<?php
/**
 * functions.php - ملف المساعدة لجلب الإعدادات من قاعدة البيانات
 */

// استدعاء الاتصال بقاعدة البيانات من مجلد database
require_once __DIR__ . '/database/database.php';
use App\Config\Database;

if (!function_exists('get_setting')) {
    function get_setting($key, $default = '') {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $row = $stmt->fetch();
            
            if ($row) {
                $value = $row['setting_value'];
                // إذا كانت القيمة مخزنة كـ JSON (مثل المصفوفات والقوائم والسوشيال ميديا)، نقوم بفكها تلقائياً
                $decoded = json_decode($value, true);
                return ($decoded !== null) ? $decoded : $value;
            }
        } catch (\Exception $e) {
            // في حال حدوث خطأ يتم إرجاع القيمة الافتراضية
        }
        return $default;
    }
}
