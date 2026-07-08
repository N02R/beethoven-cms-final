<?php
namespace App\Core;

class CMS {
    public static function get($page, $section, $key) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT item_value FROM site_content WHERE page_name=? AND section_name=? AND item_key=?");
        $stmt->execute([$page, $section, $key]);
        $result = $stmt->fetchColumn();
        return $result !== false ? $result : "لم يتم تعيين قيمة لـ $key";
    }

    public static function update($page, $section, $key, $value) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE site_content SET item_value = :value 
                              WHERE page_name = :page AND section_name = :section AND item_key = :key");
        return $stmt->execute(['value' => $value, 'page' => $page, 'section' => $section, 'key' => $key]);
    }

    public static function editable($page, $section, $key, $type = 'text') {
        $value = self::get($page, $section, $key);
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            $name = "{$section}_{$key}";
            return ($type === 'textarea') 
                ? "<textarea name='$name' class='form-control admin-edit-field'>$value</textarea>"
                : "<input type='text' name='$name' value='" . htmlspecialchars($value) . "' class='form-control admin-edit-field'>";
        }
        return $value;
    }
}
