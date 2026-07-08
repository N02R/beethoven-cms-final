<?php
namespace App\Core;

class CMS {
    
    /**
     * جلب المحتوى من قاعدة البيانات
     */
    public static function get($page, $section, $key) {
        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT content FROM site_content WHERE page_key=? AND section_key=? AND field_key=?");
            $stmt->execute([$page, $section, $key]);
            $result = $stmt->fetchColumn();
            return $result !== false ? $result : "نص افتراضي";
        } catch (\Exception $e) {
            return "خطأ في جلب البيانات";
        }
    }

    /**
     * تحديث المحتوى في قاعدة البيانات
     */
    public static function update($page, $section, $key, $value) {
        return \App\Core\Database::getInstance()->update($page, $section, $key, $value);
    }

    /**
     * دالة مساعدة لعرض الصور (Media Manager)
     * سنتوسع فيها لاحقاً عند بناء مدير الصور
     */
    public static function getImageUrl($page, $section, $key) {
        $path = self::get($page, $section, $key);
        return !empty($path) ? $path : '/assets/img/default.png';
    }
}
