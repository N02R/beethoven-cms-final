<?php
namespace App\Core;

use PDO;

class CMS {
    private static function fetchFromDB($page, $section, $field) {
        $host = '127.0.0.1';
        $db   = 'beethoven_db';
        $user = 'root';
        $pass = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ? LIMIT 1");
            $stmt->execute([$page, $section, $field]);
            return $stmt->fetchColumn();
        } catch (\PDOException $e) {
            return null; 
        }
    }

    public static function get($page, $section, $field) {
        $content = self::fetchFromDB($page, $section, $field) ?: "/assets/img/logo.png";

        // منطق خاص للوجو (إرجاع وسم صورة)
        if ($field === 'logo_path') {
            $img = "<img src='$content' alt='شعار بيتهوفن سيتي' width='178' height='72' loading='lazy'>";
            
            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
                return "<div class='editable-wrapper' data-page='$page' data-section='$section' data-field='$field' style='position:relative; display:inline-block;'>
                            <span class='editable-content'>$img</span>
                            <span class='edit-icon' style='cursor:pointer; background:blue; color:white; padding:2px 5px; border-radius:3px; position:absolute; top:0; right:-25px;'>✏️</span>
                        </div>";
            }
            return $img;
        }

        // منطق النصوص الافتراضي
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            return "<div class='editable-wrapper' data-page='$page' data-section='$section' data-field='$field' style='position:relative; display:inline-block;'>
                        <span class='editable-content'>" . htmlspecialchars($content) . "</span>
                        <span class='edit-icon' style='cursor:pointer; background:blue; color:white; padding:2px 5px; border-radius:3px; font-size:10px; vertical-align:middle; margin-right:5px;'>✏️</span>
                    </div>";
        }

        return htmlspecialchars($content);
    }
}
