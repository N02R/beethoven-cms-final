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
        $stmt = $pdo->query("SELECT * FROM site_settings");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // تحويل النتائج إلى مصفوفة مترابطة (Key => Value) لسهولة الاستخدام
        $settings = [];
        foreach ($results as $row) {
            // اعتماداً على بنية جدولك، إذا كان يحتوي على حقول مثل setting_key و setting_value
            if (isset($row['setting_key'])) {
                $settings[$row['setting_key']] = $row['setting_value'];
            } else {
                // إذا كان الجدول مخزناً كصف واحد يحتوي على أعمدة مباشرة
                $settings = $row;
                break;
            }
        }
        return $settings;
    }

    /**
     * تحديث أو حفظ إعدادات الموقع
     */
    public static function updateSettings(array $data): bool
    {
        $pdo = Database::getConnection();
        
        // التحقق مما إذا كان الجدول يعتمد على نظام المفتاح والقيمة أو الأعمدة المباشرة
        // هنا سنقوم بتحديث الأعمدة مباشرة بناءً على المفاتيح المرسلة
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($key === 'csrf_token' || $key === 'action') {
                continue; // استبعاد رموز الحماية والإجراءات
            }
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($fields)) {
            return false;
        }

        // نفترض هنا وجود صف رئيسي للإعدادات (ID = 1) أو تحديث عام
        // يمكنك تعديل الشرط حسب تصميم جدول site_settings لديك
        $sql = "UPDATE site_settings SET " . implode(', ', $fields) . " WHERE id = 1";
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute($values);
    }
}
