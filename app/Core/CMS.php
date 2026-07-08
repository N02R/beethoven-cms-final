<?php
namespace App\Core;

class CMS {
    public static function get($page, $section, $field) {
        // الاتصال بقاعدة البيانات وجلب المحتوى (الموجود لديك مسبقاً)
        $content = self::fetchFromDB($page, $section, $field);

        // التحقق من حالة المدير
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            return "
            <div class='editable-wrapper' data-page='$page' data-section='$section' data-field='$field'>
                <span class='editable-content'>" . htmlspecialchars($content) . "</span>
                <span class='edit-icon'>✏️</span>
            </div>";
        }

        return htmlspecialchars($content);
    }
}
