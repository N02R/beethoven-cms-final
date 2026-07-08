<?php
namespace App\Core;

use PDO;

class CMS {
    private static function fetchFromDB($page, $section, $field) {
        $host = '127.0.0.1'; $db = 'beethoven_db'; $user = 'root'; $pass = '';
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $stmt = $pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ? LIMIT 1");
            $stmt->execute([$page, $section, $field]);
            return $stmt->fetchColumn() ?: ($field === 'logo_path' ? '/assets/img/logo.png' : '...');
        } catch (\PDOException $e) { return "Error"; }
    }

    public static function get($page, $section, $field) {
        $content = self::fetchFromDB($page, $section, $field);
        $isAdmin = (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true);

        // إذا كان لوجو
        if ($field === 'logo_path') {
            $img = "<img src='$content' alt='Logo' width='178' height='72'>";
            return $isAdmin ? "<span class='editable-element' data-page='$page' data-section='$section' data-field='$field'>$img<i class='edit-icon'>✏️</i></span>" : $img;
        }

        // إذا كان نص عادي
        return $isAdmin ? "<span class='editable-element' data-page='$page' data-section='$section' data-field='$field'>" . htmlspecialchars($content) . "<i class='edit-icon'>✏️</i></span>" : htmlspecialchars($content);
    }
}
