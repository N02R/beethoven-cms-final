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
            $res = $stmt->fetchColumn();
            return $res !== false ? $res : ""; 
        } catch (\PDOException $e) { return ""; }
    }

    public static function get($page, $section, $field, $is_img = false, $is_html = false) {
        $content = self::fetchFromDB($page, $section, $field);
        $isAdmin = (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true);

        // تحديد نوع العنصر
        if ($is_img) {
            $element = "<img src='$content' alt='Logo'>";
        } elseif ($is_html) {
            $element = $content; // نتركه كما هو (HTML) بدون تنظيف
        } else {
            $element = htmlspecialchars($content); // للنصوص العادية
        }

        // إضافة القلم إذا كان المدير مسجلاً
        if ($isAdmin) {
            return "<span class='editable-element' data-page='$page' data-section='$section' data-field='$field'>$element<i class='edit-icon'>✏️</i></span>";
        }
        return $element;
    }
}
