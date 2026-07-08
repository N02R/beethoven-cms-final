<?php
namespace App\Core;

class CMS {
    public static function get($page, $section, $key) {
        $db = \App\Core\Database::getConnection();
        
        $stmt = $db->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ?");
        $stmt->execute([$page, $section, $key]);
        
        $result = $stmt->fetchColumn();
        
        // إذا وجدنا محتوى نعيده، وإلا نعيد نص افتراضي
        return ($result !== false) ? $result : "نص افتراضي";
    }
}
