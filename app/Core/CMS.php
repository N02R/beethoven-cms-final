<?php
namespace App\Core;

use PDO;

class CMS {
    private static function fetchFromDB($page, $section, $field) {
        // بيانات الاتصال بقاعدة البيانات الخاصة بكِ
        $host = 'localhost';
        $db   = 'beethoven_db'; // استبدلي هذا باسم قاعدة بياناتك
        $user = 'root';         // استبدلي هذا باسم المستخدم
        $pass = '';             // استبدلي هذا بكلمة المرور
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("SELECT content FROM site_content WHERE page_key = ? AND section_key = ? AND field_key = ? LIMIT 1");
            $stmt->execute([$page, $section, $field]);
            $result = $stmt->fetchColumn();
            
            return $result !== false ? $result : "[نص غير موجود]";
        } catch (\PDOException $e) {
            // للتشخيص فقط: يمكنكِ إزالة هذا السطر لاحقاً لأسباب أمنية
            return "خطأ: " . $e->getMessage();
        }
    }

    public static function get($page, $section, $field) {
        $content = self::fetchFromDB($page, $section, $field);

        // وضع التعديل المباشر (يظهر فقط إذا كان المدير مسجلاً دخوله)
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            return "
            <div class='editable-wrapper' data-page='$page' data-section='$section' data-field='$field' style='position:relative; display:inline-block;'>
                <span class='editable-content'>" . htmlspecialchars($content) . "</span>
                <span class='edit-icon' style='cursor:pointer; background:blue; color:white; padding:2px 5px; border-radius:3px; font-size:10px; vertical-align:middle; margin-right:5px;'>✏️</span>
            </div>";
        }

        return htmlspecialchars($content);
    }
}
