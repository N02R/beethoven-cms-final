<?php
namespace App\Core;

class CMS {
    /**
     * لجلب قيمة محتوى من قاعدة البيانات
     */
    public static function get($page, $section, $key) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT item_value FROM site_content WHERE page_name=? AND section_name=? AND item_key=?");
        $stmt->execute([$page, $section, $key]);
        $result = $stmt->fetchColumn();
        
        return $result !== false ? $result : "لم يتم تعيين قيمة لـ $key";
    }

    /**
     * لتحديث قيمة محتوى في قاعدة البيانات
     */
    public static function update($page, $section, $key, $value) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE site_content SET item_value = :value 
                              WHERE page_name = :page AND section_name = :section AND item_key = :key");
        
        return $stmt->execute([
            'value'   => $value,
            'page'    => $page,
            'section' => $section,
            'key'     => $key
        ]);
    }
}
