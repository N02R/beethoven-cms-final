<?php
namespace App\Core;
use PDO;

class CMS {
    // الاتصال بقاعدة البيانات (لإعادة استخدامه)
    private static function getDB() {
        $host = '127.0.0.1'; $db = 'beethoven_db'; $user = 'root'; $pass = '';
        return new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    private static function fetchFromDB($page, $section, $field) {
        try {
            $pdo = self::getDB();
            $stmt = $pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ? LIMIT 1");
            $stmt->execute([$page, $section, $field]);
            $res = $stmt->fetchColumn();
            return $res !== false ? $res : ""; 
        } catch (\PDOException $e) { return ""; }
    }

    // --- الدالة الجديدة للتحديث ---
    public static function update($page, $section, $field, $value) {
        try {
            $pdo = self::getDB();
            // تحويل المصفوفات (مثل الروابط) إلى JSON للحفظ في القاعدة
            $finalValue = is_array($value) ? json_encode($value) : $value;
            
            $stmt = $pdo->prepare("UPDATE site_content SET content = ? WHERE page_key = ? AND section_key = ? AND field_key = ?");
            $stmt->execute([$finalValue, $page, $section, $field]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function get($page, $section, $field, $is_img = false, $is_html = false) {
        $content = self::fetchFromDB($page, $section, $field);
        
        // إذا كان محتوى السوشيال ميديا عبارة عن JSON، نقوم بفك التشفير
        if ($field === 'social_links') {
            return json_decode($content, true) ?? [];
        }

        // تحديد نوع العنصر للعرض
        if ($is_img) {
            return "<img src='$content' alt='Content'>";
        } elseif ($is_html) {
            return $content;
        } else {
            return htmlspecialchars($content);
        }
    }
}
