<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

class GuideBlog1Model {
    /**
     * جلب بيانات المقال الأول من قاعدة البيانات
     */
    public static function getData(): array {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key LIKE 'guide_blog1_%'");
            $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            return [
                'blog_title'   => $results['guide_blog1_title'] ?? 'عنوان المقال الأول الافتراضي',
                'blog_desc'    => $results['guide_blog1_desc'] ?? 'ملخص أو وصف قصير للمقال الأول...',
                'blog_content' => $results['guide_blog1_content'] ?? 'محتوى المقال التفصيلي يكتب هنا...',
                'blog_img'     => $results['guide_blog1_img'] ?? '',
            ];
        } catch (\Exception $e) {
            return [
                'blog_title'   => 'عنوان المقال الأول الافتراضي',
                'blog_desc'    => 'ملخص أو وصف قصير للمقال الأول...',
                'blog_content' => 'محتوى المقال التفصيلي يكتب هنا...',
                'blog_img'     => '',
            ];
        }
    }

    /**
     * حفظ أو تحديث بيانات المقال الأول
     */
    public static function saveData(array $data): bool {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value");

            foreach ($data as $key => $value) {
                $settingKey = 'guide_blog1_' . $key;
                $stmt->execute(['key' => $settingKey, 'value' => $value]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
