<?php
namespace App\Core;

class CMS {
    public static function get($page, $section, $key) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT item_value FROM site_content WHERE page_name=? AND section_name=? AND item_key=?");
        $stmt->execute([$page, $section, $key]);
        return $stmt->fetchColumn() ?: "لم يتم تعيين قيمة لـ $key";
    }
}
