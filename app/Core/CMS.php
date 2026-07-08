<?php
namespace App\Core;

class CMS {
    // نفترض أن لديك دالة fetchFromDB جاهزة لجلب البيانات
    public static function get($page, $section, $field) {
        $content = self::fetchFromDB($page, $section, $field);

        // وضع التعديل (Visual Editing)
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
            return "
            <div class='editable-wrapper' data-page='$page' data-section='$section' data-field='$field'>
                <span class='editable-content'>$content</span>
                <span class='edit-icon'>✏️</span>
            </div>";
        }

        return $content;
    }
}
