<?php
namespace App\Core;

use PDO;

class CMS {

    /**
     * الاتصال بقاعدة البيانات
     */
    private static function getDB() {
        $host = '127.0.0.1';
        $db   = 'beethoven_db';
        $user = 'root';
        $pass = '';
        
        return new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    /**
     * جلب المحتوى الخام من القاعدة
     */
    private static function fetchFromDB($page, $section, $field) {
        try {
            $pdo = self::getDB();
            $stmt = $pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ? LIMIT 1");
            $stmt->execute([$page, $section, $field]);
            $res = $stmt->fetchColumn();
            return $res !== false ? $res : ""; 
        } catch (\PDOException $e) {
            return ""; 
        }
    }

    /**
     * تحديث البيانات في القاعدة
     */
    public static function update($page, $section, $field, $value) {
        try {
            $pdo = self::getDB();
            // تحويل المصفوفات إلى JSON للحفظ في القاعدة
            $finalValue = is_array($value) ? json_encode($value) : $value;
            
            $stmt = $pdo->prepare("UPDATE site_content SET content = ? WHERE page_key = ? AND section_key = ? AND field_key = ?");
            $stmt->execute([$finalValue, $page, $section, $field]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * استرجاع البيانات للواجهة
     */
    public static function get($page, $section, $field, $is_img = false, $is_html = false) {
        $content = self::fetchFromDB($page, $section, $field);
        
        // 1. التعامل الخاص مع روابط السوشيال ميديا (مصفوفة)
        if ($field === 'social_links') {
            $data = json_decode($content, true);
            return is_array($data) ? $data : [];
        }

        // 2. التعامل مع النصوص والصور
        if ($is_img) {
            return $content; // نرجع المسار فقط
        } elseif ($is_html) {
            return $content;
        } else {
            return htmlspecialchars($content);
        }
    }
}
